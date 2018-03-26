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
                        <h2>@lang('dictionary.refresh-api')</h2>
                    </div>
                </div>
            </div>
            <!-- Admin Users Header Area Ends Here -->
            <hr />
            <!-- Admin Users Body Area Starts Here -->
            <div class="admin-users-section-body">
                <!-- Marketing Automation Dashboard Widgets Area Starts Here -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-md-3">
                            <a href="{{ route('gocardless.refresh') }}" class="btn btn-primary" style="width: 100%;">@lang('dictionary.refresh.gocardless')</a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('properties.refresh') }}" class="btn btn-primary" style="width: 100%;">@lang('dictionary.refresh.properties')</a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('tenancies.refresh') }}" class="btn btn-primary" style="width: 100%;">@lang('dictionary.refresh.tenancies')</a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('yodlee.refresh') }}" class="btn btn-primary" style="width: 100%;">@lang('dictionary.refresh.yodlee')</a>
                        </div>
                    </div>
                </div>   
            </div>

            <div class="admin-users-section-header">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-4 text-center">
                        <h2>OR</h2>
                    </div>
                </div>
            </div>

            <div class="admin-users-section-body">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="{{ route('admin.refresh.all') }}" class="btn btn-primary" style="width: 100%;">@lang('dictionary.refresh.all')</a>
                    </div>
                </div>
            </div>
            <!-- Admin Users Body Area Ends Here -->  
        </div>  
    </div>
</section>

@endsection