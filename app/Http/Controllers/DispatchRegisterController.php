<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;

class DispatchRegisterController extends Controller
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
    
    public function create()
    {
        return view('dispatch-register/add');

    }

   

}
