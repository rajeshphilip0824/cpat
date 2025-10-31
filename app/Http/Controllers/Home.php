<?php

namespace App\Http\Controllers;

use App\Services\Corrison;
use App\Services\CorrisonTest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Home extends Controller
{
    public function index(){
        return view('upload');
    }
    public function upload(Request $request)
    {
        $request->validate([
            'pc' => 'nullable|file|max:2048',
            'cm1' => 'nullable|file|max:4096',
            'cm2' => 'nullable|file|max:2048',
        ]);

        $paths = [];

        // Handle file1
        if ($request->hasFile('pc')) {
            $paths['pc'] = basename($request->file('pc')->store('pc', 'public'));
            $pcid = DB::table('files')->insertGetId([
                'type'=>'pc',
                'file'=>$paths['pc']
            ]);
            $pc = [$pcid,$paths['pc']];
        }

        // Handle file2
        if ($request->hasFile('cm1')) {
            $paths['cm1'] = basename($request->file('cm1')->store('cm1', 'public'));

            $cm1id = DB::table('files')->insertGetId([
                'type'=>'cm1',
                'file'=>$paths['cm1']
            ]);
            $cm1 = [$cm1id,$paths['cm1']];
        }

        // Handle file3
        if ($request->hasFile('cm2')) {
            $paths['cm2'] = basename($request->file('cm2')->store('cm2', 'public'));
            $cm2id = DB::table('files')->insertGetId([
                'type'=>'cm2',
                'file'=>$paths['cm2']
            ]);
            $cm2 = [$cm2id,$paths['cm2']];
        }
        Session::put('thickness',$request->thickness);
        Session::put('pcValue',$pc[0]);
        Session::put('cm1Value',$cm1[0]);
        Session::put('cm2Value',$cm2[0]);
        $this->test($pc,$cm1,$cm2);
        return redirect()->route('calculate');
    }
    public function calculate(Corrison $service){

        $thickness = Session::get('thickness');
        //Session::forget('thickness');
        $data = $service->calculate($thickness);
        $blueLine = $data[5]; // Example data
        $labels = range(1, count($data[5]));
        $orangeLine = $data[6];
        $orangeLine = array_map(function($v) {
            if (is_infinite($v)) {
                return 0; // or 0 if you want a number
            }
            return $v;
        }, $orangeLine);
        //dd($data);
        return view('welcome',compact('data','labels','blueLine','orangeLine'));
    }
    public function calculateTest(CorrisonTest $service){

        $data = $service->calculate();
        $blueLine = $data[5]; // Example data
        $labels = range(1, count($data[5]));
        $orangeLine = $data[6];
        $orangeLine = array_map(function($v) {
            if (is_infinite($v)) {
                return 0; // or 0 if you want a number
            }
            return $v;
        }, $orangeLine);
        //dd($data);
        return view('welcome',compact('data','labels','blueLine','orangeLine'));
    }
    public function test($pcA,$cm1A,$cm2A){
        //dd($pc[0],$cm1,$cm2);
        DB::beginTransaction();

        try{


        $pc = array_map(function($line) {
            return str_getcsv($line, "\t");
        }, file(storage_path('app/public/pc/'.$pcA[1])));
        $cm1 = array_map(function($line) {
            return str_getcsv($line, "\t");
        }, file(storage_path('app/public/cm1/'.$cm1A[1])));
         $cm2 = array_map(function($line) {
            return str_getcsv($line, "\t");
        }, file(storage_path('app/public/cm2/'.$cm2A[1])));
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
                    'file_id'=>$pcA[0],
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
                    'file_id'=>$cm1A[0],
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
                    'file_id'=>$cm2A[0],
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
    public function test1(){
        //dd($pc[0],$cm1,$cm2);
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
        }, file(storage_path('app/public/CM2.txt')));
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
                    'file_id'=>0,
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
                    'file_id'=>0,
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
                    'file_id'=>0,
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
}
