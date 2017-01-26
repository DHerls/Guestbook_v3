<?php

use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Member::class, 200)->create()->each(function($m){
            foreach (range(0,random_int(0,2)) as $index){
                $m->adults()->save(factory(\App\Adult::class)->make());
            }
            for ($index2 = 0; $index2 < random_int(0,3); $index2++){
                $m->children()->save(factory(\App\Child::class)->make());
            }
            foreach (range(0,random_int(0,2)) as $index){
                $m->phones()->save(factory(\App\Phone::class)->make());
            }
            foreach (range(0,random_int(0,2)) as $index){
                $m->emails()->save(factory(\App\Email::class)->make());
            }
        });

        factory(\App\MemberRecord::class, 1000)->create();
    }


}
