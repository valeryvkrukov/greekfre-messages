<?php

use Illuminate\Database\Seeder;
use App\User;
use Faker\Factory;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$faker = Factory::create();
        User::truncate();

        User::create([
            'name' => 'Valery',
            'email' => 'valery.v.krukov@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123123'),
            'twilio_phone' => '+16476948433',
            'twilio_sid' => 'AC00f8f083dd81066aef5220e6677857b9',
            'twilio_token' => '8615990d2f6475094fcd22ce4a850987',
            'default_message' => 'Hi $name, this message sent to phone: $phone',
            'remember_token' => Str::random(10),
        ]);

        /*foreach(range(1, 100) as $i) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ]);
        }*/
    }
}
