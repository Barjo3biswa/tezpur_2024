<?php

use Illuminate\Database\Seeder;
use App\Course;

class SubPreferenceCombinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $preference_codes = [["pref_code"=>"401","comb_codes"=>"113,112"],["pref_code"=>"403","comb_codes"=>"115,114"],["pref_code"=>"404","comb_codes"=>"118,117"],["pref_code"=>"405","comb_codes"=>"213,202"],["pref_code"=>"217","comb_codes"=>"218"]];

        DB::beginTransaction();
          try{
            Course::where('sub_preference',1)->update(["sub_preference" => 0]);
            foreach($preference_codes as $pref){
                $pref_codes = explode(",",$pref["pref_code"]);
                $comb_codes = explode(",",$pref["comb_codes"]);
                $pref_courses = Course::whereIn('code', $pref_codes)->update([
                    "sub_preference" => 1,
                    "sub_combination_id" => $pref_codes[0]
                ]);
                $comb_courses = Course::whereIn('code', $comb_codes)->update([
                    "sub_combination_id" => $pref_codes[0]
                ]);

            }    
          }catch(\Throwable $th) {
            DB::rollback();
            Log::error($th);
            throw new Exception("Whoops! Something went wrong.", 1);
          }
          DB::commit();
    }
}
