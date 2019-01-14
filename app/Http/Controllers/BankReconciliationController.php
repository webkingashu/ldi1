<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankReconciliationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank-reconciliation.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $this->validate($request,[
            'csv_file' => 'required|file'
        ]);
        
//         $original_file_name = $entity_slug . '-' . strtolower(str_replace([' ', '_'], '-', $file_upload[$key]->getClientOriginalName()));
//                 $documents = "bank-statement/" . $year . "/" . $month . "/" . $original_file_name;
//                 $file_upload[$key]->move($path, $documents);

//         $path = $request->file('csv_file')->getRealPath();
//         $data = \Excel::load($path)->get();
// dd($data);
//         foreach ($data as $key => $value) {
//                 $arr[] = ['Cheque No.' => $value->title, 
//                          ];

//             }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
