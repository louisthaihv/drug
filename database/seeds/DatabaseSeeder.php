<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        Model::unguard();

        // truncate data
        App\Models\Role::truncate();
        App\Models\User::truncate();

        $this->call(RolesTableSeeder::class);
        $this->call(UserTableSeeder::class);

        Model::reguard();
        DB::statement("SET foreign_key_checks=1");
    }
}
