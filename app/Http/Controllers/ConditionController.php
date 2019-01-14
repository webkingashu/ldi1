<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  Conditions
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\ConditionController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Exception;
use App\Condition;
use Session;
use Redirect;
use Auth;
use Toast;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;


/**
 * This class provides a all operation to manage the Condition data.
 *
 * The ConditionController is responsible for managing the basic details require for genarating the Condition report.
 * 
 */

class ConditionController extends Controller
{
    public $user_id,$user;   
    public function __construct()
    {
     $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
        //$this->entity_details = new PermissionMapperController();
        return $next($request);
        });
    }

    /**
     * Display a listing of the Conditions, with Name. Also provide actions to edit,delete.
     *
     * @return \Illuminate\Http\Response

     * This Function Provide the list of all Conditions Data.
     *  Pseudo Steps: <br>
     * 1)Create view to list down the coulmns.<br>
     * 2) Create Model and add table,list down table coulmns, and use soft delete class<br>
     * 3) Retreive records in Controller by accessing Model with scope resolution operator.<br>
     * 4) Store result in variable and pass the variable to view of listing.<br>
     * 5) Foreach this varaible in listing View to fetch each record from table with actions to be performed.<br>
     * 
     * @param mixed[] $request Request structure to get the post data for pagination like limit and offset.
     * 
     * @var int $limit Should contain a number for limit of record by default it is 10.
     * @var int $offset Should contain a number for offset of record by default it is 0.
     * 
     * @return json response for Conditions list.
     
     */

    public function index()
    {

        if(roleEntityMapping($this->user_id,'GAR','can_view')) {
     
        try { 
            $data= Condition::select('id','condition_name')->where(['deleted_at' => Null])
            ->orderBy('id','desc')->get();

                if(isset($data) && !empty($data)) {
                    return view('conditions.list', compact('data'));
                } else  {
                    return view('condition.list')->with('danger','Data Not Found');
                }

            } catch (\Exception $e) { 
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
             return Redirect::to('/conditions');
            } 

        } else {
            Toast::error('You Dont have permssion to Access Condition Master');
            return Redirect::to('/dashboard');
        }
      
    }

   /**
     * Show the form for creating a new Condition.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */

    public function create()
    { 
        try {

        return view('conditions.add');

        } catch (\Exception $e) { 
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return Redirect::to('/conditions');
        }  
    }

   /**
     * Store a newly created Condition in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Condition Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Department list.
     * 
     * @var array[] $departments Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $conditionName to fetch data from model
     * @var array[] $insert_collection to store data into database
     * 
     */

    public function store(Request $request)
    {
        $this->validate($request,[
           'condition_name' => 'required|max:255',
        ]);
        try { 

        $condition_name = isset($request->condition_name)?$request->condition_name:"";
        $conditionName = Condition::where('condition_name',$condition_name)->get();
            
            if($conditionName =='[]') {
               
            $insert_collection = Condition::create(['condition_name'=>$condition_name,'created_by'=> $this->user_id]);
            } else  {
                Toast::error('Condition Name Already Exits.');
                return Redirect::to('/conditions');
            }
            
            if($insert_collection) {  
            Toast::success('Condition added successfully');  
            return Redirect::to('/conditions');
            }  else  {
                Toast::error('Something Went Wrong.');
                return Redirect::to('/conditions');
            }

        } catch (\Exception $e) { 
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
         return Redirect::to('/conditions');
        }      
    }

    
    /**
     * Show the form for editing the specified Condition.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $condition_data retrieve data from Model
     * @var string $is_update_url initialize 1 for edit view
     */

    public function edit($id)
    {   
     try {
           if(isset($id) && !empty($id))  
            {
                $condition_data = Condition::find($id);
                $is_update_url = 1;

               if(isset($condition_data) && !empty($condition_data)) {
                return view('conditions.add',compact('condition_data','is_update_url'));
               } else {
                Toast::error('Data Not found.');
                return Redirect::to('/conditions');
               }
            } else {
                Toast::error('Id not found.');
             return Redirect::to('/conditions');
            }
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
         return Redirect::to('/conditions');
        }    
    }

    /**
     * Update the specified Condition in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Update Condition Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Condition list.
     * 
     * @var array[] $updated_data Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * 
     */

    public function update(Request $request, $id)
    {
        $this->validate($request,[
           'condition_name' => 'required|max:255',
        ]);
        try {
            if(isset($id) && !empty($id)) {
                $updated_data = Condition::where('id',$id)->update(['condition_name'=>$request->condition_name]);
                if(isset($updated_data) && !empty($updated_data))  {
                    Toast::success('Condition Successfully Updated.');
                return Redirect::to('/conditions');
                } else  {
                    Toast::error('Something went wrong while updating data.');
                return Redirect::to('/conditions');
                }
            } else {
                Toast::error('Id not found.');
            return Redirect::to('/conditions');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something Went Wrong.');
             return Redirect::to('/conditions');
        }     
    }

   /**
     * Remove the specified Condition from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @var string $condition_id to find and delete record against particular id  
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {  
    try {
        $condition_id = Condition::destroy($id);

            if(isset($condition_id) && !empty($condition_id))   {
                Toast::success('Department deleted successfully!');
                return redirect('/conditions');
            } else {
                Toast::error('Something went wrong');
                return redirect('admin/departments');
            }

        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return Redirect::to('/conditions');
        }   
    }
}
