@extends('layouts.app')

@section('title', trans('dictionary.gocardless'))

@section('content')
    <section class="admin-users-section">
        <div class="container">
            <div class="admin-users-section-bg">
                <div class="admin-users-section-header">
                    <div class="row">
                        <div class="col-sm-3">
                            <h2>@lang('dictionary.feeds') > @lang('dictionary.yodlee')</h2>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="admin-users-section-body">
                    <div class="admin-users-table">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="datatableYodlee" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="col-sm-1">@lang('dictionary.status')</th>
                                            <th class="col-sm-3">@lang('dictionary.allocated-against')
                                                (@lang('dictionary.property-address'))
                                            </th>
                                            <th class="col-sm-2">@lang('dictionary.amount')</th>
                                            <th class="col-sm-2">@lang('dictionary.date')</th>
                                            <th class="col-sm-2">@lang('dictionary.description')</th>
                                            <th class="col-sm-2">@lang('dictionary.id')</th>
                                            <th class="hide">status_payment</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($transactions as $payment)
                                            <tr class="bg-{{$payment->getViewStatus()["color"]}}">
                                                <td>{{ (($payment->tenancy_id)?"":"No ")."Match" }}</td>
                                                <td>
                                                    <div class="flex flex-hcb">
                                                        @if(isset($payment->tenancy_id))
                                                            <div class="flex flex-vcb">
                                                                <span>TEN: {{ $payment->tenancy_id }}</span>
                                                                <span class="text-wrap">{{ $payment->tenancy["property_full_address"] }}</span>
                                                            </div>
                                                            <span><i class="fa fa-users text-info"></i></span>
                                                        @endif
                                                        {!! Form::open(['method' => 'get', 'route' => ['yodlee.edit', $payment->id],'class'=>isset($payment->tenancy_id)?'':'col-sm-12 nopadding']) !!}
                                                        <button class="btn btn-transparent nopadding {{isset($payment->tenancy_id)?"":"flex flex-hcb col-sm-12"}}">
                                                            @if(isset($payment->tenancy_id))
                                                                <i class="fa fa-chevron-circle-right"></i>
                                                            @else
                                                                <span class="text-info">allocate</span>
                                                                <i class="fa fa-question-circle"></i>
                                                            @endif
                                                        </button>
                                                        {!! Form::close() !!}
                                                    </div>

                                                </td>
                                                <td>
                                                    <div class="flex flex-hcb">
                                                        {{ json_decode($payment->amount)->amount }} {{ json_decode($payment->amount)->currency }}
                                                        <i class="fa fa-{{$payment->getViewStatus()["icon"]}} text-{{$payment->getViewStatus()["color"]}}"></i>
                                                    </div>
                                                </td>
                                                <td data-order="{{strtotime($payment->date)}}">{{date('d/m/Y', strtotime($payment->date))}}</td>
                                                <td>{{ $payment->description }}</td>
                                                <td>{{ $payment->id }}</td>
                                                <td class="hide">
                                                    {{ ($payment->tenancy_id)?$payment->getParsedStatus():"pending" }}
                                                </td>
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
        </div>
    </section>

@endsection

@push('scripts')
    <script type="text/javascript">
        $('#datatableYodlee').dataTable({
            order: [[3, 'desc']],
            dom: '<"#table-top.datatable-top"lf<"filter-bar">>rtpi',
            info:true,
            initComplete: function (e) {
                addBtnFilter($('#table-top'), this.api(), [
                    {column: 6, value: "pending", title: "Pending"},
                    {column: 0, value: "Match", title: "Matched"},
                    {column: 0, value: "No Match", title: "No Match"}
                ]);
            }
        });
    </script>
@endpush