<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ApplicationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $array = [
                "name"       => "currency",
                "value"      => 71.66,
                "value_type" => "float",
            ];
            Setting::truncate();
            Setting::create($array);    
        } catch (\Throwable $th) {
            throw new Exception("Error Processing Request", 1);
        }
    }
}
