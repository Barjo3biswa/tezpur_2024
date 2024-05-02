<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(SessionTableSeeder::class);
        $this->call(CasteSeeder::class);
        $this->call(CountriesTableSeeder::class);
        //$this->call(DepartmentsTableSeeder::class);
        //$this->call(CorsesTableSeeder::class);
        $this->call(BranchesTableSeeder::class);
        // $this->call(CourseTypeTableSeeder::class);
    }
}
