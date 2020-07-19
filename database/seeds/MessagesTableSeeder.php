<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Messages;
use Faker\Factory;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        Messages::truncate();

        foreach(range(1, 100) as $i) {
            $name = $faker->firstName;
            $owner = User::find($faker->numberBetween(1, 99));
            $message = Messages::create([
                'name' => $name,
                'order' => $faker->numberBetween(1000, 9999),
                'phone' => $faker->phoneNumber,
                'message' => sprintf("Hi, %s Your order is now ready for pickup!\nThanks, The Greek Freak.\nNote: For customer service please call 289 660 4660", $name),
                'picked_up' => $faker->boolean,
            ]);
            
            $message->owner()->associate($owner);
            $message->save();
        }
    }
}
