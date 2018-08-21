<?php

use App\Models\User;
use App\Services\UserService;
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
        $service = app(UserService::class);

        if (!User::where('name', 'admin')->first()) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
            ]);

            $service->create($user, 'admin', 'admin', false);

            $user = User::create([
                'name' => 'Member',
                'email' => 'member@example.com',
                'password' => bcrypt('member'),
            ]);

            $service->create($user, 'agency', 'agency', false);

            $user = User::create([
                'name' => 'Agency',
                'email' => 'agency@example.com',
                'password' => bcrypt('agency'),
            ]);

            $service->create($user, 'agency', 'agency', false);

            $user = User::create([
                'name' => 'Agency-Member',
                'email' => 'agency-member@example.com',
                'password' => bcrypt('agency-member'),
            ]);

            $service->create($user, 'agency-member', 'agency-member', false);

            DB::statement('update user_meta set is_active = 1, activation_token=null');
        }

    }
}
