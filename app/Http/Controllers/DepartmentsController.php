<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  Department
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\DepartmentsController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Department;
use App\OfficeType;
use App\Location;
use Illuminate\Http\Request;
use Session;
use Toast;
use Redirect;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;

/**
 * This class provides a all operation to manage the Department data.
 *
 * The DepartmentsController is responsible for managing the basic details require for genarating the Department report.
 * 
 */

class DepartmentsController extends Controller
{
     public $user_id,$user;
     public function __construct()
    {
       // $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
        return $next($request);

    });
    }
    /**
     * Display a listing of the Department, with Name, Office Type, Location, etc. Also provide actions to edit,delete.
     *
     * @return \Illuminate\Http\Response

     * This Function Provide the list of all Department Data.
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
     * @return json response for Department list.
     
     */
    public function index(Request $request)
    { 
    try{
        $keyword = $request->get('search');
        $perPage = 15;
            if (!empty($keyword)) {
                $departments = Department::where('name', 'LIKE', "%$keyword%")
                    ->latest()->paginate($perPage);
            } else {
                $departments = Department::select('departments.*')
                ->where(['departments.deleted_at'=> Null])
                ->latest()->paginate($perPage);
            } 
            
            if(isset($departments) && !empty($departments)) {
                return view('/departments.index', compact('departments'));
            } else {
                return view('/departments.index')->with('danger','Data Not found');
            }
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
         Toast::error('Something went wrong'); 
         return redirect('/departments');
        } 
    }

    /**
     * Show the form for creating a new Department.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    {  
        try {
            return view('departments.create');
        } catch (\Exception $e) {
         
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong'); 
            return redirect('/departments');
        }   
    }

     /**
     * Store a newly created Department in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Department Data,with manadatory fields for Report Generation.
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
     * @var array[] $requestData to fetch data from request
     * @var array[] $department to store data into database
     * 
     */
    public function store(Request $request)
    {   
     try {
        $this->validate($request,[
            'name'           => 'required',     
        ]);
            
        $department = Department::create(['name' =>$request->name ,'created_by'=>$this->user_id]);
            
            if(isset($department) && !empty($department)) {
                Toast::success('Department added successfully!'); 
                return redirect('/departments');
            } else {
                Toast::error('Something went wrong');
                return redirect('/departments');
            }    
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/departments');
        }     
    }
    
    /**
     * Show the form for editing the specified Department.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $department retrieve data from Model
     * @var string $office_type retrieve Office Type data from Model
     * @var string $city retrieve Location data from Model
     */
    public function edit($id)
    {   
     try {
            $department = Department::findOrFail($id);        
            return view('/departments.edit', compact('department'));
       } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/departments');
        } 
    }

   /**
     * Update the specified Department in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Update Department Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success department list.
     * 
     * @var array[] $updated_dept Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $requestData to fetch data from request
     * @var array[] $department to find id from model
     * 
     */
    public function update(Request $request, $id)
    {
    try {
        $this->validate($request,[
            'name'           => 'required'         
        ]);

        $requestData = $request->all();
        $department = Department::findOrFail($id);
        $updated_dept = $department->update($requestData);

            if(isset($updated_dept) && !empty($updated_dept))
            {   
                Toast::success('Department updated successfully!');
                return redirect('/departments');
            } else {
                Toast::error('Something went wrong');
                return redirect('/departments');
            } 
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/departments');
        }           
    }

    /**
     * Remove the specified Deparment from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @var string $dept_id to find and delete record against particular id  
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        try {
            if(isset($id) && !empty($id)) {
                $dept_id = Department::destroy($id);
           
                if(isset($dept_id) && !empty($dept_id))  {
                    Toast::success('Department deleted successfully!');
                    return redirect('/departments');
                } else  {
                    Toast::error('Department is not deleted please try again.');
                    return redirect('/departments');
                }
            } else {
                Toast::error('Cant find the Record.' );
             return redirect('/departments');
            }  
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/departments');
        }     
    }
}
