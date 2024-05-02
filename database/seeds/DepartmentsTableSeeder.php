<?php

use App\Department;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            [
                "name"      => "Education",
                "code"      => "Education",
                "school_id" => 1,
            ],
            [
                "name"      => "English and Foreign Languages",
                "code"      => "English and Foreign Languages",
                "school_id" => 1,
            ],
            [
                "name"      => "Chemical Sciences",
                "code"      => "Chemical Sciences",
                "school_id" => 2,
            ],
            [
                "name"      => "Mathematical Sciences",
                "code"      => "Mathematical Sciences",
                "school_id" => 2,
            ],
            [
                "name"      => "Physics",
                "code"      => "Physics",
                "school_id" => 2,
            ],
            [
                "name"      => "Commerce",
                "code"      => "Commerce",
                "school_id" => 3,
            ],
            [
                "name"      => "Molecular Biology and Biotechnology",
                "code"      => "Molecular Biology and Biotechnology",
                "school_id" => 2,
            ],
            [
                "name"      => "Civil Engineering",
                "code"      => "Civil Engineering",
                "school_id" => 4,
            ],
            [
                "name"      => "Electronics and Communication Engineering",
                "code"      => "Electronics and Communication Engineering",
                "school_id" => 4,
            ],
            [
                "name"      => "Computer Science and Engineering",
                "code"      => "Computer Science and Engineering",
                "school_id" => 4,
            ],
            [
                "name"      => "Energy",
                "code"      => "Energy",
                "school_id" => 4,
            ],
            [
                "name"      => "Food Engineering and Technology",
                "code"      => "Food Engineering and Technology",
                "school_id" => 4,
            ],
            [
                "name"      => "Mechanical Engineering",
                "code"      => "Mechanical Engineering",
                "school_id" => 4,
            ],
            [
                "name"      => "Mass Communication and Journalism",
                "code"      => "Mass Communication and Journalism",
                "school_id" => 1,
            ],
            [
                "name"      => "Cultural Studies",
                "code"      => "Cultural Studies",
                "school_id" => 1,
            ],
            [
                "name"      => "Hindi",
                "code"      => "Hindi",
                "school_id" => 1,
            ],
            [
                "name"      => "Social Work",
                "code"      => "Social Work",
                "school_id" => 1,
            ],
            [
                "name"      => "Sociology",
                "code"      => "Sociology",
                "school_id" => 1,
            ],
            [
                "name"      => "Environmental Science",
                "code"      => "Environmental Science",
                "school_id" => 2,
            ],
            [
                "name"      => "Business Administration",
                "code"      => "Business Administration",
                "school_id" => 3,
            ],
            [
                "name"      => "Centre for Inclusive Development",
                "code"      => "Centre for Inclusive Development",
                "school_id" => 1,
            ],
            [
                "name"      => "Chandraprabha Saikiani Centre for Women’s Studies",
                "code"      => "Chandraprabha Saikiani Centre for Women’s Studies",
                "school_id" => 1,
            ],
        ];
        Department::truncate();
        foreach ($array as $data) {
            Department::create($data);
        }
    }
}
