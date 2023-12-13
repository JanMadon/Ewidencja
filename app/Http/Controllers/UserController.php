<?php

namespace App\Http\Controllers;

use App\Models\AddedLogs;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\LaravelIgnition\FlareMiddleware\AddLogs;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();
        //dd('test');
        return Inertia::render('UsersList', ['users'=>$users]);
    }

    public function requests(Request $request)
    {
        $usersRequests=[];

        $data = AddedLogs::get()->toArray();
        // dd($data);
        foreach($data as $log) {
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
        
            // dd($usersRequests);
        
        
        return Inertia::render('UsersRequests', ['usersRequests'=>$usersRequests1]);    
    }

    public function arrPaginate($array, $perPage = 5, $page = null ) {
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

        if($log->is_approved && $log->approved_by) return 'accpeted';
        if(!$log->is_approved && $log->approved_by) return 'rejected';

        return 'waiting';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        return Inertia::render('UserShow', ['id'=>$id]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id = null )
    {
        $user = User::find($id);
        return Inertia::render('UserEdit', ['user'=>$user]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TO DO VALIDACJA I UPDATE DB
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
