<?php

use App\Course;
use Illuminate\Database\Seeder;

class CourseRollumberSet extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // index id wise course update
        $array = [
            1  => [
                "code"     => 107,
                "series_1" => "EDB20",
                "series_2" => "001",
            ],
            2  => [
                "code"     => 108,
                "series_1" => "EGC20",
                "series_2" => "001",
            ],
            3  => [
                "code"     => 112,
                "series_1" => "CHB20",
                "series_2" => "001",
            ],
            4  => [
                "code"     => 114,
                "series_1" => "MSB20",
                "series_2" => "001",
            ],
            5  => [
                "code"     => 117,
                "series_1" => "PHB20",
                "series_2" => "001",
            ],
            6  => [
                "code"     => 110,
                "series_1" => "EGI20",
                "series_2" => "001",
            ],
            7  => [
                "code"     => 111,
                "series_1" => "COI20",
                "series_2" => "001",
            ],
            9  => [
                "code"     => 401,
                "series_1" => "CHI20",
                "series_2" => "001",
            ],
            10 => [
                "code"     => 116,
                "series_1" => "MBI20",
                "series_2" => "001",
            ],
            11 => [
                "code"     => 115,
                "series_1" => "MSI20",
                "series_2" => "001",
            ],
            13 => [
                "code"     => 119,
                "series_1" => "EEB20",
                "series_2" => "201",
            ],
            14 => [
                "code"     => 204,
                "series_1" => "ECO20",
                "series_2" => "001",
            ],
            15 => [
                "code"     => 203,
                "series_1" => "CEM20",
                "series_2" => "001",
            ],
            17 => [
                "code"     => 205,
                "series_1" => "ECD20",
                "series_2" => "001",
            ],
            18 => [
                "code"     => 206,
                "series_1" => "ENE20",
                "series_2" => "001",
            ],
            19 => [
                "code"     => 207,
                "series_1" => "FEM20",
                "series_2" => "001",
            ],
            20 => [
                "code"     => 202,
                "series_1" => "CSI20",
                "series_2" => "001",
            ],
            21 => [
                "code"     => 208,
                "series_1" => "MEM20",
                "series_2" => "001",
            ],
            22 => [
                "code"     => 218,
                "series_1" => "MCD20",
                "series_2" => "001",
            ],
            23 => [
                "code"     => 209,
                "series_1" => "CTM20",
                "series_2" => "001",
            ],
            24 => [
                "code"     => 210,
                "series_1" => "EDM20",
                "series_2" => "001",
            ],
            25 => [
                "code"     => 211,
                "series_1" => "EGE20",
                "series_2" => "001",
            ],
            26 => [
                "code"     => 214,
                "series_1" => "HIM20",
                "series_2" => "001",
            ],
            27 => [
                "code"     => 212,
                "series_1" => "EGL20",
                "series_2" => "001",
            ],
            28 => [
                "code"     => 217,
                "series_1" => "MCM20",
                "series_2" => "001",
            ],
            29 => [
                "code"     => 219,
                "series_1" => "SWM20",
                "series_2" => "001",
            ],
            30 => [
                "code"     => 220,
                "series_1" => "SOM20",
                "series_2" => "001",
            ],
            31 => [
                "code"     => 224,
                "series_1" => "COM20",
                "series_2" => "001",
            ],
            32 => [
                "code"     => 225,
                "series_1" => "CHM20",
                "series_2" => "001",
            ],
            33 => [
                "code"     => 227,
                "series_1" => "ESE20",
                "series_2" => "001",
            ],
            34 => [
                "code"     => 228,
                "series_1" => "MSM20",
                "series_2" => "001",
            ],
            35 => [
                "code"     => 229,
                "series_1" => "MBP20",
                "series_2" => "101",
            ],
            36 => [
                "code"     => 230,
                "series_1" => "PHM20",
                "series_2" => "001",
            ],
            37 => [
                "code"     => 201,
                "series_1" => "CSM20",
                "series_2" => "001",
            ],
            38 => [
                "code"     => 223,
                "series_1" => "BAT20",
                "series_2" => "001",
            ],
            39 => [
                "code"     => 221,
                "series_1" => "IDD20",
                "series_2" => "001",
            ],
            40 => [
                "code"     => 215,
                "series_1" => "HIT20",
                "series_2" => "001",
            ],
            41 => [
                "code"     => 222,
                "series_1" => "WSD20",
                "series_2" => "001",
            ],
            42 => [
                "code"     => 313,
                "series_1" => "BAP20",
                "series_2" => "101",
            ],
            43 => [
                "code"     => 314,
                "series_1" => "CHP20",
                "series_2" => "101",
            ],
            44 => [
                "code"     => 301,
                "series_1" => "CEP20",
                "series_2" => "101",
            ],
            45 => [
                "code"     => 302,
                "series_1" => "CSP20",
                "series_2" => "101",
            ],
            46 => [
                "code"     => 307,
                "series_1" => "CTP20",
                "series_2" => "101",
            ],
            47 => [
                "code"     => 308,
                "series_1" => "EDP20",
                "series_2" => "101",
            ],
            48 => [
                "code"     => 303,
                "series_1" => "ECP20",
                "series_2" => "101",
            ],
            50 => [
                "code"     => 309,
                "series_1" => "EGP20",
                "series_2" => "101",
            ],
            51 => [
                "code"     => 315,
                "series_1" => "ESP20",
                "series_2" => "101",
            ],
            52 => [
                "code"     => 305,
                "series_1" => "FEP20",
                "series_2" => "101",
            ],
            53 => [
                "code"     => 310,
                "series_1" => "HIP20",
                "series_2" => "101",
            ],
            54 => [
                "code"     => 311,
                "series_1" => "MCO20",
                "series_2" => "101",
            ],
            55 => [
                "code"     => 316,
                "series_1" => "MSP20",
                "series_2" => "101",
            ],
            56 => [
                "code"     => 306,
                "series_1" => "MEP20",
                "series_2" => "101",
            ],
            57 => [
                "code"     => 317,
                "series_1" => "MBP20",
                "series_2" => "101",
            ],
            58 => [
                "code"     => 318,
                "series_1" => "PHP20",
                "series_2" => "101",
            ],
            59 => [
                "code"     => 319,
                "series_1" => "SWP20",
                "series_2" => "001",
            ],
            60 => [
                "code"     => 312,
                "series_1" => "SOP20",
                "series_2" => "101",
            ],
            62 => [
                "code"     => 404,
                "series_1" => "PHI20",
                "series_2" => "001",
            ],
            66 => [
                "code"     => 216,
                "series_1" => "LAM20",
                "series_2" => "001",
            ],
            69 => [
                "code"     => 232,
                "series_1" => "ASM20",
                "series_2" => "001",
            ],
            70 => [
                "code"     => 120,
                "series_1" => "FEB20",
                "series_2" => "201",
            ],
            71 => [
                "code"     => 406,
                "series_1" => "MCD20",
                "series_2" => "001",
            ],
            72 => [
                "code"     => 240,
                "series_1" => "CEB20",
                "series_2" => "001",
            ],
            73 => [
                "code"     => 241,
                "series_1" => "CSB20",
                "series_2" => "001",
            ],
            74 => [
                "code"     => 242,
                "series_1" => "FEB20",
                "series_2" => "001",
            ],
            75 => [
                "code"     => 243,
                "series_1" => "ECB20",
                "series_2" => "001",
            ],
            76 => [
                "code"     => 243,
                "series_1" => "FEB20",
                "series_2" => "001",
            ],
            77 => [
                "code"     => 244,
                "series_1" => "MEB20",
                "series_2" => "001",
            ],
        ];
        DB::beginTransaction();
        try {
            $collection = collect($array);
            dump($collection->keys());
            $course = Course::whereIn("id", $collection->keys())->withTrashed()->get();
            $course->each(function($course) use ($array){
                if(isset($array[$course->id])){
                    $course->series_1 = $array[$course->id]["series_1"];
                    $course->series_2 = $array[$course->id]["series_2"];
                    $course->save();
                }
            });
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            dd($th->getMessage());
        }
        DB::commit();
    }
}
