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
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Eas;
use App\Vendor;
use App\Department;
use App\Role;
use App\Status;
use App\GAR;
use App\ReleaseOrder;
use App\Entity;
use App\Transaction;
use App\WorkflowName;
use App\PurchaseOrder;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Auth;
use Toast;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;

/**
* This class provides a all operation to manage the Condition data.
*
* The ConditionController is responsible for managing the basic details require for genarating the Condition report.
* 
*/

class DashboardController extends Controller
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

 public function index(Request $request)
 {  
  // try {


   $user_details = getUserDetails($this->user_id);
   //dd($user_details['departments']);
  /* foreach ($user_details['departments'] as $key => $value) {
     $user_details['departments']['id'] = $value->id;

   }*/
   //dd($user_details['departments']['id']);
  // if(isset($role_details) && $role_details['status'] == 200 ) {
  
    $keyword = $request->get('search');
    $perPage = 25;

    if (!empty($keyword)) {
     $eas_masters = eas_master::where('sanction_title', 'LIKE', "%$keyword%")
     ->orWhere('sanction_purpose', 'LIKE', "%$keyword%")
     ->orWhere('competent_authority', 'LIKE', "%$keyword%")
     ->orWhere('serial_no_of_sanction', 'LIKE', "%$keyword%")
     ->orWhere('file_number', 'LIKE', "%$keyword%")
     ->orWhere('sanction_total', 'LIKE', "%$keyword%")
     ->orWhere('budget_code', 'LIKE', "%$keyword%")
     ->orWhere('validity_sanction_period', 'LIKE', "%$keyword%")
     ->orWhere('date_issue', 'LIKE', "%$keyword%")
     ->orWhere('vendor_id', 'LIKE', "%$keyword%")
     ->orWhere('cfa_note_number', 'LIKE', "%$keyword%")
     ->orWhere('cfa_dated', 'LIKE', "%$keyword%")
     ->orWhere('cfa_file_number', 'LIKE', "%$keyword%")
     ->orWhere('fa_number', 'LIKE', "%$keyword%")
     ->orWhere('fa_dated', 'LIKE', "%$keyword%")
     // ->orWhere('fc_on_page', 'LIKE', "%$keyword%")
     // ->orWhere('fc_on_file_no', 'LIKE', "%$keyword%")
     ->latest()->paginate($perPage);
   } else {
    // $eas_final_status = getStatus('eas');
 
  
         $eas_masters = Eas::select('eas_masters.created_at','eas_masters.file_number','eas_masters.sanction_total','budget_list.budget_code','eas_masters.validity_sanction_period','eas_masters.id','eas_masters.sanction_title','eas_masters.competent_authority','vendor_master.vendor_name','status.status_name','eas_masters.vendor_id')
         ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
         ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
         ->join('status','status.id','=','eas_masters.status_id')
         ->where(['eas_masters.deleted_at'=> Null,'vendor_master.deleted_at'=>Null,'status.deleted_at'=>Null,'budget_list.deleted_at'=>Null])
         ->whereIn('eas_masters.department_id',$user_details['departments_id'])
         ->orderBy('eas_masters.id','desc')->latest()->paginate($perPage);

       
//dd($eas_masters);
     $total_eas = count($eas_masters);
        //dd($total_eas);

     $total_gar =  GAR::select('gar.id')
     ->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
     ->join('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')
     ->join('status', 'status.id', '=', 'gar.status_id')
     ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
     ->where(['gar.deleted_at' => NULL, 'eas_masters.deleted_at' => NULL, 'release_order_master.deleted_at' => NULL])
     ->whereIn('eas_masters.department_id',$user_details['departments_id'])
     ->count();

     $total_ro = ReleaseOrder::select('release_order_master.id')
                ->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
                ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                ->join('status','status.id','=','release_order_master.status_id')
                //->join('location','location.id','=','release_order_master.location_id')
               ->join('departments','departments.id','=','eas_masters.department_id')
               // ->join('office_type','office_type.id','=','release_order_master.office_type_id')
                ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                ->where(['release_order_master.deleted_at'=>Null,'status.deleted_at'=>Null,'eas_masters.deleted_at'=>Null])
                ->orderBy('release_order_master.id','desc')
     ->count();

     $total_purchase_order =PurchaseOrder::select('purchase_order_masters.id') 
     ->join('status','status.id','=','purchase_order_masters.status_id')
     ->join('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
     ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
     ->join('departments','departments.id','=','eas_masters.department_id')
     ->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null,'vendor_master.deleted_at'=>NULL])
     ->whereIn('eas_masters.department_id',$user_details['departments_id'])
     ->orderBy('purchase_order_masters.id','desc')
     ->count();


   }
   if(isset($eas_masters) && !empty($eas_masters)) { 
     return view('dashboard.dashboard', compact('eas_masters','total_purchase_order','total_eas','total_approved_eas','total_gar','total_ro'));
   } else {
     return view('dashboard.dashboard')->with('danger','Data not found');
   }
 // } else {  
 //   Toast::error('User details not found.');
 //   return redirect('/dashboard');   
 // }    
 // } catch (\Exception $e) {

 //    Log::critical($e->getMessage());
 //    app('sentry')->captureException($e);
 //    Toast::error('Something went wrong!');
 //    return redirect('/dashboard'); 
 //   }    
}

public function easDashboard(Request $request,$id)
{ 

  try 
  {
    if (Session::get('eas_id') !== NULL) {
      Session::remove('eas_id');
      Session::put('eas_id',$id);
    } else {
      Session::put('eas_id',$id);
    }

    if(isset($id) && !empty($id)) 
    {
      $user_details = getUserDetails($this->user_id);
      // dd($user_details['departments_id']);
      if (isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])) {
      
   
        $eas_master = Eas::select('eas_masters.sanction_title', 'eas_masters.status_id' ,
        'eas_masters.sanction_purpose','eas_masters.competent_authority', 'eas_masters.serial_no_of_sanction','eas_masters.file_number', 'eas_masters.sanction_total',
        'eas_masters.budget_code','eas_masters.validity_sanction_period',
        'eas_masters.date_issue','eas_masters.cfa_note_number','eas_masters.cfa_dated',
        'eas_masters.cfa_designation','eas_masters.whether_being_issued_under',
        'eas_masters.fa_number', 'eas_masters.fa_dated',
        'eas_masters.id','status.status_name','budget_list.budget_head_of_acc','vendor_master.vendor_name')
        ->join('status','status.id','=','eas_masters.status_id')
        ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
        ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
        ->where('eas_masters.id' ,'=',$id)
        ->where(['eas_masters.deleted_at'=> Null,'status.deleted_at'=>Null])
        ->whereIn('eas_masters.department_id',$user_details['departments_id'])
        ->first();

        $eas_workflow_details = getStatus('eas');
      
        if (isset($eas_master) && isset($eas_workflow_details)) {
          if ($eas_workflow_details['final_status'] == $eas_master['status_id']) {
          $can_create_po_ro = 1;
          }
        } 
      }

      Session::put('eas_title',$eas_master->sanction_title);

      $status = Status::select('status.status_name','status.id')->get();

      $data['purchase_order_listing'] = PurchaseOrder::select( 'purchase_order_masters.id',
        'purchase_order_masters.vendor_name','purchase_order_masters.subject',
        'purchase_order_masters.bid_number', 'purchase_order_masters.title_of_bid',
        'status.status_name','eas_masters.sanction_title') 
      ->join('status','status.id','=','purchase_order_masters.status_id')
      ->join('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
      ->where('purchase_order_masters.eas_id',$id)
      ->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null])
      ->orderBy('purchase_order_masters.id','desc')
      ->get();
    $data['entity_details'] = Entity::select('type_name','id','workflow_id','entity_slug','final_status')
    ->where('type_name','=','PO')
    ->where('deleted_at',NULL)
    ->first();
    $data['po_count'] = count($data['purchase_order_listing']);


    $data['release_order'] = ReleaseOrder::select('release_order_master.id',
      'release_order_master.created_at','release_order_master.status_approved_date',
      'release_order_master.release_order_amount', 'release_order_master.status_id',
      'status.status_name' ,'eas_masters.file_number','release_order_master.created_at','vendor_master.vendor_name')
    ->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
    ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
    ->join('status','status.id','=','release_order_master.status_id')
    //->join('location','location.id','=','release_order_master.location_id')
   // ->join('departments','departments.id','=','release_order_master.department_id')
   // ->join('office_type','office_type.id','=','release_order_master.office_type_id')
    //->where(['release_order_master.location_id'=>$role_details['location_id'],'release_order_master.department_id'=>$role_details['department_id'],'release_order_master.office_type_id'=>$role_details['office_type_id']])
    ->where('release_order_master.eas_id',$id)
    ->where(['release_order_master.deleted_at'=>Null,'status.deleted_at'=>Null,'eas_masters.deleted_at'=>Null])
    ->whereIn('eas_masters.department_id',$user_details['departments_id'])
    ->orderBy('release_order_master.id','desc')
    ->get();
    $data['ro_count'] = count($data['release_order']);
    $data['entity_details'] = Entity::select('id','workflow_id','entity_slug','final_status')->where(['deleted_at' => Null,'type_name' =>"RO"])->first();
//dd($data['release_order']);

    $data['gar'] = GAR::select('gar.id', 'gar.release_order_amount', 'gar.amount_paid', 'gar.actual_payment_amount', 'gar.status_id','eas_masters.sanction_title', 'release_order_master.ro_title', 'eas_masters.file_number', 'diary_register.diary_register_no', 'vendor_master.vendor_name', 'status.status_name')->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')->join('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')->join('status', 'status.id', '=', 'gar.status_id')->leftjoin('diary_register', 'diary_register.gar_id', '=', 'gar.id')
    ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
    ->where(['gar.deleted_at' => NULL, 'eas_masters.deleted_at' => NULL, 'release_order_master.deleted_at' => NULL])
    ->where('release_order_master.eas_id',$id)
    ->whereIn('eas_masters.department_id',$user_details['departments_id'])
    ->orderBy('gar.id', 'desc')
    ->orderBy('gar.id','desc')->get();

    $data['gar_count'] = count($data['gar']);
    if ($data['ro_count'] > 0) {
      $can_create_gar = 1;
     }
    $data['entity_details'] =Entity::select('id', 'workflow_id', 'entity_slug','final_status')->where(['deleted_at' => Null, 'type_name' => "GAR"])->first();

    return view('dashboard.eas-dashboard',compact('eas_master','status','data','purchase_order_master','can_create_po_ro','can_create_gar'));



  } else {
   Toast::error('Something went wrong!');
   return redirect('/dashboard'); 
 }

/*} else {  
 Toast::error('User details not found.');
 return redirect('/eas-dashboard');   
}  */ 


 } catch (\Exception $e) {

    Log::critical($e->getMessage());
    app('sentry')->captureException($e);
   Toast::error('Something went wrong!');
    return redirect('/dashboard'); 
}  

}



public function getStatus(Request $request)
{
  try {

       $eas_id =  Session::get('eas_id'); 
       $from_date = $request->from_date;
       $to_date = $request->to_date;

       //$role_details=getUserDetails($this->user_id);
       if(isset($request->entity_name) && !empty($request->entity_name))
       {   

        switch($request->entity_name) {

         case 'purchase_order':

         $query =PurchaseOrder::select('purchase_order_masters.id','purchase_order_masters.vendor_name','purchase_order_masters.subject','purchase_order_masters.bid_number', 'purchase_order_masters.title_of_bid','status.status_name','eas_masters.sanction_title') 
         ->join('status','status.id','=','purchase_order_masters.status_id')
         ->join('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
         ->where('purchase_order_masters.eas_id',$eas_id)
         // ->where(['purchase_order_masters.location_id'=>$role_details['location_id'],
         //   'purchase_order_masters.department_id'=>$role_details['department_id'],
         //   'purchase_order_masters.office_type_id'=>$role_details['office_type_id']])
         ->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null])
         ->orderBy('purchase_order_masters.id','desc');

         if(isset($request->status_name) && !empty($request->status_name))
         {
           $query->where('purchase_order_masters.status_id','=', $request->status_name);
         }
         if(isset($from_date) && !empty($from_date) && isset($to_date) && !empty($to_date)) 
         {
                 // $query->whereBetween('purchase_order_masters.created_at', array($from_date,$to_date));
          $query->where('purchase_order_masters.created_at','>=', date("Y-m-d", strtotime($from_date)));
          $query->where('purchase_order_masters.created_at','<=',  date("Y-m-d", strtotime($to_date)));
        }

        $data['purchase_order'] = $query->get(); 

        $data['entity_details'] = Entity::select('type_name','id','workflow_id','entity_slug','final_status')
        ->where('type_name','=','PO')
        ->where('deleted_at',NULL)
        ->first();
        $data = view('purchase_order.po_status',compact('data','entity_details'))->render();
        break;



        case 'release_order':
           //dd($request->entity_name);
        $query =ReleaseOrder::select('release_order_master.id',
      'release_order_master.created_at','release_order_master.status_approved_date',
      'release_order_master.release_order_amount', 'release_order_master.status_id','status.status_name' ,'eas_masters.file_number','release_order_master.created_at','vendor_master.vendor_name')
        ->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
        ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
        ->join('status','status.id','=','release_order_master.status_id')
        //->join('location','location.id','=','release_order_master.location_id')
        ->where('release_order_master.eas_id',$eas_id)
        //->join('departments','departments.id','=','release_order_master.department_id')
        //->join('office_type','office_type.id','=','release_order_master.office_type_id')
        //->where(['release_order_master.location_id'=>$role_details['location_id'],'release_order_master.department_id'=>$role_details['department_id'],'release_order_master.office_type_id'=>$role_details['office_type_id']])

        ->where(['release_order_master.deleted_at'=>Null,'status.deleted_at'=>Null,'eas_masters.deleted_at'=>Null])
        ->orderBy('release_order_master.id','desc');

             //dd($request->status_name , '',$request->entity_name,'dss');
        if(isset($request->status_name) && !empty($request->status_name))
        {
          $query->where('release_order_master.status_id','=', $request->status_name);
        }
        if(isset($from_date) && !empty($from_date) && isset($to_date) && !empty($to_date)) 
        {
                 // $query->whereBetween('purchase_order_masters.created_at', array($from_date,$to_date));
         $query->where('release_order_master.created_at','>=', date("Y-m-d", strtotime($from_date)));
         $query->where('release_order_master.created_at','<=',  date("Y-m-d", strtotime($to_date)));
       }

       $data['release_order'] = $query->get(); 

       $data['entity_details'] = Entity::select('id','workflow_id','entity_slug','final_status')->where(['deleted_at' => Null,'type_name' =>"RO"])->first();

       $data = view('ro.ro_status',compact('data','entity_details'))->render();
       break;

       case 'gar':
           //dd($request->entity_name);
       $query = GAR::select('gar.id', 'gar.release_order_amount', 'gar.amount_paid', 'gar.actual_payment_amount', 'gar.status_id', 'eas_masters.sanction_title', 'release_order_master.ro_title', 'eas_masters.file_number', 'diary_register.diary_register_no', 'vendor_master.vendor_name', 'status.status_name')
       ->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
       ->join('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')
       ->join('status', 'status.id', '=', 'gar.status_id')
       ->leftjoin('diary_register', 'diary_register.gar_id', '=', 'gar.id')
       ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
       ->where(['gar.deleted_at' => NULL, 'eas_masters.deleted_at' => NULL, 'release_order_master.deleted_at' => NULL])
       ->orderBy('gar.id','desc');

             //dd($request->status_name , '',$request->entity_name,'dss');
       if(isset($request->status_name) && !empty($request->status_name))
       {
         $query->where('gar.status_id','=', $request->status_name);
       }
       if(isset($from_date) && !empty($from_date) && isset($to_date) && !empty($to_date)) 
       {
                 // $query->whereBetween('purchase_order_masters.created_at', array($from_date,$to_date));
        $query->where('gar.created_at','>=', date("Y-m-d", strtotime($from_date)));
        $query->where('gar.created_at','<=',  date("Y-m-d", strtotime($to_date)));
      }

      $data['gar'] = $query->get(); 

      $data['entity_details'] = Entity::select('id','workflow_id','entity_slug','final_status')->where(['deleted_at' => Null,'type_name' =>"RO"])->first();

      $data = view('gar.gar_status',compact('data','entity_details'))->render();
      break;


      }

      return $data;
      }
} catch (\Exception $e) {

    Log::critical($e->getMessage());
    app('sentry')->captureException($e);
   Toast::error('Something went wrong!');
    return redirect('/dashboard'); 
}  
}

}