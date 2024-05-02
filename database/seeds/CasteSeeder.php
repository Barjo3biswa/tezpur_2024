<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Caste;

class CasteSeeder extends Seeder
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
            Caste::truncate();
            $castes = [
                [
                    "name" => "General(Unreserved)",
                ],
                [
                    "name" => "EWS",
                ],
                [
                    "name" => "OBC (Creamy Layer)",
                ],
                [
                    "name" => "OBC (Non Creamy Layer)",
                ],
                [
                    "name" => "SC",
                ],
                [
                    "name" => "ST",
                ],
                
                
            ];
            foreach ($castes as $caste) {
                $data = [
                    'name'          => $caste['name'],
                    "created_at"    => current_date_time(),
                    "updated_at"    => current_date_time(),
                ];
                Caste::create($data);
            }
        }catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
        DB::commit();  
    }
}
