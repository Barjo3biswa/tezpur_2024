<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::truncate();
        DB::table("countries")->insert([
            [
                "name" => "India",
                "code" => "IN",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
            [
                "name" => "Bangladesh",
                "code" => "BAN",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
            [
                "name" => "Bhutan",
                "code" => "BHU",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
            [
                "name" => "Nepal",
                "code" => "NEP",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
        ]);
    }
}
