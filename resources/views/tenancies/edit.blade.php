@extends('layouts.app')

@section('title', trans('dictionary.tenancies'))

@section('content')
    <!-- General Login Section Starts Here -->
    <section class="general-registration-section">
        <div class="container general-registration-white-bg">
            {!! Form::open(['method' => 'put', 'route' => ['tenancies.update', $tenancy->id]]) !!}
            <div class="general-registration-top">
                <div class="row">
                    <div class="col-sm-11 col-sm-offset-1">
                        <h2>@lang('dictionary.update') @lang('dictionary.tenancy'): {{ $tenancy->id }} </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 col-sm-offset-1 general-registration-left">
                        <label for="selectcuenta" class="btn-block">Yodlee Transactions</label>
                        <select data-placeholder="Select the Transaction(s)"
                                class="chosen-select form-control" multiple tabindex="4"
                                name="yodlee_act[]" id="yodlee_act">
                            @foreach ($yodlee_available as $account)
                                <option value="{{ $account }}">{{ $account }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5 general-registration-left">

                        <label for="selectcuenta" class="btn-block">GoCardLess Payments</label>
                        <select data-placeholder="Select the Payment(s)"
                                class="chosen-select form-control" multiple tabindex="4"
                                name="gocardless_act[]" id="gocardless_act">
                            @foreach ($gocardless_available as $account)
                                <option value="{{ $account }}">{{ $account }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="general-registration-bottom">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <button class="btn btn-primary waves-effect waves-light custom-form-btn">@lang('dictionary.add-payment')
                            (s)
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            @if(count($payments)>0)
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-4">
                        <h2 class="text-center">@lang('dictionary.payments')</h2>
                    </div>
                </div>
                <div class="admin-users-section-body">

                    <div class="admin-users-table">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="paymentsTenancy" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>@lang('dictionary.id')</th>
                                            <th>@lang('dictionary.amount')</th>
                                            <th>@lang('dictionary.origin')</th>
                                            <th>@lang('dictionary.date')</th>
                                            <th>@lang('dictionary.status')</th>
                                            <th>@lang('dictionary.payType')</th>
                                            <th>@lang('dictionary.delete')</th>
                                            <th class="hide"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($payments as $transaction)
                                            <tr>
                                                <td>{{ $transaction['id'] }}</td>
                                                <td>{{ $transaction['amount'] }}</td>
                                                <td>{{ $transaction['origin'] }}</td>
                                                <td>{{ $transaction['date'] }}</td>
                                                <td>{{ $transaction['status'] }}</td>
                                                <td> {{$transaction['pay_type']}} </td>
                                                <td class="text-center">

                                                    {!! Form::open(['method' => 'post', 'route' => [ $transaction['origin'].'.deleteTenancy', $transaction['id'] ], 'data-confirm' => trans('messages.confirm'), 'data-title' => trans('messages.confirm-title'), 'data-type' => 'warning']) !!}
                                                    <input type="hidden" id="idUser" class="form-control" name="tenancy"
                                                           value='null'>
                                                    <button class="btn btn-icon waves-effect waves-light btn-primary"><i
                                                                class="fa fa-trash-o"></i></button>
                                                    {!! Form::close() !!}
                                                </td>
                                                <td class="hide"> {{ ($transaction['isDeposit']?'Yes':'No') }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="admin-users-section-body">
                <div class="admin-users-table">

                    <div class="row">
                        <h2 class="text-center">@lang('dictionary.tenancy.fiscal')</h2>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="paymentsTenancyDeficid" class="table table-striped table-bordered status-table">
                                    <thead>
                                    <tr>
                                        <th>@lang('dictionary.date')</th>
                                        <th>@lang('dictionary.paid')</th>
                                        <th>@lang('dictionary.status')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tenancy->fiscal as $transaction)
                                        {{--@if(!$transaction["payed"])--}}
                                            <tr>
                                                <td>{{ $transaction['date'] }}</td>
                                                <td>{{ $transaction['amount'] }}</td>
                                                <td>
                                                    <i class="fa fa-{{
                                                        $transaction['status']==1?'check':(
                                                        $transaction['status']==0?'exclamation':
                                                        'times')
                                                        }}-circle text-{{
                                                        $transaction['status']==1?'success':(
                                                        $transaction['status']==0?'warning':
                                                        'danger')
                                                        }}"></i>
                                                        {{
                                                            $transaction['status']==1?'paid':(
                                                            $transaction['status']==0?'incomplete':
                                                            'unpaid')
                                                        }}
                                                </td>
                                            </tr>
                                        {{--@endif--}}
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="pull-right">@lang('dictionary.tenancy.balance'): {{ number_format($tenancy->balance,2,'.',',') }} </h3>
                        </div>
                    </div>
                        <div class="row">
                            <div class="tenacy-box">
                                <span class="tenacy-field">FISCAL_START: {{ env("FISCAL_START",false) }} </span>
                                <span class="tenacy-field">@lang('dictionary.tenancy.start_at'): {{ $tenancy->start_at}} </span>
                                <span class="tenacy-field">@lang('dictionary.tenancy.start_at_period'): {{ $tenancy->start_at_period }} </span>
                                <span class="tenacy-field">@lang('dictionary.tenancy.rent_term'): {{ $tenancy->rent_term }} </span>
                                <span class="tenacy-field">@lang('dictionary.tenancy.total_rent_amount'): {{ $tenancy->total_rent_amount }} </span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        $('#paymentsTenancy').dataTable({
            order: [[3, "desc"]],
            dom: '<"#table-top.datatable-top"lf<"filter-bar">>rtpi',
            initComplete: function (e) {
                addBtnFilter($('#table-top'), this.api(), [
                    {column: 7, value: "No", title: "Regular Payments"},
                    {column: 7, value: "Yes", title: "Deposit Balance Payments"}
                ]);
            }
        });
    </script>
@endpush

