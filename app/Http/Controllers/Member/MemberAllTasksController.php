<?php

namespace App\Http\Controllers\Member;

use App\DataTables\Admin\AllTasksDataTable;
use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Task;
use App\TaskCategory;
use App\SportType;
use App\TaskboardColumn;
use App\TaskFile;
use App\User;
use App\EmployeeDetails;
use App\WoType;
use App\TaskLabelList;
use App\Country;
use App\State;
use App\ClientDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MemberAllTasksController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.tasks';
        $this->pageIcon = 'fa fa-tasks';
        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(AllTasksDataTable $dataTable)
    {
        if (!request()->ajax()) {
          //  $this->projects = Project::allProjects();
            $this->clients = User::allClients();
            $this->employees = User::allEmployees();
            $this->taskBoardStatus = TaskboardColumn::all();
            $this->taskLabels = TaskLabelList::all();
            $this->wotype = WoType::all();
            $this->startDate = Carbon::today()->subDays(30)->format($this->global->date_format);
            $this->endDate = Carbon::today()->addDays(15)->format($this->global->date_format);
        }

        return $dataTable->render('member.all-tasks.index', $this->data);
    }

        /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false, 'exportable' => false],
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('app.task') => ['data' => 'heading', 'name' => 'heading'],
            __('modules.tasks.assigned') => ['data' => 'name', 'name' => 'name', 'visible' => false],
            __('modules.tasks.assignTo') => ['data' => 'users', 'name' => 'member.name', 'exportable' => false],
            __('app.dueDate') => ['data' => 'due_date', 'name' => 'due_date'],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'visible' => false],
            __('app.columnStatus') => ['data' => 'board_column', 'name' => 'board_column', 'exportable' => false, 'searchable' => false],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    public function edit($id)
    {
        if (!$this->user->can('edit_tasks') && $this->global->task_self == 'no') {
            abort(403);
        }
        $this->task = Task::with('users', 'label')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->task->client_id)->first();
        $this->clients = User::allClients();
        $this->labelIds = $this->task->label->pluck('label_id')->toArray();

        $this->employees  = User::allEmployees();
        $this->wotype = WoType::all();
        $this->sport = SportType::all();
        $this->categories = TaskCategory::all();
        $this->taskLabels = TaskLabelList::all();
        $this->taskBoardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', '<>', $completedTaskColumn->id)
                ->where('id', '!=', $id);

            if ($this->task->project_id != '') {
                $this->allTasks = $this->allTasks->where('project_id', $this->task->project_id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        return view('member.all-tasks.edit', $this->data);
    }

    public function update(StoreTask $request, $id)
    {

        $task = Task::findOrFail($id);
        $oldStatus = TaskboardColumn::findOrFail($task->board_column_id);

        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        if($request->start_date){
            $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        }else{
            $task->start_date = null;
        }
        if($request->due_date){
            $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        }else{
            $task->due_date = null;
        }
                
        $task->task_category_id = $request->category_id;
      //  $task->wo_id = $request->task_type;
       // $task->sport_id = $request->sport_type;
        $task->client_id = $request->client_id;
      //  $task->qty = $request->task_qty;
            
        if($request->user_id && $request->status == "1"){
            $task->board_column_id = 2;
        }else{
            $task->board_column_id = $request->status;
        }

        $taskBoardColumn = TaskboardColumn::findOrFail($request->status);

        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now()->format('Y-m-d');
        } else {
            $task->completed_on = null;
        }

        if ($request->project_id != "all") {
            $task->project_id = $request->project_id;
        } else {
            $task->project_id = null;
        }
        $task->save();

        // save labels

        // Sync task users
        $task->users()->sync($request->user_id);

 
        return Reply::dataOnly(['taskID' => $task->id]);
        //        return Reply::redirect(route('client.all-tasks.index'), __('messages.taskUpdatedSuccessfully'));
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // If it is recurring and allowed by user to delete all its recurring tasks
        if ($request->has('recurring') && $request->recurring == 'yes') {
            Task::where('recurring_task_id', $id)->delete();
        }

        Task::destroy($id);

        //calculate project progress if enabled

        return Reply::success(__('messages.taskDeletedSuccessfully'));
    }


    public function create()
    {
      //
    }


    public function store(StoreTask $request)
    {

      //
    }

    public function showFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('member.all-tasks.ajax-file-list', $this->data);
    }

    public function remindForTask($taskID)
    {
        
        $task = Task::with('users')->findOrFail($taskID);

        // Send  reminder notification to user

        event(new TaskReminderEvent($task));
        return Reply::success('messages.reminderMailSuccess');
    }

    public function show($id)
    {
        $this->task = Task::with('board_column', 'users', 'files', 'comments', 'notes', 'labels', 'wotype', 'sporttype', 'category')->findOrFail($id);
        
        $this->clientDetail = User::where('id', '=', $this->task->client_id)->first();
        $state_id = json_decode($this->task->labels->contacts, true);
        $this->state = State::where('id', '=', $state_id['site_state'])->first();
        $this->country = Country::where('id', '=', $this->state->country_id)->first();
      //  $this->sport = SportType::all();
        // $this->employees = User::join('employee_details', 'users.id', '=', 'employee_details.user_id')->orderBy('users.name')
        //     ->get();

        // $this->employees = $this->employees->select(
        //     'users.name',
        //     'users.image',
        //     'users.id'
        // );

      //  $this->employees = $this->employees->where('project_time_logs.task_id', '=', $id);

        // $this->employees = $this->employees->groupBy('project_time_logs.user_id')
        //     ->orderBy('users.name')
        //     ->get();
            
        

        $view = view('member.all-tasks.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function updateTaskDuration(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
        $task->save();

        return Reply::success('messages.taskUpdatedSuccessfully');
    }
    public function history($id)
    {
        $this->task = Task::with('board_column', 'history', 'history.board_column')->findOrFail($id);
        $view = view('admin.tasks.history', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }
        /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'All_Task_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }

        public function download($id) {
        //        header('Content-type: application/pdf');
        $this->task = Task::with('board_column', 'users', 'files', 'comments', 'notes', 'labels', 'wotype', 'sporttype', 'category')->findOrFail($id);
        $state_id = json_decode($this->task->labels->contacts, true);
        $this->user = User::where('id', '=', $this->task->client_id)->first();
        
        $this->state = State::where('id', '=', $state_id['site_state'])->first();
        $this->country = Country::where('id', '=', $this->state->country_id)->first();
        $this->settings = $this->global;
        
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('admin.tasks.download-task', $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = $this->task->id;
        //       return $pdf->stream();
        return $pdf->download($filename . '.pdf');
    }
}
