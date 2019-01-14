<?php
/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  Vendor
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\VendorController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use Redirect;
use Auth;
use Session;
use Toast;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;

/**
 * This class provides a all operation to manage the Vendor data.
 *
 * The VendorController is responsible for managing the basic details of vendor which require for genarating the EAS report.
 * 
 */

class VendorController extends Controller
{
    public $user_id,$user,$user_details;
     public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
        $this->user_details = new CommonController();
        return $next($request);

        });
    }

    /**
     * Display a listing of the Vendor,with Vendor Name, Contact Number etc. Also provide actions to edit,delete.
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
     * @return json response for Vendor list.
     
     */
    public function index()
    {
     try {   
            $perPage = 25;
            $result = Vendor::select('id','vendor_name','email','contact_no','mobile_no','address','bank_acc_no','ifsc_code', 'bank_name','bank_branch','created_by','gstin','account_type','vendor_status')
           ->where(['deleted_at' => NULL])
           //->where(['vendor_status' => 'Enable'])
           ->orderBy('id','desc')
           ->latest()->paginate($perPage);
           
            return view('vendor.list',compact('result'));
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
          return redirect('/vendor');
        }     
    }

    /**
     * Show the form for creating a new Vendor.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    { try {
       return view('vendor/add');
    } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('/vendor');
        } 
    }

    /**
     * Store a newly created Vendor in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Vendor Data,with manadatory fields.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Vendor list.
     * 
     * @var array[] $result Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $user_details is used to store list of data which include location id, officetype id, department id of loged in user.
     * @var array[] $vender_data to fetch data from request
     * 
     */
    public function store(Request $request)
    {
       $this->validate($request,[
            'vendor_name'   => 'required',
            'email'         => 'required|email|unique:vendor_master,email,NULL,id,deleted_at,NULL',
            'mobile_no'     => 'required|numeric|unique:vendor_master,mobile_no,NULL,id,deleted_at,NULL',
            'bank_acc_no'   => 'required|unique:vendor_master,bank_acc_no,NULL,id,deleted_at,NULL',
            'ifsc_code'     =>'required',
            'bank_name'=>'required',
            'gstin'=>'required'
        ]);

    try {   
 
            $vender_data = Vendor::create(['vendor_name'=>$request->vendor_name,'email'=>$request->email,'contact_no'=>$request->contact_no,'mobile_no'=>$request->mobile_no,'address'=>trim($request->address),'bank_acc_no'=>$request->bank_acc_no,'ifsc_code'=>$request->ifsc_code,'bank_name'=>$request->bank_name,'bank_branch'=>$request->bank_branch,'gstin'=>$request->gstin,'account_type'=>$request->account_type,'created_by' => $this->user_id,'bank_code'=>$request->bank_code]);

            if (isset($vender_data) && !empty($vender_data))  {
                
                  Toast::success('Vendor Details Added Successfully!');
                  return Redirect::to('/vendor');
                } else {

                Toast::error('Something went wrong.');
                return Redirect::to('/vendor');    
            }  

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong.');
        return redirect('/vendor');
        }      
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
     * Show the form for editing the specified Vendor.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $result retrieve data from Model
     */
    public function edit($id)
    {
     try {
        if(isset($id) && !empty($id)) {
              
          $result = Vendor::select('id','vendor_name','email','contact_no','mobile_no','address','bank_acc_no','ifsc_code','bank_name','bank_branch','bank_code','gstin','created_by','account_type')->where(['id'=>$id,'deleted_at' => NULL])->first();
          $is_update_url = 1;

        }
        return view('vendor.add',compact('result','is_update_url'));

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong.');
          return redirect('/vendor');
        } 
    }

   /**
     * Update the specified Vendor in storage.
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
     * @return json result for success vendor list.
     * 
     * @var array[] $result Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $request to fetch data from request
     * 
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'vendor_name'   => 'required',
            'email'         => 'required|email|unique:vendor_master,email,'.$id.',id,deleted_at,NULL',
            'mobile_no'     => 'required|numeric|unique:vendor_master,mobile_no,'.$id.',id,deleted_at,NULL',
            'bank_acc_no'   => 'required|unique:vendor_master,bank_acc_no,'.$id.',id,deleted_at,NULL',
            'ifsc_code'     =>'required',
            'bank_name'=>'required',
            'gstin'=>'required'
        ]);
    try { 
            if(isset($id) && !empty($id)) {
                    
            $result = Vendor::where('id',$id)->update(['vendor_name'=>$request->vendor_name,'email'=>$request->email,'contact_no'=>$request->contact_no,'mobile_no'=>$request->mobile_no,'address'=>trim($request->address),'bank_acc_no'=>$request->bank_acc_no,'ifsc_code'=>$request->ifsc_code,'bank_name'=>$request->bank_name,'bank_branch'=>$request->bank_branch,'bank_code'=>$request->bank_code,'gstin'=>$request->gstin,'account_type'=>$request->account_type]);

               if(isset($result) && !empty($result)) {
                Toast::success('Vendor Details Updated Successfully.');
                return Redirect::to('/vendor');
                } else {
                 Toast::error('Something went wrong!');
                 return redirect('/vendor');
                } 
            } else {
                Toast::error('Id not found.');
                return redirect('/vendor');
            }
            
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong!');
         return redirect('/vendor');
        } 
        //return view('vendor.list',compact('result'));
    }

    /**
     * Remove the specified Vendor from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @param  int  $id
     * @var string $id to find record against particular id  
     * @var string $result to delete record
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
     //try {
    
            if(isset($id) && !empty($id)) {

                if($request->vendor_status == "Disable") {

                  $result = Vendor:: where('id',$id)->update(['vendor_status'=>'Enable']);
                } else {

                  $result = Vendor:: where('id',$id)->update(['vendor_status'=>'Disable']);
                }

           
                if(isset($result) && !empty($result)) {
                Toast::success('Vendor Status Updated Successfully.');
                 return Redirect::to('/vendor');
                } else {
                Toast::error('Something went wrong!');
                 return redirect('/vendor');
                }
            } else {
             Toast::error('Id not foound');
             return redirect('/vendor');
            }
           
        // } catch (\Exception $e) {
        // Log::critical($e->getMessage());
        // app('sentry')->captureException($e);
        // Toast::error('Something went wrong!');
        // return redirect('/vendor');
        // }    
    }

}
