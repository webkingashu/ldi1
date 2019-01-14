<?php

namespace App\Http\Controllers;

class DairyRegisterController extends Controller
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
        return view('diary-register/add');

    }

   

}
