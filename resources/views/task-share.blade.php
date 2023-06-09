<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $task->heading }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

    <!-- This is Sidebar menu CSS -->
    <link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}"   rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}"   rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

@stack('head-script')

<!-- This is a Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    <link href="{{ asset('css/colors/default.css') }}" id="theme"  rel="stylesheet">
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/custom-new.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .admin-logo {
            max-height: 40px;
        }

        .right-sidebar {
            position: unset;
            background: #fff;
            top: 0;
            height: 100%;
            box-shadow: 5px 1px 40px rgba(0,0,0,.1);
            transition: all .3s ease;
            display: block;
            width: 100%;
        }

        .nav>li>a {
           padding: 10px 7px;
        }
    </style>
</head>
<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">

<!-- Left navbar-header end -->
    <!-- Page Content -->
    <div id="page-wrapper" style="margin-left: 0px !important;">
        <div class="container-fluid">

        <!-- .row -->
            <div class="row">
                <div class="col-md-offset-2 col-md-8 m-t-40 m-b-40">
                        <img src="{{ $global->logo_url }}" alt="home" class="admin-logo"/>
                </div>

                <div class="col-md-offset-2 col-md-8 col-md-offset-2">
                    <div class="card">
                        <div class="card-body right-sidebar">
           
                            <div class="rpanel-title"> @lang('app.task') # {{ $task->id }}</div>
                            <div class="r-panel-body p-t-0">

                                <div class="row">
                                    <div class="col-xs-6 col-md-9 p-t-20 b-r h-scroll">
                          
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h3>
                                                        @lang('modules.tasks.wodetails')
                                                   </h3>
                                                    @if($task->labels->id)<P><strong>Work Order:  </strong>{{ ucwords($task->id) }}</P>@endif
                                                   @if($task->task_category_id)<p><strong>Project:  </strong>{{ ucwords($task->category->category_name) }}</p>@endif
                                                   <!-- @if($task->p_order)<p><strong>PO Number:  </strong> {{ ucwords($task->p_order) }}</p>@endif -->
                                                   @if($task->created_at)<p><strong>Order Date:  </strong> {{ $task->created_at->format($global->date_format) }}</p>@endif
                                                   @if($task->heading)<p><strong>Summary:  </strong> {{ ucwords($task->heading) }}</p>@endif
                                                   @if($task->wotype)<p><strong>Work Order Type:  </strong> {{ ucwords($task->wotype->name) }}</p>@endif
                                                   @if($task->sporttype)<p><strong>Sport Type:  </strong> {{ ucwords($task->sporttype->name) }}</p>@endif
                                                   @if($task->qty)<p><strong>Surface Quantity: </strong> {{ ucwords($task->qty) }}</p>@endif
                                                   @if($task->client_id)<p><strong>Client:  </strong> {{ ucwords($clientDetail->name) }}</p>@endif
                                                   @if($task->create_by)<p><strong>Submitted By:  </strong> {{ ucwords($task->create_by->name) }}</p>@endif
                                                </div>
                                                <div class="col-md-6">
                                                <h3>
                                                     @lang('modules.tasks.siteinfo')
                                                </h3>
                                                @php 
                                                $contacts = json_decode($task->labels->contacts, true);
                                                @endphp
                                                    @if($task->labels->id)<P><strong>Site ID: </strong> {{$task->labels->id}}</P>@endif
                                                    @if($task->labels->label_name)<P><strong>Site Name:  </strong> {{$task->labels->label_name}}</p>@endif
                                                    @if($task->labels->id)<P><strong>Time Zone:  </strong></p>@endif
                                                    @if(!empty($contacts['site_address']))<P><strong>Address:  </strong>{{$contacts['site_address']}}</p>@endif
                                                <h3>
                                                    @lang('modules.tasks.sitecontacts')
                                                </h3>
                                                    @if($contacts['site_pname'])<P><strong>Primary:  </strong> {{ $contacts['site_pname'] }}</p>@endif
                                                    @if($contacts['site_pphone'])<P><strong>Phone:  </strong> {{$contacts['site_pphone']}}</p>@endif
                                                    @if($contacts['site_pemail'])<P><strong>Email:  </strong> {{$contacts['site_pemail']}}</p>@endif
                                                </div>
                                            </div>
                                
                                        </div>


                                        <ul class="nav customtab nav-tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">@lang('app.task')</a></li>
                                            <li role="presentation" class=""><a href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.file') ({{ sizeof($task->files) }})</a></li>
                                            <!-- <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.comment') ({{ count($task->comments) }})</a></li> -->
                            
                                            <li role="presentation" >  <a href="#history1" id="view-task-history" role="tab" data-toggle="tab" aria-expanded="false" data-task-id="{{ $task->id }}" > <span class="hidden-xs">@lang('modules.tasks.history')</span></a></li>
                                        </ul>
                            
                                        <div class="tab-content" id="task-detail-section">
                                            <div role="tabpanel" class="tab-pane fade active in" id="home1">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12 m-t-10">
                                                        <label class="font-bold">@lang('app.description')</label><br>
                                                        <div class="task-description m-t-10">
                                                            {!! $task->description ?? __('messages.noDescriptionAdded') !!}
                                                        </div>
                                                    </div>
                            
                                                </div>
                                            </div>
                            
                           
                                            <div role="tabpanel" class="tab-pane" id="messages1">
                                                <div class="col-xs-12">
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
                                                                
                                                                    <span class="clearfix m-l-10">{{ $file->created_at->diffForHumans() }}</span>
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
                            
                                            <!-- <div role="tabpanel" class="tab-pane" id="settings1">
                                                <div class="col-xs-12 b-b">
                                                    <h4>@lang('modules.tasks.comment')</h4>
                                                </div>
                            
                                                <div class="col-xs-12" id="comment-container">
                                                    <div id="comment-list">
                                                        @forelse($task->comments as $comment)
                                                            <div class="row b-b m-b-5 font-12">
                                                                <div class="col-xs-12">
                                                                    <h5>{{ ucwords($comment->user->name) }} <span class="text-muted font-12">{{ ucfirst($comment->created_at->diffForHumans()) }}</span></h5>
                                                                </div>
                                                                <div class="col-xs-12">
                                                                    {!! ucfirst($comment->comment)  !!}
                                                                </div>
                            
                                                            </div>
                                                        @empty
                                                            <div class="col-xs-12">
                                                                @lang('messages.noRecordFound')
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div> -->
                            
                                            <div role="tabpanel" class="tab-pane" id="history1">
                                                <div class="col-xs-12">
                                                    <label class="font-bold">@lang('modules.tasks.history')</label>
                                                </div>
                                                <div class="col-xs-12" id="task-history-section">
                                                </div>
                                            </div>
                            
                                        </div>
                            
                            
                            
                                        <div class="col-xs-12" id="task-history-section">
                                        </div>
                                    </div>
                            
                                    <div class="col-xs-6 col-md-3">
                            
                                        <div class="row">
                                            <div class="col-xs-12 p-10 p-t-20 ">
                                                <span id="columnStatusColor" style="width: 15px; height: 15px; background-color: {{ $task->board_column->label_color }}" class="btn btn-small btn-circle">&nbsp;</span> <span id="columnStatus">{{ $task->board_column->column_name }}</span>
                                            </div>
                            
                                            <div class="col-xs-12">
                                                <hr>
                                                @if($task->client_id)
                                                    <div class="col-xs-12">
                                                        <label class="font-12" for="">@lang('modules.tasks.client')</label><br>
                                                        <img src="{{ $clientDetail->image_url }}" data-toggle="tooltip"
                                                            data-original-title="{{ ucwords($clientDetail->name) }}" data-placement="right" class="img-circle" width="35" height="35" alt="">

                                                        {{ ucwords($clientDetail->name) }}
                                                        <hr>
                                                    </div>
                                                @endif
                                                <hr>
                                            </div>
                                            <div class="col-xs-12">
                                                @foreach ($task->users as $item)
                                                    @if($task->create_by->id != $item->id)
                                                        <label class="font-12" for="">@lang('modules.tasks.techsite')</label><br>
                                                        <img src="{{ $item->image_url }}" data-toggle="tooltip"
                                                        data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                                                        class="img-circle" width="35" height="35" alt="">
                                                        {{ ucwords($item->name) }}
                                                        @if($item->mobile)<P><strong>Tech Phone: </strong> {{$item->mobile}}</P>@endif
                                                    @endif
                                                @endforeach
                                                <hr>
                                            </div>
                                            @if($task->create_by)
                                                <div class="col-xs-12">
                                                    <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                                                    <img src="{{ $task->create_by->image_url }}" data-toggle="tooltip"
                                                    data-original-title="{{ ucwords($task->create_by->name) }}" data-placement="right" class="img-circle" width="35" height="35" alt="">
                                                    {{ ucwords($task->create_by->name) }}
                                                    <hr>
                                                </div>
                                            @endif
                            
                                            @if($task->start_date)
                                                <div class="col-xs-12  ">
                                                    <label class="font-12" for="">@lang('app.startDate')</label><br>
                                                    <span class="text-success" >{{ $task->start_date->format($global->date_format) }}</span><br>
                                                    <hr>
                                                </div>
                                            @endif
                            
                                    
                                        </div>
                            
                            
                                    </div>
                            


                                </div>

                            </div>

                            

                   
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="subTaskModal" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <span class="caption-subject font-red-sunglo bold uppercase" id="subTaskModelHeading">Sub Task e</span>
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

<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='https:{{ asset('js/bootstrap-select.min.js') }}'></script>

<!-- Sidebar menu plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

{{--sticky note script--}}
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.init.js') }}"></script>
<script>
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    })

    
    $('#view-task-history').click(function () {
        var id = $(this).data('task-id');

        var url = '{{ route('front.task-history', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                $('#task-history-section').html(response.view);
            }
        })

    })

    $('.close-task-history').click(function () {
        $('#task-detail-section').show();
        $('#task-history-section').html('');
        $(this).hide();
        $('#view-task-history').show();
    })

    $('#uploadedFiles').click(function () {

        var url = '{{ route("front.task-files", ':id') }}';

        var id = {{ $task->id }};
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });
</script>

</body>
</html>
