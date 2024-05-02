<?php

use App\IncomeRange;
use Illuminate\Database\Seeder;

class IncomeRangesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
            try {
                IncomeRange::truncate();
                $ranges = [
                [
                    "min"          => "0",
                    "max"          => "50000",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "min"          => "50001",
                    "max"          => "250000",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "min"          => "250001",
                    "max"          => "500000",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "min"          => "500001",
                    "max"          => "600000",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "min"          => "600001",
                    "max"          => "800000",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "min"          => "800001",
                    "max"          => "1000000",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "min"          => "1000001",
                    "max"          => "and above",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                
            ];
            foreach ($ranges as $range) {
                $data = [
                    'min'          => $range['min'],
                    'max'          => $range['max'],
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ];
                IncomeRange::create($data);
            }
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
        DB::commit();  
    }
}
