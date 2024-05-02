<?php

use Illuminate\Database\Seeder;
use App\Priority;

class PrioritiesTableSeeder extends Seeder
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
                "name"  => "Priority I: Widows/wards of Defence personnel killed in action",
            ],
            [
                "name"  => "Priority II: Wards of disabled in action and boarded out from service",
            ],
            [
                "name"  => "Priority III: Widows/Wards of Defence personnel who die in service with death attributable to military service ",
            ],
            [
                "name"  => "Priority IV: Wards of Defence personnel disabled in service and boarded out with disability attributable to military service",
            ],
            [
                "name"  => "Priority V: Wards of ex-servicemen and serving personnel who are in receipt of Gallantry Awards",
            ],
            [
                "name"  => "Priority VI: Wards of ex-servicemen",
            ],
            [
                "name"  => "Priority VII: Wards of serving personnel",
            ],
        ];
        Priority::truncate();
        foreach ($array as $data) {
            Priority::create($data);
        }
    }
}
