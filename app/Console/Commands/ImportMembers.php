<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Member;
use App\Adult;
use App\Child;
use App\Phone;
use App\Email;

class ImportMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import members from a csv file given';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (($handle = fopen($this->argument('file'),'r')) !== FALSE)
        {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
            {

                $member = new Member();
                $member->address_line_1 = $data[6];
                if ($data[7]){
                    $member->address_line_2 = $data[7];
                }
                $member->city = $data[8];
                $member->state = $data[9];
                $member->zip = $data[10];
                $member->save();

                for($i = 0; $i < 3; $i++){
                    if ($data[2 * $i]){
                        $adult = new Adult();
                        $adult->last_name = $data[2 * $i];
                        $adult->first_name = $data[2 * $i + 1];
                        $member->adults()->save($adult);
                    }
                }

                for($i = 0; $i < 5; $i++){
                    if ($data[20 + 2 * $i]){
                        $child = new Child();
                        $child->first_name = $data[20 + 2 * $i];
                        $child->last_name = $member->adults()->first()->last_name;
                        if ($data[21 + 2 * $i]) {
                            $child->birth_year = $data[21 + 2 * $i];
                        }
                        $member->children()->save($child);
                    }
                }

                for($i = 0; $i < 3; $i++){
                    if ($data[11 + 2 * $i]){
                        $phone = new Phone();
                        $phone->number = $data[11 + 2 * $i];
                        if ($data[12 + 2 * $i]) {
                            $phone->description = $data[12 + 2 * $i];
                        }
                        $member->phones()->save($phone);
                    }
                }

                for($i = 0; $i < 2; $i++){
                    if ($data[17 + $i]){
                        $email = new Email();
                        $email->address = $data[17 +  $i];
                        $member->emails()->save($email);
                    }
                }
            }
            fclose($handle);
        }
    }
}
