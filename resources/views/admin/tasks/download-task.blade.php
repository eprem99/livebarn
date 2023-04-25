<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>@lang("app.invoice") # {{ $task->id }}</title>
    <style>

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 100%;
            height: auto;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-size: 14px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        h2 {
            font-weight:normal;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 11px;
        }

        #logo img {
            height: 55px;
            margin-bottom: 15px;
        }

        #company {

        }

        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {

        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 5px 10px 7px 10px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #57B223;
            width: 10%;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }


        table .total {
            background: #57B223;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total
        {
            font-size: 1.2em;
            text-align: center;
        }

        table td.unit{
            width: 35%;
        }

        table td.desc{
            width: 45%;
        }

        table td.qty{
            width: 5%;
        }

        .status {
            margin-top: 15px;
            padding: 1px 8px 5px;
            font-size: 1.3em;
            width: 80px;
            color: #fff;
            float: right;
            text-align: center;
            display: inline-block;
        }

        .status.unpaid {
            background-color: #E7505A;
        }
        .status.paid {
            background-color: #26C281;
        }
        .status.cancelled {
            background-color: #95A5A6;
        }
        .status.error {
            background-color: #F4D03F;
        }

        table tr.tax .desc {
            text-align: right;
            color: #1BA39C;
        }
        table tr.discount .desc {
            text-align: right;
            color: #E43A45;
        }
        table tr.subtotal .desc {
            text-align: right;
            color: #1d0707;
        }
        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 10px 20px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1em;
            white-space: nowrap;
            border-bottom: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }

        table.billing td {
            background-color: #fff;
        }

        table td div#invoiced_to {
            text-align: left;
        }

        #notes{
            color: #767676;
            font-size: 11px;
        }
        .img-circle {
            border-radius: 150%;
            float: left;
            margin-top: -20px;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <table cellpadding="0" cellspacing="0" class="billing">
        <tr>
            <td>
                <div id="invoiced_to">
                    <img src="http://btr.services/user-uploads/app-logo/a320f286f94fc8cc02ed61dd6f3cf76d.png" alt="home" class="admin-logo" style="width: 160px; height: 90px;"/>
                </div>
            </td>
            <td>
                <div id="company">
                    <small>@lang("modules.invoices.generatedBy"):</small>
                    <h2 class="name">{{ ucwords($global->company_name) }}</h2>
                    @if(!is_null($settings))
                        <div>{!! nl2br($global->address) !!}</div>
                        <div>{{ $global->company_phone }}</div>
                    @endif
                        <div>@lang('WO'): {{ $task->id }}</div>
                </div>
            </td>
        </tr>
    </table>
</header>
<main>
    <table>
        <tr>
            <td>
            @if($task->client_id)
                <div class="col-xs-12">
                    <label class="font-12" for="">@lang('modules.tasks.client')</label><br>
                    <img src="http://btr.services/user-uploads/avatar/2b24e165ed64099d08087b320806724c.jpg" class="img-circle" width="35" height="35">
                    {{ ucwords($user->name) }}
                    <hr>
                </div>
            @endif
            </td>
            <td>
            @foreach ($task->users as $item)
                @if($task->create_by->id != $item->id)
                <label class="font-12" for="">@lang('modules.tasks.techsite')</label><br>
                    <img src="http://btr.services/user-uploads/avatar/2b24e165ed64099d08087b320806724c.jpg" class="img-circle" width="35" height="35" alt="">
                    {{ ucwords($item->name) }}
                    @if($item->mobile)<P><strong>Tech Phone: </strong> {{$item->mobile}}</P>@endif
                @endif
            @endforeach
            <hr>
            </td>
            <td>

            @if($task->create_by)
                    <div class="col-xs-12">
                        <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                        <img src="http://btr.services/user-uploads/avatar/2b24e165ed64099d08087b320806724c.jpg" class="img-circle" width="35" height="35" alt="">

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
            </td>
        </tr>
    </table>    
    <table>
        <tr>
            <td>
            <h3>
                @lang('modules.tasks.wodetails')
            </h3>
                @if($task->labels->id)<P><strong>Work Order:  </strong>{{ ucwords($task->id) }}</P>@endif
                @if($task->category)<p><strong>Project:  </strong>{{ ucwords($task->category->category_name) }}</p>@endif
                @if($task->created_at)<p><strong>Order Date:  </strong> {{ $task->created_at->format($global->date_format) }}</p>@endif
                @if($task->heading)<p><strong>Summary:  </strong> {{ ucwords($task->heading) }}</p>@endif
                @if($task->wotype)<p><strong>Work Order Type:  </strong> {{ ucwords($task->wotype->name) }}</p>@endif
                @if($task->sporttype)<p><strong>Sport Type:  </strong> {{ ucwords($task->sporttype->name) }}</p>@endif
                @if($task->qty)<p><strong>Surface Quantity: </strong> {{ ucwords($task->qty) }}</p>@endif
                @if($task->client_id)<p><strong>Project Manager:  </strong> {{ ucwords($user->name) }}</p>@endif
                @if($task->create_by)<p><strong>Submitted By:  </strong> {{ ucwords($task->create_by->name) }}</p>@endif
            </td>
            <td>
                <h3>
                        @lang('modules.tasks.siteinfo')
                </h3>
                @php 
                $contacts = json_decode($task->labels->contacts, true);
                @endphp
                    @if($task->labels->id)<P><strong>Site ID: </strong> {{$task->labels->id}}</P>@endif
                    @if($task->labels->label_name)<P><strong>Site Name:  </strong> {{$task->labels->label_name}}</p>@endif
                    @if(!empty($contacts['site_timezone']))<P><strong>Time Zone:  </strong>{{$contacts['site_timezone']}}</p> @endif
                    <P><strong>Address:  </strong>                            
                    @if(!empty($contacts['site_address'])){{$contacts['site_address']}}, @endif 
                    @if(!empty($contacts['site_city'])) {{$contacts['site_city']}}, @endif 
                    @if(!empty($state->names)){{$state->names}}, @endif 
                    @if(!empty($contacts['site_zip'])) {{$contacts['site_zip']}}, @endif  
                    @if(!empty($country->name)){{$country->name}} @endif </p>
                        
                <h3>
                    @lang('modules.tasks.sitecontacts')
                </h3>
                    @if($contacts['site_pname'])<P><strong>Primary:  </strong> {{ $contacts['site_pname'] }}</p>@endif
                    @if($contacts['site_pphone'])<P><strong>Phone:  </strong> {{$contacts['site_pphone']}}</p>@endif
                    @if($contacts['site_pemail'])<P><strong>Email:  </strong> {{$contacts['site_pemail']}}</p>@endif
            </td>
        </tr>
    </table>   
    <div class="description">
        <h3>@lang('app.task')</h3>
        {!! $task->description ?? __('messages.noDescriptionAdded') !!}
    </div> 
    <div class="notes">
        <h3>@lang('app.notes')</h3>
        <div id="note-list">
            @forelse($task->notes as $note)
                <div class="row b-b m-b-5 font-12">
                    <div class="col-xs-12 m-b-5">
                        <span class="font-semi-bold">{{ ucwords($note->user->name) }}</span> <span class="text-muted font-12">{{ ucfirst($note->created_at->format($global->date_format)) }}  {{ ucfirst($note->created_at->format($global->time_format)) }}</span>
                    </div>
                    <div class="col-xs-10">
                        {!! ucfirst($note->note)  !!}
                    </div>
                </div>
            @empty
                <div class="col-xs-12">
                    @lang('messages.noNoteFound')
                </div>
            @endforelse
        </div>
    </div> 
</main>
</body>
</html>