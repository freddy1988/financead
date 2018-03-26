<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Roles
    	$this->call(RoleTableSeeder::class);

	    // Add Users
	    $this->call(UserTableSeeder::class);
    }
}
