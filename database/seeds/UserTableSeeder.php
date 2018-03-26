<?php

use App\User;
use App\Role;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@finance.com';
        $user->password = bcrypt('123456789');
        $user->save();
        $user->roles()->attach($role_admin);
    }
}
