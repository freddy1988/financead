@extends('layouts.app')

@section('title', trans('dictionary.gocardless'))

@section('content')
<section class="admin-users-section">
    <div class="container">
        <div class="admin-users-section-bg">
            <!-- Admin Users Header Area Starts Here -->
            <div class="admin-users-section-header">
                <div class="row">
                    <div class="col-sm-3">
                        <h2>Active @lang('dictionary.tenancies')</h2>
                        @if (isset($day))
                            @lang('dictionary.tenancy.day_to_pay') {{$day}}
                        @endif
                    </div>
                </div>
            </div> 
            <hr />
            <div class="admin-users-section-body">
                <div class="admin-users-table">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="datatableTenancies" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-4">
                                                <div class="flex flex-vcb">
                                                    <span>@lang('dictionary.id')</span>
                                                    <span>@lang('dictionary.property-full-address')</span>
                                                </div>
                                            </th>
                                            <th class="col-sm-4">@lang('dictionary.landlord')</th>
                                            <th class="col-sm-4">@lang('dictionary.tenancy.balance')</th>
                                            <th class="col-sm-4">next_date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tenancies as $tenancy)
                                            <tr @php echo  $tenancy->err==true ? 'class="rowRed"' : '' @endphp>
                                                <td>
                                                    <div class="flex flex-hcb">
                                                        <div class="flex flex-vcb">
                                                            <span>{{ $tenancy->id }}</span>
                                                            <span class="text-wrap">{{ $tenancy->property_full_address }}</span>
                                                        </div>
                                                        {!! Form::open(['method' => 'get', 'route' => ['tenancies.edit', $tenancy->id]]) !!}
                                                        <button class="btn btn-transparent nopadding">
                                                            <i class="fa fa-chevron-circle-right"></i>
                                                        </button>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </td>
                                                <td> {{ json_decode($tenancy->property)->landlord->data->name }} </td>
                                                <td>{{ $tenancy->balance }}</td>
                                                <td>{{ $tenancy->next_payment_date }}</td>
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
    var table = $('#datatableTenancies').DataTable({
        order: [],
        dom: '<"#table-top.datatable-top"lf<"filter-bar">>rtpi',
        initComplete: function (e) {
            addBtnFilter($('#table-top'), this.api(), [
                {eval:'Number(data[2])<0', title: "Arrears"},
                {eval:'Number(data[2])==0' , title: "Zero Balance"},
                {eval:'Number(data[2])>0' , title: "In Credit"}
            ]);
        }
    });
  </script>
@endpush
