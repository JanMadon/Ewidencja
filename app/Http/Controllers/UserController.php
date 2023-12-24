<?php

namespace App\Http\Controllers;

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

class UserController extends Controller
{
    public function getBill() {

        return Inertia::render('User/DashboardUser', [
            'data'=>[],
            'user' => Auth::user(),
        ]);
    }

    public function getBillPeriod(Request $request)
    {
        $user = Auth::user();
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
        $workTime = $workTime->format('%H:%I:%S');

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
            'id' => Auth::id(),
        ]);
    }

    public function LogsPeriod(Request $request)
    {
        $id = Auth::id();
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
        $id = Auth::id();
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
        $data = AddedLogs::where('employee_id', Auth::user()->id)
            ->where('is_active', true)
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

        $data = AddedLogs::where('employee_id', Auth::user()->id)
            ->where('is_active', false)
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
        $user = User::find($id);
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
                    ->where('is_active', true)
            )
            ->orderBy('date_time')
            ->get()->toArray();

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
