@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-7 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ $pageTitle }} </h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-5 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.employees.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.employees.addNewEmployee') <i class="fa fa-plus" aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<style>
    #employees-table_wrapper .dt-buttons{
        display: none !important;
    }
</style>
@endpush

@section('filter-section')
<div class="row" id="ticket-filters">

    <form action="" id="filter-form">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">@lang('app.status')</label>
                <select class="form-control select2" name="status" id="status" data-style="form-control">
                    <option value="all">@lang('modules.client.all')</option>
                    <option selected="" value="active">@lang('app.active')</option>
                    <option value="deactive">@lang('app.inactive')</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">@lang('modules.employees.title')</label>
                <select class="form-control select2" name="employee" id="employee" data-style="form-control">
                    <option value="all">@lang('modules.client.all')</option>
                    @forelse($employees as $employee)
                        <option value="{{$employee->id}}">{{ ucfirst($employee->name) }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">@lang('modules.employees.role')</label>
                <select class="form-control select2" name="role" id="role" data-style="form-control">
                    <option value="all">@lang('modules.client.all')</option>
                    @forelse($roles as $role)
                        @if ($role->id <= 3)
                            <option value="{{$role->id}}">{{ __('app.' . $role->name) }}</option>
                        @else
                            <option value="{{$role->id}}">{{ ucfirst($role->name )}}</option>
                        @endif
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
<!--         <div class="col-md-12 m-b-10">
        <div class="checkbox checkbox-info">
            <input type="checkbox" checked id="hide-inactive">
            <label for="hide-inactive">@lang('app.hideInactiveTechs')</label>
        </div>
    </div> -->
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-xs-12">&nbsp;</label>
                <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                <button type="button" id="reset-filters" class="btn btn-inverse col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
            </div>
        </div>
    </form>
</div>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    var table;

    $(function() {
        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.textDelete')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.employees.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.easyBlockUI('#employees-table');
                                window.LaravelDataTables["employees-table"].draw();
                                $.easyUnblockUI('#employees-table');
                            }
                        }
                    });
                }
            });
        });
   });


    function loadTable(){
        window.LaravelDataTables["employees-table"].draw();
    }

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    $('#apply-filters').click(function () {
        $('#employees-table').on('preXhr.dt', function (e, settings, data) {
            var employee = $('#employee').val();
            var status   = $('#status').val();
            var role     = $('#role').val();
            if ($('#hide-inactive').is(':checked')) {
                var hideCanceled = 'active';
            } else {
                var hideCanceled = 'all';
            }
            data['employee'] = employee;
            data['status'] = status;
            data['role'] = role;
            data['inactive'] = hideCanceled;
        });

        $.easyBlockUI('#employees-table');
        window.LaravelDataTables["employees-table"].draw();
        $.easyUnblockUI('#employees-table');
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        // $('#status').val('all');
        $('.select2').val('all');
        $('#filter-form').find('select').select2();
        loadTable();
    })

    function exportData(){
        if ($('#hide-inactive').is(':checked')) {
                var hideCanceled = 'active';
            } else {
                var hideCanceled = 'all';
            }
        var employee = $('#employee').val();
        var status   = $('#status').val();
        var role     = $('#role').val();
        var inactive     = hideCanceled;

        var url = '{{ route('admin.employees.export', [':status' ,':employee', ':role']) }}';
        url = url.replace(':role', role);
        url = url.replace(':status', status);
        url = url.replace(':employee', employee);
        url = url.replace(':hide-inactive', employee);

        window.location.href = url;
    }
    $('body').on('click', '.assign_role', function() {
            var id = $(this).data('user-id');
            var role = $(this).data('role-id');;
            var token = "{{ csrf_token() }}";
            if (typeof id !== 'undefined') {

                $.easyAjax({
                    url: '{{route('admin.employees.assignRole')}}',
                    type: "POST",
                    data: {
                        role: role,
                        userId: id,
                        _token: token
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            $.easyBlockUI('#employees-table');
                            window.LaravelDataTables["employees-table"].draw();
                            $.easyUnblockUI('#employees-table');
                        }
                    }
                })
            }

        });
</script>
@endpush
