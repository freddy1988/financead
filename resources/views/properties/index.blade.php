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
                            <h2>@lang('dictionary.properties')</h2>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="admin-users-section-body">
                    <div class="admin-users-table">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="datatableProperties" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="col-sm-4">@lang('dictionary.property-address')</th>
                                            <th class="col-sm-4">@lang('dictionary.landlord')</th>
                                            <th class="col-sm-4">@lang('dictionary.status')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($properties as $property)
                                            <tr>
                                                <td>
                                                    <div class="flex flex-hcb">
                                                        <div class="flex flex-vcb">
                                                            <span>{{ $property->full_address }}</span>
                                                        </div>
                                                        {{--{!! Form::open(['method' => 'get', 'route' => ['tenancies.edit', $property->id]]) !!}--}}
                                                        <button class="btn btn-transparent nopadding">
                                                            <i class="fa fa-chevron-circle-right"></i>
                                                        </button>
                                                        {{--{!! Form::close() !!}--}}
                                                    </div>
                                                </td>
                                                <td> {{ json_decode($property->landlord)->data->name }} </td>
                                                <td>{{ json_decode($property->state)->data->display_name }}</td>
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
        var table = $('#datatableProperties').DataTable({
            order: [],
            dom: '<"#table-top.datatable-top"lf>rtpi'
        });
    </script>
@endpush