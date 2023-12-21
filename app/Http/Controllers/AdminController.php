<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Models\AddedLogs;
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

class AdminController extends Controller
{

    public function dashboard() {
        // funckcja na dabeli raw_log sprawdzająca jaki uzytkownik ma dziś nie parszystą liczbę odbić
        // czyli jest w pracy   TODO DO IMPLENETACJI
        // $today = Carbon::now()->format('Y-m-d');
        // $currentEpmloyees = RawLogs::where('date_time', '>' ,$today)->first()->user;

        // dd($currentEpmloyees->name);
        // $users = User::where('id', $currentEpmloyees);
        $users = [];


        return Inertia::render('Admin/DashboardAdmin', [
            'users' => $users
        ]);
    }

    public function list()
    {
        $users = User::get();
        return Inertia::render('Admin/UsersList', ['users' => $users]);
    }

    public function create()
    {
        //
    }

    public function edit(string $id = null)
    {
        $user = User::find($id);
        return Inertia::render('Admin/UserEdit', ['user' => $user]);
    }

    public function update(EditUserRequest $request, string $id)
    {   // change ID
        $request->validated();
        $user = User::find($id);
        $user->id = $request->id;
        $user->save();
        
       return to_route('users.list');
    }

    public function delete(string $id)
    {
        $user = User::find($id);
        dd($user);
        $user ->delete();
       return to_route('users.list');

    }

    public function userLogs( string $id)
    {   
        return Inertia::render('Admin/UserLogs', [
            'id' => $id,
            'user' => User::find($id) ?? [],
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
            'user' => User::find($id) ?? [],
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
            'approved_by' => Auth::id(),
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

    public function getBill(string $id) {
        $user = User::find($id);
        return Inertia::render('Admin/UserBill', ['id' => $id, 'data'=>[], 'user' => $user,]);
    }

    public function getBillPeriod(Request $request, string $id)
    {
        $user = User::find($id);
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
        $workTime = $workTime->format('%H:%I:%S');

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

    public function setNewSalary(Request $request, string $id){

        $user = User::find($id);
        $salary = new Salary();
        $salary->set_by = Auth::id();
        $salary->employee_id = $user->id;
        $salary->salary = $request->newSalry;
        $salary->valid_from = $request->validFrom . '-01';
        $salary->save();

        return Inertia::render('Admin/UserBill', [
            'id' => $id, 
            'data'=>[], 
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
                'userName' => User::find($log['employee_id'])->name ?? null,
                'approvedBy' => User::find($log['approved_by'])->name ?? null,
                'status' => $this->AddedLogStatus($log['id']),
                'createdAt' => Carbon::parse($log['created_at'])->format('Y-m-d H:i:s'),
            ];
        }
        $usersRequests1 = $this->arrPaginate($usersRequests, 10);
        $usersRequests1->setPath($request->url());

        return Inertia::render('Admin/UsersRequests', ['usersRequests' => $usersRequests1]);
    }

    public function requestAccept(Request $request)
    {

        if ($request->action === 'accpet') {
            $log = AddedLogs::find($request->requestId);
            $log->approved_by = Auth::id();
            $log->is_approved = true;
            $log->save();
        } elseif ($request->action === 'reject') {
            $log = AddedLogs::find($request->requestId);
            $log->approved_by = Auth::id();
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

    protected function getDataForUser(String $id, $timeFrom, $timeTo,): array
    {
        
        $logs = RawLogs::select('date_time')
            ->where('raw_logs.date_time', '>', $timeFrom)
            ->where('raw_logs.date_time', '<', $timeTo)
            ->where('raw_logs.employee_id', $id)
            ->union(
                AddedLogs::select('date_time')
                    ->where('added_logs.date_time', '>', $timeFrom)
                    ->where('added_logs.date_time', '<', $timeTo)
                    ->where('employee_id', $id)
                    ->where('is_approved', true)
                    ->where('is_active', true)
            )
            ->orderBy('date_time')
            ->get()->toArray();
        // dd($logs);

        $daysLogs = [];
        foreach ($logs as $log) {
            $dateTime = $log['date_time'];
            list($date, $time) = explode(' ', $dateTime);
            $daysLogs[$date]['logs'][] = $time;
        }
        // dd($daysLogs);

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

            // Czy przysługuje premia? *gdy nie ma odbicia miedz 9-10 *jeżeli wszytkie logi są po 9(czyli 1)
            // *gdy ostatni log jest przed 9
            $daysLogs[$date]['premia'] = true;
            foreach ($daysLogs[$date]['logs'] as $key => $log) {
                if ("09:00:00" < $log && $log < "10:00:00") {
                    $daysLogs[$date]['premia'] = false;
                }
                // dump($daysLogs[$date]['num_logs'] );
                if ($key % 2 !== 0  && isset($daysLogs[$date]['logs'][$key + 1])) {
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

        return $daysLogs;
    }
}
