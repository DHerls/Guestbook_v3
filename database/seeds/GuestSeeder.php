<?php

use Illuminate\Database\Seeder;
use App\GuestRecord;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        factory(GuestRecord::class, 1000)->create()->each(function($gr) use($faker){
            foreach (range(1,random_int(1,4)) as $index){
                $gr->guests()->save(factory(\App\Guest::class)->make(), ['free_pass' => random_int(1,4) == 1,
                    'created_at' => $faker->dateTimeThisMonth($max = 'now', $timezone = date_default_timezone_get())]);
            }
        });
    }
}
