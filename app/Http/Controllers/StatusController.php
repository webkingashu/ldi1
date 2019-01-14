<?php
/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  Status
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\StatusController
 * @link  https://choicetechlab.com/
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Exception;
use App\Status;
use Session;
use Toast;
use Redirect;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;


/**
 * This class provides a all operation to manage the Status.
 *
 * The StatusController is responsible for managing the basic details of status which require for genarating Transaction and Workdflow.
 * 
 */

class StatusController extends Controller
{
     public $user_id,$user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    //     $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
        return $next($request);

    });
    }
    /**
     * Display a listing of the Status,with Vendor Status Name etc. Also provide actions to edit,delete.
     *
     * @return \Illuminate\Http\Response

     * This Function Provide the list of all Vendor Data.
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
     * @return json response for Status list.
     
     */
    public function index()
    {
    try {
            if(isset($this->user) &&  $this->user->can('can_view')) {

            $status_data = Status::select('status_name','id')->where('deleted_at',NULL)->orderBy('id','desc')
           ->latest()->paginate(25);

            return view('status.list',compact('status_data'));

            } else {
            Toast::error('Not having permission to view Status');
            return Redirect::to('/');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong');
        return Redirect::to('/status');    
        }
       
    }

    /**
     * Show the form for creating a new Status.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    {
        try {
            if(isset($this->user) &&  $this->user->can('can_create')) {

            return view('status.add');

            } else {

            Toast::error('Not having permission to create Status');
            return Redirect::to('/');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
         return Redirect::to('/status');    
        }    
    }

    /**
     * Store a newly created Sttaus in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Status Data,with manadatory fields.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Vendor list.
     * 
     * @var array[] $insert_status Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $user_details is used to store list of data which include location id, officetype id, department id of loged in user.
     * @var array[] $statusName to fetch data from request
     * 
     */
    public function store(Request $request)
    {

      
            
            $this->validate($request,[
                'status_name' => 'required|max:255',
            ]);
         
           try {
            $statusName = Status::where('status_name',$request->status_name)->get();

            if($statusName =='[]') {
                $insert_status = Status::create(['status_name'=>$request->status_name,'created_by'=>$this->user_id]);
            } else {
                
                Toast::error('Status Name Already Exits');

               return Redirect::to('/status'); 
            }
            if($insert_status) {

                Toast::success('Status Added Successfully');

                return Redirect::to('/status');  
            } else {
                
                Toast::error('Something Went Wrong');
                return Redirect::to('/status'); 
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong');
        return Redirect::to('/status'); 
        
        }
    }

   /**
     * Show the form for editing the specified Status.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $status_data retrieve data from Model
     */
    public function edit($id)
    {  
     try {
            if(isset($this->user) &&  $this->user->can('can_update')) {
                if(isset($id) && !empty($id))  {

                  $status_data = Status::find($id);
                  $is_update_url = 1;

                    if(isset($status_data) && !empty($status_data)) {

                      return view('status.add',compact('status_data','is_update_url'));

                    } else {
                        Toast::error('Data Not found.');
                        return Redirect::to('/status'); 
                    }

                } else {
                    Toast::error('Id not found.');
                    return Redirect::to('/status');
                }

            } else {
                Toast::error('Not having permission to Update Status.');
                return Redirect::to('/');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
          Toast::error('Something Went Wrong.');  
          return Redirect::to('/status');
        }     

    }

    /**
     * Update the specified Status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Vendor Data,with manadatory fields..
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Status list.
     * 
     * @var array[] $update_data Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $request to fetch data from request
     * 
     */
    public function update($id,Request $request)
    {
       
           $this->validate($request,[
            'status_name' => 'required|max:255',
            ]);
           try {
            
           if(isset($id) && !empty($id)) {

            $update_data = Status::where('id',$id)->update(['status_name'=>$request->status_name,'created_by'=>$this->user_id]);

                if(isset($update_data) && !empty($update_data)) { 
                  Toast::success('Status Successfully Updated.');
                  return Redirect::to('/status'); 

                } else {
                    Toast::error('Something went wrong while updating data.');
                   return Redirect::to('/status');
                }

           } else {
            Toast::error('Id not found.');
            return Redirect::to('/status');
           }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
         Toast::error('Something Went Wrong.');
         return Redirect::to('/status');
        }
    }

    /**
     * Remove the specified Status from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @param  int  $id
     * @var string $id to find record against particular id  
     * @var string $delete_data to delete record
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        try {

             if(isset($this->user) &&  $this->user->can('can_delete')) {  
                 if(isset($id) && !empty($id)) {

                $delete_data = Status::where('id',$id)->delete();

                    if(isset($delete_data) && !empty($delete_data)) { 
                      Toast::success('Status Successfully Deleted.');
                      return Redirect::to('/status'); 

                    } else {
                       Toast::error('Something went wrong while deleteing data.');
                       return Redirect::to('/status'); 
                    }

               } else {
                Toast::error('Id not found.');
                return Redirect::to('/status');
               }
            } else {
                Toast::error('Not having permission to Delete Status.');
                return Redirect::to('/');
            }

        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
           Toast::error('Something Went Wrong.'); 
           return Redirect::to('/status');
        }
        
    }

}
