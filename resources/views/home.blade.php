@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        You are logged in!
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default tabs-component" data-active="0">
                    <div class="tabs-heading">
                        <a href="#next-to-pay"> Next to pay </a>
                        <a href="#test"> Recently failed transactions</a>
                    </div>
                    <div class="tabs-body">
                        <div id="next-to-pay" class="flex flex-hcc flex-wrap">
                            <?php $pos = 1 ?>
                            @foreach ($week as $key=>$value)
                                <a href="{{route("tenancies.byDate",["date"=>$value["start_date"]."|".$value["end_date"]])}}"
                                   class="square-item flex flex-vcc"
                                >
                                    <h3> {{$key}}</h3>
                                    <span class="info-box-text">{{$value["count"]}}</span>
                                </a>
                                <?php $pos++ ?>
                            @endforeach
                        </div>
                        <div id="test" class="flex flex-vcs">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th class="col-sm-1">@lang('dictionary.status')</th>
                                    <th class="col-sm-4">
                                        <div class="flex flex-vsc">
                                            <span>@lang('dictionary.allocated-against')</span>
                                            <span>(@lang('dictionary.property-address'))</span>
                                        </div>
                                    </th>
                                    <th class="col-sm-2">@lang('dictionary.amount')</th>
                                    <th class="col-sm-2">@lang('dictionary.date')</th>
                                    <th class="col-sm-2">@lang('dictionary.id')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($failure_payments as $payment)
                                    <tr>
                                        <td>{{ (($payment->tenancy_id)?"":"No ")."Match" }}</td>
                                        <td>
                                            <div class="flex flex-hcb">
                                                @if(isset($payment->tenancy_id))
                                                <div class="flex flex-vcb" style="margin-right: auto">
                                                    <span>TEN: {{ $payment->tenancy_id }}</span>
                                                    <span class="text-wrap">{{ $payment->tenancy["property_full_address"] }}</span>
                                                </div>
                                                <span><i class="fa fa-users text-info"></i></span>
                                                @endif
                                                {!! Form::open(['method' => 'get', 'route' => ['gocardless.edit', $payment->id],'class'=>isset($payment->tenancy_id)?'':'col-sm-12 nopadding']) !!}
                                                <button class="btn btn-transparent {{isset($payment->tenancy_id)?"":"flex nopadding flex-hcb col-sm-12"}}">
                                                    @if(isset($payment->tenancy_id))
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    @else
                                                        <span class="text-info">allocate</span>
                                                        <i class="fa fa-question-circle" style="padding-right: 12px"></i>
                                                    @endif
                                                </button>
                                                {!! Form::close() !!}
                                            </div>

                                        </td>
                                        <td>
                                            <div class="flex flex-hcb">
                                                {{ number_format($payment->amount/100,2,'.',',') }} {{ $payment->currency }}
                                                <i class="fa fa-{{$payment->getViewStatus()["icon"]}} text-{{$payment->getViewStatus()["color"]}}"></i>
                                            </div>
                                        </td>
                                        <td data-order="{{strtotime($payment->charge_date)}}">{{date('d/m/Y', strtotime($payment->charge_date))}}</td>
                                        <td>{{ $payment->id }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
