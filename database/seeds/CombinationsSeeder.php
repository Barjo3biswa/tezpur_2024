<?php

use App\Course;
use App\Models\CourseCombination;
use Illuminate\Database\Seeder;

class CombinationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $combination_array = [
            [
                "ids" => "28, 29", //comma separated course ids
            ],
            [
                "ids"   => "15, 17, 18, 19, 21"
            ]
        ];
        $preferences_ids = [14, 15, 17, 18, 19, 21];
        DB::beginTransaction();
        try {
            Course::where('preference', 1)->update([
                "preference" => 0
            ]);
            CourseCombination::truncate();
            foreach ($combination_array as $ids) {
                $ids     = explode(",", $ids["ids"]);
                $courses = Course::find($ids);
                $combination_name = implode("+", $courses->map(function ($item) {
                    return $item->code;
                })->toArray());
                $combination = CourseCombination::create([
                    "name" => $combination_name,
                ]);
                Course::whereIn("id", $ids)->update([
                    "preference"     => 1,
                    "combination_id" => $combination->id,
                ]);
            }
            if ($preferences_ids) {
                Course::whereIn("id", $preferences_ids)->update(["preference" => 1]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            throw new Exception("Whoops! Something went wrong.", 1);

        }
        DB::commit();
    }
}
