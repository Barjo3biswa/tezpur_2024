<?php

use App\Course;
use Illuminate\Database\Seeder;

class BtechCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::beginTransaction();
        $courses = [
            [
                "code"            => 240,
                "course_type_id"  => 4,
                "name"            => "B.Tech. in Civil Engineering",
                "course_duration" => 8,
                "preference"       => 1,
                "department_id"   => 8,
                "program_id"      => 1,
                "eligibility"       => "<p> 10+2 Standard or equivalent examination with minimum 60% aggregate marks† and pass marks† in (1) Physics, (2) Mathematics, (3) Language, (4) Chemistry/Biology/Biotech/Technical vocational subject (any one of them), and (5) any other Subject.</p>",
            ],
            [
                "code"            => 241,
                "course_type_id"  => 4,
                "name"            => "B.Tech. in Computer Science and Engineering",
                "course_duration" => 8,
                "preference"       => 1,
                "department_id"   => 10,
                "program_id"      => 1,
                "eligibility"       => "<p> 10+2 Standard or equivalent examination with minimum 60% aggregate marks† and pass marks† in (1) Physics, (2) Mathematics, (3) Language, (4) Chemistry/Biology/Biotech/Technical vocational subject (any one of them), and (5) any other Subject.</p>",
            ],
            [
                "code"            => 242,
                "course_type_id"  => 4,
                "name"            => "B.Tech. in Electrical Engineering",
                "course_duration" => 8,
                "preference"       => 1,
                "department_id"   => 25,
                "program_id"      => 1,
                "eligibility"       => "<p> 10+2 Standard or equivalent examination with minimum 60% aggregate marks† and pass marks† in (1) Physics, (2) Mathematics, (3) Language, (4) Chemistry/Biology/Biotech/Technical vocational subject (any one of them), and (5) any other Subject.</p>",
            ],
            [
                "code"            => 243,
                "course_type_id"  => 4,
                "name"            => "B.Tech. in Electronics and Communication Engineering",
                "course_duration" => 8,
                "preference"       => 1,
                "department_id"   => 9,
                "program_id"      => 1,
                "eligibility"       => "<p> 10+2 Standard or equivalent examination with minimum 60% aggregate marks† and pass marks† in (1) Physics, (2) Mathematics, (3) Language, (4) Chemistry/Biology/Biotech/Technical vocational subject (any one of them), and (5) any other Subject.</p>",
            ],
            [
                "code"            => 243,
                "course_type_id"  => 4,
                "name"            => "B.Tech. in Food Engineering and Technology",
                "course_duration" => 8,
                "preference"       => 1,
                "department_id"   => 12,
                "program_id"      => 1,
                "eligibility"       => "<p> 10+2 Standard or equivalent examination with minimum 60% aggregate marks† and pass marks† in (1) Physics, (2) Mathematics, (3) Language, (4) Chemistry/Biology/Biotech/Technical vocational subject (any one of them), and (5) any other Subject.</p>",
            ],
            [
                "code"            => 244,
                "course_type_id"  => 4,
                "name"            => "B.Tech. in Mechanical Engineering",
                "course_duration" => 8,
                "preference"       => 1,
                "department_id"   => 13,
                "program_id"      => 1,
                "eligibility"       => "<p> 10+2 Standard or equivalent examination with minimum 60% aggregate marks† and pass marks† in (1) Physics, (2) Mathematics, (3) Language, (4) Chemistry/Biology/Biotech/Technical vocational subject (any one of them), and (5) any other Subject.</p>",
            ],
        ];
        try {
            
            foreach ($courses as $data) {
                Course::create($data);
            }
            Log::info(sizeof($courses)." Data Inserted.");
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
        DB::commit();
        return "Done";
    }
}
