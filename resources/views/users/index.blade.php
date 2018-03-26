@extends('layouts.app')

@section('title', trans('dictionary.users'))

@section('content')
<section class="admin-users-section">
    <div class="container">
        <div class="admin-users-section-bg">
            <!-- Admin Users Header Area Starts Here -->
            <div class="admin-users-section-header">
                <div class="row">
                    <div class="col-sm-3">
                        <h2>@lang('dictionary.users')</h2>
                    </div>
                    <div class="col-sm-9">
                        <div class="admin-users-section-header-right">
                            <a href="{{route('users.create')}}"><button class="btn btn-primary">@lang('dictionary.add-user')</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Admin Users Header Area Ends Here -->
            <hr />
            <!-- Admin Users Body Area Starts Here -->
            <div class="admin-users-section-body">
                <!-- Marketing Automation Dashboard Widgets Area Starts Here -->
                <div class="admin-users-table">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="datatableOne" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>@lang('dictionary.name')</th>
                                            <th>@lang('dictionary.role')</th>
                                            <th>@lang('dictionary.edit')</th>
                                            <th>@lang('dictionary.delete')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $u)
                                            <tr id="{{ $u->id }}">
                                                <td>{{ $u->name != $u->email ? $u->name : 'No name'}} ({{$u->email}})</td>
                                                <td>{{ $u->roles->first()->description }}</td>
                                                <td class="text-center">
                                                    {!! Form::open(['method' => 'get', 'route' => ['users.edit', $u->id]]) !!}
                                                    <button class="btn btn-icon waves-effect waves-light btn-primary"> <i class="fa fa-edit"></i> </button>
                                                    {!! Form::close() !!}
                                                </td>                 
                                                <td class="text-center">
                                                    {!! Form::open(['method' => 'delete', 'route' => ['users.destroy', $u->id], 'data-confirm' => trans('messages.confirm'), 'data-title' => trans('messages.confirm-title'), 'data-type' => 'warning']) !!}
                                                    <button class="btn btn-icon waves-effect waves-light btn-primary"> <i class="fa fa-trash-o"></i> </button>
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Marketing Automation Dashboard Widgets Area Ends Here -->    
            </div>
            <!-- Admin Users Body Area Ends Here -->  
        </div>  
    </div>
</section>

@endsection