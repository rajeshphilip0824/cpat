<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Corrison
{
    private $a_scan_max;
    private $wall_loss_percentage;
    private $wall_loss_unit;
    private $remaining_wall_unit;
    private $remaining_wall_percentage;
    private $nominal_weight = 10;
    private $avg_remaining_wall_percentage;
    private $avg_remaining_wall_graph;
    private $avg_remaining_wall_unit;
    private $cm_1_percentage;
    private $cm_2_percentage;
    private $cm1 = [];
    private $cm2 = [];
    private $cm_sum;
    private $cm_avg;
    private $minRWTUnit;
    private $minRWTPercentage;
    private $scanAxisIndex;
    private $minimumDeviation;
    private $remaininingWallMinAvg;
    public function __construct()
    {
        $this->calculateAScanMax();
        $this->calculateCM1();
        $this->calculateCM2();
        //dd($this->cm2);


    }
    public function calculate($thickness)
    {
        $this->nominal_weight = $thickness;
        $this->wall_loss_percentage = $this->calculateWallLossPercentage($this->a_scan_max);
        if (!is_array($this->wall_loss_percentage) && count($this->wall_loss_percentage)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->wall_loss_unit = $this->calculateWallLossInUnit($this->wall_loss_percentage);
        if (!is_array($this->wall_loss_unit) && count($this->wall_loss_unit)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->remaining_wall_unit = $this->calculateRemainingWallInUnit($this->wall_loss_unit);

        if (!is_array($this->remaining_wall_unit) && count($this->remaining_wall_unit)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->remaining_wall_percentage = $this->calculateRemainingWallInPercentage($this->remaining_wall_unit);
        if (!is_array($this->remaining_wall_percentage) && count($this->remaining_wall_percentage)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->avg_remaining_wall_percentage = $this->calculateAvgRemainingWallInPercentage($this->remaining_wall_percentage, 10);
        if (!is_array($this->avg_remaining_wall_percentage) && count($this->avg_remaining_wall_percentage)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->avg_remaining_wall_graph = $this->calculateAvgRemainingWallGraph($this->avg_remaining_wall_percentage);
        if (!is_array($this->avg_remaining_wall_graph) && count($this->avg_remaining_wall_graph)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->avg_remaining_wall_unit = $this->calculateRemainingWallAvgInUnit($this->avg_remaining_wall_graph);
        if (!is_array($this->avg_remaining_wall_unit) && count($this->avg_remaining_wall_unit)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->cm_1_percentage = $this->calculateCM1Percentage($this->cm1);
        if (!is_array($this->cm_1_percentage) && count($this->cm_1_percentage)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->cm_2_percentage = $this->calculateCM2Percentage($this->cm2);
        if (!is_array($this->cm_2_percentage) && count($this->cm_2_percentage)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->cm_sum = $this->calculateCMSum();
        if (!is_array($this->cm_sum) && count($this->cm_sum)) {
            throw new Exception('Wall Percentage return empty array');
        }

        $this->cm_avg = $this->calculateCMAvg($this->cm_sum);
        if (!is_array($this->cm_avg) && count($this->cm_avg)) {
            throw new Exception('Wall Percentage return empty array');
        }
       // dd($this->cm_avg);
        $this->calculateMinRWTUnit();
        $this->calculateMinRWTPercentage();
        $this->calculateScanAxisUnit();
        $this->calculateCMStandardDeviation();
        $this->calculateRemainingWallMinAvg();
        return [$this->minRWTUnit, $this->minRWTPercentage, $this->scanAxisIndex, $this->minimumDeviation, $this->remaininingWallMinAvg, $this->avg_remaining_wall_percentage, $this->cm_sum];
    }
    public function calculateWallLossPercentage($data)
    {
        if (!$data) {
            throw new Exception('A Scan Max data is empty');
        }
        $wall_loss_percentage = [];
        foreach ($data as $key => $value) {
            $wall_loss_percentage[] = round((1 - ((float)$value / 80)) * 100, 5);
        }
        return $wall_loss_percentage;
    }
    public function calculateWallLossInUnit($data)
    {

        $wall_loss_unit = [];
        foreach ($data as $key => $value) {
            $wall_loss_unit[] = round(($value / 110) * $this->nominal_weight, 9);
        }
        //dd($wall_loss_unit);
        return $wall_loss_unit;
    }
    public function calculateRemainingWallInUnit($data)
    {

        $result = [];
        foreach ($data as $key => $value) {
            $result[] = $this->nominal_weight - $value;
        }
        return $result;
    }
    public function calculateRemainingWallInPercentage($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] =  round($value / $this->nominal_weight, 9);
        }
        return $result;
    }
    public function calculateAvgRemainingWallInPercentage(array $data, int $windowSize = 10, bool $strict = false)
    {
        $result = [];
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            // Calculate window range dynamically
            $end = $i + $windowSize;

            if ($end > $count) {
                // Not enough ahead, shift window backward
                $start = max(0, $count - $windowSize);
                $slice = array_slice($data, $start, $count - $start);
            } else {
                // Normal forward window
                $slice = array_slice($data, $i, $windowSize);
            }

            // Strict mode: skip incomplete windows
            if ($strict && count($slice) < $windowSize) {
                continue;
            }

            $avg = array_sum($slice) / count($slice);
            $result[] = round($avg, 9);
        }

        return $result;
    }

    public function calculateAvgRemainingWallGraph($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] =  min($value, 1);
        }
        return $result;
    }
    public function calculateRemainingWallAvgInUnit($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] =  round($this->nominal_weight * $value, 9);
        }
        //dd($result);
        return $result;
    }
    public function calculateCM1Percentage($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] =  round(20 * log10($value / 80), 9);
        }
        //dd($result);
        return $result;
    }
    public function calculateCM2Percentage($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] =  round(20 * log10($value / 80), 9);
        }
        // dd($result);
        return $result;
    }
    public function calculateCMSum()
    {
        $result = [];
        $cm1 = $this->cm_1_percentage;
        $cm2 = $this->cm_2_percentage;
        $result = array_map(fn($x, $y) => $x + $y, $cm1, $cm2);
        //dd($result);
        return $result;
    }

    public function calculateCMAvg(array $data, int $windowSize = 10, bool $strict = false)
    {
        $result = [];
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            // Default window: current + next (windowSize - 1) elements
            $end = $i + $windowSize;

            if ($end > $count) {
                // Near the end → shift window backward
                $start = max(0, $count - $windowSize);
                $slice = array_slice($data, $start, $count - $start);
            } else {
                // Normal forward window
                $slice = array_slice($data, $i, $windowSize);
            }

            // Strict mode: skip incomplete windows
            if ($strict && count($slice) < $windowSize) {
                continue;
            }

            $avg = array_sum($slice) / count($slice);
            $result[] = round($avg, 9);
        }

        return $result;
    }

    public function calculateMinRWTUnit()
    {
        $H = $this->avg_remaining_wall_percentage;
        $J = $this->avg_remaining_wall_unit;
        //dd($J);
        // 1. Find min value in H
        $minValue = min($H);

        // 2. Find index of min value in H
        $index = array_search($minValue, $H);

        // 3. Get corresponding value from J
        $this->minRWTUnit = round($J[$index], 2);
    }
    public function calculateMinRWTPercentage()
    {
        $H = $this->avg_remaining_wall_percentage;
        $this->minRWTPercentage = round(min($H) * 100, 2);
    }
    public function calculateScanAxisUnit()
    {
        $H = $this->avg_remaining_wall_percentage;

        $minValue = min($H);

        // Step 2: find index of min value
        $this->scanAxisIndex = array_search($minValue, $H); // 0-based index

        // Step 3: fetch corresponding value from A
        // $result = $A[$index];
    }
    public function calculateCMStandardDeviation()
    {
        $H = $this->avg_remaining_wall_percentage;
        $P = $this->cm_sum;
        $minValue = min($H);

        // Step 2: find index of min value
        $index = array_search($minValue, $H); // 0-based index

        // Step 3: get corresponding value from P
        $this->minimumDeviation =  round($P[$index], 2);
    }
    public function calculateRemainingWallMinAvg()
    {
        $H = $this->avg_remaining_wall_percentage;



        // Find min from full H range
        $minValue = min($H);
        $this->remaininingWallMinAvg = $minValue;
    }
    public function calculateAScanMax()
    {

        $id = Session::get('pcValue');
        Session::forget('pcValue');
        // Fetch all rows as collections of objects
        $rows = DB::table('pc_reading')->select('amplitude')->where('file_id',$id)->get();

        $data = $rows->map(function ($item) {
            $decoded = json_decode($item->amplitude, true); // decode JSON string to array
            if (!is_array($decoded)) {
                return []; // or handle invalid JSON accordingly
            }
            // convert each value to float
            return array_map('floatval', $decoded);
        })->toArray();
        // dd($data);
        $numCols = count($data[0]);
        $result = [];
       // dd($numCols);
        for ($col = 0; $col < $numCols; $col++) {
            $validValues = [];
            foreach ($data as $row) {
                if ($row[$col] <= 80) {
                    $validValues[] = $row[$col];
                }
            }

            if (!empty($validValues)) {
                $result[] = max($validValues);
            } else {
                $result[] = null;  // or handle no valid values per your needs
            }
        }
        $this->a_scan_max = $result;
        //dd($result); // Dump the result for debugging or use it further

    }
    public function calculateCM1()
    {

         $id = Session::get('cm1Value');
        Session::forget('cm1Value');
        // Fetch all rows as collections of objects
        $rows = DB::table('cm1_reading')->select('amplitude')->where('file_id',$id)->get();
        //dd($rows);
        $data = $rows->map(function ($item) {
            $decoded = json_decode($item->amplitude, true); // decode JSON string to array
            if (!is_array($decoded)) {
                return []; // or handle invalid JSON accordingly
            }
            // convert each value to float
            return array_map('floatval', $decoded);
        })->toArray();
        //dd($data);
        $numCols = count($data[0]);
        $result = [];
        //dd($numCols);
        for ($col = 0; $col < $numCols; $col++) {
            $validValues = [];
            foreach ($data as $row) {
                // dd($row[$col]);
                if (count($row) > 0 && $row[$col] <= 101) {
                    $validValues[] = $row[$col];
                }
            }
            //          dd($validValues);
            if (!empty($validValues)) {
                $result[] = max($validValues);
            } else {
                $result[] = null;  // or handle no valid values per your needs
            }
        }
        $this->cm1 = $result;
       // dd($result); // Dump the result for debugging or use it further

    }
    public function calculateCM2()
    {


         $id = Session::get('cm2Value');
        Session::forget('cm2Value');

        // Fetch all rows as collections of objects
        $rows = DB::table('cm2_reading')->select('amplitude')->where('file_id',$id)->get();

        $data = $rows->map(function ($item) {
            $decoded = json_decode($item->amplitude, true); // decode JSON string to array
            if (!is_array($decoded)) {
                return []; // or handle invalid JSON accordingly
            }
            // convert each value to float
            return array_map('floatval', $decoded);
        })->toArray();
        // dd($data);
        $numCols = count($data[0]);
        $result = [];

        for ($col = 0; $col < $numCols; $col++) {
            $validValues = [];
            foreach ($data as $row) {
                if (count($row) > 0 && $row[$col] <= 101) {
                    $validValues[] = $row[$col];
                }
            }

            if (!empty($validValues)) {
                $result[] = max($validValues);
            } else {
                $result[] = null;  // or handle no valid values per your needs
            }
        }
        $this->cm2 = $result;
        //dd($result); // Dump the result for debugging or use it further

    }
}
