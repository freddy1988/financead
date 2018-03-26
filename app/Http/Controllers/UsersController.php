<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use App\Role;

class UsersController extends Controller
{
    /**
     * Show the users page.
     *
     * @return \Illuminate\Http\Response
     * @throws \InvalidArgumentException
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the users create page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('description', 'name');
        return view('users.create',  compact('roles'));
    }

    /**
     * Show the users store create.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->save();
            $user->roles()->attach(Role::where('name', $request->get('role'))->first());

            flash(trans('messages.success'), 'success');
        } catch (\Exception $e) {
            flash(trans('messages.exception'), 'danger');
        }

        return redirect(route('users.index'));
    }

    /**
     * Show the users edit page.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {   
        $roles = Role::pluck('description', 'name');
        return view('users.edit', compact('user','roles'));
    }

    /**
     * Show the users edit page.
     *
     * @param User $user
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserRequest $request)
    {
        try {
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $pass = $request->get('password');

            if (!empty($pass)) {
                $user->password = bcrypt($pass);
            }

            $user->save();
            $user->roles()->detach();
            $user->roles()->attach(Role::where('name', $request->get('role'))->first());

            flash(trans('messages.success'), 'success');
        } catch (\Exception $e) {
            flash(trans('messages.exception'), 'danger');
        }

        return redirect(route('users.index'));
    }

    /**
     * Show the users edit page.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->roles()->detach();
            $user->delete();
            flash(trans('messages.success'), 'success');
        } catch (\Exception $e) {
            flash(trans('messages.exception'), 'danger');
        }

        return redirect(route('users.index'));
    }
}
