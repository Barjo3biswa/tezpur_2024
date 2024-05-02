<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Branch;

class BranchesTableSeeder extends Seeder
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
            Branch::truncate();
            $branches = [
                [
                    "name"          =>'Electronics & Communication Engnieering',
                    "code"          => "ECE",
                    "course_id"     => "4",
                    "department_id" => "4",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Computer Science & Engnieering',
                    "code"          => "CSE",
                    "course_id"     => "5",
                    "department_id" => "5",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Electrical Engnieering',
                    "code"          => "EE",
                    "course_id"     => "5",
                    "department_id" => "5",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Civil Engineering',
                    "code"          => "CE",
                    "course_id"     => "2",
                    "department_id" => "2",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
                [
                    "name"          =>'Food Engnieering & Technology',
                    "code"          => "FET",
                    "course_id"     => "6",
                    "department_id" => "6",
                    "created_at"    =>current_date_time(),
                    "updated_at"    =>current_date_time(),
                ],
            ];
            foreach ($branches as $branch) {
                $data = [
                    'code'          => $branch['code'],
                    'name'          => $branch['name'],
                    'department_id' => $branch['department_id'],
                    'course_id' => $branch['course_id'],
                ];
                Branch::create($data);
            }

        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
        DB::commit();    
    }
}
