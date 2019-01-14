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
 	try {


 		$role_details=$this->user_details->getUserDetails($this->user->role_id);
 		if(isset($role_details) && $role_details['status'] == 200 ) {
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
 				->orWhere('fc_number', 'LIKE', "%$keyword%")
 				->orWhere('fc_dated', 'LIKE', "%$keyword%")
 				->orWhere('fc_on_page', 'LIKE', "%$keyword%")
 				->orWhere('fc_on_file_no', 'LIKE', "%$keyword%")
 				->latest()->paginate($perPage);
 			} else {
 				$eas_masters = Eas::select('eas_masters.*','vendor_master.vendor_name','status.status_name')
 				->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
 				->join('status','status.id','=','eas_masters.status_id')
 				->where(['eas_masters.deleted_at'=> Null,'vendor_master.deleted_at'=>Null,'status.deleted_at'=>Null])
 				//->where(['eas_masters.location_id'=>$role_details['location_id'],'eas_masters.department_id'=>$role_details['department_id'],'eas_masters.office_type_id'=>$role_details['office_type_id']])
 				->latest()->paginate($perPage);

 				$total_eas = Eas::select('eas_masters.*','vendor_master.vendor_name','status.status_name')
 				->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
 				->join('status','status.id','=','eas_masters.status_id')
 				// ->where('eas_masters.status_id','=','3')
 				->where(['eas_masters.deleted_at'=> Null,'vendor_master.deleted_at'=>Null,'status.deleted_at'=>Null])
 				//->where(['eas_masters.location_id'=>$role_details['location_id'],'eas_masters.department_id'=>$role_details['department_id'],'eas_masters.office_type_id'=>$role_details['office_type_id']])
 				->count();
 				//dd($total_eas);
 				
 				$total_gar = GAR::select('gar.*','status.status_name') 
 				->join('status','status.id','=','gar.status_id')
                     // ->where(
                     //   ['purchase_order_masters.location_id' => $role_details['location_id'],
                     //   'purchase_order_masters.department_id' => $role_details['department_id'],
                     //   'purchase_order_masters.office_type_id' => $role_details['office_type_id']
                     // ])
 				->where(['gar.deleted_at' => Null,'status.deleted_at'=>Null])
 				->count();

 				$total_ro = ReleaseOrder::select('release_order_master.*','status.status_name') 
 				->join('status','status.id','=','release_order_master.status_id')
                     // ->where(
                     //   ['purchase_order_masters.location_id' => $role_details['location_id'],
                     //   'purchase_order_masters.department_id' => $role_details['department_id'],
                     //   'purchase_order_masters.office_type_id' => $role_details['office_type_id']
                     // ])
 				->where(['release_order_master.deleted_at' => Null,'status.deleted_at'=>Null])
 				->count();

 				$total_purchase_order = PurchaseOrder::select('purchase_order_masters.*','status.status_name') 
 				->join('status','status.id','=','purchase_order_masters.status_id')
 				// ->where(
 				// 	['purchase_order_masters.location_id' => $role_details['location_id'],
 				// 	'purchase_order_masters.department_id' => $role_details['department_id'],
 				// 	'purchase_order_masters.office_type_id' => $role_details['office_type_id']
 				// ])
 				->where(['purchase_order_masters.deleted_at' => Null,'status.deleted_at'=>Null])
 				->count();


 			}
 			if(isset($eas_masters) && !empty($eas_masters)) { 
 				return view('dashboard.dashboard', compact('eas_masters','total_purchase_order','total_eas','total_approved_eas','total_gar','total_ro'));
 			} else {
 				return view('dashboard.dashboard')->with('danger','Data not found');
 			}
 		} else {  
 			Toast::error('User details not found.');
 			return redirect('/dashboard');   
 		}    
 	} catch (\Exception $e) {

 		Log::critical($e->getMessage());
 		app('sentry')->captureException($e);
 		Toast::error('Something went wrong!');
 		return redirect('/dashboard'); 
 	}    
 }

 public function eas_dashboard(Request $request,$id)
 { 
 	
 	 try 
 	 {
 	$role_details=$this->user_details->getUserDetails($this->user->role_id);

 	if(isset($role_details) && $role_details['status'] == 200 ) {

 		if(isset($id) && !empty($id)) 
 		{
 			$get_purchase_orders_count = PurchaseOrder::select('purchase_order_masters.*','eas_id', 'id')
 			->where('eas_id','=',$id)
 			->where(['purchase_order_masters.location_id'=>$role_details['location_id'],'purchase_order_masters.department_id'=>$role_details['department_id'],'purchase_order_masters.office_type_id'=>$role_details['office_type_id']])
 			->where('deleted_at', null)
 			->count();

 			$eas_master = Eas::select('eas_masters.*','eas_masters.id','status.status_name')
 			->join('status','status.id','=','eas_masters.status_id')
 			->where('eas_masters.id' ,'=',$id)
 			->where(['eas_masters.deleted_at'=> Null,'status.deleted_at'=>Null])
 			->where(['eas_masters.location_id'=>$role_details['location_id'],'eas_masters.department_id'=>$role_details['department_id'],'eas_masters.office_type_id'=>$role_details['office_type_id']])
 			->first();

 			$status = Status::select('status.*')->get();
 			
 			
 			$purchase_order = $this->PO($role_details,$id);
 			$release_order = $this->RO($role_details,$id);
 			//$gar_details = $this->GAR($role_details,$id);
 			 //$this->getStatus($id);
 			return view('dashboard.eas-dashboard',compact('get_purchase_orders_count','eas_master','purchase_order','release_order','gar_details','status'));
 		} else {
 			Toast::error('Something went wrong!');
 			return redirect('/eas-dashboard'); 
 		}

 	} else {  
 		Toast::error('User details not found.');
 		return redirect('/eas-dashboard');   
 	}   


 	} catch (\Exception $e) {

 		Log::critical($e->getMessage());
 		app('sentry')->captureException($e);
           Toast::error('Something went wrong!');
 		return redirect('/eas-dashboard'); 
 	}  
 	
 }

 public function PO($role_details,$id)
 {
	//dd($id);
 	$data['purchase_order_listing'] = PurchaseOrder::select('purchase_order_masters.*','status.status_name','eas_masters.sanction_title') 
 	->join('status','status.id','=','purchase_order_masters.status_id')
 	->join('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
 	->where(['purchase_order_masters.location_id'=>$role_details['location_id'],
 		'purchase_order_masters.department_id'=>$role_details['department_id'],
 		'purchase_order_masters.office_type_id'=>$role_details['office_type_id']])
 	->where('purchase_order_masters.eas_id',$id)
 	->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null])
 	->orderBy('purchase_order_masters.id','desc')
 	->get();
                //dd($data['purchase_order_listing']);

 	$data['entity_details'] = Entity::select('type_name','id','workflow_id','entity_slug','final_status')
 	->where('type_name','=','PO')
 	->where('deleted_at',NULL)
 	->first();

 	$data['id'] = $id;

 	return $data;
 }

 public function RO($role_details,$id)
 {
 	$release_order['role_details']=$this->user_details->getUserDetails($this->user->role_id);
 	$release_order['releaseOrder'] = ReleaseOrder::select('release_order_master.*','status.status_name' ,'eas_masters.file_number','release_order_master.created_at','location.city_name','departments.name as department_name','office_type.office_type_name','vendor_master.vendor_name')
 	->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
 	->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
 	->join('status','status.id','=','release_order_master.status_id')
 	->join('location','location.id','=','release_order_master.location_id')
 	->join('departments','departments.id','=','release_order_master.department_id')
 	->join('office_type','office_type.id','=','release_order_master.office_type_id')
 	->where(['release_order_master.location_id'=>$role_details['location_id'],'release_order_master.department_id'=>$role_details['department_id'],'release_order_master.office_type_id'=>$role_details['office_type_id']])
 	->where('release_order_master.eas_id',$id)
 	->where(['release_order_master.deleted_at'=>Null,'status.deleted_at'=>Null,'eas_masters.deleted_at'=>Null,'location.deleted_at'=>Null,'office_type.deleted_at'=>Null,'departments.deleted_at'=>Null])
 	->orderBy('release_order_master.id','desc')
 	->get();

 	$release_order['count'] = count($release_order['releaseOrder']);


 	$release_order['entity_details'] = Entity::select('id','workflow_id','entity_slug','final_status')->where(['deleted_at' => Null,'type_name' =>"RO"])->first();

 	return $release_order;
 }

 // public function GAR($role_details,$id)
 // {
 // 	$gar_details['role_details']=$this->user_details->getUserDetails($this->user->role_id);
 // 	// $gar_details['gar'] = GAR::select('gar.*', 'eas_masters.sanction_title', 'release_order_master.ro_title', 'eas_masters.file_number', 'diary_register.diary_register_no', 'vendor_master.vendor_name', 'status.status_name')
 // 	 	$gar_details['gar'] = GAR::select('gar.*', 'release_order_master.ro_title', 'diary_register.diary_register_no', 'vendor_master.vendor_name', 'status.status_name')

 // 	->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
 // 	// ->join('eas_masters', 'eas_masters.id', '=', 'gar.eas_id')
 // 	->join('status', 'status.id', '=', 'gar.status_id')
 // 	->leftjoin('diary_register', 'diary_register.gar_id', '=', 'gar.id')
 // 	->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
 // 	// ->where('gar.eas_id',$id)
 // 	->where(['gar.location_id'=>$role_details['location_id'],'gar.department_id'=>$role_details['department_id'],'gar.office_type_id'=>$role_details['office_type_id']])
 // 	->where(['gar.deleted_at' => NULL,'release_order_master.deleted_at' => NULL])->orderBy('gar.id', 'desc')->get();
 // 	if (isset($role_details['role_name']) && $role_details['role_name'] == "DDO") {
 // 		$query->where(['gar.status_id' => 1]);
 // 	}
 // 	if (isset($role_details['role_name']) && $role_details['role_name'] == "PAO") {
 // 		$query->where(['gar.status_id' => 31]);
 // 		$query->orwhere(['gar.status_id' => 28]);
 // 		$query->orwhere(['gar.status_id' => 32]);
 // 		$query->orwhere(['gar.status_id' => 33]);
 // 	}


 // 	$gar_details['count'] = count($gar_details['gar']);


 // 	$gar_details['entity_details'] =Entity::select('id', 'workflow_id', 'entity_slug','final_status')->where(['deleted_at' => Null, 'type_name' => "GAR"])->first();

 // 	return $gar_details;
 // }


 public function getStatus(Request $request)
 {
 	
 	$role_details=$this->user_details->getUserDetails($this->user->role_id);


 	if(isset($request->entity_name) && !empty($request->entity_name))
        {  // dd($request); 
        	//dd($request->status_name);
            switch($request->entity_name) {

            case 'purchase_order':
                  
           $query =PurchaseOrder::select('purchase_order_masters.*','status.status_name','eas_masters.sanction_title') 
 			->join('status','status.id','=','purchase_order_masters.status_id')
 			->join('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
 			->where(['purchase_order_masters.location_id'=>$role_details['location_id'],
 				'purchase_order_masters.department_id'=>$role_details['department_id'],
 				'purchase_order_masters.office_type_id'=>$role_details['office_type_id']])
 			->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null])
 			->orderBy('purchase_order_masters.id','desc');
 			if(isset($request->status_name) && !empty($request->status_name))
 			{
 				$query->where('purchase_order_masters.status_id','=', $request->status_name);
 			}
 			if(isset($request->from_date) && !empty($request->from_date) && isset($request->to_date) && !empty($request->to_date)) 
 			{
 				$query->whereBetween('purchase_order_masters.created_at', array($request->from_date,$request->to_date));
 			}
 			
            // ->whereBetween('purchase_order_masters.created_at', array($to_date))
 			$data['purchase_order'] = $query->get(); 

 			//dd($data);

 			$data['entity_details'] = Entity::select('type_name','id','workflow_id','entity_slug','final_status')
 			->where('type_name','=','PO')
 			->where('deleted_at',NULL)
 			->first();

 			//dd($data);

 			$data = view('purchase_order.po_status',compact('data','entity_details'))->render();
            break;

            //  case 'ro':
        
            //   $result = ReleaseOrder::select('release_order_master.release_order_amount','release_order_master.office_type_id','release_order_master.email_users','vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch','vendor_master.bank_code', 'vendor_master.bank_acc_no','office_type.slug as office_slug','departments.name as department_name')
            //         ->leftjoin('office_type','release_order_master.office_type_id' ,'=', 'office_type.id')
            //         ->leftjoin('departments','release_order_master.department_id' ,'=', 'departments.id')
            //         ->leftjoin('eas_masters','eas_masters.id' ,'=', 'release_order_master.eas_id')
            //         ->leftjoin('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
            //         ->where('release_order_master.id' ,'=',$request->id)
            //         ->where(['release_order_master.deleted_at'=> NULL,'vendor_master.deleted_at'=>NULL,'office_type.deleted_at'=>NULL])->first();
            //         //dd($result );
        
            // break;

            // case 'gar':

            // $result = GAR::select('gar.*','vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch','vendor_master.bank_code', 'vendor_master.bank_acc_no')
            //         ->leftjoin('eas_masters','eas_masters.id' ,'=', 'gar.eas_id')
            //         ->leftjoin('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
            //         ->where('gar.id' ,'=',$request->id)
            //         ->where(['gar.deleted_at'=> NULL,'vendor_master.deleted_at'=>NULL,'eas_masters.deleted_at'=>NULL])->first();
            
            // break;
            
            }
        }
 	//dd($from_date);

 	// if(isset($status_name) && !empty($status_name) && isset($entity_name) && !empty($entity_name)){
 	 	//if(isset($status_name) && !empty($status_name) && isset($entity_name) && !empty($entity_name)){

 	// 	if($entity_name == 'purchase_order') 
 	// 	{

 	// 		 $query =PurchaseOrder::select('purchase_order_masters.*','status.status_name','eas_masters.sanction_title') 
 	// 		->join('status','status.id','=','purchase_order_masters.status_id')
 	// 		->join('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
 	// 		->where(['purchase_order_masters.location_id'=>$role_details['location_id'],
 	// 			'purchase_order_masters.department_id'=>$role_details['department_id'],
 	// 			'purchase_order_masters.office_type_id'=>$role_details['office_type_id']])
 	// 		->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null])
 	// 		->orderBy('purchase_order_masters.id','desc');
 	// 		if($status_name)
 	// 		{
 	// 			$query->where('purchase_order_masters.status_id','=', $status_name);
 	// 		}
 	// 		if($from_date && $to_date) 
 	// 		{
 	// 			$query->whereBetween('purchase_order_masters.created_at', array($from_date,$to_date));
 	// 		}
 			
  //           // ->whereBetween('purchase_order_masters.created_at', array($to_date))
 	// 		$data['purchase_order'] = $query->get(); 

 	// 		//dd($data);

 	// 		$data['entity_details'] = Entity::select('type_name','id','workflow_id','entity_slug','final_status')
 	// 		->where('type_name','=','PO')
 	// 		->where('deleted_at',NULL)
 	// 		->first();

 	// 		//dd($data);

 	// 		$data = view('purchase_order.po_status',compact('data','entity_details'))->render();

 	// 	} elseif ($entity_name == 'release_order') {

 	// 		$data['role_details']=$this->user_details->getUserDetails($this->user->role_id);
 	// 		$data['releaseOrder'] = ReleaseOrder::select('release_order_master.*','status.status_name' ,'eas_masters.file_number','release_order_master.created_at','location.city_name','departments.name as department_name','office_type.office_type_name','vendor_master.vendor_name')
 	// 		->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
 	// 		->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
 	// 		->join('status','status.id','=','release_order_master.status_id')
 	// 		->join('location','location.id','=','release_order_master.location_id')
 	// 		->join('departments','departments.id','=','release_order_master.department_id')
 	// 		->join('office_type','office_type.id','=','release_order_master.office_type_id')
 	// 		->where('release_order_master.status_id','=', $status_name)
 	// 		->where(['release_order_master.location_id'=>$role_details['location_id'],'release_order_master.department_id'=>$role_details['department_id'],'release_order_master.office_type_id'=>$role_details['office_type_id']])
 	// 		// ->where('release_order_master.eas_id',$id)
 	// 		->where(['release_order_master.deleted_at'=>Null,'status.deleted_at'=>Null,'eas_masters.deleted_at'=>Null,'location.deleted_at'=>Null,'office_type.deleted_at'=>Null,'departments.deleted_at'=>Null])
 	// 		->orderBy('release_order_master.id','desc')
 	// 		->get();

 			
 	// 		$data['entity_details'] = Entity::select('id','workflow_id','entity_slug','final_status')->where(['deleted_at' => Null,'type_name' =>"RO"])->first();

 	// 		//dd($data);

 	// 		$data = view('ro.ro_status',compact('data'))->render();
 	// 	} elseif($entity_name == 'gar') {

 	// 		$data['role_details']=$this->user_details->getUserDetails($this->user->role_id);
 	// 		$data['gar'] = GAR::select('gar.*', 'eas_masters.sanction_title', 'release_order_master.ro_title', 'eas_masters.file_number', 'diary_register.diary_register_no', 'vendor_master.vendor_name', 'status.status_name')->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')->join('eas_masters', 'eas_masters.id', '=', 'gar.eas_id')->join('status', 'status.id', '=', 'gar.status_id')->leftjoin('diary_register', 'diary_register.gar_id', '=', 'gar.id')
 	// 		->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
 	// 		->where('gar.status_id','=', $status_name)
 	//          // ->where('gar.eas_id',$id)
 	// 		->where(['gar.location_id'=>$role_details['location_id'],'gar.department_id'=>$role_details['department_id'],'gar.office_type_id'=>$role_details['office_type_id']])
 	// 		->where(['gar.deleted_at' => NULL, 'eas_masters.deleted_at' => NULL, 'release_order_master.deleted_at' => NULL])->orderBy('gar.id', 'desc')->get();
 	// 		if (isset($role_details['role_name']) && $role_details['role_name'] == "DDO") {
 	// 			$query->where(['gar.status_id' => 1]);
 	// 		}
 	// 		if (isset($role_details['role_name']) && $role_details['role_name'] == "PAO") {
 	// 			$query->where(['gar.status_id' => 31]);
 	// 			$query->orwhere(['gar.status_id' => 28]);
 	// 			$query->orwhere(['gar.status_id' => 32]);
 	// 			$query->orwhere(['gar.status_id' => 33]);
 	// 		}


 	// 		$data['entity_details'] =Entity::select('id', 'workflow_id', 'entity_slug','final_status')->where(['deleted_at' => Null, 'type_name' => "GAR"])->first();

 	// 		$data = view('gar.gar_status',compact('data'))->render();
 	// 	} 


 		
 	// } else 
 	// {

 	// }

 	return $data;
 }


 
}