<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Service\AdminService;
use App\Http\Service\DatabaseHelper;
use Route;
Use Auth;

class LockerIssuesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //TODO: Uncomment this so that you have to log in to view the page
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function location_list(){
        $locations = AdminService::get_locations();
        $lockers = AdminService::get_lockers();

        return view('admin.lockerIssues', compact('locations', 'lockers'));
    }

    public function update_status(Request $request) {
        $route = Route::current();
        if (empty($request))
            return redirect()->back()->with(['alert' => 'danger', 'alertMessage' => 'Error trying to delete the submission.']);

        $status = "";

        if($request->broken)
        {
            $status = "broken";
        }
        else if($request->fixed)
        {
            $status = "available";
        }
        $updateReturn = DatabaseHelper::update_locker_status($request->locker_id, $status);

        if($updateReturn)
        {
            return redirect()->back()->with(['alert' => 'success', 'alertMessage' => 'The locker status has been updated.']);
        }

        return redirect()->back()->with(['alert' => 'danger', 'alertMessage' => "The locker status did not update. Please try again."]);
    }

    public function expiry_list() {
        $expired = AdminService::get_expiry_list('expired');
        $expiring = AdminService::get_expiry_list('expiring');
        return view('admin.expiry', compact('expired', 'expiring'));
    }

}