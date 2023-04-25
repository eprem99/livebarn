@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.projects')</li>
            </ol>
        </div>
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">

            <a href="{{ route('admin.clients.edit',$client->id) }}"
               class="btn btn-outline btn-success btn-sm">@lang('modules.lead.edit')
                <i class="fa fa-edit" aria-hidden="true"></i></a>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@section('content')

    <div class="row">


        @include('admin.clients.client_header')

        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('admin.clients.tabs')

                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="checkbox checkbox-info">
                                                    <input type="checkbox" id="hide-completed-tasks">
                                                    <label for="hide-completed-tasks">@lang('app.hideCompletedTasks')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                                                    id="tasks-table">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>@lang('app.task')</th>
                                                        <th>@lang('app.dueDate')</th>
                                                        <th>@lang('app.status')</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </section>
                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script>
    showTable();

    $('#leave-table').dataTable({
        responsive: true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0, 'width': '20%' },
            { responsivePriority: 2, targets: 1, 'width': '20%' }
        ],
        "autoWidth" : false,
        searching: false,
        paging: false,
        info: false
    });

    var table;

    function showTable() {
        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        } else {
            var hideCompleted = '0';
        }

        var url = '{{ route('admin.clients.tasks', [$client->id, ':hideCompleted']) }}';
        url = url.replace(':hideCompleted', hideCompleted);

        table = $('#tasks-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: url,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            "order": [[0, "desc"]],
            columns: [
                { data: 'id', name: 'id' },
                {data: 'heading', name: 'heading', width: '20%'},
                {data: 'due_date', name: 'due_date'},
                {data: 'column_name', name: 'taskboard_columns.column_name'},
            ]
        });
    }

    $('#hide-completed-tasks').click(function () {
        showTable();
    });

    $('#tasks-table').on('click', '.show-task-detail', function () {
        $(".right-sidebar").slideDown(50).addClass("shw-rside");

        var id = $(this).data('task-id');
        var url = "{{ route('admin.all-tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    })
    $('ul.showClientTabs .clientProjects').addClass('tab-current');
   
   </script>
@endpush