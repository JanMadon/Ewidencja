<?php

namespace App\Console\Commands;

use App\Models\RawLogs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class uploadLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
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
        $count =  RawLogs::insertOrIgnore($data);
        $this->info('success, uploaded ' . $count . ' logs');  
    }
}
