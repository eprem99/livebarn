<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */
namespace App\Http\Controllers;

use App\ClientDetails;
use App\EmployeeDetails;
use App\Http\Requests\TicketForm\StoreTicket;
use App\Task;
use App\Ticket;
use App\TicketCustomForm;
use App\TicketReply;
use App\TicketType;
use App\UniversalSearch;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Helper\Reply;
use App\PusherSetting;
use App\Setting;
use App\TaskboardColumn;
use App\TaskFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Hash;
use Nwidart\Modules\Facades\Module;
use GuzzleHttp\Client;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->global = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function login()
    {
        return redirect(route('login'));
    }

    public function app()
    {
        $setting = Setting::select('id', 'company_name')->first();

        return ['data' => $setting];
    }

    public function taskDetail($id)
    {
        $this->task = Task::with('board_column', 'users', 'files')->findOrFail($id);
        $view = view('task_detail', [
            'task' => $this->task,
            'global' => $this->global,
        ])->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function taskFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('task-files', ['taskFiles' => $this->taskFiles]);
    }

    public function history($id)
    {

        $this->task = Task::with('board_column', 'history', 'history.board_column')->findOrFail($id);
        $view = view('admin.tasks.history', [
            'task' => $this->task,
            'global' => $this->global,
        ])->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function taskboard(Request $request, $encrypt)
    {

        try {
            $companyName = decrypt($encrypt);
            if ($companyName != $this->global->company_name) {
                abort(404);
            }
        } catch (DecryptException $e) {
            abort(404);
        }

        $this->pusherSettings = PusherSetting::first();
        $this->startDate = Carbon::today()->subDays(15)->format($this->global->date_format);
        $this->endDate = Carbon::today()->addDays(15)->format($this->global->date_format);
        $this->clients = User::allClients();
        $this->employees = User::allEmployees();


        return view('taskboard', [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'projects' => $this->projects,
            'clients' => $this->clients,
            'employees' => $this->employees,
            'global' => $this->global,
            'pusherSettings' => $this->pusherSettings,
        ]);
    }

    public function taskBoardData(Request $request)
    {

        if (request()->ajax()) {

            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

            $boardColumns = TaskboardColumn::with(['tasks' => function ($q) use ($startDate, $endDate, $request) {
                $q->with(['comments', 'users'])
                    ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                    ->join('users', 'task_users.user_id', '=', 'users.id')
                    ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
                    ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
                    ->select('tasks.*')
                    ->groupBy('tasks.id');

                $q->where(function ($task) use ($startDate, $endDate) {
                    $task->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

                    $task->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
                });


                if ($request->assignedTo != '' && $request->assignedTo != null && $request->assignedTo != 'all') {
                    $q->where('task_users.user_id', '=', $request->assignedTo);
                }

                if ($request->assignedBY != '' && $request->assignedBY != null && $request->assignedBY != 'all') {
                    $q->where('creator_user.id', '=', $request->assignedBY);
                }

                $q->where('tasks.is_private', '=', 0);
            }])->orderBy('priority', 'asc')->get();

            $this->boardColumns = $boardColumns;

            $this->startDate = $startDate;
            $this->endDate = $endDate;

            $view = view('taskboard_board_data', [
                'boardColumns' => $this->boardColumns,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'global' => $this->global,
            ])->render();
            return Reply::dataOnly(['view' => $view]);
        }
    }

    public function taskShare($id)
    {
        $this->pageTitle = 'app.task';
        
        $this->task = Task::with('board_column', 'users')
            ->where('hash', $id)
            ->firstOrFail();
        $this->clientDetail = User::where('id', '=', $this->task->client_id)->first();
        return view('task-share', [
            'task' => $this->task,
            'global' => $this->global,
            'clientDetail' => $this->clientDetail
        ]);
    }


    /**
     * custom lead form
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketForm()
    {
        $this->pageTitle = 'app.ticketForm';
        $this->ticketFormFields = TicketCustomForm::where('status', 'active')
            ->orderBy('field_order', 'asc')
            ->get();
        $this->types = TicketType::all();

        return view('embed-forms.ticket-form', [
            'pageTitle' => $this->pageTitle,
            'ticketFormFields' => $this->ticketFormFields,
            'global' => $this->global,
            'types' => $this->types
        ]);
    }

    /**
     * save lead
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketStore(StoreTicket $request)
    {
        $setting = \App\Setting::with('currency')->first();

        if ($setting->ticket_form_google_captcha) {
            // Checking is google recaptcha is valid
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                return Reply::error(__('auth.recaptchaFailed'));
            }
        }

        // $rules['g-recaptcha-response'] = 'required';
        $existing_user = User::withoutGlobalScopes(['active'])->select('id', 'email')->where('email', $request->input('email'))->first();
        $newUser = $existing_user;
        if (!$existing_user) {
            $password = str_random(8);
            // create new user
            $client = new User();
            $client->name = $request->input('name');
            $client->email = $request->input('email');
            $client->password = Hash::make($password);
            $client->save();

            // attach role
            $client->attachRole(3);

            $clientDetail = new ClientDetails();
            $clientDetail->user_id = $client->id;
            $clientDetail->save();

            //log search
            $this->logSearchEntry($client->id, $client->name, 'admin.clients.edit', 'client');
            $this->logSearchEntry($client->id, $client->email, 'admin.clients.edit', 'client');

            $newUser = $client;
        }

        // Create New Ticket
        $ticket = new Ticket();
        $ticket->subject = (request()->has('ticket_subject') ? $request->ticket_subject : '');;
        $ticket->status = 'open';
        $ticket->user_id = $newUser->id;
        $ticket->type_id = (request()->has('type') ? $request->type : null);
        $ticket->priority = (request()->has('priority') ? $request->priority : 'medium');
        $ticket->save();

        //save first message
        $reply = new TicketReply();
        $reply->message = (request()->has('message') ? $request->message : '');
        $reply->ticket_id = $ticket->id;
        $reply->user_id = $newUser->id;; //current logged in user
        $reply->save();

        return Reply::success(__('messages.ticketAddSuccess'));
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $setting = \App\Setting::with('currency')->first();

        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                    [
                        'secret' => $setting->google_recaptcha_secret,
                        'response' => $googleRecaptchaResponse,
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        return $body->success;
    }

    public function googleRecaptchaMessage()
    {
        throw ValidationException::withMessages([
            'g-recaptcha-response' => [trans('auth.recaptchaFailed')],
        ]);
    }

    public function logSearchEntry($searchableId, $title, $route, $type)
    {
        $search = new UniversalSearch();
        $search->searchable_id = $searchableId;
        $search->title = $title;
        $search->route_name = $route;
        $search->module_type = $type;
        $search->save();
    }
}
