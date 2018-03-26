@extends('layouts.app')

@section('title', trans('dictionary.yodlee'))

@section('content')
<!-- General Login Section Starts Here -->
<section class="general-registration-section">
    <div class="container general-registration-white-bg">
        {!! Form::open(['method' => 'put', 'route' => ['yodlee.update', $yodlee->id]]) !!}
            <div class="general-registration-top">
                <div class="row">
                    <div class="col-sm-11 col-sm-offset-1">
                        <h2>@lang('dictionary.update') @lang('dictionary.yodlee') @lang('dictionary.transaction'): {{ $yodlee->id }}</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5 col-sm-offset-1 general-registration-left">
                        <div>
                            {!! Form::openGroup('tenancy', trans('dictionary.tenancy-id')) !!}
                            {!! Form::select('tenancy', $tenancies, $yodlee->tenancy_id) !!}
                            {!! Form::closeGroup() !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="general-registration-bottom">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <button class="btn btn-primary waves-effect waves-light custom-form-btn">@lang('dictionary.update')</button>
                    </div>
                </div>
            </div> 
             {!! Form::close() !!} 
    </div>
</section>

@endsection

