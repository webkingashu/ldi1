<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Exception;
use App\Entity;
use Session;
use Redirect;
use Auth;
use Toast;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;


class EntityController extends Controller
{
    public $user_id,$user;
     public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
        return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
    try {
            $data = Entity::select('type_name','id','workflow_id')->where('deleted_at',NULL)->get();
            return view('entity.list',compact('data'));

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong'); 
        return Redirect::to('/entity');
        } 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
    try {
        return view('entity.add');
       } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong'); 
        return Redirect::to('/entity');
        } 
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
        'type_name' => 'required|max:255',
        'workflow_id'=>'required',
        ]);
    try {     
            $type_name = Entity::where('type_name',$request->type_name)->get();
            $entity_slug = strtolower(str_replace(' ', '-',$request->type_name));
            if($type_name =='[]') {
                $data = Entity::create(['type_name'=>$request->type_name,'workflow_id'=>$request->workflow_id,'created_by'=>$this->user_id,'entity_slug'=>$entity_slug]);
            } else {
                Toast::error('Entity Name Already Exits.'); 
              return Redirect::to('/entity');
            }
            if($data) {
                Toast::success('Entity Added Successfully');
              return Redirect::to('/entity');
            } else {
                Toast::error('Something Went Wrong.');
               return Redirect::to('/entity');
            }
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
        return Redirect::to('/entity');
        }  

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
    try {    
            if(isset($id) && !empty($id))  {

              $data = Entity::find($id);
              $is_update_url = 1;

                if(isset($data) && !empty($data)) {

                  return view('entity.add',compact('data','is_update_url'));

                } else {
                    Toast::error('Data Not found.');
                    return Redirect::to('/entity'); 
                }
            } else {
                Toast::error('Id not found.');
              return Redirect::to('/entity');
            }

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
        return Redirect::to('/entity');
        }     
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
        $this->validate($request,[
        'type_name' => 'required|max:255',
        'workflow_id'=>'required',
        ]);
    try {  
           if(isset($id) && !empty($id)) {
            $entity_slug = strtolower(str_replace(' ', '-',$request->type_name));
            $update_data = Entity::where('id',$id)->update(['type_name'=>$request->type_name,'workflow_id'=>$request->workflow_id,'created_by'=>$this->user_id,'entity_slug'=>$entity_slug]);

                if(isset($update_data) && !empty($update_data)) { 
                    Toast::success('Entity Successfully Updated.');
                  return Redirect::to('/entity'); 

                } else {
                    Toast::error('Something went wrong while updating data.');
                   return Redirect::to('/entity'); 
                }

           } else {
            Toast::error('Id not found.');
            return Redirect::to('/entity');
           }
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
        return Redirect::to('/entity');
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
    try {    
            if(isset($id) && !empty($id)) {

            $delete_data = entity::where('id',$id)->delete();

                if(isset($delete_data) && !empty($delete_data)) { 
                    Toast::success('Entity Successfully Deleted.');
                  return Redirect::to('/entity'); 

                } else {
                    Toast::error('Something went wrong while deleteing data.');
                   return Redirect::to('/entity'); 
                }

           } else {
            return Redirect::to('/entity')->with('danger', 'Id not found.');
           }
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
        return Redirect::to('/entity');
        } 
    }
}
