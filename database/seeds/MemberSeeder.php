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
        $faker = Faker\Factory::create();

        foreach (range(1,200) as $index) {

            //Members
            DB::table('members')->insert([
                'address_line_1' => $faker->buildingNumber . " " . $faker->streetName,
                'city' => $faker->city,
                'state' => 'CT',
                'zip' => '064'.random_int(10,99),
                'current_balance' => 0.0
            ]);

            //Adults
            foreach (range(0,random_int(0,2)) as $index2){
                DB::table('adults')->insert([
                    'member_id' => $index,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName
                ]);
            }

            //Children
            for ($index2 = 0; $index2 < random_int(0,3); $index2++){
                DB::table('children')->insert([
                    'member_id' => $index,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'birth_year' => random_int(1995,2016)
                ]);
            }

            //Emails
            for ($index2 = 0; $index2 < random_int(1,2); $index2++){
                DB::table('emails')->insert([
                    'member_id' => $index,
                    'address' => $faker->email,
                    'description' => $faker->word,
                ]);
            }

            //Phone Numbers
            for ($index2 = 0; $index2 < random_int(1,2); $index2++){
                DB::table('phones')->insert([
                    'member_id' => $index,
                    'number' => random_int(1000000000,9999999999),
                    'description' => $faker->word,
                ]);
            }
        }
    }
}
