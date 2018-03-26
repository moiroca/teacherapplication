<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SeedStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aclc:student-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Student Data From ACLC';

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
        echo "\n Seeding ACLC Students: ";

        $students = $this->fetchStudents();

        $students = array_chunk($students->toArray(), 500);

        foreach ($students as $key => $studentChunk) {
            foreach ($studentChunk as $student) {
                User::create([
                    'username'      => $student->{config('aclc.column')},
                    'role'          => 2,
                    'is_confirmed'  => 1
                ]);

                echo ".";
            }
        }
        
        echo "\n ACLC Students Seeded! \n";
    }

    public function fetchStudents()
    {
      return \DB::connection('aclc')->table(config('aclc.table'))
                      ->select(config('aclc.column'))
                      ->get();
    }
}
