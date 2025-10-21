<?php

namespace App\Http\Controllers;

use App\Services\Corrison;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Home extends Controller
{
    public function index(Corrison $service){
        $labels = range(1, 200);

        $data = $service->calculate();
        $blueLine = $data[5]; // Example data
        $orangeLine = $data[6];
        return view('welcome',compact('data','labels','blueLine','orangeLine'));
    }
    public function test(){
        DB::beginTransaction();

        try{


        $pc = array_map(function($line) {
            return str_getcsv($line, "\t");
        }, file(storage_path('app/public/PC.txt')));
        $cm1 = array_map(function($line) {
            return str_getcsv($line, "\t");
        }, file(storage_path('app/public/CM1.txt')));
         $cm2 = array_map(function($line) {
            return str_getcsv($line, "\t");
        }, file(storage_path('app/public/cm2.txt')));
        array_shift($pc[0]);
        $countRowPC = count($pc[0]);

         foreach ($pc as $key => $row) {
            $rowCount = 1;
            $pcValues = [];
            if($key > 0){
                while($rowCount <= $countRowPC){
                    $value = trim($row[$rowCount]);
                    if ($value !== '' && $value !== null) {
                        $pcValues[] = $value;
                    }
                    $rowCount++;
                }
                if(count($pcValues) !== $countRowPC){
                    throw new Exception('Invalid Row Count',500);
                }
                DB::table('pc_reading')->insert([
                    'angle' => $row[0],
                    'amplitude' => json_encode($pcValues),
                   // 'cm1' => json_encode($cmValues)

                ]);

            }

        }
      //  exit(0);
       /*   foreach ($cm1 as $key => $row) {
            $rowCount = 1;
            $pcValues = [];
            if($key > 0){
                while($rowCount <= 200){
                    $pcValues[] = $row[$rowCount];
                    $rowCount++;
                }
                dd($pcValues); */
               /*  DB::table('measurements_1')->insert([
                    'angle' => $row[0],
                    'amplitude' => json_encode($pcValues),
                   // 'cm1' => json_encode($cmValues)

                ]); */
          /*   }

        } */
       // array_shift($cm1);
        $transposed = array_map(null, ...$cm1);
        array_shift($transposed[0]);
       // dd($transposed);
        $countRowCM1 = count($transposed[0]);

        foreach ($transposed as $key => $row) {

            $rowCount = 0;
            $pcValues = [];
            if($key > 0){
                array_shift($row);
                //dd($row,$countRowCM1);
                while($rowCount <= ($countRowCM1-1)){
                    $value = trim($row[$rowCount]);
                    if ($value !== '' && $value !== null) {
                        $pcValues[] = $value;
                    }
                    $rowCount++;
                }
                //dd($pcValues);
                /* if(count($pcValues) !== $countRowCM1){
                    throw new Exception('Invalid Row Count',500);
                } */
                 DB::table('cm1_reading')->insert([
                    'angle' => 0.00,
                    'amplitude' => json_encode($pcValues),
                   // 'cm1' => json_encode($cmValues)

                ]);
            }
        }
        $transposed = array_map(null, ...$cm2);
        array_shift($transposed[0]);
       // dd($transposed);
        $countRowCM1 = count($transposed[0]);

        foreach ($transposed as $key => $row) {

            $rowCount = 0;
            $pcValues = [];
            if($key > 0){
                array_shift($row);
                //dd($row,$countRowCM1);
                while($rowCount <= ($countRowCM1-1)){
                    $value = trim($row[$rowCount]);
                    if ($value !== '' && $value !== null) {
                        $pcValues[] = $value;
                    }
                    $rowCount++;
                }
                //dd($pcValues);
                /* if(count($pcValues) !== $countRowCM1){
                    throw new Exception('Invalid Row Count',500);
                } */
                 DB::table('cm2_reading')->insert([
                    'angle' => 0.00,
                    'amplitude' => json_encode($pcValues),
                   // 'cm1' => json_encode($cmValues)

                ]);
            }
        }
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
        dd($e->getMessage());
    }
       /*  //dd($pcValues);
        foreach ($cm1 as $key => $row) {
            $rowCount = 1;
            $cmValues = [];
            if($key > 0){
                while($rowCount <= 200){
                    $cmValues[] = $row[$rowCount];
                    $rowCount++;
                }
            }
        }
 */

    }
    /* public function ascan(Corrison $service){
        $service->calculateAScanMax();
    } */

}
