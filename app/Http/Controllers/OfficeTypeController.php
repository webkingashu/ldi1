<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  OfficeType
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\OfficeTypeController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OfficeType;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Auth;
use Toast;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;

/**
 * This class provides a all operation to manage the OfficeType data.
 *
 * The OfficeTypeController is responsible for managing the basic details require for genarating the OfficeType report.
 * 
 */

class OfficeTypeController extends Controller
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
     * Display a listing of the OfficeType, with name. Also provide actions to edit,delete.
     *
     * @return \Illuminate\Http\Response

     * This Function Provide the list of all OfficeType Data.
     *  Pseudo Steps: <br>
     * 1)Create view to list down the coulmns.<br>
     * 2) Create Model and add table,list down table coulmns, and use soft delete class<br>
     * 3) Retreive records in Controller by accessing Model with scope resolution operator.<br>
     * 4) Store result in variable and pass the variable to view of listing.<br>
     * 5) Foreach this varaible in listing View to fetch each record from table with actions to be performed.<br>
     * 
     * @param mixed[] $request Request structure to get the post data for pagination like limit and offset.
     * 
     * @var array $officetype to fetch data from model
     * @var int $limit Should contain a number for limit of record by default it is 10.
     * @var int $offset Should contain a number for offset of record by default it is 0.
     * 
     * @return json response for OfficeType list.
     
     */
    public function index(Request $request)
    {
    try {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword))  {
                $officetype = OfficeType::where('office_type', 'LIKE', "%$keyword%")
                    ->latest()->paginate($perPage);
            } else  {
                $officetype = OfficeType::latest()->paginate($perPage);
            }
            
            if(isset($officetype) && !empty($officetype)) {
                return view('office-type.index', compact('officetype'));
            } else {
                 return view('office-type.index')->with('danger','Data not found');
            }    
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('office-type');
        }             
    }

    /**
     * Show the form for creating a new OfficeType.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    {
        try {
        return view('office-type.create');
         } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('office-type');
        } 
    }

   /**
     * Store a newly created OfficeType in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store OfficeType Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success OfficeType list.
     * 
     * @var array[] $office_type Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $office_type to fetch data from request and store into database
     * 
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'office_type_name'   => 'required'       
        ]);
 
        try {
            $office_type = OfficeType::create(['office_type_name' => $request->office_type_name,'created_by'=>$this->user_id]);

            if(isset($office_type) && !empty($office_type)) {
                Toast::success('OfficeType added Successfully!');
                return redirect('office-type');
            } else  {
                Toast::error('Something went wrong');
                return redirect('office-type');
            }

        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('office-type');
        } 
       
    }

    /**
     * Show a view of  created OfficeType 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Display the specified OfficeType in readonly format against particular record
     * Pseudo Steps:<br>
     * 1)  Create view structure to display record of selected Id <br>
     * 2) Fetch records from Model and store in a variable and pass it to  a View<br>
     * 3) Foreach to fetch record
     * @param  int  $id
     * @var string $officetype To select record from table 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    try {
            if(isset($id) && !empty($id))
            {
                $officetype = OfficeType::findOrFail($id);

                if(isset($officetype) && !empty($officetype)) {
                    return view('office-type.show', compact('officetype'));
                }
                else {
                    return view('office-type.show')->with('danger','Data not found');
                }
            } else {
                return view('office-type.show')->with('danger','Id not found');
            }   
        } catch (\Exception $e) {  
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('office-type');
        }     
    }

    /**
     * Show the form for editing the specified OfficeType.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $officetype retrieve data from Model
     */
    public function edit($id)
    {  
     try {
            if(isset($id) && !empty($id))
            {
                $officetype = OfficeType::findOrFail($id);

                if(isset($officetype) && !empty($officetype)) {
                    return view('office-type.edit', compact('officetype'));
                } else  {
                    return view('office-type.edit')->with('danger','Data not found');
                }
            } else {
                return view('office-type.edit')->with('danger','Id not found');
            } 
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('office-type');
        }     
    }

    /**
     * Update the specified OfficeType in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Update OfficeType Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success OfficeType list.
     * 
     * @var array[] $update_office_type Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $requestData to fetch data from request
     * @var array[] $officetype to find id from model
     * 
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'office_type_name'    => 'required'       
        ]);

    try {    
            $requestData = $request->all();
            if(isset($id) && !empty($id)) 
            {
                $officetype = OfficeType::findOrFail($id);

                $update_office_type = $officetype->update($requestData);

                if(isset($update_office_type) && !empty($update_office_type)) {
                    Toast::success('OfficeType updated successfully!');
                    return redirect('office-type');
                } else  {
                    Toast::error('Something went wrong');
                    return redirect('office-type');
                }
            } else {
                Toast::error('Id not found');
                return redirect('office-type');
            }  
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('office-type');
        }     
    }
    
    /**
     * Remove the specified OfficeType from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @var string $delete_data to find and delete record against particular id  
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    try {    
            if(isset($id) && !empty($id))  {
                $delete_data = OfficeType::destroy($id);

                if(isset($delete_data) && !empty($delete_data)) {
                    Toast::success('OfficeType deleted successfully!');
                    return redirect('office-type');
                } else {
                    Toast::error('Something went wrong');
                    return redirect('office-type');
                }
            } else  {
                Toast::error('Id not found');
                return redirect('office-type');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('office-type');
        }     
    }
}
