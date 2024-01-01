<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class uploadUserList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-users';

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
        $employees = Storage::get('users.txt');
        if(!$employees) {
            $this->error('file users.txt does not exist');
        } else {
            $rows = explode("\n", $employees);
        foreach($rows as $items) {
            $item = explode('|', $items);
            if($item[0] === '#') continue;
            if((count($item))<8) continue;
            $data = [
                'id' => $item[0],
                'name' => $item[1],
                'email' => $item[2],
                'firstname' => $item[3],
                'lastname' => $item[4],
                'is_premia' => $item[5],
                'is_active' => $item[6],
                'is_admin' => $item[7],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            Employee::insertOrIgnore($data);
        }
        $this->info('success');   
        }
    }
}
