<?php

namespace App\Http\Controllers;

use App\Models\RawLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Termwind\Components\Raw;

class LogController extends Controller
{
    public function list()
    {
        $logs = RawLogs::get();

        return Inertia::render('LogList', ['logs' => $logs]);
    }

    public function setLog()
    {

        $rowFormat = '/^\d{10}\s+\d{4}\/\d{2}\/\d{2}\s+\d{2}:\d{2}:\d{2}$/';
        $logs = Storage::get('list.txt');
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
