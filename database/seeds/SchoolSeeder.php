<?php

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        School::truncate();
        $array = [
            [
                "name"  => "Humanities and Social Sciences"
            ],
            [
                "name"  => "Sciences"
            ],
            [
                "name"  => "Management Sciences"
            ],
            [
                "name"  => "Engineering"
            ]
        ];
        foreach ($array as $key => $data) {
            School::create($data);
        }
    }
}
