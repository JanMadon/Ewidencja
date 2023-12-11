<?php

namespace App\Http\Controllers;

use App\Models\AddedLogs;
use App\Models\DeletedLogs;
use App\Models\RawLogs;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Termwind\Components\Raw;

class LogController extends Controller
{

    public function logAdd(Request $reguest){

        // tzeba zrobić walidacja która sprawdza czy DANY user nie dodał już logu o danej godzinie i czy nie ma tej godziny w RawLog 
        // id jest wysyłane w url, wiec to raczej dla admina

        $data = [
            'date_time' => $reguest->newRecord,
            'employee_id' => $reguest->id,
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
         AddedLogs::insert($data);
    }

    public function list()
    {
        $logs = RawLogs::get();
        return Inertia::render('LogList', ['logs' => $logs]);
    }

    public function userLogs(Request $request, string $id)
    {
        $timeFrom = $request['date'];
        $timeTo = Carbon::parse($timeFrom)->addMonth()->format('Y-m-d');
        
        //  dd($timeFrom,  $timeTo);


        // $logs = RawLogs::select('date_time')
        //     ->where('date_time', '>', $timeFrom)
        //     ->where('date_time', '<', $timeTo)
        //     ->where('employee_id', $id)
        //     ->orderBy('date_time')
        //     ->get()->toArray();

            $logs = RawLogs::select('date_time')
            ->where('raw_logs.date_time', '>', $timeFrom)
            ->where('raw_logs.date_time', '<', $timeTo)
            ->where('raw_logs.employee_id', $id)
            ->union(AddedLogs::select('date_time')
                            ->where('employee_id', $id)
                            ->where('is_approved', true)
                            ->where('is_active', true)
                            )
            ->orderBy('date_time')
            ->get()->toArray();

            // $deletedLogs = DeletedLogs::select('date_time')
            //                     ->where('is_approved', true)
            //                     ->where('is_active', true)
            //                     ->get()->toArray();
              dd($logs);


        $daysLogs = [];
        foreach ($logs as $log) {
            $dateTime = $log['date_time'];
            list($date, $time) = explode(' ', $dateTime);
            $daysLogs[$date]['logs'][] = $time;
        }


        //czy poprawna liczba rekordów danego dnia 
        foreach ($daysLogs as $date => $Logs) {

            $daysLogs[$date]['num_logs'] = count($Logs['logs']);
            $daysLogs[$date]['is_correct'] = count($Logs['logs']) % 2 ? false : true;

            //jeśli jest poprawna zlicz godziny
            if ($daysLogs[$date]['is_correct']) {

                $sumTime = CarbonInterval::seconds(00);
                $sumWorkTime = CarbonInterval::seconds(00);

                $sumTime->add(CarbonInterval::createFromFormat('H:i:s', $daysLogs[$date]['logs'][0]))->cascade();
                $sumTime->sub(CarbonInterval::createFromFormat('H:i:s', $daysLogs[$date]['logs'][$daysLogs[$date]['num_logs'] - 1]))->cascade();

                for ($i = 0; $i < $daysLogs[$date]['num_logs']; $i++) {
                    if ($i % 2 === 0) {
                        $sumWorkTime->add(CarbonInterval::createFromFormat('H:i:s', $daysLogs[$date]['logs'][$i]))->cascade();
                    } else {
                        $sumWorkTime->sub(CarbonInterval::createFromFormat('H:i:s', $daysLogs[$date]['logs'][$i]))->cascade();
                    }
                }
                $sumBreakTime = Carbon::parse($sumTime)->diff(Carbon::parse($sumWorkTime));

                $daysLogs[$date]['time'] = $sumTime->format('%H:%I:%S');
                $daysLogs[$date]['work_time'] = $sumWorkTime->format('%H:%I:%S');
                $daysLogs[$date]['break_time'] = $sumBreakTime->format('%H:%I:%S');
            } else {
                $daysLogs[$date]['work_time'] = null;
                continue;
            }

            //Czy jest odliczone 30min przrwy?
            $daysLogs[$date]['is_added_break'] = false;
            if ($daysLogs[$date]['time'] > "08:00:00" && $daysLogs[$date]['work_time'] > "07:30:00" && $daysLogs[$date]['break_time'] > "00:00:00") {

                $daysLogs[$date]['is_added_break'] = true;

                if ($daysLogs[$date]['break_time'] < '00:30:00') {

                    $daysLogs[$date]['work_time'] = CarbonInterval::createFromFormat('H:i:s', $daysLogs[$date]['work_time']);
                    $daysLogs[$date]['work_time']->add(CarbonInterval::createFromFormat('H:i:s', $daysLogs[$date]['break_time']))->cascade();
                    $daysLogs[$date]['work_time'] = $daysLogs[$date]['work_time']->format('%H:%I:%S');
                } else {
                    $maxAddBreak = "00:30:00";
                    $daysLogs[$date]['work_time'] = CarbonInterval::createFromFormat('H:i:s', $daysLogs[$date]['work_time']);
                    $daysLogs[$date]['work_time']->add(CarbonInterval::createFromFormat('H:i:s', $maxAddBreak))->cascade();
                    $daysLogs[$date]['work_time'] = $daysLogs[$date]['work_time']->format('%H:%I:%S');
                }
            }

            // Czy przysługuje premia? *gdy nie ma odmidzia miedz 9-10 *jeżeli wszytkie logi są po 9(czyli 1)
            // *gdy ostatni log jest przed 9
            $daysLogs[$date]['premia'] = true;
            foreach ($daysLogs[$date]['logs'] as $key => $log) {
                if ("09:00:00" < $log && $log < "10:00:00") {
                    $daysLogs[$date]['premia'] = false;
                }

                if ($key % 2 !== 0) {
                    if ($daysLogs[$date]['logs'][$key] < "09:00:00" && $daysLogs[$date]['logs'][$key + 1] > "09:00:00") {
                        $daysLogs[$date]['premia'] = false;
                    }
                }
            }

            if ($daysLogs[$date]['logs'][0] > '10:00:00') {
                $daysLogs[$date]['premia'] = false;
            }
            if ($daysLogs[$date]['logs'][$daysLogs[$date]['num_logs'] - 1] < '09:00:00') {
                $daysLogs[$date]['premia'] = false;
            }
        }

        return Inertia::render('UserShow', [
            'id' => $id,
            'setTime'=> [$timeFrom, $timeTo],
            'daysData' => $daysLogs,
        ]);
    }

    public function setLog()
    {

        $rowFormat = '/^\d{10}\s+\d{4}\/\d{2}\/\d{2}\s+\d{2}:\d{2}:\d{2}$/';
        $logs = Storage::get('list3.txt');
        $rows = explode("\n", $logs);
        $data = [];
        $keys = ['employee_id', 'date_time'];

        foreach ($rows as $row) {
            if (preg_match($rowFormat, $row)) {
                $col = explode("\t", $row);
                $col = array_combine($keys, $col);
                array_push($data, $col);
            } else {
                // invalid data format trought or somethink
            }
        }
        // dd($data);

        RawLogs::insertOrIgnore($data);
    }
}
