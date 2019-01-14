<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category   PurchaseOrder
 * @author    Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license   https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\PurchaseOrderController
 * @link      https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GARRegister;
use App\ECRegister;
use App\DiaryRegister;
use App\DispatchRegister;
use App\WorkflowName;
use App\Role;
use App\User;
use App\Entity;
use App\Eas;
use Auth;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Log;
use DB;
use Toast;
use App\RejectComment;
use Redirect;
use App\EasLog;
use App\MediaMaster;

/**
 * This class provides all operations to manage the PurchaseOrder data 
 *
 */

class RegistersController extends Controller
{


   public $user_id,$user,$user_details;
   public function __construct()
   {
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
        $this->user_details = new CommonController();
        return $next($request);
    });
}
    /**
     * Display a listing of the PurchaseOrder
     *
     * @return \Illuminate\Http\Response
    *  @var string $keyword Returns the user input for search.
     * @var int $perPage To store number of entries for listing
     * @var string $purchase_order_masters Returns the data from purchase_order_masters table from the user inputs for search
     * @var bool $purchase_order_listing Returns the data from purchase_order_masters table for listing
     * This Function Provide the list of all Advance Data.
     *  Pseudo Steps: <br>
     * 1) Create view to list down the columns.<br>
     * 2) Create Model and add table,list down table coulmns, and use soft delete class<br>
     * 3) Retreive records in Controller by accessing Model with scope resolution operator.<br>
     * 4) Store result in variable and pass the variable to view of listing.<br>
     * 5) Foreach this varaible in listing View to fetch each record from table with actions to be performed.<br>
     * 
     * @param mixed[] $request Request structure to get the post data for pagination like limit and offset.
     *
     */
    public function garRegister(Request $request)
    {  

      // try {
          
           $keyword = $request->get('search');
           $perPage = 25;

           if (!empty($keyword)) {
            $gar_register = GARRegister::where('budget_head', 'LIKE', "%$keyword%")
            ->orWhere('date_of_issue', 'LIKE', "%$keyword%")
            ->orWhere('bill_no', 'LIKE', "%$keyword%")
            ->latest()->paginate($perPage);
        } else {

            $user_details = getUserDetails($this->user_id);           
            if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){

            $gar_register = GARRegister::select('gar_register.bill_no','gar_register.created_at','release_order_master.ro_title','release_order_master.release_order_amount','budget_list.budget_head_of_acc','gar_register.date_of_issue')
            ->join('release_order_master','release_order_master.id','=','gar_register.gar_id')
            ->join('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
            ->join('vendor_master', 'eas_masters.vendor_id', '=', 'vendor_master.id')
            ->whereIn('eas_masters.department_id',$user_details['departments_id'])
            ->leftjoin('budget_list', 'budget_list.id', 'eas_masters.budget_code')
            ->latest()->paginate($perPage);
            }
        }
        if(isset($gar_register) && !empty($gar_register)) {

            return view('registers.gar_register', compact('gar_register'));
        }
    // } catch (\Exception $e) {

    //     Log::critical($e->getMessage());
    //     app('sentry')->captureException($e);
    //     Toast::error('Something went wrong');
    //     return redirect('/dashboard'); 

    // }
}
    public function ecRegister(Request $request)
    {  

          // try {

               $keyword = $request->get('search');
               $perPage = 25;

               if (!empty($keyword)) {
                $ec_register = ECRegister::where('bill_no', 'LIKE', "%$keyword%")
                ->orWhere('budget_head', 'LIKE', "%$keyword%")
                ->orWhere('budget_head_amount', 'LIKE', "%$keyword%")
                ->orWhere('budget_head_balance', 'LIKE', "%$keyword%")
                ->orWhere('date_of_er_issue', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
            } else {

               $user_details = getUserDetails($this->user_id);           
 
            if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){  
                $ec_register = ECRegister::select('ec_register.bill_no','ec_register.nature_of_expense','release_order_master.release_order_amount','ec_register.date_of_er_issue','ec_register.created_at','budget_list.budget_head_of_acc','budget_list.amount','ec_register.budget_head_balance','release_order_master.ro_title')
                 ->join('release_order_master','release_order_master.id','=','ec_register.gar_id')
                 ->join('eas_masters', 'eas_masters.id', 'release_order_master.eas_id')
                 ->leftjoin('budget_list', 'budget_list.id', 'eas_masters.budget_code')
                 ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                ->latest()->paginate($perPage);
               }
            }
            if(isset($ec_register) && !empty($ec_register)) {

                return view('registers.ec_register', compact('ec_register'));
            } 
        // } catch (\Exception $e) {

        //     Log::critical($e->getMessage());
        //     app('sentry')->captureException($e);
        //     Toast::error('Something went wrong');
        //     return redirect('/dashboard'); 

        // }
    }

    public function diaryRegister(Request $request)
    {  

           try {

               $keyword = $request->get('search');
               $perPage = 25;

               if (!empty($keyword)) {
                $diary_register = DiaryRegister::where('diary_register_no', 'LIKE', "%$keyword%")
                ->orWhere('date_of_receiving', 'LIKE', "%$keyword%")
                ->orWhere('date_of_forwarding', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
            } else {

                $user_details = getUserDetails($this->user_id);           
 
            if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){
                $diary_register = DiaryRegister::select('diary_register.date_of_forwarding','diary_register.date_of_receiving','diary_register.created_at','diary_register.diary_register_no','release_order_master.release_order_amount','eas_masters.file_number','vendor_master.vendor_name','release_order_master.ro_title','gar.amount_paid')
                ->join('gar','diary_register.gar_id', '=', 'gar.id')
                ->join('release_order_master','release_order_master.id','=','diary_register.gar_id')
                ->join('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')
                ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                ->latest()->paginate($perPage) ;
            }
            }
            if(isset($diary_register) && !empty($diary_register)) {

                return view('registers.diary_register', compact('diary_register'));
            } 
        } catch (\Exception $e) {

            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/dashboard'); 

        }
    }


    public function dispatchRegister(Request $request)
    {  

          // try {

               $keyword = $request->get('search');
               $perPage = 25;

               if (!empty($keyword)) {
                $dispatch_register = DispatchRegister::where('dispatch_register_no', 'LIKE', "%$keyword%")
                ->orWhere('date_of_receiving', 'LIKE', "%$keyword%")
                ->orWhere('date_of_forwarding', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
            } else {
                $user_details = getUserDetails($this->user_id);           
 
            if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){
                $dispatch_register = DispatchRegister::select('dispatch_register.created_at','dispatch_register.id','dispatch_register.dispatch_register_no','dispatch_register.date_of_forwarding','dispatch_register.date_of_receiving','release_order_master.release_order_amount','release_order_master.release_order_amount','eas_masters.file_number','vendor_master.vendor_name','release_order_master.ro_title','gar.amount_paid')
                //->join('release_order_master','release_order_master.id','=','dispatch_register.gar_id')
                ->join('gar','dispatch_register.gar_id', '=', 'gar.id')
                ->join('release_order_master','release_order_master.id','=','dispatch_register.gar_id')
                ->join('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')
                ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                 ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                ->latest()->paginate($perPage);
            }
            }
            if(isset($dispatch_register) && !empty($dispatch_register)) {

                return view('registers.dispatch_register', compact('dispatch_register'));
            } 
        // } catch (\Exception $e) {

        //     Log::critical($e->getMessage());
        //     app('sentry')->captureException($e);
        //     Toast::error('Something went wrong');
        //     return redirect('/dashboard'); 

        // }
    }
}
