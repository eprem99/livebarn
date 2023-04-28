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
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.client.updateTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateClient','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">
                            <h3 class="box-title ">@lang('modules.client.clientDetails')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.client.clientName')</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $userDetail->name }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.client.clientEmail')</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ $userDetail->email }}">
                                        <span class="help-block">@lang('modules.client.emailNote')</span>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.client.password')</label>
                                        <input type="password" style="display: none">
                                        <input type="password" name="password" id="password" class="form-control">
                                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span class="help-block"> @lang('modules.client.passwordUpdateNote') </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('app.mobile')</label>
                                    <div class="form-group">
                                        <input type="tel" name="mobile" id="mobile" class="form-control" value="{{ $userDetail->mobile }}"> 
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.client.officePhoneNumber')</label>
                                        <input type="text" name="office" id="office"  value="{{ $clientDetail->office ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!--/row-->
                            <h3 class="box-title">@lang('modules.client.clientAddressDetails')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.stripeCustomerAddress.country')</label>
                                        <select name="country" class="form-control" id="country">
                                           
                                            <option value>@lang('app.site.country')</option>
                                            @foreach ($countries as $item)
                                                <option
                                                @if ($item->id == $clientDetail->country)
                                                    selected
                                                @endif
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>   
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.stripeCustomerAddress.state')</label>
                                        <select name="state" class="form-control" id="state">
                                            <option value="0"> -- Select -- </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.city')</label>
                                        <input type="text" name="city" id="city"  value="{{ $clientDetail->city ?? '' }}"class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                        <input type="text" name="postal_code" id="postalcode"  value="{{ $clientDetail->postal_code ?? '' }}"   class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.address')</label>
                                        <textarea name="address"  id="address"  rows="3" class="form-control">{{ $clientDetail->address ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!--/row-->
                            <h3 class="box-title ">@lang('modules.client.clientOtherDetails')</h3>
                            <hr>
                            <!--row gst number-->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.login')</label>
                                        <select name="login" id="login" class="form-control">
                                            <option @if($userDetail->login == 'enable') selected @endif value="enable">@lang('app.enable')</option>
                                            <option @if($userDetail->login == 'disable') selected @endif value="disable">@lang('app.disable')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="m-b-10">
                                            <label class="control-label">@lang('modules.emailSettings.emailNotifications')</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" 
                                            @if ($userDetail->email_notifications)
                                                checked
                                            @endif
                                            name="email_notifications" id="email_notifications1" value="1">
                                            <label for="email_notifications1" class="">
                                                @lang('app.enable') </label>

                                        </div>
                                        <div class="radio radio-inline ">
                                            <input type="radio" name="email_notifications"
                                            @if (!$userDetail->email_notifications)
                                                checked
                                            @endif

                                                   id="email_notifications2" value="0">
                                            <label for="email_notifications2" class="">
                                                @lang('app.disable') </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.status')</label>
                                        <select name="status" id="status" class="form-control">
                                            <option @if($userDetail->status == 'active') selected
                                                    @endif value="active">@lang('app.active')</option>
                                            <option @if($userDetail->status == 'deactive') selected
                                                    @endif value="deactive">@lang('app.inactive')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <label>@lang('app.note')</label>
                                    <div class="form-group">
                                        <textarea name="note" id="note" class="form-control summernote" rows="3">{{ $clientDetail->note ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('admin.clients.index') }}" class="btn btn-default">@lang('app.back')</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.clients.update', [$userDetail->id])}}',
            container: '#updateClient',
            type: "POST",
            redirect: true,
            data: $('#updateClient').serialize()
        })
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

    $('#country').select2({
        }).on("change", function (e) {
        var id = $(this).val();
        var url = "{{ route('admin.clients.country',':id') }}";
        url = url.replace(':id', id);
       // console.log(url);
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            data: $('#updateClient').serialize(),
            success: function (data) {
            //  alert(data.data)
                $('#state').html(data.data);
            }
        })
    });
    jQuery(document).ready(function($) {
        $.each($('#country option:selected'), function(){            
       // var id = $(this).val();
        var url = '{{route('admin.clients.country', [$userDetail->id])}}';
      //  url = url.replace(':id', id);
       // console.log(url);
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            data: $('#updateProfile').serialize(),
            success: function (data) {
            //  alert(data.data)
                $('#state').html(data.data);
                
            }
        })
        });
	});
</script>
@endpush
