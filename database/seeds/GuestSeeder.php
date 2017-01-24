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
                    'created_at' => $gr->created_at]);
            }
        });

        $records = GuestRecord::all();
        foreach ($records as $record){
            if (date('N', strtotime($record->created_at)) >= 6){
                $record->price = 15 * $record->guests()->whereType('adult')->wherePivot('free_pass', '=', 0)->count() +
                    5 * $record->guests()->whereType('child')->wherePivot('free_pass', '=', 0)->count();
            } else {
                $record->price = 10 * $record->guests()->whereType('adult')->wherePivot('free_pass', '=', 0)->count() +
                    5 * $record->guests()->whereType('child')->wherePivot('free_pass', '=', 0)->count();
            }
            if ($record->payment_method == 'account'){
                $br = new \App\BalanceRecord();
                $br->change_amount = $record->price;
                $br->user_id = $record->user_id;
                $br->member_id = $record->member_id;
                $br->reason = "Guest Check-In";
                $record->balanceRecord()->save($br);
            }
            $record->save();
        }
    }
}
