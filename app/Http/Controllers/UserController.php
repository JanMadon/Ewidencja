<?php

namespace App\Http\Controllers;

use App\Models\AddedLogs;
use App\Models\Employee;
use App\Models\RawLogs;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getBill() {

        return Inertia::render('User/DashboardUser', [
            'data'=>[],
            'user' => Auth::user()->employee(),
        ]);
    }

    public function getBillPeriod(Request $request)
    {
        $user = Auth::user()->employee();
        $id = $user->id;
        $timeFrom = $request['date'] ?? Carbon::now()->format('Y-m-01');
        $timeTo = Carbon::parse($timeFrom)->addMonth()->format('Y-m-d');
        //dd($timeFrom, $timeTo);

        $daysLogs = $this->getDataForUser($id, $timeFrom, $timeTo);
        $errors = 0;
        $workTime = CarbonInterval::seconds(00);
        $doubleHours = 0;
        $salary = Salary::where('employee_id', $id)->orderBy('valid_from','desc')->first()->salary ?? 0;
        $salaryVaildFrom = '2023-01';
        $salaryVaildto = 'to implement';

        foreach($daysLogs as $daydata) {
            if(!$daydata['is_correct']){
                $errors++;
                continue;
            }
            if($daydata['premia']) {
                $doubleHours++;
            }
            $workTime->add(CarbonInterval::createFromFormat('H:i:s', $daydata['work_time']))->cascade();
        }
        $workTime = $workTime->format('%D:%H:%I:%S');
        $workTime = $this->changeTimeFormat($workTime);

        return Inertia::render('User/DashboardUser', [
            'user' => $user,
            'setTime' => [$timeFrom, $timeTo],
            'data' => [
                'workTime' => $workTime,
                'doubleHours' => $doubleHours,
                'errors' => $errors,
                'salary' => $salary,
                'salaryVaildFrom' => $salaryVaildFrom,
                'salaryVaildTo' => $salaryVaildto
        ],
        ]);
    }
    public function Logs()
    {

        return Inertia::render('User/Logs', [
            'id' => Auth::user()->employee()->id,
            'setTime' => [],
            'daysData' => []
        ]);
    }

    public function LogsPeriod(Request $request)
    {
        $id = Auth::user()->employee()->id;
        $timeFrom = $request['date'] ?? Carbon::now()->format('Y-m-01');
        $timeTo = Carbon::parse($timeFrom)->addMonth()->format('Y-m-d');
        //dd($timeFrom, $timeTo);

        $daysLogs = $this->getDataForUser($id, $timeFrom, $timeTo);
        return Inertia::render('User/Logs', [
            'id' => $id,
            'setTime' => [$timeFrom, $timeTo],
            'daysData' => $daysLogs,
        ]);
    }

    public function addLogRequest(Request $reguest)
    {
        // tzeba zrobić walidacja która sprawdza czy DANY user nie dodał już logu o danej godzinie i czy nie ma tej godziny w RawLog
        // id jest wysyłane w url, wiec to raczej dla admina
        $id = Auth::user()->employee()->id;
        $data = [
            'date_time' => $reguest->newRecord,
            'employee_id' => $id,
            'is_active' => true,
            'is_approved' => false,
            'approved_by' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        // dd($data);
        AddedLogs::insert($data);

        return Inertia::render('User/Logs', [
            'id' => $id,
            'recordAdded' => $reguest->newRecord
        ]);
    }

    public function getRequests(Request $request)
    {
        //można by sprawdzać jaki user jest zalogowany
        $usersRequests = [];
        $data = AddedLogs::where('employee_id', Auth::user()->employee()->id)
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        foreach ($data as $log) {
            $usersRequests[] = [
                'logId' => $log['id'],
                'userId' => $log['employee_id'],
                'userName' => Employee::find($log['employee_id'])->name ?? null,
                'approvedBy' => Employee::find($log['approved_by'])->name ?? null,
                'status' => $this->AddedLogStatus($log['id']),
                'date_time' => $log['date_time'],
                'createdAt' => Carbon::parse($log['created_at'])->format('Y-m-d H:i:s'),
            ];
        }
        $usersRequests1 = $this->arrPaginate($usersRequests, 10);
        $usersRequests1->setPath($request->url());

        return Inertia::render('User/Requests', ['usersRequests' => $usersRequests1]);
    }

    public function deleteRequests(Request $request)
    {
        $log = AddedLogs::find($request->requestId);
        $log->is_active = false;
        $log->save();

        return to_route('my.requests');
    }

    public function trash(Request $request) {

        $usersRequests = [];

        $data = AddedLogs::where('employee_id', Auth::user()->employee()->id)
            ->where('is_active', false)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        foreach ($data as $log) {
            $usersRequests[] = [
                'logId' => $log['id'],
                'userId' => $log['employee_id'],
                'userName' => Employee::find($log['employee_id'])->name ?? null,
                'approvedBy' => Employee::find($log['approved_by'])->name ?? null,
                'status' => $this->AddedLogStatus($log['id']),
                'date_time' =>$log['date_time'],
                'createdAt' => Carbon::parse($log['created_at'])->format('Y-m-d H:i:s'),
            ];
        }
        $usersTrash = $this->arrPaginate($usersRequests, 10);
        $usersTrash->setPath($request->url());

        return Inertia::render('User/Trash', ['usersRequests' => $usersTrash]);
    }

    public function arrPaginate($array, $perPage = 5, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($array);
        $currentPage = $page;
        $offset = ($currentPage * $perPage) - $perPage;
        $itemsToShow = array_slice($array, $offset, $perPage);
        return new LengthAwarePaginator($itemsToShow, $total, $perPage);
    }

    public function AddedLogStatus($logId): string
    {
        $log = AddedLogs::find($logId);

        if ($log->is_approved && $log->approved_by) return 'accpeted';
        if (!$log->is_approved && $log->approved_by) return 'rejected';

        return 'waiting';
    }

    public function create()
    {
        //narazie jest przez rejstracje twozenie userów ,,
    }

    public function edit(string $id = null)
    {
        $user = Employee::find($id);
        return Inertia::render('UserEdit', ['user' => $user]);
    }

    public function update(Request $request, string $id)
    {
        // TO DO VALIDACJA I UPDATE DB
        dd($request);
    }

    public function destroy(string $id)
    {
        //
    }

    
}
