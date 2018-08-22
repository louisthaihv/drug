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
        $faker = \Faker\Factory::create();
        $agents = \App\Models\Agent::pluck('id');

        $dataSeeder = [
            'email' => $faker->unique()->safeEmail,
            'name' => $faker->userName,
            'password' => $password = bcrypt('secret'),
            'dob' => $faker->time('Y-m-d'),
            'mobile' => $faker->phoneNumber,
            'gender' => array_rand([USER_GENDER_MALE, USER_GENDER_FEMALE]),
            'status' => USER_STATUS_ACTIVE,
            'avatar' => $faker->imageUrl(200, 200),
            'address' => $faker->address,
            'remember_token' => str_random(10),
        ];


        $users = [];
        $users[] = array_merge($dataSeeder, [
            'email' => 'admin@example.com',
            'name' => 'Root',
            'role_id' => 1,
            'agent_id' => null
        ]);
        foreach ($agents as $agent) {
            $users[] = array_merge($dataSeeder, [
                'email' => 'agent_' . $agent . '@agent.com',
                'name' => 'Quản trị viên ' . $agent,
                'role_id' => 2,
                'agent_id' => $agent
            ]);

            for($i = 1; $i < 10; $i++) {
                $users[] = array_merge($dataSeeder, [
                    'email' => 'staff_' . $i . '_' . $agent . '@gmail.com',
                    'name' => 'Nhân viên ' . $agent,
                    'role_id' => 3,
                    'agent_id' => $agent
                ]);
            }
        }

        \App\Models\User::insert($users);

        DB::statement('update users set status = 1');
        

    }
}
