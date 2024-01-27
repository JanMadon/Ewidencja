<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Models\AddedLogs;
use App\Models\Employee;
use App\Models\RawLogs;
use App\Models\Salary;
//use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function dashboard()
    {
         $today = Carbon::now()->format('Y-m-d');
         $tomorrow = Carbon::tomorrow()->format('Y-m-d');
         $logs = RawLogs::where('date_time', '>' ,$today)
            ->where('date_time', '<' ,$tomorrow)
            ->get()
            ->groupBy('employee_id');

         $usersLogs = [];

         foreach($logs as $employeeId => $employeeLogs) {
            $userName = Employee::find($employeeId)->name ?? RawLogs::find($employeeId)->id;
            $usersLogs[$userName] = $employeeLogs;
         }

         //dd($usersLogs);
        return Inertia::render('Admin/DashboardAdmin', [
            'usersLogs' => $usersLogs
        ]);
    }

    public function list()
    {
        $users = Employee::get();
        return Inertia::render('Admin/UsersList', ['users' => $users]);
    }

    public function create()
    {

        //dd('jetsm');
        return Inertia::render('Admin/UserCreate');
    }

    public function createPost(CreateUserRequest $request)
    {
        $request->validated();
        $data[] = [
            'id' => $request->id,
            'name' => $request->name,
            'email' => $request->email,
            'lastname' => $request->lastname,
            'is_premia' => $request->isPremia,
            'is_active' => $request->isActive,
            'is_admin' => $request->isAdmin,
            'working_since' => $request->workingSince,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        Employee::insert($data);

        return to_route('users.list');
    }

    public function edit(string $id = null)
    {
        $user = Employee::find($id);
        return Inertia::render('Admin/UserEdit', ['user' => $user]);
    }

    public function update(EditUserRequest $request, string $id)
    {   // change ID
        $request->validated($request, $id);
        $user = Employee::find($id);
        $user->id = $request->id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->is_active = $request->isActive;
        $user->is_admin = $request->isAdmin;
        $user->is_premia = $request->isPremia;
        $user->save();

        return to_route('users.list');
    }

    public function delete(string $id)
    {
        $user = Employee::find($id);
        $user->delete();
        return to_route('users.list');
    }

    public function userLogs(string $id)
    {
        return Inertia::render('Admin/UserLogs', [
            'id' => $id,
            'user' => Employee::find($id) ?? [],
            'setTime' => [],
            'daysData' => [],
        ]);
    }


    public function userLogsPeriod(Request $request, string $id)
    {
        $timeFrom = $request['date'] ?? Carbon::now()->format('Y-m-01');
        $timeTo = Carbon::parse($timeFrom)->addMonth()->format('Y-m-d');
        //dd($timeFrom, $timeTo);

        $daysLogs = $this->getDataForUser($id, $timeFrom, $timeTo);
        // dd($daysLogs);

        return Inertia::render('Admin/UserLogs', [
            'id' => $id,
            'user' => Employee::find($id) ?? [],
            'setTime' => [$timeFrom, $timeTo],
            'daysData' => $daysLogs,
        ]);
    }

    public function addLog(Request $reguest, string $id)
    {

        // tzeba zrobić walidacja która sprawdza czy DANY user nie dodał już logu o danej godzinie i czy nie ma tej godziny w RawLog
        // id jest wysyłane w url, wiec to raczej dla admina

        $data = [
            'date_time' => $reguest->newRecord,
            'employee_id' => $reguest->id,
            'is_active' => true,
            'is_approved' => true,
            'approved_by' => Auth::user()->employee()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        // dd($data);
        AddedLogs::insert($data);

        return Inertia::render('Admin/UserLogs', [
            'id' => $id,
            'recordAdded' => $reguest->newRecord
        ]);
    }

    public function getBill(string $id)
    {
        $user = Employee::find($id);
        return Inertia::render('Admin/UserBill', [
            'id' => $id,
            'data' => [],
            'user' => $user,
        ]);
    }

    public function getBillPeriod(Request $request, string $id)
    {
        $user = Employee::find($id);
        $timeFrom = $request['date'] ?? Carbon::now()->format('Y-m-01');
        $timeTo = Carbon::parse($timeFrom)->addMonth()->format('Y-m-d');
        //dd($timeFrom, $timeTo);

        $daysLogs = $this->getDataForUser($id, $timeFrom, $timeTo);
        $errors = 0;
        $workTime = CarbonInterval::seconds(00);
        $doubleHours = 0;
        $salary = Salary::where('employee_id', $id)->orderBy('valid_from', 'desc')->first()->salary ?? 0;
        $salaryVaildFrom = '2023-01';
        $salaryVaildto = 'to implement';

        foreach ($daysLogs as $daydata) {
            if (!$daydata['is_correct']) {
                $errors++;
                continue;
            }
            if ($daydata['premia']) {
                $doubleHours++;
            }
            $workTime->add(CarbonInterval::createFromFormat('H:i:s', $daydata['work_time']))->cascade();
        }
        $workTime = $workTime->format('%D:%H:%I:%S');
        $workTime = $this->changeTimeFormat($workTime);

        return Inertia::render('Admin/UserBill', [
            'id' => $id,
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

    public function setNewSalary(Request $request, string $id)
    {

        $user = Employee::find($id);
        $salary = new Salary();
        $salary->set_by = Auth::id();
        $salary->employee_id = $user->id;
        $salary->salary = $request->newSalry;
        if($request->validFrom) {
            $salary->valid_from = $request->validFrom . '-01';
        }
        $salary->save();

        return Inertia::render('Admin/UserBill', [
            'id' => $id,
            'data' => [],
            'user' => $user,
            'newSalaryAdded' => true,
        ]);
    }

    public function requestsList(Request $request)
    {
        //można by sprawdzać jaki user jest zalogowany
        $usersRequests = [];

        $data = AddedLogs::where('is_active', true)
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
        $usersRequests = $this->arrPaginate($usersRequests, 10);
        $usersRequests->setPath($request->url());
    

        return Inertia::render('Admin/UsersRequests', [
            'usersRequests' => $usersRequests
        ]);
    }

    public function requestAccept(Request $request)
    {

        if ($request->action === 'accpet') {
            $log = AddedLogs::find($request->requestId);
            $log->approved_by = Auth::user()->employee()->id;
            $log->is_approved = true;
            $log->save();
        } elseif ($request->action === 'reject') {
            $log = AddedLogs::find($request->requestId);
            $log->approved_by = Auth::user()->employee()->id;
            $log->is_approved = false;
            $log->save();
        } elseif ($request->action === 'undo') {
            $log = AddedLogs::find($request->requestId);
            $log->approved_by = null;
            $log->is_approved = false;
            $log->save();
        } else {
            // throw an exception
        }

        return to_route('users.requests');
    }

    public function moveToTrash(Request $request)
    {
        $log = AddedLogs::find($request->requestId);
        $log->is_active = false;
        $log->save();

        return to_route('users.requests');
    }

    public function trash(Request $request)
    {

        $usersRequests = [];

        $data = AddedLogs::where('is_active', false)
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
        $usersRequests = $this->arrPaginate($usersRequests, 10);

        $usersRequests->setPath($request->url());

        return Inertia::render('Admin/Trash', ['usersRequests' => $usersRequests]);
    }

    protected function arrPaginate($array, $perPage = 5, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($array);
        $currentPage = $page;
        $offset = ($currentPage * $perPage) - $perPage;
        $itemsToShow = array_slice($array, $offset, $perPage);
        return new LengthAwarePaginator($itemsToShow, $total, $perPage);
    }

    protected function AddedLogStatus($logId): string
    {
        $log = AddedLogs::find($logId);

        if ($log->is_approved && $log->approved_by) return 'accpeted';
        if (!$log->is_approved && $log->approved_by) return 'rejected';

        return 'waiting';
    }

    
}
