@extends('layouts.app')

@section('title', trans('dictionary.users'))

@section('content')
<!-- General Login Section Starts Here -->
<section class="general-registration-section">
    <div class="container general-registration-white-bg">
        {!! Form::open(['method' => 'put', 'route' => ['users.update', $user->id]]) !!}
            <div class="general-registration-top">
                <div class="row">
                    <div class="col-sm-11 col-sm-offset-1">
                        <h2>@lang('dictionary.update'): {{ $user->name }}</h2>
                    </div>
                </div>

                <div class="row">
                   
                        <div class="col-sm-5 col-sm-offset-1 general-registration-left">
                            <div >
                                {!! Form::openGroup('name', trans('dictionary.name')) !!}
                                {!! Form::text('name', $user->name) !!}
                                {!! Form::closeGroup() !!}
                            </div>

                            <div>
                                {!! Form::openGroup('email', trans('dictionary.email')) !!}
                                {!! Form::text('email', $user->email) !!}
                                {!! Form::closeGroup() !!}
                            </div>
                            <div>
                                {!! Form::openGroup('role', trans('dictionary.role')) !!}
                                {!! Form::select('role', $roles, $user->roles->first()->name) !!}
                                {!! Form::closeGroup() !!}
                            </div>
                        </div>
                        <div class="col-sm-5 general-registration-right">
                            <div >
                                {!! Form::openGroup('password', trans('dictionary.password')) !!}
                                {!! Form::password('password') !!}
                                {!! Form::closeGroup() !!}
                            </div>

                            <div >
                                {!! Form::openGroup('password_confirmation', trans('dictionary.password-confirmation')) !!}
                                {!! Form::password('password_confirmation') !!}
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

