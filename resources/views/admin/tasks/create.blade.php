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
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
<style>
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: unset!important;
    }

    .panel-black a, .panel-inverse a {
        color: #4c5667;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.tasks.newTask')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'storeTask','class'=>'ajax-form','method'=>'POST']) !!}

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
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
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
                                                <option value="{{ $label->id }}">{{ $label->label_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="margin-bottom: 9px;" for="client" class="required"> @lang('app.site.client')</label>
                                        <select name="client_id" class="select2 form-control" id="client">
                                            @foreach($clients as $client)
                                                <option value="{{$client->id}}">{{$client->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.title')</label>
                                        <input type="text" id="heading" name="heading" class="form-control" >
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.description')</label>
                                        <textarea id="description" name="description" class="form-control summernote"></textarea>
                                    </div>
                                </div>

                                <div class="row" id="dependent-fields" style="display: none">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.tasks.dependentTask')</label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.tasks.chooseTask')" name="dependent_task_id" id="dependent_task_id" >
                                                <option value=""></option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">@lang('app.startDate')</label>
                                                <input type="text" name="start_date" id="start_date2" class="form-control" value="" autocomplete="off">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <!--/span-->
                                        <div class="col-md-3" style="display: none">
                                            <div class="form-group">
                                                <label class="control-label">@lang('app.dueDate')</label>
                                                <input type="text" name="due_date" id="due_date2" class="form-control" value=""
                                                       autocomplete="off">
                                            </div>
                                        </div>


                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label required">@lang('modules.tasks.assignTo')</label>
                                                <a href="javascript:;" id="add-employee" class="btn btn-xs btn-success btn-outline"><i class="fa fa-plus"></i></a>
                                                <a href="javascript:;" style="color:#1b69b6" class="text-info" id="assign-self">@lang('modules.tasks.assignMe')</a>
                                                <select class="select2 select2-multiple " multiple="multiple"
                                                        data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                                        name="user_id[]" id="user_id">
                                                    <option value=""></option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                    @endforeach
                                                </select>
    
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="" class="control-label">@lang('app.status')</label>
                                                <select name="board_column_id" id="board_column_id" class="form-control">
                                                    @foreach ($taskboardColumns as $item)
                                                        <option value="{{ $item->id }}">{{ $item->column_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.type')</label>
                                        <select name="task_type" class="select2 form-control">
                                        <option value=""> --- </option>
                                            @foreach($wotype as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.sporttype')</label>
                                        <select name="sport_type" class="select2 form-control">
                                        <option value=""> --- </option>
                                            @foreach($sport as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.qty')</label>
                                        <input type="text" name="task_qty" class="form-control" value="">
                                    </div>
                                </div>
 
                                    <!--/span-->

                                    <!--/span-->
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
                                            
                                            <input type="hidden" name="addedFiles" id="addedFiles">
                                        </div>
                                    </div>
    
                                </div>
                                
                            </div>
                            <!--/row-->

                        </div>
                        <div class="form-actions">
                            <button type="button" id="store-task" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

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
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="taskLabelModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
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
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>
//    $('#multiselect').selectpicker();
    projectID = '';
    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('admin.task-files.store') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 100,
        maxFiles: 10,
        //  acceptedFiles: "image/*,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
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
        var ids = $('#taskID').val();
        formData.append('task_id', ids);
    });

    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('messages.taskCreatedSuccessfully')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('admin.all-tasks.index') }}'

    });

    $('#assign-self').click(function () {
        $('#user_id').val('{{$user->id}}');
        $('#user_id').select2().trigger('change');
        $('#assign-self-block').remove();
    });
    //    update task
    $('#store-task').click(function () {
        $('#addedFiles').val(myDropzone.getQueuedFiles().length);
        $.easyAjax({
            url: '{{route('admin.all-tasks.store')}}',
            container: '#storeTask',
            type: "POST",
            data: $('#storeTask').serialize(),
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
    });

    $('#due_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    });
    var minDate = new Date();
    $('#due_date2').datepicker("update", minDate);
    $('#due_date2').datepicker('setStartDate', minDate);

    jQuery('#start_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function (selected) {

        var minDate = new Date(selected.date.valueOf());
        $('#due_date2').datepicker("update", minDate);
        $('#due_date2').datepicker('setStartDate', minDate);
    });

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
            ["view", ["fullscreen"]]
        ]
    });

    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true,
        format: '{{ $global->date_picker_format }}'
    });



</script>
<script>
    $('#createTaskCategory').click(function(){
        var url = '{{ route('admin.taskCategory.create-cat')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal', url);
    })

    $('#createTaskLabel').click(function(){
        var url = '{{ route('admin.site.create-label')}}';
        $('#modelHeading').html("");
        $.ajaxModal('#taskLabelModal', url);
    })

    $('#add-employee').click(function () {
        var url = '{{ route('admin.employees.create')}}';
        $('#modelHeading').html("@lang('app.add') @lang('app.employee')");
        $.ajaxModal('#projectTimerModal', url);
    });

</script>
@endpush

