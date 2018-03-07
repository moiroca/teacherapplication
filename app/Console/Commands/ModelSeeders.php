<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;
use Faker\Generator;

class ModelSeeders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Models With Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Generator $faker)
    {
        parent::__construct();
        $this->faker = $faker;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->seedTeachers();
        $this->seedStudents();
        $this->seedSubjects();
    }

    private function seedTeachers($count = 10)
    {
        // Default Teacher
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'role'  => 1,
            'password' => bcrypt('password'),
        ]);

        for ($i=1; $i <= $count; $i++) { 
            User::create([
                'name' => $this->faker->name,
                'email' => $this->faker->safeEmail,
                'role'  => 1,
                'password' => bcrypt('password'),
            ]);
            echo '.';
        }

        echo "\n Teachers Seeded\n";
    }

    private function seedStudents($count = 10)
    {
        // Default Student
        User::create([
            'name' => 'Mike Gibson',
            'email' => 'mike.gibson@example.com',
            'role'  => 2,
            'password' => bcrypt('password'),
        ]);

        for ($i=1; $i <= $count; $i++) { 
            User::create([
                'name' => $this->faker->name,
                'email' => $this->faker->safeEmail,
                'role'  => 2,
                'password' => bcrypt('password'),
            ]);
            echo '.';
        }

        echo "\n Students Seeded\n";
    }

    private function seedSubjects($count = 5)
    {
        // Seed Only First 10 Teachers
        $teachers = User::where('role', 1)->limit(10)->get();

        $subjectTitle = ['Programming', 'IT Elective', 'Database Management', 'Linux Administration'];

        foreach ($teachers as $key => $teacher) {
            for ($i=0; $i < $count; $i++) { 
                Subject::create([
                    'name' => $subjectTitle[rand(0,3)] . ' ' . ($i + 1),
                    'teacher_id' => $teacher->id
                ]);
                echo '.';
            }
        }

        echo "\n Teacher Subjects Seeded\n";
    }
}
