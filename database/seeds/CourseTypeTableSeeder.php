<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\CourseType;


class CourseTypeTableSeeder extends Seeder
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
                CourseType::truncate();
                $courseTypes = [
                [
                    "name"          =>'B.Ed',
                    "code"          => "BED",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Certificate',
                    "code"          => "CERTIFICATE",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Integrated',
                    "code"          => "INTEGRATED",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'B.Tech',
                    "code"          => "BTECH",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                
                [
                    "name"          =>'M.Tech',
                    "code"          => "MTECH",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'M.A.',
                    "code"          => "MA",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'M.Com.',
                    "code"          => "MCOM",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'M.Sc.',
                    "code"          => "MSC",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          => "Others",
                    "code"          => "OTHER",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'P.G. Diploma',
                    "code"          => "PGDIPLOMA",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Ph.D.',
                    "code"          => "PHD",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Lateral Entry',
                    "code"          => "LATERAL",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
            ];
            foreach ($courseTypes as $type) {
                $data = [
                    'code'          => $type['code'],
                    'name'          => $type['name'],
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ];
                CourseType::create($data);
            }
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
        DB::commit();  
    }
}
