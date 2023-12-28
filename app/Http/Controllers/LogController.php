<?php

namespace App\Http\Controllers;

use App\Models\RawLogs;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LogController extends Controller
{
    public function list()
    {
        $logs = RawLogs::with('employee')->orderBy('date_time', 'desc');
        $first = RawLogs::with('employee')->orderBy('date_time')->first();
        
        $numOfLogs = $logs->count();
        $last = $logs->first();
        $logs = $logs->simplePaginate(20);
        return Inertia::render('Admin/LogList', [
            'logs' => $logs,
            'numOfLogs' => $numOfLogs,
            'first' => $first,
            'last' => $last,
        ]);
    }

    public function uploadLog()
    {
        $rowFormat = '/^\d{10}\s+\d{4}\/\d{2}\/\d{2}\s+\d{2}:\d{2}:\d{2}$/';
        $logs = Storage::get(env('LOG_FILE_NAME'));
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

    public function clearRawLogsTable() {
        RawLogs::truncate();
    }
}
