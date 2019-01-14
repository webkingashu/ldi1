<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  Location
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\LocationController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Auth;
use Toast;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;
/**
 * This class provides a all operation to manage the Location data.
 *
 * The LocationController is responsible for managing the basic details require for genarating the Location report.
 * 
 */

class LocationController extends Controller
{
    public $user_id,$user;
     public function __construct()
    {

        $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
       
        return $next($request);
        });
    }

   /**
     * Display a listing of the Location, with City name. Also provide actions to edit,delete.
     *
     * @return \Illuminate\Http\Response

     * This Function Provide the list of all Location Data.
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
     * @return json response for Location list.
     
     */
    public function index(Request $request)
    {
    try {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $city = Location::where('city_name', 'LIKE', "%$keyword%")
                    ->latest()->paginate($perPage);
            } else {
                $city = Location::latest()->paginate($perPage);
            }

            return view('location.index', compact('city'));
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went Wrong.');
        return redirect('location');
        }     
    }
    
    /**
     * Show the form for creating a new Location.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    { 
        try {
        return view('location.create');
        } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something Went Wrong.');
            return redirect('location');
        } 
    }

     /**
     * Store a newly created Location in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Location Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Location list.
     * 
     * @var array[] $city Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $city to fetch data from request and store into database
     * 
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'city_name'   => 'required'       
        ]);
    try {
            $city = Location::create(['city_name'=> $request->city_name, 'created_by'=>$this->user_id]);

            if(isset($city) && !empty($city))  {
                Toast::success('Location added successfully!');
                return Redirect::to('location');
               // return redirect('location');
            } else {
                Toast::error('Something went wrong');
                return Redirect::to('location');
                //return redirect('location');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('location');
        }     
    }
    
    /**
     * Show a view of  created Location 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Display the specified Location in readonly format against particular record
     * Pseudo Steps:<br>
     * 1)  Create view structure to display record of selected Id <br>
     * 2) Fetch records from Model and store in a variable and pass it to  a View<br>
     * 3) Foreach to fetch record
     * @param  int  $id
     * @var string $city To select record from table 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    try {
            if(isset($id) && !empty($id)) {
                $city = City::findOrFail($id);

                if(isset($city) && !empty($city))  {
                    return view('location.show', compact('city'));
                } else  {
                    return view('location.show')->with('danger','Data not found');
                }
            } else {
                return view('location.show')->with('danger','Id not found');
            }  
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        return redirect('location')->with('danger', 'Something went wrong');
        }     
    }

    /**
     * Show the form for editing the specified Location.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $city retrieve data from Model
     */
    public function edit($id)
    {
    try {
            if(isset($id) && !empty($id)) {
                $city = Location::findOrFail($id);

                if(isset($city) && !empty($city)) {
                    return view('location.edit', compact('city'));
                } else  {
                    return view('location.edit')->with('danger','Data not found');
                }
            } else {
                return view('location.edit')->with('danger','Id not found');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('location');
        }     
    }
    /**
     * Update the specified Location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Update Location Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Location list.
     * 
     * @var array[] $updated_dept Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $requestData to fetch data from request
     * @var array[] $city to find id from model
     * @var array[] $update_location to update record
     * 
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'city_name'   => 'required'      
        ]);
    try {    
            $requestData = $request->all();
            
            if(isset($id) && !empty($id)) {
                $city = Location::findOrFail($id);
                $update_location = $city->update($requestData);

                if(isset($update_city) && !empty($update_city))  {
                    Toast::success('Location updated successfully!');
                    return redirect('location');
                }   else {
                    Toast::error('Something went wrong');
                    return redirect('location');
                }
            } else {
                Toast::error('Id not found');
                return redirect('location');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('location');
        }         
    }
    
    /**
     * Remove the specified Location from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @var string $delete_location to find and delete record against particular id  
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    try {       
            if(isset($id) && !empty($id)) {
                $delete_location = Location::destroy($id);

                if(isset($delete_city) && !empty($delete_city)) {
                    Toast::success('Location deleted successfully!');
                    return redirect('location');
                } else {
                    Toast::error('Something went wrong');
                    return redirect('location');
                }
            } else  {
                Toast::error('Id not found');
                return redirect('location');
            }
        } catch (\Exception $e) {
         
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('location');
        
        }                 
    }
}
