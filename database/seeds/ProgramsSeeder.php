<?php

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramsSeeder extends Seeder
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
                "name"  => "UG Degree/Integrated/Diploma/Certificate Programmes",
                "type"  => "UG",
            ],
            [
                "name"  => "PG Degree/Diploma Programmes",
                "type"  => "PG",
            ],
            [
                "name"  => "Ph.D. Programmes",
                "type"  => "PHD"
            ],
        ];
        Program::truncate();
        foreach ($array as $data) {
            Program::create($data);
        }
    }
}
