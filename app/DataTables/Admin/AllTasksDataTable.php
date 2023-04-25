<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Task;
use App\TaskboardColumn;
use Carbon\Carbon;
use App\State;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AllTasksDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $taskBoardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();

        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) use ($taskBoardColumns) {

                if ($this->user->can('delete_tasks')) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="' . route('admin.all-tasks.edit', $row->id) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a href="javascript:;"  data-task-id="' . $row->id . '" data-recurring="no" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';
                }elseif($this->user->can('edit_tasks') && $this->user->hasRole('client')){
                    $action = '<a href="' . route('client.all-tasks.edit', $row->id) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }elseif($this->user->can('edit_tasks') && $this->user->hasRole('Editors')){
                    $action = '<a href="' . route('member.all-tasks.edit', $row->id) . '" class="btn btn-info btn-circle"
                    data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }else{
                    $action = '<a target="_blank" href="' . route('front.task-share', [$row->hash]) . '" class="btn btn-info btn-circle"
                    data-toggle="tooltip" data-original-title="View"><i class="fa fa-share-alt" aria-hidden="true"></i></a>';
                }
              //  return $this->user->can('delete_projects');
               return $action;
            })
            ->editColumn('label_name', function ($row) {
                $site = '';            
                if ($row->label_name) {
                    $site = $row->label_name;
                } 
               return ucwords($site);
            })

            ->addColumn('siteid', function ($row) {
                $site = '';            
                if ($row->ids) {
                    $site = $row->ids;
                } 
                
               return $site;
            })
            ->addColumn('site_state', function ($row) {
                $site = '';            
                if ($row->contacts) {
                   $state = json_decode($row->contacts, true);
                    $site = $state['site_state'];
                    if($site != NULL){
                        $site = State::where('id', '=', $site)->first();
                        if($site != '' || !empty($site)){
                            $state = $site->names;
                        }else{
                            $state = '';
                        } 
                    }

                } 
                
               return $state;
            })
            ->addColumn('city', function ($row) {
                $site = '';            
                if ($row->contacts) {
                   $state = json_decode($row->contacts, true);
                    $site = $state['site_city'];
                } 
                
               return $site;
            })
            ->addColumn('due_date', function ($row) {

                // if ($row->due_date->endOfDay()->isPast()) {
                //     return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                // } elseif ($row->due_date->setTimezone($this->global->timezone)->isToday()) {
                //     return '<span class="text-success">' . __('app.today') . '</span>';
                // }
                // return '<span >' . $row->due_date->format($this->global->date_format) . '</span>';
                if($row->start_date){
                    $date = '<div id="changedate" class="row" style="cursor:pointer;">';
                    $date .= '<div class="col-md-12">'.$row->start_date->format($this->global->date_format).'</div>';
                    $date .= '</div>';
                    $date .= '<div id="changedateinput" class="row" style="display:none;">';
                    $date .= '<div class="col-md-9"><input id="datapicker" class="form-control d-none" name="start-date" value="'.$row->start_date->format($this->global->date_format).'"/></div><div class="col-md-2"><button data-task-id="' . $row->id . '" type="button" id="update-date" class="btn btn-success"><i class="fa fa-check"></i></button></div>';
                    $date .= '</div>';
                    return $date;
                }else{
                    $date = '<div id="changedate" class="row" style="cursor:pointer;">';
                    $date .= '<div class="col-md-12">----</div>';
                    $date .= '</div>';
                    $date .= '<div id="changedateinput" class="row" style="display:none;">';
                    $date .= '<div class="col-md-9"><input id="datapicker" class="form-control d-none" name="start-date" value="----"/></div><div class="col-md-2"><button data-task-id="' . $row->id . '" type="button" id="update-date" class="btn btn-success"><i class="fa fa-check"></i></button></div>';
                    $date .= '</div>';
                    return $date;
                }
                
            })



            ->addColumn('users', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    if($row->created_by_id != $member->id){
                        $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . $member->image_url . '"
                        alt="' . ucwords($member->name) . '" class="img-circle" width="25" height="25"> <br/><span>'.ucwords($member->name).'</span>';
                    }
                }
                return $members;
            })
            ->addColumn('assignedTo', function ($row) {
                $members = [];
                foreach ($row->users as $member) {
                    if($row->created_by != $member->name){
                    $members[] = $member->name;
                    }
                }
                return implode(',', $members);
            })
            ->addColumn('assignedBy', function ($row) {
                return ($row->clientName) ? ucwords($row->clientName) : '-';
            })
            ->editColumn('created_by', function ($row) {
                if (!is_null($row->created_by)) {
                    
                    return ($row->created_image) ? '<img src="' . asset_url('avatar/' . $row->created_image) . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by) : '<img src="' . asset('img/default-profile-3.png') . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by);
                }
                return '--';
            })
            ->editColumn('heading', function ($row) {

                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a> ';


               return $name;
            })
            ->editColumn('board_column', function ($row) use ($taskBoardColumns) {
                $status = '<div class="btn-group dropdown">';
                $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs"  style="border-color: ' . $row->label_color . '; color: ' . $row->label_color . '" type="button">' . $row->board_column . ' <span class="caret"></span></button>';
                $status .= '<ul role="menu" class="dropdown-menu pull-right">';
                if ($this->user->can('delete_tasks')) {
                    foreach ($taskBoardColumns as $key => $value) {
                        $status .= '<li><a href="javascript:;" data-task-id="' . $row->id . '" class="change-status" data-status="' . $value->slug . '">' . $value->column_name . '  <span style="width: 15px; height: 15px; border-color: ' . $value->label_color . '; background: ' . $value->label_color . '"
                                class="btn btn-warning btn-small btn-circle">&nbsp;</span></a></li>';
                    }
                }elseif($this->user->can('edit_tasks')){
                    foreach ($taskBoardColumns as $key => $value) {
                        if($value->role_id == 3){
                            $status .= '<li><a href="javascript:;" data-task-id="' . $row->id . '" class="change-status" data-status="' . $value->slug . '">' . $value->column_name . '  <span style="width: 15px; height: 15px; border-color: ' . $value->label_color . '; background: ' . $value->label_color . '"
                                class="btn btn-warning btn-small btn-circle">&nbsp;</span></a></li>';
                        }
                    }

                }else{
                    $status = '<div class="btn-group dropdown">';
                    $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs"  style="border-color: ' . $row->label_color . '; color: ' . $row->label_color . '" type="button">' . $row->board_column . '</button>';
                
                }
                $status .= '</ul>';
                $status .= '</div>';
                return $status;
            })
            ->addColumn('board', function ($row) use ($taskBoardColumns) {
                $status = '<div class="btn-group dropdown">';
                $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs"  style="border-color: ' . $row->label_color . '; color: ' . $row->label_color . '" type="button">' . $row->board_column . ' <span class="caret"></span></button>';
                $status .= '</div>';
                return $status;
            })
            ->addColumn('status', function ($row) {
                return ucfirst($row->column_name);
            })

            ->rawColumns(['board_column', 'board', 'action',  'clientName', 'label_name', 'contacts', 'due_date', 'users', 'created_by', 'heading']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Task $model)
    {
        $request = $this->request();
        $startDate = null;
        $endDate = null;
        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        }
        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
        }
        
        
        
        $hideCompleted = $request->hideCompleted;
        $taskBoardColumn = TaskboardColumn::completeColumn();

        $model = $model->with('users', 'labels')->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('users as clients', 'tasks.client_id', '=', 'clients.id')
            ->join('users as client', 'task_users.user_id', '=', 'client.id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->join('client_details', 'client_details.user_id', '=', 'tasks.client_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->join('task_label_list', 'tasks.site_id', '=', 'task_label_list.id')
            ->selectRaw('tasks.id, tasks.heading, tasks.start_date, tasks.hash, task_label_list.contacts, task_label_list.label_name, task_label_list.id as ids, creator_user.name as created_by, 
            creator_user.id as created_by_id, creator_user.image as created_image, clients.name as clientName,
             tasks.due_date, taskboard_columns.column_name as board_column, taskboard_columns.label_color')
           //  ->orderBy('id', 'desc')
            ->groupBy('tasks.id');
            
            if($this->user->hasRole('admin') || $this->user->hasRole('Editors') || $this->user->can('delete_tasks')){
                
            }elseif($this->user->hasRole('client') && $this->user->can('edit_tasks')){
               // $model = $model->where('tasks.client_id', '=', $this->user->id);
               $model = $model->where('client_details.category_id', '=', $this->user->client_details->category_id);
            }else{
                $model = $model->where('task_users.user_id', '=', user()->id);
            }
         //   dd(user()->id);

        if ($startDate !== null && $endDate !== null) {
            $model->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

                $q->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
            });
        }

        if ($request->assignedTo != '' && $request->assignedTo !=  null && $request->assignedTo !=  'all') {
            $model->where('task_users.user_id', '=', $request->assignedTo);
        }

        if ($request->clientID != '' && $request->clientID !=  null && $request->clientID !=  'all') {
            $model->where('tasks.client_id', '=', $request->clientID);
        }
        if (isset($_GET['stat']) && $_GET['stat'] == '11') {
            $model->where('tasks.board_column_id', '=', '10');
        }
        if ($request->hideCompleted == '1') {
            $model->where('tasks.board_column_id', '<>', '10');
        }
        if ($request->hideClosed == '1') {
            $model->where('tasks.board_column_id', '<>', '11');
        }
        if ($request->hideCanceled == '1') {
            $model->where('tasks.board_column_id', '<>', '12');
        }
        if($request->status != '' && $request->status !=  null && $request->status !=  'all'){
            $model->where('tasks.board_column_id', '=', $request->status);
        }
        if ($request->label != '' && $request->label !=  null && $request->label !=  'all') {
            $model->where('tasks.site_id', '=', $request->label);
        }
        if ($request->wo_id != '' && $request->wo_id !=  null && $request->wo_id !=  'all') {
            $model->where('tasks.wo_id', '=', $request->wo_id);
        }
        if ($request->category_id != '' && $request->category_id !=  null && $request->category_id !=  'all') {
            $model->where('task_label_list.contacts', 'like', '%site_state":"' . $request->category_id . '"%');
            // $model->WhereJsonContains('contacts', ['site_state' => $request->category_id]);
        }

        if ($request->client != '' && $request->client !=  null && $request->client !=  'all') {
            $model->where('tasks.client_id', '=', $request->client);
        }



        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('allTasks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
          // ->orderColumns(['id', 'heading'], '-:column $1')
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make(['extend' => 'export', 'buttons' => ['excel', 'pdf'], 'text' => '<i class="fa fa-download"></i> ' . trans('app.exportExcel') . '&nbsp;<span class="caret"></span>'])
            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["allTasks-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
              	search([
              	   'regex' => true
              	   ]);
     }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
      
        return [
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('app.task') => ['data' => 'heading', 'name' => 'heading'],
            __('modules.tasks.site')  => ['data' => 'label_name', 'name' => 'label_name', 'searchable' => false, 'orderable' => true],
            __('modules.tasks.state')  => ['data' => 'site_state', 'name' => 'contacts', 'searchable' => false, 'orderable' => false],
            __('modules.tasks.city')  => ['data' => 'city', 'name' => 'city', 'searchable' => false, 'orderable' => false],

            __('modules.tasks.client') => ['data' => 'assignedBy', 'name' => 'assignedBy'],

            __('modules.tasks.techsite') => ['data' => 'users', 'name' => 'name', 'orderable' => false, 'printable' => false, 'exportable' => false, 'searchable' => false],
      
            __('Tech') => ['data' => 'assignedTo', 'name' => 'assignedTo', 'visible' => false, 'searchable' => false, 'printable' => true, 'exportable' => true],

            __('app.startDate') => ['data' => 'due_date', 'name' => 'due_date'],
            __('WO Status')  => ['data' => 'board', 'name' => 'Status', 'visible' => false, 'searchable' => false, 'printable' => true, 'exportable' => true],
            
            __('app.columnStatus') => ['data' => 'board_column', 'name' => 'board_column', 'searchable' => false, 'exportable' => false, 'printable' => false],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(40)
                ->addClass('text-center'),
              //  
        ];
        
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
}
