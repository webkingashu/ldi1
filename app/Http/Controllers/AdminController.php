<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use Redirect;

class AdminController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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


    public function workflowList()
    {
        return view('workflow.add');
    }

    public function getStatusList()
    {
        $ob = new StatusController();
        $result = $ob->index();
        return view('status.list',compact('result'));
    }

    public function addStatus() 
    {

        $ob = new TaskTypeController();
        $result = $ob->index();
        $task_type = $result['body'];
        return view('status.add',compact('result','task_type'));
    }
    /* Store Entity*/

    public  function storeStatus(Request $postdata)
    {
        $this->validate($postdata,[
        'status_name' => 'required|max:255',
        ]);

        if($postdata->status_name!=null) {
            $ob = new StatusController();
            $result = $ob->storeStatus($postdata);
        }
        $result = $ob->index();
        return view('status.list',compact('result'));
    }


    public function updateStatusForm(Request $postdata)
    {
        $result ="";
        $id = isset($postdata->id)?$postdata->id:"";
        if($id!=null){
                $obj = new TaskTypeController();
                $result = $obj->index();
                $task_type = $result['body'];
                $ob = new StatusController();
                $result = $ob->updateStatus($postdata);
                $result = $result['result'];
               // dd($result);
                $is_update_url = $result['is_update_url'];
                // echo "<pre>tst "; print_r($update_url);exit;
        }
        return view('status.add',compact('result','is_update_url','task_type'));
    }

    public function updateStatus(Request $postdata)
    {

    $id = isset($postdata->id)?$postdata->id:"";
    if($id!=null){
                $ob = new StatusController();
                $result = $ob->updateStatusById($postdata);
        }
        $result = $ob->index();
        return view('status.list',compact('result'));
    }
}
