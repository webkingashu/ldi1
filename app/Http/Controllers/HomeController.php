<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        return view('auth.otp');
         } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
        }
        //return view('home');
        // return redirect('/sendOtp');
    }
}
