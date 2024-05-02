<?php

use Illuminate\Database\Seeder;

class DataPrepare extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SchoolSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(ProgramsSeeder::class);
        $this->call(CourseTypeTableSeeder::class);
        $this->call(CorsesTableSeeder::class);
    }
}
