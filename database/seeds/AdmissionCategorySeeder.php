<?php

use Illuminate\Database\Seeder;

class AdmissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if(DB::table('admission_categories')->get()->count() == 0){
            DB::table('admission_categories')->insert([
                ['name'=>'General'],
                ['name'=>'ST'],
                ['name'=>'SC'],
                ['name'=>'OBC'],
                ['name'=>'OBC-NCL'],
                ['name'=>'EWS'],
                ['name'=>'PWD']
            ]);
        }

    }
}
