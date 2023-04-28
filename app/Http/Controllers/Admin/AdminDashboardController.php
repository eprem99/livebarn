<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */
namespace App\Http\Controllers\Admin;

use App\DashboardWidget;
use App\EmployeeDetails;
use App\ClientDetails;
use App\Helper\Reply;
use App\Task;
use App\TaskboardColumn;
use App\Team;
use App\Traits\CurrencyExchange;
use App\User;
use App\UserActivity;
use Carbon\Carbon;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends AdminBaseController
{
    use CurrencyExchange, AppBoot;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.dashboard';
        $this->pageIcon = 'icon-speedometer';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->taskBoardColumn = TaskboardColumn::all();

        $completedTaskColumn = $this->taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select count(users.id) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "client") as totalClients'),
                DB::raw('(select count(users.id) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "employee" and users.status = "active") as totalEmployees'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id=' . $completedTaskColumn->id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id != ' . $completedTaskColumn->id . ') as totalPendingTasks')
            )
            ->first();
            $from = date('Y-m-d', strtotime('-1 day'));
            $this->pendingTasks = Task::with('labels')
            ->where('tasks.board_column_id', '<>', '1')
            ->where('tasks.start_date', 'LIKE', Carbon::today()->format('Y-m-d'))
            ->orderBy('id', 'desc')
            ->get();
          
            $this->newTasks = Task::with('labels')
            ->where('board_column_id', '=', '1')
            ->orderBy('id', 'desc')
            ->get();

            $this->employee = EmployeeDetails::with('user')->get();
            $this->clients = ClientDetails::with('user')->get();

        $this->userActivities = UserActivity::with('user')->limit(15)->orderBy('id', 'desc')->get();

        // earning chart
        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30);
        $this->toDate = Carbon::now()->timezone($this->global->timezone);

        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-dashboard')
        ->where('status', 1)->get()->pluck('widget_name')->toArray();
        $this->isCheckScript();

        $exists = Storage::disk('storage')->exists('down');
       
        $this->tasks = Task::with('board_column')->select('tasks.*')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('tasks.start_date', '!=', null)
            ->groupBy('tasks.id')
            ->get();

        return view('admin.dashboard.index', $this->data);
    }

public function filter(Request $request) 
{

    $tasks = Task::with('board_column')->select('tasks.*')
    ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
    ->where('tasks.start_date', '!=', null);

    if($request->tech != 0){
        $tasks->where('task_users.user_id', '=', $request->tech);
    }
    if($request->client != 0){
        $tasks->where('tasks.client_id', '=', $request->client);
    }
    if($request->status != 0){
        $tasks->where('board_column_id', '=', $request->status);
    }
    $task = $tasks->groupBy('tasks.id')->get();
    //dd($tasks);
    return Reply::dataOnly($task);
}

    private function progressbarPercent()
    {
        $totalItems = 4;
        $completedItem = 1;
        $progress = [];
        $progress['progress_completed'] = false;

        if ($this->global->company_email != 'company@email.com') {
            $completedItem++;
            $progress['company_setting_completed'] = true;
        }

        if ($this->smtpSetting->verified !== 0 || $this->smtpSetting->mail_driver == 'mail') {
            $progress['smtp_setting_completed'] = true;

            $completedItem++;
        }

        if ($this->user->email != 'admin@example.com') {
            $progress['profile_setting_completed'] = true;

            $completedItem++;
        }


        if ($totalItems == $completedItem) {
            $progress['progress_completed'] = true;
        }

        $this->progress = $progress;


        return ($completedItem / $totalItems) * 100;
    }

    public function widget(Request $request, $dashboardType)
    {
        $data = $request->all();
        unset($data['_token']);
        DashboardWidget::where('status', 1)->where('dashboard_type', $dashboardType)->update(['status' => 0]);

        foreach ($data as $key => $widget) {
            DashboardWidget::where('widget_name', $key)->where('dashboard_type', $dashboardType)->update(['status' => 1]);
        }

        return Reply::success(__('messages.updatedSuccessfully'));
    }
    // client Dashboard start
    public function clientDashboard(Request $request)
    {
        $this->pageTitle = 'app.clientDashboard';

        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->timezone($this->global->timezone)->toDateString();
        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-client-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-client-dashboard')->where('status', 1)->get()->pluck('widget_name')->toArray();
        if (request()->ajax()) {
            if (!is_null($request->startDate) && $request->startDate != "null" && !is_null($request->endDate) && $request->endDate != "null") {
                $this->fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $this->toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            }

            $this->totalClient = User::withoutGlobalScope('active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
                ->where('roles.name', 'client')
                ->whereBetween(DB::raw('DATE(client_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select('users.id')
                ->get()->count();



            $this->recentLoginActivities = User::withoutGlobalScope('active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
                ->where('roles.name', 'client')
                ->whereNotNull('last_login')
                ->whereBetween(DB::raw('DATE(client_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select('users.id', 'users.name', 'users.last_login', 'client_details.company_name')
                ->limit(10)
                ->orderBy('users.last_login', 'desc')
                ->get();

                
            $this->latestClient = User::withoutGlobalScope('active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
                ->where('roles.name', 'client')
                ->whereBetween(DB::raw('DATE(client_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select('users.id', 'users.name', 'users.created_at', 'client_details.company_name')
                ->limit(10)
                ->orderBy('users.created_at', 'Asc')
                ->get();


            // client wise timelogs

            $view = view('admin.dashboard.client-dashboard', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }
        return view('admin.dashboard.client', $this->data);
    }
    // client Dashboard end


    // HR Dashboard start

    public function hrDashboard(Request $request)
    {

        $this->pageTitle = 'app.hrDashboard';
        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->timezone($this->global->timezone)->toDateString();

        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-hr-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-hr-dashboard')->where('status', 1)->get()->pluck('widget_name')->toArray();

        if (request()->ajax()) {
            if (!is_null($request->startDate) && $request->startDate != "null" && !is_null($request->endDate) && $request->endDate != "null") {
                $this->fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $this->toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            }

            $this->totalNewEmployee = EmployeeDetails::whereBetween(DB::raw('DATE(`joining_date`)'), [$this->fromDate, $this->toDate])->get()->count();
            $this->totalEmployeeExits = EmployeeDetails::whereBetween(DB::raw('DATE(`last_date`)'), [$this->fromDate, $this->toDate])->get()->count();

            $this->departmentWiseEmployee = Team::join('employee_details', 'employee_details.department_id', 'teams.id')
                ->whereBetween(DB::raw('DATE(employee_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select(DB::raw('count(employee_details.id) as totalEmployee'), 'teams.team_name')
                ->groupBy('teams.team_name')
                ->get()->toJson();

            $this->roleWiseEmployee = EmployeeDetails::whereBetween(DB::raw('DATE(employee_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->Join('users', 'users.id', 'employee_details.user_id')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.name', '<>', 'client')
                ->select(DB::raw('count(employee_details.id) as totalEmployee'), 'roles.name')
                ->groupBy('roles.name')
                ->orderBy('roles.name', 'ASC')
                ->get()->toJson();


            $view = view('admin.dashboard.hr-dashboard', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }

        return view('admin.dashboard.hr', $this->data);
    }

    // Ticket Dashboard end

}
