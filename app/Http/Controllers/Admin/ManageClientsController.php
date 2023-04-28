<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */
namespace App\Http\Controllers\Admin;

use App\ClientDetails;
use App\DataTables\Admin\ClientsDataTable;
use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Client\StoreClientRequest;
use App\Http\Requests\Admin\Client\UpdateClientRequest;
use App\TaskboardColumn;
use App\Traits\CurrencyExchange;
use App\UniversalSearch;
use App\User;
use App\Task;
use App\State;
use App\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ManageClientsController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.clients';
        $this->pageIcon = 'icon-people';
        $this->middleware(function ($request, $next) {
            if (!in_array('clients', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClientsDataTable $dataTable)
    {
       // 
        if (!request()->ajax()) {
            $this->clients = User::allClients();
            $this->tasks = Task::all();
            $this->countries = Country::all();

            $this->totalClients = count($this->clients);
        }

        return $dataTable->render('admin.clients.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($leadID = null)
    {
        $this->countries = Country::all();

        $client = new ClientDetails();

        if (request()->ajax()) {
            return view('admin.clients.ajax-create', $this->data);
        }

        return view('admin.clients.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));

        unset($data['phone_code']);
        $data['country_id'] = $request->input('phone_code');
        $data['name'] = $request->input('salutation')." ".$request->input('name');
        $data['state_id'] = $request->input('state_id');
        $user = User::create($data);
        $user->client_details()->create($data);

        $user->attachRole(3);

        cache()->forget('all-clients');


        if ($request->has('ajax_create')) {
            $teams = User::allClients();
            $teamData = '';

            foreach ($teams as $team) {
                $teamData .= '<option value="' . $team->id . '"> ' . ucwords($team->name) . ' </option>';
            }

            return Reply::successWithData(__('messages.clientAdded'), ['teamData' => $teamData]);
        }


        return Reply::redirect(route('admin.clients.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $id)->first();
      
        $this->clientStats = $this->clientStats($id);

        return view('admin.clients.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->id)->first();
        $this->countries = Country::all();

        return view('admin.clients.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $data =  $request->all();

        unset($data['password']);
        if ($request->password != '') {
            $data['password'] = Hash::make($request->input('password'));
        }
        $data['country_id'] = $request->input('phone_code');
        $user->update($data);

        if ($user->client_details) {
             $data['state_id'] = $request->input('state_id');
            $fields = $request->only($user->client_details->getFillable());
            $user->client_details->fill($fields);
            $user->client_details->save();
        } else {
            $user->client_details()->create($data);
        }

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $user->client_details->updateCustomFieldData($request->get('custom_fields_data'));
        }
        return Reply::redirect(route('admin.clients.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'client')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
        User::destroy($id);
        ClientDetails::where('user_id', '=', $id)->delete();
        return Reply::success(__('messages.clientDeleted'));
    }

    public function showProjects($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);
       
        $taskBoardColumn = TaskboardColumn::completeColumn();

        $this->taskCompleted = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $id)
            ->where('tasks.board_column_id', $taskBoardColumn->id)
            ->count();
         // return $dataTable->render('admin.clients.projects', $this->data);
        return view('admin.clients.projects', $this->data);
    }

    public function tasks($userId, $hideCompleted)
    {
        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->select('tasks.id', 'tasks.heading', 'tasks.due_date', 'taskboard_columns.column_name', 'taskboard_columns.label_color')
            ->where('task_users.user_id', $userId);

        if ($hideCompleted == '1') {
            $tasks->where('tasks.status', '=', 'incomplete');
        }

        $tasks->get();

        return DataTables::of($tasks)
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('heading', function ($row) {
                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';

                return $name;
            })
            ->editColumn('column_name', function ($row) {
                return '<label class="label" style="background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })

            ->rawColumns(['column_name', 'due_date', 'heading'])
            ->make(true);
        }


    public function country(Request $request, $id)
    {
       
        //  dd($request->country_id);
          if($request->country != 0 || $request->country != ''){
              $states = State::where('country_id', '=', $request->country)->get();
              $option = '' ;
               $option .= '<option value=""> -- Select -- </option>';
                   foreach($states as $state){
                       if($request->state == $state->id){
                           $option .= '<option selected value="'.$state->id.'">'.$state->names.'</option>';
                       }else{
                           $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
                       }
                   }
          }else{
            $this->clientDetail = ClientDetails::where('user_id', '=', $id)->first();
            $states = State::where('country_id', '=', $this->clientDetail->country)->get();

            $option = '' ;
             $option .= '<option value=""> -- Select -- </option>';
            // dd($this->clientDetail);
                 foreach($states as $state){
                     if($this->clientDetail->state == $state->id){
                         $option .= '<option selected value="'.$state->id.'">'.$state->names.'</option>';
                     }else{
                         $option .= '<option value="'.$state->id.'">'.$state->names.'</option>';
                     }
                 }
          }
  
          return Reply::dataOnly(['data'=> $option]);
    }

    public function clientStats($id)
    {
        $completedTaskColumn = TaskboardColumn::completeColumn();
        $clientData = DB::table('users')
            ->select(
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where task_users.user_id = ' . $id . ') as totalAllTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id!=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $id . ') as totalPendingTasks')
            )
            ->first();


        $earnings = 0;


        return [$clientData, $earnings];
    }
    
}