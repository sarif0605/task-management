<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $attendance = Attendance::with('user.profile')->orderBy('created_at', 'DESC')->get();
            return response()->json(['data' => $attendance]);
        }
        $users = User::with(['profile', 'position'])
        ->whereDoesntHave('position', function($query) {
            $query->where('name', 'Admin');
        })
        ->get();
        return view('contractor.attendance.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Attendance::where('user_id', Auth::user()->id)->whereDate('in_time', Carbon::now())->count();
        if ($check > 0) {
            return redirect()->route('attendance.index')->with('error', 'You have already checked in today');
        }
        return view('contractor.attendance.create');
    }

    public function store(Request $request)
    {
        if($request->image){
            $now = Carbon::now();
            $start = Carbon::createFromTimeString('07:00');
            $end = Carbon::createFromTimeString('08:00');
            if($now->between($start, $end)) $status = "masuk";
            else $status = "telat";
            $base64String = $request->image;
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
            $imageName = uniqid() . '.jpeg';
            $upload = Storage::disk('local')->put("public/absensi/masuk/$imageName", $image);
            if($upload){
                Attendance::create([
                    'user_id' => Auth::user()->id,
                    'in_image' => $imageName,
                    'in_time' => now(),
                    'in_info' => $status,
                ]);
                return redirect()->route('attendance');
            }
        }
        return redirect()->route('attendance');
    }

    public function edit()
    {
        // $check = Attendance::where('user_id', Auth::user()->id)->whereDate('in_time', Carbon::now())->count();
        // $check2 = Attendance::where('user_id', Auth::user()->id)->whereDate('out_time', Carbon::now())->count();
        // if ($check > 0) {
        //     return redirect()->route('attendance');
        // }
        // if ($check2 > 0) {
        //     return redirect()->route('attendance');
        // }
        return view('contractor.attendance.edit');
    }

// Method to handle clock-out
public function update(Request $request)
{
    if($request->image){
        $now = Carbon::now();
        $start = Carbon::createFromTimeString('16:00');
        $end = Carbon::createFromTimeString('17:00');
        if($now->between($start, $end)) $status = "masuk";
        else $status = "telat";
        $base64String = $request->image;
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        $imageName = uniqid() . '.jpeg';
        $upload = Storage::disk('local')->put("public/absensi/keluar/$imageName", $image);
        if($upload){
            Attendance::where('user_id', Auth::user()->id)->whereDate('in_time', Carbon::today())->update([
                'user_id' => Auth::user()->id,
                'out_image' => $imageName,
                'out_time' => now(),
                'out_info' => $status,
            ]);
            return redirect()->route('attendance');
        }
    }
    return redirect()->route('attendance');
}
}
