<?php
/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category   ReleaseOrder
 * @author    Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license   https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\RoController
 * @link      https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReleaseOrder;
use App\Role;
use App\Eas;
use App\Entity;
use App\User;
use App\Vendor;
use App\WorkflowName;
use Auth;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Log;
use DB;
use Toast;
use App\RejectComment;
use Redirect;
use URL;
use App\EasLog;
use App\MediaMaster;
use App\InvoiceDetails;
use App\CopyToMaster;
/**
 * This class provides all operations to manage the ReleaseOrder data 
 *
 */

class RoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public $user_id,$user,$user_details,$vendor_details;
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
     * Display a listing of the ReleaseOrder
     *
     * @return \Illuminate\Http\Response
    *  @var string $keyword Returns the user input for search.
     * @var int $perPage To store number of entries for listing
     * @var string $releaseOrder Returns the data from release_order_master table from the user inputs for search
     * @var bool $releaseOrder Returns the data from release_order_master table for listing
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

     public function index(Request $request)
    {
     

    if(roleEntityMapping($this->user_id,'ro','can_view')) {
         $request->session()->forget('eas_id');
            try {
           // $role_details=getUserDetails($this->user->role_id);
            $role_details = '';
            //if(isset($role_details) && $role_details['status'] == 200 ) {
                $keyword = $request->get('search');
                $perPage = 25;
                    if (!empty($keyword)) {
                       $releaseOrder = ReleaseOrder::where('ro_title', 'LIKE', "%$keyword%")
                ->orWhere('release_order_amount', 'LIKE', "%$keyword%")
                ->orWhere('advance_ro', 'LIKE', "%$keyword%")
                ->orWhere('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
            } else {

                $user_details = getUserDetails($this->user_id);
                 // dd($user_details['departments_id']);     
                if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){
    
                $releaseOrder = ReleaseOrder::select('release_order_master.ro_title','release_order_master.release_order_amount','release_order_master.created_at','release_order_master.status_id','release_order_master.id','status.status_name' ,'eas_masters.file_number','release_order_master.created_at','vendor_master.vendor_name','eas_masters.sanction_title','departments.name as department_name')
                ->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
                ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                ->join('status','status.id','=','release_order_master.status_id')
                //->join('location','location.id','=','release_order_master.location_id')
                ->join('departments','departments.id','=','eas_masters.department_id')
              //  ->join('office_type','office_type.id','=','release_order_master.office_type_id')
                //->where(['release_order_master.location_id'=>$role_details['location_id'],'release_order_master.department_id'=>$role_details['department_id'],'release_order_master.office_type_id'=>$role_details['office_type_id']])
                ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                //->where(['release_order_master.deleted_at'=>Null,'status.deleted_at'=>Null,'eas_masters.deleted_at'=>Null])
                ->orderBy('release_order_master.id','desc')
                ->paginate($perPage);
             }   
                    }
                  $entity_details = Entity::select('id','workflow_id','entity_slug','final_status')->where(['deleted_at' => Null,'type_name' =>"RO"])->first();
                    if(isset($releaseOrder) && !empty($releaseOrder)) { 
                        return view('/ro.index', compact('releaseOrder','role_details','entity_details'));
                    } else {
                        return view('/ro.index')->with('danger','Data not found');
                    }
                // } else {  
                //     Toast::error('User details not found.');
                //     return redirect('/dashboard');   
                // }    
            } catch (\Exception $e) {
             
                Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/ro'); 
            }
        } else {
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/dashboard');
        }    
    }
         
    /**
     * To create new ReleaseOrder 
     *
     * @return \Illuminate\Http\Response
     * 
     * @var string $keyword Returns the user input for search.
     * @var int $perPage To store number of entries for listing
     * @var string $eas Returns the data from eas_masters table. Sanction title is listed in dropdown.
     * @var string $user_details Returns the data from Role table.On selection of sanction title, vendor details is fetch from eas table against selected EAS
     *
     */

    public function create()
    {
        if(roleEntityMapping($this->user_id,'ro','can_create')) {
           try {
    
                $eas_final_status = getStatus('eas');
               // $user_details = getUserDetails($this->user->role_id);
               // $users = User::select('name', 'id')->where('deleted_at', null)->where(['users.user_status' => 'Enable'])->orderBy('id','desc')->get();
                $user_details = getUserDetails($this->user_id);
                $eas = Eas::select('eas_masters.sanction_title','eas_masters.id','eas_masters.budget_code','eas_masters.sanction_total')
                ->where('eas_masters.status_id','=', $eas_final_status->final_status)
                ->whereIn('eas_masters.department_id',$user_details['departments_id']) 
                ->where(['eas_masters.deleted_at'=> null])
                ->get();
              
                $entity_details = getStatus('ro');

                $users = User::select('users.id','users.name')
                    ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                    ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                    ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                    ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                    ->whereIn('role_department_mapper.department_id',$user_details['departments_id'])
                    ->where(['users.user_status' => 'Enable'])
                    ->where('users.id','!=',$this->user_id)
                    ->distinct()
                    ->orderBy('users.id','desc')
                    ->get();

                   if (isset($user_details) && !empty($user_details) && isset($user_details['departments']) &&  !empty($user_details['departments']) ) {

                     $list_of_departments = $user_details['departments'];
                   }
                $is_create = 1; 
        
                 return view('/ro.create',compact('eas','entity_details','users','is_create','list_of_departments','users'));
            } catch (\Exception $e) {

                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong');
                return redirect('/ro'); 
            } 

        } else {
           Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/ro');
        }

    }

    /**
     * To store new ReleaseOrder 
     *
     * @return \Illuminate\Http\Response
     * 
     * @var array $ro_details To assign value from requested data to columns and insert array
     * @var array $user_details Vendor Id is mapped in EAS master table. Sanction title is listed in dropdown,on selection of sanction title, vendor details is fetch from eas table against selected EAS
     * @var bool $workflow Returns the data from Entity table.
     * @var bool $workflow Returns the data from Entity table against entity id.
     * @var bool $status_id Returns the data from workflow_name table against workflow id.
     *
     */
     public function store(Request $request)
    {
        //dd($request->file('vendor_invoice'));
     if(roleEntityMapping($this->user_id,'ro','can_create')) {
       try {
            if(isset($request->advance_ro))
            {
                    $this->validate($request,[
                    'ro_title' => 'required|unique:release_order_master,ro_title,NULL,id,deleted_at,NULL',
                    'eas_id' => 'required',
                    'release_order_amount' => 'required',
                    'fa_diary_number' => 'required'

                ]);
            }
            else
            {       $this->validate($request,[
                    'ro_title' => 'required|unique:release_order_master,ro_title,NULL,id,deleted_at,NULL',
                    'eas_id' => 'required',
                    'release_order_amount' => 'required'
                ]);
            }
           
          //try { 
           
            $user_details= getUserDetails($this->user->role_id);
           // $entity_id = $request->entity_id;

            $workflow = Entity::select('workflow_id')->where(['id' => $request->entity_id,'deleted_at' => Null])->first();

            $status_id = WorkflowName::select('default_status')->where(['id' => $workflow->workflow_id])->first();


            if(isset($request->email_users) && !empty($request->email_users)) {
            $email_mail = $request->email_users;
                $send_email = implode(",", $email_mail);
            }else {
                $send_email= '';
            }
                //dd($requestData);

           // $file_name = $request->file('file_name');

                // if(isset($user_details) && !empty($user_details) && $user_details != Null)
                // {
                    $ro_details = ReleaseOrder::create([
                    'ro_title'=>$request->ro_title,
                    'eas_id'=>$request->eas_id,
                    'release_order_amount'=>$request->release_order_amount,
                    // 'location_id'=>$user_details['location_id'],
                    // 'office_type_id' => $user_details['office_type_id'],
                    // 'department_id' =>$user_details['department_id'],
                    'copy_to' => $request->copy_to,
                    'email_users' => $send_email,
                    'status_id' => $status_id['default_status'],
                    'created_by'=>$this->user_id,'advance_ro'=>$request->advance_ro,'fa_diary_number'=>$request->fa_diary_number,'copy_to'=>$request->copy_to]);

     
                    if(isset($ro_details) && !empty($ro_details))  {

                        if (isset($request->copy) && !empty($request->copy)) {
 
                        foreach ($request->copy as $key => $value) {
                              
                           $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$ro_details->id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
                          // dd($create_invoice);
                        }

                      }
                  
                     $requestData = $request->all();
                    
                     $details = $this->user_details->storeLogDetails($ro_details->id,$request->entity_id,$requestData);

                      if (isset($request->is_invoice_present) && !empty($request->is_invoice_present) &&  isset($request->invoice) && !empty($request->invoice)) {
 
                        foreach ($request->invoice as $key => $value) {

                            if(isset($value['invoice_no']) && !empty($value['invoice_no']) && isset($value['agency_name']) && !empty($value['agency_name']) && isset($value['period']) && !empty($value['period']) && isset($value['amount_payment']) && !empty($value['amount_payment']) && isset($value['applicable_taxes']) && !empty($value['applicable_taxes'])) {
                              
                           $create_invoice = InvoiceDetails::create(['ro_id'=>$ro_details->id,'invoice_no'=>$value['invoice_no'],'agency_name'=>$value['agency_name'],'qty'=>$value['qty'],'period'=>$value['period'],'amount_payment'=>$value['amount_payment'],'sla_amount'=>$value['sla_amount'],'applicable_taxes'=>$value['applicable_taxes'],'withheld_amount'=>$value['withheld_amount'],'net_payable_amount'=>$value['net_payable_amount'],'created_by'=>$this->user->id]);
                         
                          // dd($create_invoice);
                            }
                        }

                      }
                     
                        if (isset($request->documents_type) && !empty($request->documents_type) && isset($request->file_upload) && $request->hasFile('file_upload')) {

                              $file_number = Eas::select('eas_masters.file_number')
                                ->join('release_order_master','release_order_master.eas_id','=','eas_masters.id')
                                ->where('eas_masters.id','=',$request->eas_id)
                                ->first();

                        $uploadDocuments = $this->user_details->uploadDocuments($request->file_upload,$request->documents_type,$ro_details->id,$this->user_id,$request->entity_id,$request->entity_slug,$file_number->file_number);

                        if(isset($uploadDocuments) && !empty($uploadDocuments) && $uploadDocuments['code'] == 200)  {
                            Toast::success('Release Order added successfully!');
                            return redirect('/ro');
                        } else{
                          Toast::error('Something went wrong while file upload!');
                         return redirect('/ro');
                        }
                    } else {
                        Toast::success('Release Order added successfully!');
                        return redirect('/ro');
                    }    

                    } else {
                         Toast::error('Something went wrong!');
                         return redirect('/ro');
                    } 
                // } else {
                //     Toast::error('Role not found');
                //     return redirect('/ro');
                // } 

            } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            return redirect('/ro');
            }
        } else {
          Toast::error('Something went wrong!');
          return redirect('/ro');
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
        if(roleEntityMapping($this->user_id,'ro','can_view')) {
            try {
                //$eas_master = Eas::findOrFail($id);
                    $releaseOrder = ReleaseOrder::select('release_order_master.id','release_order_master.status_id','release_order_master.ro_title','release_order_master.release_order_amount','release_order_master.created_by','budget_list.budget_code','release_order_master.ro_pdf',
                        'eas_masters.sanction_title','vendor_master.vendor_name','vendor_master.address','vendor_master.mobile_no','vendor_master.ifsc_code','vendor_master.bank_name','vendor_master.bank_branch','vendor_master.bank_acc_no','vendor_master.bank_code','eas_masters.sanction_total','status.status_name','vendor_master.bank_code','vendor_master.bank_acc_no','eas_masters.department_id','users.name as assignee')
                            ->leftjoin('status','status.id','=','release_order_master.status_id')
                            ->leftjoin('eas_masters','eas_masters.id','=','release_order_master.eas_id')
                            ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
                            ->leftjoin('assignee_mapper','assignee_mapper.master_id','=','release_order_master.id')
                            ->leftjoin('users','assignee_mapper.assignee','=','users.id')
                            ->leftjoin('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                            ->where('release_order_master.id' ,'=',$id)
                            ->orderBy('assignee_mapper.id','desc')
                            //->where(['release_order_master.deleted_at'=>NUll,'eas_masters.deleted_at'=> Null,'status.deleted_at'=>Null,'vendor_master.deleted_at'=>Null])
                            ->first();

                     $invoice_details = InvoiceDetails::select('id','ro_id','invoice_no','agency_name','qty','period','amount_payment','sla_amount','applicable_taxes','withheld_amount','net_payable_amount')->where(['deleted_at'=>NULL,'ro_id'=>$id])->orderBy('id','asc')->get()->toArray(); 

                
                    $transaction_details = getTransactionDetails($id,$releaseOrder,$entity_slug="ro");
                    $entity_details = $transaction_details['entity_details'];
                    $transaction_data = $transaction_details['transaction_data'];
                    $documents_details = $transaction_details['documents_details'];
                    $added_comment = $transaction_details['added_comment'];
                    $user_details = getUserDetails($this->user_id);

                    if(isset($user_details) && isset($user_details['roles']) && !empty($user_details['roles'])){
                  
                    $trans_permission  = checkRole($user_details['roles'],$entity_details->entity_slug,$transaction_data,$releaseOrder->created_by);
                    } else {
                     $trans_permission = '';
                    }    
                  
                    $is_show = 1;
                    $assigne = getAssignee($entity_details->id,$id,$releaseOrder->status_id);
                    $users = User::select('users.id','users.name')
                    ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                    ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                    ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                    ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                    ->where(['departments.id'=>$releaseOrder->department_id,'users.user_status' => 'Enable','users.deleted_at'=>NULL])
                    ->where('users.id','!=',$this->user_id)
                    ->orderBy('id','desc')
                    ->get();
                    $users_json = json_encode($users);

            return view('/ro.show', compact('releaseOrder','transaction_data','trans_permission','added_comment','documents_details','is_show','entity_details','assigne','users_json','invoice_details'));

        } catch (\Exception $e) {
           
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
            return redirect('/ro');
        }
    } else {
        Toast::error('You are not Authorized to perform this Action.');
        return Redirect::to('/ro');
    }
} 

     /**
     * To edit the records of ReleaseOrder
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $ro retrieve data from eas_masters
     * @var string $ro_table_data retrieve release order data from release-order_master
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     */

    public function edit($id)
    {  
        if(roleEntityMapping($this->user_id,'ro','can_update')) {
            try {  

                //$users = User::select('name', 'id')->where('deleted_at', null)->where(['users.user_status' => 'Enable'])->orderBy('id','desc')->get();
               
                $eas_final_status = getStatus('eas');

                $ro = ReleaseOrder::select('eas_masters.sanction_title','budget_list.budget_code','eas_masters.sanction_total','vendor_master.vendor_name','vendor_master.address','vendor_master.mobile_no','vendor_master.ifsc_code','vendor_master.bank_name','vendor_master.bank_branch','vendor_master.bank_acc_no','vendor_master.bank_code','release_order_master.release_order_amount','release_order_master.ro_title','release_order_master.eas_id','release_order_master.id','release_order_master.advance_ro','release_order_master.fa_diary_number','release_order_master.status_id','release_order_master.created_by','release_order_master.email_users','release_order_master.copy_to','release_order_master.is_invoice_present','release_order_master.copy_to')
                    ->join('eas_masters','release_order_master.eas_id','=','eas_masters.id' )
                ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id' )
                ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
                ->where('eas_masters.status_id','=', $eas_final_status->final_status)
                ->where(['eas_masters.deleted_at'=> null,'vendor_master.deleted_at'=> null,'release_order_master.deleted_at'=>Null,'release_order_master.id'=>$id])
                ->first();

                $user_details = getUserDetails($this->user_id);
                $eas = Eas::select('eas_masters.sanction_title','eas_masters.id','eas_masters.budget_code','eas_masters.sanction_total')
                ->where('eas_masters.status_id','=', $eas_final_status->final_status)
                ->whereIn('eas_masters.department_id',$user_details['departments_id']) 
                ->where(['eas_masters.deleted_at'=> null])
                ->get();
                
                 $ro_table_data = ReleaseOrder::select('release_order_master.id','release_order_master.ro_title','release_order_master.release_order_amount')
            ->join('eas_masters','release_order_master.eas_id','=','eas_masters.id')
            ->where(['release_order_master.deleted_at'=>NULL,'eas_masters.deleted_at'=>NULL,'release_order_master.eas_id'=>$ro->eas_id ])->get();

                //$releaseOrder = $ro->id; 
                $is_update_url = 1;
                $transaction_details = getTransactionDetails($id,$ro,$entity_slug="ro");
                $entity_details = $transaction_details['entity_details'];
                $documents_details = $transaction_details['documents_details'];
                $added_comment = $transaction_details['added_comment'];
                $invoice_details = InvoiceDetails::select('id','ro_id','invoice_no','agency_name','qty','period','amount_payment','sla_amount','applicable_taxes','withheld_amount','net_payable_amount')->where(['deleted_at'=>NULL,'ro_id'=>$id])->orderBy('id','asc')->get()->toArray();
                
                $mail = $ro['email_users'];
                
               $selected_mail_users = explode(",", $mail);

                $users = User::select('users.id','users.name')
                    ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                    ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                    ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                    ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                    ->whereIn('role_department_mapper.department_id',$user_details['departments_id'])
                    ->where('users.id','!=',$this->user_id)
                    ->where(['users.user_status' => 'Enable'])
                    ->distinct()
                    ->orderBy('users.id','desc')
                    ->get();

                 $copy_to_details = CopyToMaster::select('copy_to_details.master_id','copy_to_details.id','copy_to_details.user_id','copy_to_details.department_id','location.location_name','departments.name as department_name','users.name as user_name')
                ->join('users','users.id','=','copy_to_details.user_id')
                ->join('departments','departments.id','=','copy_to_details.department_id')
                ->join('location','location.id','=','departments.location_id')
                ->where(['master_id'=>$id,'entity_id'=>$entity_details->id])
                ->orderBy('id','asc')->get()->toArray();    
                    
               if (isset($user_details) && !empty($user_details) && isset($user_details['departments']) &&  !empty($user_details['departments']) ) {

                 $list_of_departments = $user_details['departments'];
               }

               return view('/ro.edit', compact('ro','entity_details','ro_table_data','added_comment','users','selected_mail_users','is_update_url','documents_details','invoice_details','eas','list_of_departments','copy_to_details'));

            } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
            return redirect('/ro');
            }  
        
        } else {
           Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/ro');
        }

    }

    /**
     * Update the specified record of ReleaseOrder 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var array $ro_updated_data To assign value from requested data to columns and update array
     * 
     */

    public function update(Request $request, $id)
    {
       
       
        if(roleEntityMapping($this->user_id,'ro','can_update')) {

            if(isset($request->advance_ro) && isset($request->copy_to)) {

              $this->validate($request, [
                'ro_title' => 'required',
                'eas_id' => 'required',
                'release_order_amount' => 'required',
                'fa_diary_number' => 'required',
                'email_users' => 'required'

            ]);
            } else {
                
                $this->validate($request, [
                    'ro_title' => 'required',
                    'eas_id' => 'required',
                    'release_order_amount' => 'required'
                ]);
            }
       try { 
                if($request->advance_ro == Null || $request->advance_ro == "")
                {
                    $advance_ro = 0;
                }
                else
                {
                    $advance_ro = $request->advance_ro;
                }
               
                if(isset($request->email_users) && !empty($requestData->email_users)){
                $email_mail = $request->email_users;
                $send_email = implode(",", $email_mail);
                }else {
                $send_email = ''; 
                }

                //$requestData = $request->all();
                $ro_order = ReleaseOrder::findOrFail($id);

                 $ro_updated_data = ReleaseOrder::where('release_order_master.id',$id)
                    ->update(['ro_title'=>$request->ro_title,
                    'eas_id'=>$request->eas_id,
                    'release_order_amount'=>$request->release_order_amount,
                    'advance_ro' => $advance_ro,
                    'fa_diary_number' => $request->fa_diary_number,
                    'email_users' => $send_email,
                    'is_invoice_present' =>$request->is_invoice_present,
                    'copy_to'=>$request->copy_to
                    ]);
//dd($ro_updated_data);

                    if (isset($request->copy) && !empty($request->copy)) {
 
                        foreach ($request->copy as $key => $value) {
                              
                           $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
                          // dd($create_invoice);
                        }

                      }
                      if (isset($request->is_invoice_present) && !empty($request->is_invoice_present) &&  isset($request->invoice) && !empty($request->invoice)) {
 
                        foreach ($request->invoice as $key => $value) {

                            if(isset($value['invoice_no']) && !empty($value['invoice_no']) && isset($value['agency_name']) && !empty($value['agency_name']) && isset($value['period']) && !empty($value['period']) && isset($value['amount_payment']) && !empty($value['amount_payment']) && isset($value['applicable_taxes']) && !empty($value['applicable_taxes'])) {

                              
                           $create_invoice = InvoiceDetails::create(['ro_id'=>$id,'invoice_no'=>$value['invoice_no'],'agency_name'=>$value['agency_name'],'qty'=>$value['qty'],'period'=>$value['period'],'amount_payment'=>$value['amount_payment'],'sla_amount'=>$value['sla_amount'],'applicable_taxes'=>$value['applicable_taxes'],'withheld_amount'=>$value['withheld_amount'],'net_payable_amount'=>$value['net_payable_amount'],'created_by'=>$this->user->id]);
                          // dd($create_invoice);
                          }
                        }

                      }

                      if (isset($request->invoice) && !empty($request->invoice)) {

                        $delete_invoice = InvoiceDetails::where(['ro_id'=>$id])->delete();
 
                        foreach ($request->invoice as $key => $value) {
                              
                           $create_invoice = InvoiceDetails::create(['ro_id'=>$id,'invoice_no'=>$value['invoice_no'],'agency_name'=>$value['agency_name'],'qty'=>$value['qty'],'period'=>$value['period'],'amount_payment'=>$value['amount_payment'],'sla_amount'=>$value['sla_amount'],'applicable_taxes'=>$value['applicable_taxes'],'withheld_amount'=>$value['withheld_amount'],'net_payable_amount'=>$value['net_payable_amount'],'created_by'=>$this->user->id]);
                          // dd($create_invoice);
                        }

                      }

                if(isset($ro_updated_data) && !empty($ro_updated_data)) {

                     $requestData = $request->all();

                     $details = $this->user_details->storeLogDetails($ro_order->id,$request->entity_id,$requestData);

                    if (isset($request->documents_type) && !empty($request->documents_type) && isset($request->file_upload) && $request->hasFile('file_upload')) {

                         $file_number = Eas::select('eas_masters.file_number')
                                ->join('release_order_master','release_order_master.eas_id','=','eas_masters.id')
                                ->where('eas_masters.id','=',$request->eas_id)
                                ->first();

                     $uploadDocuments = $this->user_details->uploadDocuments($request->file_upload,$request->documents_type,$id,$this->user_id,$request->entity_id,$request->entity_slug,$file_number->file_number);

                        if(isset($uploadDocuments) && !empty($uploadDocuments) && $uploadDocuments['code'] == 200)  {
                          Toast::success('Release Order updated successfully!');
                        return redirect('/ro/'.$id);
                        } else{
                          Toast::error('Something went wrong while file upload!');
                         return redirect('/ro/'.$id);
                        }
                    } else {
                        Toast::success('Release Order updated successfully!');
                        return redirect('/ro/'.$id);
                    } 
                       
                    //return redirect('/eas')->with('success', 'Eas updated successfully!');
                } else {
                    Toast::error('Something went wrong');
                    return redirect('/ro');
                }  

            } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/ro');
            }
        } else {
           Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/ro');
        }   
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $ro to find record against particular id  
     *
     */
    public function destroy($id)
    {   
        if(roleEntityMapping($this->user_id,'ro','can_delete')) {
            try {      
                if(isset($id) && !empty($id)) { 
                  //  $eas = Eas::delete($id);
                    $ro = ReleaseOrder::where('id',$id)->delete();
                    if(isset($ro) && !empty($ro)) {
                        Toast::success('Release Order deleted successfully!');
                         return redirect('/ro');
                    } else {
                        Toast::error('Something went wrong');
                        return redirect('/ro');
                    }
                } else  {
                    Toast::error('Id not found');
                    return redirect('/ro');
                }
            } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
              return redirect('/ro');
            }
        } else {
           Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/ro');
        } 

    }    

    public function downloadRoPdf($id)
    { 
        try {

         $file_name = ReleaseOrder::select('ro_pdf')
                ->where('id',$id)
                ->first();
       // dd($file_name );
        return response()->download(storage_path("documents/{$file_name->ro_pdf}"));
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('File Not found!');
            return redirect()->back();
       }  
    }

}
