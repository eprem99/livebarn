<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\ClientDetails;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class ClientsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                if(in_array('clients',$this->user->modules)){
                    $action = '<div class="btn-group dropdown m-r-10">
                    <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                    <ul role="menu" class="dropdown-menu pull-right">
                      <li><a href="' . route('admin.clients.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                      <li><a href="' . route('admin.clients.show', [$row->id]) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . __('app.view') . '</a></li>
                      <li><a href="javascript:;"  data-user-id="' . $row->id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';
    
                    $action .= '</ul> </div>';
                }else{
                    $action = '<a href="' . route('client.clients.edit', [$row->id]) . '" class="btn btn-info btn-circle" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn(
                'name',
                function ($row) {
                    if(in_array('clients',$this->user->modules)){
                        return '<a href="' . route('admin.clients.show', $row->id) . '">' . ucfirst($row->name) . '</a>';
                    }else{
                        return '<a href="' . route('client.clients.edit', $row->id) . '">' . ucfirst($row->name) . '</a>';
                    }
                }
            )
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    if ($row->status == 'active') {
                        return '<label class="label label-success">' . __('app.active') . '</label>';
                    } else {
                        return '<label class="label label-danger">' . __('app.inactive') . '</label>';
                    }
                }
            )
            ->addIndexColumn()
            ->rawColumns(['name', 'action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $request = $this->request();
        

        if(!in_array('clients',$this->user->modules)){
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.status')
            ->where('client_details.category_id', '=', $this->clientDetail->category_id)
            ->where('roles.name', 'client');
        }else{
            $users = User::withoutGlobalScope('active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.status')
            ->where('roles.name', 'client');  
        }    



        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $users = $users->where(DB::raw('DATE(users.`created_at`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $users = $users->where(DB::raw('DATE(users.`created_at`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && $request->status != '') {
            $users = $users->where('users.status', $request->status);
        }elseif($request->status == ''){
            $users = $users->where('users.status', 'active');
        }
        if (!is_null($request->country_id) && $request->country_id != 'all') {   
            $users->whereHas('country', function ($query)use($request) {
                $users = $query->where('id',  $request->country_id);
            });      
        }


        return $users;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        if($this->user->can('delete-client')){
            return $this->builder()
            ->setTableId('clients-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["clients-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ])
            ->buttons(Button::make(['extend' => 'export', 'buttons' => ['excel', 'pdf'], 'text' => '<i class="fa fa-download"></i> ' . trans('app.exportExcel') . '&nbsp;<span class="caret"></span>']));
    

        }else{
            return $this->builder()
            ->setTableId('clients-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->parameters([

                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ])
            ->buttons(Button::make(['extend' => 'export', 'buttons' => ['excel', 'csv'], 'text' => '<i class="fa fa-download"></i> ' . trans('app.exportExcel') . '&nbsp;<span class="caret"></span>']));
    
        }
}

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            __('app.name') => ['data' => 'name', 'name' => 'name'],
            __('app.email') => ['data' => 'email', 'name' => 'email'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
            __('app.createdAt') => ['data' => 'created_at', 'name' => 'created_at'],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(70)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'clients_' . date('YmdHis');
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
