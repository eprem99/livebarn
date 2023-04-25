@extends('layouts.app')
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
            
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.all-tasks.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">

<style>
    .panel-black .panel-heading a,
    .panel-inverse .panel-heading a {
        color: unset!important;
    }
    .list-group-item a {
        color: #FFFFFF !important;
    }
</style>

@endpush
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.tasks.updateTask')<div class="text-right">WO #{{$task->id}}</div></div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateTask','class'=>'ajax-form','method'=>'PUT']) !!}

                        <div class="form-body">
                            <div class="row">
                            <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tasks.taskCategory')
                                        <a href="javascript:;"
                                               id="createTaskCategory"
                                               class="btn btn-xs btn-outline btn-success" style="float:right; margin-left:15px;">
                                                <i class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')
                                            </a>
                                        </label>
                                        <select class="select2 form-control" name="category_id" id="category_id"
                                                data-style="form-control">
                                               <option value=""> --- </option>
                                            @forelse($categories as $category)
                                                @if($category->id == $task->task_category_id)
                                                    <option selected value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                                @else
                                                    <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                                @endif
                                            @empty
                                                <option value="">@lang('messages.noTaskCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label required mb-2"><span>@lang('modules.tasks.site')</span>
                                        <a href="{{ route('admin.site.create') }}" class="btn btn-xs btn-outline btn-success" style="float:right; margin-left:15px;">
                                                <i class="fa fa-plus"></i>@lang('modules.tasks.addsite') 
                                            </a>
                                        </label>

      
                                        <select name="task_labels" class="select2 form-control">
                                            @foreach($taskLabels as $label)
                                                @if($label->id == $task->site_id)
                                                    <option selected value="{{ $label->id }}">{{ $label->label_name }}</option>
                                                @else
                                                    <option value="{{ $label->id }}">{{ $label->label_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="margin-bottom: 9px;" for="client" class="required"> @lang('app.site.client')</label>
                                        <select name="client_id" class="select2 form-control" id="client">
                                            @foreach($clients as $client)
                                                <option @if($task->client_id == $client->id) selected @endif value="{{$client->id}}">{{$client->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.title')</label>
                                        <input type="text" id="heading" name="heading" class="form-control" value="{{ $task->heading }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.description')</label>
                                        <textarea id="description" name="description" class="form-control summernote">{{ $task->description }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.startDate')</label>
                                        <input type="text" name="start_date" id="start_date2" class="form-control" autocomplete="off" value="@if($task->start_date != '-0001-11-30 00:00:00' && $task->start_date != null){{ $task->start_date->format($global->date_format) }} @endif">
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.dueDate')</label>
                                        <input type="text" name="due_date" id="due_date2" class="form-control" autocomplete="off" value="@if($task->due_date != '-0001-11-30 00:00:00' && $task->due_date != null){{ $task->due_date->format($global->date_format) }}@endif">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.tasks.assignTo')
                                            <a href="javascript:;" id="add-employee" class="btn btn-xs btn-success btn-outline"><i class="fa fa-plus"></i></a>
                                        </label>

                                        <select class="select2 select2-multiple " multiple="multiple"
                                                data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                                name="user_id[]" id="user_id">
                                                @foreach($employees as $employee)

                                                    @php
                                                        $selected = '';
                                                    @endphp

                                                    @foreach ($task->users as $item)
                                                        @if($item->id == $employee->id)
                                                            @php
                                                                $selected = 'selected';
                                                            @endphp
                                                        @endif

                                                    @endforeach

                                                    <option {{ $selected }}
                                                            value="{{ $employee->id }}">{{ ucwords($employee->name) }}
                                                    </option>

                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.status')</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach($taskBoardColumns as $taskBoardColumn)
                                                <option @if($task->board_column_id == $taskBoardColumn->id) selected @endif value="{{$taskBoardColumn->id}}">{{ $taskBoardColumn->column_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                                <!--/span-->
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.type')</label>
                                        <select name="task_type" class="select2 form-control">
                                            @foreach($wotype as $type)
                                                @if($type->id == $task->wo_id)
                                                    <option selected value="{{ $type->id }}">{{ $type->name }}</option>
                                                @else
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.sporttype')</label>
                                        <select name="sport_type" class="select2 form-control">
                                            @foreach($sport as $type)
                                                @if($type->id == $task->sport_id)
                                                    <option selected value="{{ $type->id }}">{{ $type->name }}</option>
                                                @else
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.qty')</label>
                                        <input type="text" name="task_qty" class="form-control" value="@if($task->qty){{ $task->qty}}@endif" >
                                    </div>
                                </div>
                            </div> -->


                            <div class="row">
                               <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button" style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i> File Select Or Upload</button>
                                        <div id="file-upload-box" >
                                            <div class="row" id="file-dropzone">
                                                <div class="col-md-12">
                                                    <div class="dropzone"
                                                         id="file-upload-dropzone">
                                                        {{ csrf_field() }}
                                                        <div class="fallback">
                                                            <input name="file" type="file" multiple/>
                                                        </div>
                                                        <input name="image_url" id="image_url"type="hidden" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="taskID" id="taskID">
                                    </div>
                                </div>
                                <div class="row" id="list">
                                    <ul class="list-group" id="files-list">
                                        @forelse($task->files as $file)
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        {{ $file->filename }}
                                                    </div>
                                                    <div class="col-md-3">

                                                            <a target="_blank" href="{{ $file->file_url }}"
                                                               data-toggle="tooltip" data-original-title="View"
                                                               class="btn btn-info btn-circle"><i
                                                                        class="fa fa-search"></i></a>

                                                        @if(is_null($file->external_link))
                                                            &nbsp;&nbsp;
                                                            <a href="{{ route('admin.task-files.download', $file->id) }}"
                                                               data-toggle="tooltip" data-original-title="Download"
                                                               class="btn btn-inverse btn-circle"><i
                                                                        class="fa fa-download"></i></a>
                                                        @endif
                                                        &nbsp;&nbsp;
                                                        <a href="javascript:;" data-toggle="tooltip"
                                                           data-original-title="Delete"
                                                           data-file-id="{{ $file->id }}"
                                                           class="btn btn-danger btn-circle sa-params" data-pk="list"><i
                                                                    class="fa fa-times"></i></a>

                                                        <span class="m-l-10">{{ $file->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        @lang('messages.noFileUploaded')
                                                    </div>
                                                </div>
                                            </li>
                                        @endforelse

                                    </ul>
                                </div>
                            </div>
                            <!--/row-->

                        </div>
                        <div class="form-actions">
                            <button type="button" id="update-task" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
                            <button type="button" id="update-cancel" class="btn btn-danger"><i class="ti-close right-side-toggle"></i> @lang('app.cancel')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

@endsection
@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>
    $('#multiselect').selectpicker();

    @if($labelIds)
        var labelIds = {{ json_encode($labelIds) }};
        $('#multiselect').selectpicker('val', labelIds);
    @endif

    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('admin.task-files.store') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 100,
        maxFiles: 10,
        // acceptedFiles: "image/*,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks:true,
        parallelUploads:10,
        init: function () {
            myDropzone = this;
        }
    });

    myDropzone.on('sending', function(file, xhr, formData) {
        console.log(myDropzone.getAddedFiles().length,'sending');
        var ids = '{{ $task->id }}';
        formData.append('task_id', ids);
    });

    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('messages.taskUpdatedSuccessfully')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('admin.all-tasks.index') }}'

    });

    //cancel task
    $('#update-cancel').click(function(){
        swal({
            title: "Are you sure?",
            text: "All changes will be lost!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                window.location.href = '{{ route('admin.all-tasks.index') }}';
            }
        });
    })
    //    update task
    $('#update-task').click(function () {
            $.easyAjax({
                url: '{{route('admin.tasks.checkTask', [$task->id])}}',
                type: "GET",
                data: {},
                success: function (data) {
                    console.log(data.taskCount);
                    if(data.taskCount > 0){
                        swal({
                            title: "@lang('messages.sweetAlertTitle')",
                            text: "@lang('messages.markCompleteTask')",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "@lang('app.yes')",
                            cancelButtonText: "@lang('messages.confirmNoArchive')",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        }, function (isConfirm) {
                            if (isConfirm) {
                                updateTask();
                            }
                        });
                    }
                    else{
                        updateTask();
                    }

                }
            });


    });

    function updateTask(){
        $.easyAjax({
            url: '{{route('admin.all-tasks.update', [$task->id])}}',
            container: '#updateTask',
            type: "POST",
            data: $('#updateTask').serialize(),
            success: function(response){
                if(myDropzone.getQueuedFiles().length > 0){
                    taskID = response.taskID;
                    $('#taskID').val(response.taskID);
                    myDropzone.processQueue();
                }
                else{
                    var msgs = "@lang('messages.taskCreatedSuccessfully')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('admin.all-tasks.index') }}'
                }
            }
        })
    }

    $('#due_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true,
        startDate: "@if($task->start_date != '-0001-11-30 00:00:00' && $task->start_date != null){{ $task->start_date->format($global->date_format) }} @endif"
    });

    jQuery('#start_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    })

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen", "codeview"]]
        ]
    });

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
        var deleteView = $(this).data('pk');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.task-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE', 'view': deleteView},
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#list ul.list-group').html(response.html);

                        }
                    }
                });
            }
        });
    });

</script>
<script>
    $('#createTaskCategory').click(function(){
        var url = '{{ route('admin.taskCategory.create-cat')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal', url);
    })

    $('#add-employee').click(function () {
        var url = '{{ route('admin.employees.create')}}';
        $('#modelHeading').html("@lang('app.add') @lang('app.employee')");
        $.ajaxModal('#projectTimerModal', url);
    });

</script>

@endpush
