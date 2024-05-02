<?php

use Illuminate\Database\Seeder;

class CourseWiseQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $question_and_data = [
            [
                "course_id"        => 71,
                "type"             => "options",
                "question"         => "Choose honors or regular ?",
                "question_details" => [
                    "major"   => [
                        "type"             => "text",
                        "operator"         => "greater_than_or_equal_to",
                        "question"         => "total and aggregate marks in Bachelor Degree with Regulars",
                        "total_marks"      => 100,
                        "eligibility_mark" => 50,
                    ],
                    "regular" => [
                        "type"             => "text",
                        "operator"         => "greater_than_or_equal_to",
                        "question"         => "total and aggregate marks in Bachelor Degree with Honors",
                        "total_marks"      => 100,
                        "eligibility_mark" => 45,
                    ],
                ],
            ],
            [
                "course_id"        => 71,
                "type"             => "options",
                "question"         => "Choose honors or regular ?",
                "question_details" => [
                    "major"   => [
                        "type"             => "text",
                        "operator"         => "greater_than_or_equal_to",
                        "question"         => "total and aggregate marks in Bachelor Degree with Regulars",
                        "total_marks"      => 100,
                        "eligibility_mark" => 50,
                    ],
                    "regular" => [
                        "type"             => "text",
                        "operator"         => "greater_than_or_equal_to",
                        "question"         => "total and aggregate marks in Bachelor Degree with Honors",
                        "total_marks"      => 100,
                        "eligibility_mark" => 45,
                    ],
                ],
            ],
        ];
    }
}
