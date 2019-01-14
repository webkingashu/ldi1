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
 * @package App\Http\Controllers\GarController
 * @link  https://choicetechlab.com/
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Session;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;
use App\GAR;
use App\User;
use App\Eas;
use App\Cheque;
use App\ReleaseOrder;
use App\GARBillType;
use App\DiaryRegister;
use Toast;
use App\Department;
use App\Role;
use App\Entity;
use App\Transaction;
use App\WorkflowName;
use App\Http\Controllers\CommonController;
use DB;
use App\DispatchRegister;
use Validator;
use App\RejectComment;
use PDF;
use File;
use App\EasLog;
use App\Budget;
use App\GarRegister;
use App\EcRegister;
use App\CopyToMaster;
/**
 * This class provides a all operation to manage the GAR data.
 *
 * The GarController is responsible for managing the basic details of vendor which require for genarating the GAR report.
 *
 */
class GarController extends Controller {
    
    public $user_id, $user, $user_details;
    public function __construct() {
        //$this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->user_id = Auth::id();
            $this->user_details = new CommonController();
            return $next($request);
        });
    }
    /**
     * Display a listing of the GAR,with EAS, RO etc. Also provide actions to edit,delete.
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
     * @return json response for GAR list.
     */
    public function index() {
        if (roleEntityMapping($this->user_id, 'gar', 'can_view')) {
            try {
                $entity_details = getStatus('gar');
                $role_details = getUserDetails($this->user_id);
                $perPage = 25;
                $user_details = getUserDetails($this->user_id);
                       
                if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){

                $query = GAR::select('gar.id','gar.status_id', 'eas_masters.sanction_title', 'release_order_master.ro_title', 'eas_masters.file_number', 'diary_register.diary_register_no', 'vendor_master.vendor_name', 'status.status_name','departments.name as department_name','gar.created_at','release_order_master.release_order_amount','gar.actual_payment_amount','gar.amount_paid')
                ->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
                ->join('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')
                ->join('status', 'status.id', '=', 'gar.status_id')
                ->leftjoin('diary_register', 'diary_register.gar_id', '=', 'gar.id')
                ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                ->join('departments','departments.id','=','eas_masters.department_id')
                ->where(['gar.deleted_at' => NULL, 'eas_masters.deleted_at' => NULL, 'release_order_master.deleted_at' => NULL])->orderBy('gar.id', 'desc')
                ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                ->orderBy('gar.id','desc');
                // if (isset($role_details['role_name']) && $role_details['role_name'] == "DDO") {
                //     $query->where(['gar.status_id' => 1]);
                // }
                // if (isset($role_details['role_name']) && $role_details['role_name'] == "PAO") {
                //     $query->where(['gar.status_id' => 31]);
                //     $query->orwhere(['gar.status_id' => $gar_final_status->final_status]);
                //     //$query->orwhere(['gar.status_id' => 32]);
                //    // $query->orwhere(['gar.status_id' => 33]);
                // }
                $result = $query->latest()->paginate($perPage);
               // $entity_details = Entity::select('id', 'workflow_id', 'entity_slug','final_status')->where(['deleted_at' => Null, 'type_name' => "GAR"])->first();
            }

                return view('gar.list', compact('result','entity_details'));
            }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                return redirect('/dashboard')->with('danger', 'Something went wrong!');
            }
        } else {
            Toast::error('You Dont have permssion to Access GAR Master');
            return Redirect::to('/dashboard');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        if (roleEntityMapping($this->user_id, 'gar', 'can_create')) {

           //$eas_final_status = Entity::select('final_status')->where(['deleted_at' => Null, 'entity_slug' => "eas"])->first()->final_status;
           
            try {
            $user_details = getUserDetails($this->user_id);
            $ro_final_status = getStatus('ro');
            $entity_details = getStatus('gar');
          //  $users = User::select('name', 'id')->where('deleted_at', null)->where(['users.user_status' => 'Enable'])->orderBy('id', 'desc')->get();
               // $eas = Eas::select('sanction_title', 'id', 'file_number')->where(['deleted_at' => NULL, 'status_id' => 3])->orderBy('id', 'desc')->get();
                // $ro = ReleaseOrder::select('release_order_master.id', 'release_order_master.ro_title', 'release_order_master.release_order_amount')
                // ->where(['release_order_master.deleted_at' => NULL,'release_order_master.status_id' =>$ro_final_status->final_status])->get();
//dd($ro_final_status->final_status);
             $ro = ReleaseOrder::select('release_order_master.id','release_order_master.ro_title')->whereNotIn('release_order_master.id',function($query) {
                 $query->select('gar.ro_id')->from('gar');
                 })
                //->join('release_order_master','gar.ro_id','=','release_order_master.id')
                ->join('eas_masters','release_order_master.eas_id','=','eas_masters.id')
               ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                ->where(['release_order_master.deleted_at' => NULL,'release_order_master.status_id' =>$ro_final_status->final_status])
                ->get();
               

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
              
    
                $gar_bill_type = GARBillType::select('id', 'gar_bill_name')->where(['deleted_at' => NULL])->orderBy('id', 'asc')->get();
                
                $is_update_url = 0;
                $role_details = getUserDetails($this->user_id);

                //$budget_head_amount = Budget::select('amount')->where(['functional_wing'=>$role_details['department_id'] ])->first()->amount;
                $is_create = 1; 

                return view('gar/add', compact('users', 'ro', 'gar_bill_type', 'entity_details', 'is_update_url','is_create','list_of_departments'));
            }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
        } else {
            Toast::error('You Dont have permssion to Access GAR Master');
            return Redirect::to('/dashboard');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (roleEntityMapping($this->user_id, 'gar', 'can_create')) {
            // if (isset($request->is_gst_present) && $request->is_gst_present == "no") {
            //     $this->validate($request, ['eas_id' => 'required',
            //     'release_order_amount' => 'required', 
            //     'ro_id' => 'required|unique:gar,ro_id,NULL,id,deleted_at,NULL',
            //     'gar_bill_type' => 'required',
            //     //'amount_used_till_date' => 'required',
            //     'amount_paid' => 'required',
            //     'gst_amount' => 'required', 'tds_amount' => 'required',
            //     'actual_payment_amount' => 'required']);
            // } else {
            //     $this->validate($request, ['eas_id' => 'required', 'release_order_amount' => 'required', 'ro_id' => 'required|unique:gar,ro_id,NULL,id,deleted_at,NULL',
            //     // 'is_diary_register' => 'required',
            //     'gar_bill_type' => 'required',
            //     //'select_type' => 'required',
            //     'amount_used_till_date' => 'required',
            //      'amount_paid' => 'required', 'is_gst_present' => 'required']);
            // }
        // dd($request);
             $this->validate($request, [
                'release_order_amount' => 'required', 
                'ro_id' => 'required|unique:gar,ro_id,NULL,id,deleted_at,NULL',
                'gar_bill_type' => 'required',
                //'amount_used_till_date' => 'required',
                'amount_to_be_paid' => 'required',
                'gst_amount' => 'required', 
                'tds_amount' => 'required',
                'actual_payment_amount' => 'required']);
            try {
                $requestData = $request->all();
                $user_details = getUserDetails($this->user_id);
                $gar_final_status = getStatus('gar');
                // $entity_id = $request->entity_id;
                // $workflow = Entity::select('workflow_id')->where(['id' => $request->entity_id, 'deleted_at' => Null])->first();
                // $status_id = WorkflowName::select('default_status')->where(['id' => $workflow->workflow_id])->first();
                if (isset($request->email_users) && !empty($request->email_users)) {
                    $email_mail = $request->email_users;
                    $send_email = implode(",", $email_mail);
                } else {
                    $send_email = '';
                }
                $create_gar = GAR::create(['status_id' =>$gar_final_status->default_status,'ro_id' => $request->ro_id, 'release_order_amount' => $request->release_order_amount, 'gar_bill_type' => $request->gar_bill_type, 'amount_paid' => $request->amount_to_be_paid, 'deducted_gst' => $request->deducted_gst,'actual_payment_amount' => $request->actual_payment_amount, 'copy_to' => $request->copy_to, 'email_users' => $send_email, 'created_by' => $this->user_id,'amount_used_till_date' => $request->amount_used_till_date, 'gst_amount' => $request->gst_amount, 'tds_amount' => $request->tds_amount, 'other_amount' => $request->other_amount, 'gst_tds_amount' => $request->gst_tds_amount,'ld_amount'=>$request->ld_amount,'with_held_amount'=>$request->with_held_amount,'tds_deducted_amount'=>$request->tds_deducted_amount]);

                if (isset($create_gar) && !empty($create_gar)) {

                     if (isset($request->copy) && !empty($request->copy)) {
 
                        foreach ($request->copy as $key => $value) {
                              
                           $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$create_gar->id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
                          // dd($create_invoice);
                        }

                      }

                     $details = $this->user_details->storeLogDetails($create_gar->id,$request->entity_id,$requestData);

                    Toast::success('GAR Sucessfully created!');
                    return Redirect('gar');
                } else {
                    Toast::error('Something went wrong while creating GAR!');
                    return Redirect('gar');
                }
            }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
        } else {
            Toast::error('You Dont have permssion to Access GAR Master');
            return Redirect::to('/dashboard');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        if (roleEntityMapping($this->user_id, 'gar', 'can_view')) {
           try {
                $gar_details = GAR::select('gar.deducted_gst','gar.status_id','gar.status_id','gar.created_by','gar.gst_tds_amount','gar.ld_amount','gar.tds_amount','gar.gst_amount','gar.with_held_amount','gar.gar_pdf','gar.id','gar.ro_id', 'gar.release_order_amount', 'gar.is_diary_register', 'gar.gar_bill_type', 'gar.amount_paid','gar.actual_payment_amount', 'gar.copy_to', 'gar.status_id', 'gar.email_users', 'gar.is_dispatch_register', 'gar.tally_entry', 'gar.amount_used_till_date', 'gar.created_by', 'release_order_master.ro_title','release_order_master.status_approved_date','eas_masters.vendor_id', 'vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch', 'budget_list.budget_code', 'vendor_master.bank_code', 'vendor_master.bank_acc_no', 'eas_masters.file_number', 'eas_masters.sanction_title', 'diary_register.diary_register_no', 'diary_register.date_of_forwarding', 'status.status_name', 'dispatch_register.dispatch_register_no', 'dispatch_register.date_of_receiving as dispatch_receiving', 'dispatch_register.date_of_forwarding as dispatch_forwarding', 'gar.forwarding_letter','gar_bill_type.gar_bill_name')->join('release_order_master', 'release_order_master.id', 'gar.ro_id')->leftjoin('eas_masters', 'eas_masters.id', 'release_order_master.eas_id')->join('gar_bill_type','gar_bill_type.id','=','gar.gar_bill_type')
                ->leftjoin('vendor_master', 'vendor_master.id', 'eas_masters.vendor_id')->leftjoin('diary_register', 'diary_register.gar_id', 'gar.id')->leftjoin('budget_list', 'budget_list.id', 'eas_masters.budget_code')->leftjoin('dispatch_register', 'dispatch_register.gar_id', 'gar.id')->leftjoin('status', 'status.id', 'gar.status_id')->where(['gar.id' => $id])->first();

                $transaction_details = getTransactionDetails($id,$gar_details,$entity_slug="gar");
                $entity_details = $transaction_details['entity_details'];
                $transaction_data = $transaction_details['transaction_data'];
                $documents_details = $transaction_details['documents_details'];
                $added_comment = $transaction_details['added_comment'];
                $user_details = getUserDetails($this->user_id);
               if(isset($user_details) && isset($user_details['roles']) && !empty($user_details['roles'])){
                $trans_permission  = checkRole($user_details['roles'],$entity_details->entity_slug,$transaction_data,$gar_details->created_by);
                } else {
                 $trans_permission ='';
                }
                $is_show = 1;
                $assigne = getAssignee($entity_details->id,$id,$gar_details->status_id);
                //$users = User::select('id','name')->where('deleted_at', null)->where(['users.user_status' => 'Enable'])->orderBy('id','desc')->get();
                // $users = User::select('users.id','users.name')
                //     ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                //     ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                //     ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                //     ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                //     ->whereIn('role_department_mapper.department_id',$user_details['departments_id'])
                //     ->where(['users.user_status' => 'Enable'])
                //     ->where('users.id','!=',$this->user_id)
                //     ->distinct()
                //     ->orderBy('users.id','desc')
                //     ->get();
                // $users_json = json_encode($users);

                return view('gar.show', compact('gar_details','entity_details','transaction_data','documents_details','added_comment','trans_permission','is_show','assigne'));
            }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
        } else {
            Toast::error('You Dont have permssion to Access GAR Master');
            return Redirect::to('/dashboard');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (roleEntityMapping($this->user_id, 'gar', 'can_update')) {
            try {
                $gar_final_status = getStatus('gar');
                $ro_final_status = getStatus('ro');
               // $entity_details = Entity::select('id', 'workflow_id', 'entity_slug','final_status')->where(['deleted_at' => Null, 'type_name' => "GAR"])->first();
                $eas_final_status = getStatus('eas');
               // $users = User::select('name', 'id')->where('deleted_at', null)->where(['users.user_status' => 'Enable'])->orderBy('id', 'desc')->get();

                $gar_bill_type = GARBillType::select('id', 'gar_bill_name')->where('deleted_at', NULL)->orderBy('id', 'asc')->get();
                $gar_details = GAR::select('gar.deducted_gst','eas_masters.department_id','gar.is_ecr_entry','gar.gar_register_entry','gar.gar_pdf','gar.ld_amount','gar.with_held_amount','gar.id','gar.ro_id', 'gar.release_order_amount', 'gar.is_diary_register', 'gar.gar_bill_type', 'gar.amount_paid as amount_to_be_paid','gar.actual_payment_amount', 'gar.copy_to', 'gar.status_id', 'gar.email_users', 'gar.is_dispatch_register', 'gar.tally_entry', 'gar.amount_used_till_date', 'gar.created_by', 'release_order_master.ro_title','release_order_master.status_approved_date', 'release_order_master.id as ro_id', 'eas_masters.vendor_id', 'vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch','budget_list.id as budget_id', 'budget_list.budget_code', 'vendor_master.bank_code', 'vendor_master.bank_acc_no', 'eas_masters.file_number', 'eas_masters.sanction_title','eas_masters.sanction_total', 'diary_register.diary_register_no', 'diary_register.date_of_forwarding', 'status.status_name', 'dispatch_register.dispatch_register_no', 'dispatch_register.date_of_receiving as dispatch_receiving', 'dispatch_register.date_of_forwarding as dispatch_forwarding', 'gar.tds_amount', 'gar.other_amount', 'gar.gst_amount', 'gar.forwarding_letter', 'gar.gst_tds_amount','budget_list.budget_shortcode','gar_register.bill_no','budget_list.amount','budget_list.budget_head_of_acc','gar.tds_deducted_amount')
                ->join('release_order_master', 'release_order_master.id', 'gar.ro_id')
                ->join('eas_masters', 'eas_masters.id', 'release_order_master.eas_id')
                ->join('vendor_master', 'vendor_master.id', 'eas_masters.vendor_id')
                ->leftjoin('diary_register', 'diary_register.gar_id', 'gar.id')
                ->leftjoin('dispatch_register', 'dispatch_register.gar_id', 'gar.id')
                ->join('status', 'status.id', 'gar.status_id')
                ->leftjoin('gar_register', 'gar_register.gar_id', 'gar.id')
                ->join('budget_list', 'budget_list.id', 'eas_masters.budget_code')
                ->where(['gar.id' => $id])->first();
              // dd($gar_details);
                // $ro = ReleaseOrder::select('release_order_master.id','release_order_master.ro_title')
                //     ->where(['release_order_master.deleted_at' => NULL,'release_order_master.status_id' =>$ro_final_status->final_status])->get();
                $current_year = getFinacialYear();
                $explode_result = explode('-', $current_year);
                $from_date = $explode_result[0]; 
                $till_date = $explode_result[1]; 
                // dd($current_year);
                 $budget_head_balance = GAR::select(DB::raw("SUM(release_order_master.release_order_amount) as budget_head_balance"))
                 ->join('release_order_master', 'release_order_master.id', 'gar.ro_id')
                 ->join('eas_masters', 'eas_masters.id', 'release_order_master.eas_id')
                 ->join('budget_list', 'budget_list.id', 'eas_masters.budget_code')
                 ->where(['eas_masters.budget_code'=>$gar_details->budget_id,'gar.deleted_at'=>NULL,'budget_list.from_date'=>$from_date,'budget_list.till_date'=>$till_date])
                ->first();
               
                 $eas_total = GAR::select(DB::raw("SUM(release_order_master.release_order_amount) as eas_total"))
                ->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
                ->join('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
                ->where(['release_order_master.eas_id'=>$gar_details->eas_id,'eas_masters.deleted_at'=>NULL,'release_order_master.status_id'=>$ro_final_status->final_status])
                ->first();
              // dd($eas_total);
                $mail = $gar_details['email_users'];
                $selected_mail_users = explode(",", $mail);
                $is_update_url = 1;
               // $transactions = new CommonController();
                // $transactions_details = $transactions->getApplicableTransaction($gar_details->status_id, $entity_details->workflow_id);
                // if ($transactions_details['body']) {
                //     $transaction_data = $transactions_details['body'];
                // } else {
                //     $transaction_data = " ";
                // }
                $transaction_details = getTransactionDetails($id,$gar_details,$entity_slug="gar");
                $entity_details = $transaction_details['entity_details'];
                $transaction_data = $transaction_details['transaction_data'];
                $documents_details = $transaction_details['documents_details'];
                $added_comment = $transaction_details['added_comment'];
                $user_details = getUserDetails($this->user_id);
                if(isset($user_details) && isset($user_details['roles']) && !empty($user_details['roles'])){
                $trans_permission  = checkRole($user_details['roles'],$entity_details->entity_slug,$transaction_data,$gar_details->created_by);
                } else {
                 $trans_permission ='';
                }

                $dispatch_register_no = DispatchRegister::select('dispatch_register_no')->orderBy('id', 'desc')->first();
                if (isset($dispatch_register_no) && !empty($dispatch_register_no->dispatch_register_no)) {
                    $dispatch_register_no_new = $dispatch_register_no->dispatch_register_no + 1;
                } else {
                    $dispatch_register_no_new = 1;
                }

                $diary_register_no = DiaryRegister::select('diary_register_no')->orderBy('id', 'desc')->first();
                if (isset($diary_register_no) && !empty($diary_register_no->diary_register_no)) {
                    $diary_register_no_new = $diary_register_no->diary_register_no + 1;
                } else {
                    $diary_register_no_new = 1;
                }
              
                if(isset($gar_details) && $gar_details->gar_register_entry == null && $gar_details->bill_no == null)  {
                    
                    $gar_register_no = GarRegister::select('bill_no')->orderBy('created_at', 'desc')->first();
                    if (isset($gar_register_no) && !empty($gar_register_no->bill_no)) {
                        $get_last_no = (ltrim(strstr($gar_register_no->bill_no, '-'), '-'))+1;  
                        $gar_register_bill_no = $gar_details->budget_shortcode.'-'.$get_last_no;
                    } else {
                        $gar_register_bill_no =  $gar_details->budget_shortcode.'-'.'1';
                    }

                } else {

                    $gar_register_bill_no = $gar_details->bill_no;
                }

                $ro = ReleaseOrder::select('release_order_master.id','release_order_master.ro_title')
                ->join('eas_masters','release_order_master.eas_id','=','eas_masters.id')
                ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                ->where(['release_order_master.deleted_at' => NULL,'release_order_master.status_id' =>$ro_final_status->final_status])
                ->get();

                //dd($gar_register_bill_no);
                $role_details = getUserDetails($this->user_id);
                $budget_head_amount = Budget::select('amount')->where(['functional_wing'=>$gar_details->department_id ])->first()->amount;

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
               $copy_to_details = CopyToMaster::select('copy_to_details.master_id','copy_to_details.id','copy_to_details.user_id','copy_to_details.department_id','location.location_name','departments.name as department_name','users.name as user_name')
                ->join('users','users.id','=','copy_to_details.user_id')
                ->join('departments','departments.id','=','copy_to_details.department_id')
                ->join('location','location.id','=','departments.location_id')
                ->where(['master_id'=>$id,'entity_id'=>$entity_details->id])
                ->orderBy('id','asc')->get()->toArray();

                
                return view('gar.add', compact('result', 'gar_details', 'gar_bill_type', 'users', 'is_update_url', 'selected_mail_users','entity_details', 'transaction_data', 'added_comment', 'dispatch_register_no_new','eas_total','budget_head_amount','gar_register_bill_no','budget_head_balance','gar_final_status','diary_register_no_new','list_of_departments','copy_to_details'));
            }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
        } else {
            Toast::error('You Dont have permssion to Access GAR Master');
            return Redirect::to('/dashboard');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // dd($request);
        if (roleEntityMapping($this->user_id, 'gar', 'can_update')) {
            $this->validate($request, [
                //'eas_id' => 'required', 
            'release_order_amount' => 'required',
            'ro_id' => 'required|unique:gar,ro_id,' . $id . ',id,deleted_at,NULL',
            'is_diary_register' => 'required', 'gar_bill_type' => 'required',
            'amount_used_till_date' => 'required', 'amount_to_be_paid' => 'required',
            'gst_amount' => 'required', 
            'tds_amount' => 'required',
             'actual_payment_amount' => 'required','gar_register_entry'=>'required','is_ecr_entry'=>'required']);
            try {
                $user_details = getUserDetails($this->user_id);
                // $workflow = Entity::select('workflow_id')->where(['id' => $request->entity_id, 'deleted_at' => Null])->first();
                // $status_id = WorkflowName::select('default_status')->where(['id' => $workflow->workflow_id])->first();
                if (isset($request->email_users) && !empty($request->email_users)) {
                    $email_mail = $request->email_users;
                    $send_email = implode(",", $email_mail);
                } else {
                    $send_email = '';
                }

                //$requestData = $request->all();
                $gar = GAR::findOrFail($id);

                $update_gar = GAR::where('id', $id)->update(['deducted_gst' => $request->deducted_gst,'ro_id' => $request->ro_id, 'release_order_amount' => $request->release_order_amount, 'gar_bill_type' => $request->gar_bill_type, 'amount_paid' => $request->amount_to_be_paid,'actual_payment_amount' => $request->actual_payment_amount, 'copy_to' => $request->copy_to, 'email_users' => $send_email, 'updated_by' => $this->user_id,'amount_used_till_date' => $request->amount_used_till_date, 'is_diary_register' => $request->is_diary_register, 'gst_amount' => $request->gst_amount, 'tds_amount' => $request->tds_amount, 'other_amount' => $request->other_amount, 'gst_tds_amount' => $request->gst_tds_amount,'ld_amount'=>$request->ld_amount,'with_held_amount'=>$request->with_held_amount]);

                if (isset($request->copy) && !empty($request->copy)) {
 
                        foreach ($request->copy as $key => $value) {
                              
                           $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
                          // dd($create_invoice);
                        }

                      }

                if ($update_gar || $diary_register) {

                     $details = $this->user_details->storeLogDetails($gar->id,$request->entity_id,$request->all());

                    Toast::success('GAR Sucessfully Updated!');
                    return Redirect('gar/'.$id);
                } else {
                    Toast::error('Something went wrong while updating GAR!');
                    return Redirect('gar/'.$id);
                }
            }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
        } else {
            Toast::error('You Dont have permssion to Access GAR Master');
            return Redirect::to('/dashboard');
        }
    }

    public function diaryRegisterEntry(Request $request) {
        try {
        $validator = Validator::make($request->all(), ['gar_id' => 'required|unique:diary_register,gar_id,NULL,id,deleted_at,NULL', ]);
        if ($validator->passes()) {
            $diary_register = DiaryRegister::create(['diary_register_no' => $request->diary_register_no, 'created_by' => $this->user_id,'date_of_receiving' => $request->date_of_receiving, 'date_of_forwarding' => $request->date_of_forwarding, 'gar_id' => $request->gar_id]);
            $update_diary_register = GAR::where('id', $request->gar_id)->update(['is_diary_register' => 1]);
            if ($diary_register && $update_diary_register) {
                $data['code'] = 200;
                $data['message'] = "Diary Register Entry Successfully Added.";
            } else {
                $data['code'] = 204;
                $data['message'] = 'Something Went Wrong while adding Diary Register Entry.';
            }
        } else {
            $data['code'] = 204;
            $data['message'] = $validator->errors()->first();
        }
        return $data;
        } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
    }

    public function dispachRegisterEntry(Request $request) {
     try {
        $validator = Validator::make($request->all(), ['gar_id' => 'required|unique:dispatch_register,gar_id,NULL,id,deleted_at,NULL']);

        if ($validator->passes()) {
            $dispatch_register = DispatchRegister::create(['dispatch_register_no'=>$request->dispatch_register_no,'created_by' => $this->user_id,'date_of_receiving' => $request->date_of_receiving, 'date_of_forwarding' => $request->date_of_forwarding, 'gar_id' => $request->gar_id]);
            $update_dispatch_register = GAR::where('id', $request->gar_id)->update(['is_dispatch_register' => 1]);
            if ($dispatch_register && $update_dispatch_register) {
                $data['code'] = 200;
                $data['message'] = "Dispatch Register Entry Successfully Added.";
            } else {
                $data['code'] = 204;
                $data['message'] = 'Something Went Wrong while adding Dispatch Register Entry.';
            }
        } else {
            $data['code'] = 204;
            $data['message'] = $validator->errors()->first();
        }
        return $data;
        } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
    }

    public function tallyEntry(Request $request) {
      try {
        $update_tally = GAR::where('id', $request->gar_id)->update(['tally_entry' => $request->tally_entry]);
        if ($update_tally) {
            $data['code'] = 200;
            $data['message'] = "Tally Entry Successfully Added.";
        } else {
            $data['code'] = 204;
            $data['message'] = 'Something Went Wrong while adding Tally Entry.';
        }
        return $data;
        } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
        }
    }

    public function garRegisterEntry(Request $request) {
       try {
        $validator = Validator::make($request->all(), [
        'bill_no'   => 'required|unique:gar_register,bill_no,NULL,id,deleted_at,NULL',
        'gar_id'   => 'required|unique:gar_register,gar_id,NULL,id,deleted_at,NULL',
        ]);

         if ($validator->passes()) {

            $create = GarRegister::create(['budget_head' => $request->budget_head,'bill_no' => $request->bill_no,'gar_id' => $request->gar_id,'date_of_issue' => $request->date_of_issue,'created_by'=>$this->user_id]);
            $update_gar = GAR::where('id', $request->gar_id)->update(['gar_register_entry' => $request->gar_register_entry]);

            if (isset($create) &&  isset($update_gar)) {
                $data['code'] = 200;
                $data['message'] = "GAR Register Entry Successfully Added.";
            } else {
                $data['code'] = 204;
                $data['message'] = 'Something Went Wrong while adding GAR Register Entry.';
            }
         } else {
            $data['code'] = 204;
            $data['message'] = $validator->errors()->first();
         }

          return response()->json($data);
        } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
    }
    
    public function garEcRegisterEntry(Request $request) {
       try {
        $validator = Validator::make($request->all(), [
        'gar_id'   => 'required|unique:ec_register,gar_id,NULL,id,deleted_at,NULL',
         'nature_of_expense'   => 'required'
        ]);

         if ($validator->passes()) {

            $create = EcRegister::create(['budget_head' => $request->budget_head,'bill_no' => $request->bill_no,'gar_id' => $request->gar_id,'nature_of_expense'=>$request->nature_of_expense,'date_of_er_issue' => $request->date_of_issue,'created_by'=>$this->user_id,'budget_head_amount'=>$request->budget_head_amount,'budget_head_balance'=>$request->budget_head_balance]);

            $update_gar = GAR::where('id', $request->gar_id)->update(['is_ecr_entry' => $request->is_ecr_entry]);

            if (isset($create) &&  isset($update_gar)) {
                $data['code'] = 200;
                $data['message'] = "GAR Register Entry Successfully Added.";
            } else {
                $data['code'] = 204;
                $data['message'] = 'Something Went Wrong while adding GAR Register Entry.';
            }
         } else {
            $data['code'] = 204;
            $data['message'] = $validator->errors()->first();
         }
        
       return response()->json($data);
        } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
        }
    }

     /**
     * PAO Approved GAR then they have to Upload Cheque  
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
     * @return json response for GAR list.
     */

    // public function uploadCheque(Request $request) {

    //     $validator = Validator::make($request->all(), [
    //     'cheque_number' => 'required','cheque_payment_amount' => 'required','payment_mode' => 'required',
    //     'file_upload' => 'required|max:10240|mimes:pdf,png,jpeg,jpg,xls,xlsx,doc,docx', 'gar_id' => 'required']);
    //     if ($validator->passes()) {
    //         // $vendor_details = $this->getVendorDetails($request->gar_id);
    //         // if (isset($vendor_details) && !empty($vendor_details)) {
    //            // $forwarding_letter = generatePdf($vendor_details, $pdf_type = 'forwarding_letter',$storage_path = 'forwarding_letter');
    //             // dd($forwarding_letter);
    //            // if (isset($forwarding_letter) && !empty($forwarding_letter)) {
    //                 $file_upload = $request->file_upload;
    //                 $year = date('Y');
    //                 $month = date('m');
    //                 $path = public_path() . "/cheque/" . $year . "/" . $month;
    //                 if (!is_dir($path)) {
    //                     mkdir($path, 0777, true);
    //                 }
    //                 $original_file_name = strtolower(str_replace([' ', '_'], '-', $file_upload->getClientOriginalName()));
    //                 $documents = "cheque/" . $year . "/" . $month . "/" . time() . "_" . $original_file_name;
    //                 $file_upload->move($path, $documents);
    //                 if (file_exists($path . '/' . time() . "_" . $original_file_name)) {
    //                     $upload_cheque = GAR::where('id', $request->gar_id)->update(['uploaded_cheque' => $documents,'cheque_number'=>$request->cheque_number,'cheque_payment_amount'=>$request->cheque_payment_amount,'payment_mode'=>$request->payment_mode]);
                       
    //                 } else {
    //                     $data['code'] = 204;
    //                     $data['message'] = "Cheque Uploading Failed.";
    //                 }
    //                 if (isset($upload_cheque) && !empty($upload_cheque)) {
    //                     $data['code'] = 200;
    //                     $data['message'] = "Cheque uploaded Successfully.";
    //                 } else {
    //                     $data['code'] = 204;
    //                     $data['message'] = "Something Went Wrong.";
    //                 }
    //             // } else {
    //             //     $data['code'] = 204;
    //             //     $data['message'] = "Forwarding letter not generated.";
    //             // }
    //         // } else {
    //         //     $data['code'] = 204;
    //         //     $data['message'] = "Vendor details Not found.";
    //         // }
    //     } else {
    //         $data['code'] = 204;
    //         $data['message'] = $validator->errors();
    //     }
    //     return response()->json($data);
    // }
    
    public function getVendorDetails($gar_id) {
      try {
        $vendor_details = GAR::select('gar.id', 'vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch', 'vendor_master.bank_code', 'vendor_master.bank_acc_no', 'gar.actual_payment_amount')
        ->join('release_order_master','release_order_master.id', '=', 'gar.ro_id')
        ->join('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
        ->join('vendor_master', 'eas_masters.vendor_id', '=', 'vendor_master.id')->where(['vendor_master.deleted_at' => NULL, 'gar.deleted_at' => NULL, 'gar.id' => $gar_id])->first()->toArray();
        return $vendor_details;
         } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
    }

    public function generateChequeListView()
    {
    if(roleEntityMapping($this->user_id,'cheque','can_create')) {

       $gar_final_status = getStatus('gar');
       $perPage = 25;
       $user_details = getUserDetails($this->user_id);
        if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){

        $gar_list = GAR::select('gar.id as gar_id', 'gar.amount_paid', 'vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch', 'vendor_master.bank_code', 'vendor_master.bank_acc_no', 'gar.actual_payment_amount','release_order_master.ro_title','status.status_name','gar.created_at')
        ->join('release_order_master','release_order_master.id', '=', 'gar.ro_id')
        ->join('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
        ->join('vendor_master', 'eas_masters.vendor_id', '=', 'vendor_master.id')
        ->join('status','status.id','=','gar.status_id')
         ->join('departments','departments.id','=','eas_masters.department_id')
        ->where(['vendor_master.deleted_at' => NULL, 'gar.deleted_at' => NULL,'gar.cheque_id' => NULL,'gar.status_id'=>$gar_final_status->final_status])
        ->whereIn('eas_masters.department_id',$user_details['departments_id'])
        ->latest()->paginate($perPage);
        }
        // dd($gar_list);

        return view('gar.cheque-upload',compact('gar_list'));
         // } catch(\Exception $e) {
         //        Log::critical($e->getMessage());
         //        app('sentry')->captureException($e);
         //        Toast::error('Something went wrong!');
         //        return Redirect('gar');
         //    }

        } else {
        Toast::error('You are not Authorized to perform this Action.');
        return Redirect::to('/dashboard');
        } 
    }

    public function getGARDetails(Request $request)
    {
        try {
        $gar_id = $request['gar_id'];
        // print_r($gar_id);

        foreach ($gar_id as $key => $value) {
            // print_r($value);
            $gar_list[] = GAR::select('gar.id as gar_id', 'gar.amount_paid', 'vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch', 'vendor_master.bank_code', 'vendor_master.bank_acc_no', 'gar.actual_payment_amount','release_order_master.ro_title','status.status_name')
            ->join('release_order_master','release_order_master.id', '=', 'gar.ro_id')
            ->join('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
            ->join('vendor_master', 'eas_masters.vendor_id', '=', 'vendor_master.id')
            ->join('status','status.id','=','gar.status_id')
            ->where(['vendor_master.deleted_at' => NULL, 'gar.deleted_at' => NULL, 'gar.id' => $value])->first();
        }

        if (count($gar_list) > 0) {
            $data['code'] = 200;
            $data['message'] = "GARs Found";
            $data['data'] = $gar_list;
        } else {
            $data['code'] = 204;
            $data['message'] = "GARs NOT Found";
        }
         return $data;
        // print_r($gar_list[0]['mobile_no']);exit;
          } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }
    }

    public function generateCheque(Request $request)
    {
        if(roleEntityMapping($this->user_id,'cheque','can_create')) {
        try {
        if (isset($request)) {
            $this->validate($request, [
                'cheque_name' => 'required', 
                'cheque_number' => 'required',
                'cheque_date' => 'required',
                'cheque_amount' => 'required',
                'selected_gar_id' => 'required',
                'file_upload' => 'required']);

            try
            {
                $date = str_replace('/', '-', $request->cheque_date);
                $cheque_date = date('Y-m-d', strtotime($date));
                // dd($cheque_date);
                $gar_id = explode(",", $request->selected_gar_id);
                // dd(strtotime($request->cheque_date));
                // $cheque_date = date('Y-m-d',strtotime($request->cheque_date));
                // dd($cheque_date);
                // dd($request->file_upload->getClientOriginalName());
                $file_upload = $request->file_upload;
                $year = date('Y');
                $month = date('m');
                $path = storage_path() . "documents/cheque/" . $year . "/" . $month;
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $original_file_name = strtolower(str_replace([' ', '_'], '-', $file_upload->getClientOriginalName()));
                $documents = "cheque/" . $year . "/" . $month . "/" . time() . "_" . $original_file_name;
                $file_upload->move($path, $documents);

                $add_cheque = Cheque::create(['cheque_name' => $request->cheque_name, 'cheque_date' => $cheque_date, 'cheque_amount' => $request->cheque_amount, 'cheque_number' => $request->cheque_number, 'cheque_amount' => $request->cheque_amount, 'file_path' => $documents, 'created_by' => $this->user_id]);

                // dd($add_cheque);

                if (isset($add_cheque)) {
                    foreach ($gar_id as $key => $value) {
                        $add_cheque_no = GAR::where('id', $value)->update(['cheque_id' => $add_cheque->id]);
                        if ($add_cheque_no != 1) {
                            $remove_cheque = Cheque::where('id',$add_cheque->id)->delete();
                            Toast::error('Cheque is uploaded but not Mapped with GAR so try again.');
                            return Redirect::to('/upload-cheque');
                        }
                    }

                    if ($add_cheque_no == 1) {
                        Toast::success('Cheque created Successfully.');
                        return Redirect::to('/list-cheque');
                    }

                    // $add_cheque_no = GAR::update()
                }
            }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                return redirect('/upload-cheque')->with('danger', 'Something went wrong!');
            }
        } else {
            Toast::error('Please fill all the mandatory details');
            return Redirect::to('/upload-cheque');
            }
     } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }

            } else {
    Toast::error('You are not Authorized to perform this Action.');
    return Redirect::to('/dashboard');
} 
    }

    public function listCheque()
    {

        if(roleEntityMapping($this->user_id,'cheque','can_view')) {
        //try {
          $cheques = Cheque::select('cheque_master.id','cheque_master.cheque_name','cheque_master.file_path','cheque_master.cheque_number','cheque_master.cheque_amount','cheque_master.cheque_date','cheque_master.forwarding_letter_id','eas_masters.sanction_title','release_order_master.ro_title','departments.name as department_name','location.location_name')
          ->join('gar','cheque_master.id','=','gar.cheque_id')
          ->join('release_order_master','release_order_master.id','=','gar.ro_id')
          ->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
          ->join('departments','departments.id','=','eas_masters.department_id')
           ->join('location','location.id','=','departments.location_id')
          ->where(['cheque_master.deleted_at'=>NULL])->orderBy('cheque_master.id','desc')->get(); 

          return view('gar.list-cheque',compact('cheques'));
         // } catch(\Exception $e) {
         //        Log::critical($e->getMessage());
         //        app('sentry')->captureException($e);
         //        Toast::error('Something went wrong!');
         //        return Redirect('gar');
         //    }

          } else {
    Toast::error('You are not Authorized to perform this Action.');
    return Redirect::to('/dashboard');
} 


    }

    public function deleteCheque(Request $request)
    {
        if(roleEntityMapping($this->user_id,'cheque','can_delete')) {

        try {
            
        if(isset($request->id) && !empty($request->id)) {
            $id = $request->id;

            $remove_cheque = Cheque::where('id',$id)->delete();

            $remove_cheque_from_gar = GAR::where('cheque_id', $id)->update(['cheque_id' => NULL]);
            if(isset($remove_cheque) && !empty($remove_cheque)) {
                $data['code'] = 200;              
                $data['message'] = "Data deleted Successfully !";

            } else {
                $data['code'] = 204;              
                $data['message']= "Data not deleted!";
            }

        } else {
            $data['code'] = 204;              
            $data['message']= "Cheque not found!";
        }
         return $data;
          } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
                return Redirect('gar');
            }

        } else {
        Toast::error('You are not Authorized to perform this Action.');
        return Redirect::to('/dashboard');
        }  
	}

    public function downloadGarPdf($id)
    { 
        //try {

         $file_name = GAR::select('gar_pdf')
                ->where('id',$id)
                ->first();
       // dd($file_name );
        return response()->download(storage_path("documents/{$file_name->gar_pdf}"));
       //  } catch (\Exception $e) {
            
       //       Log::critical($e->getMessage());
       //       app('sentry')->captureException($e);
       //       Toast::error('Something went wrong!');
       //       return redirect('/dashboard'); 
       // }  
    }

       public function downloadCheque($id)
    { 
        try {

           $file_name = Cheque::select('file_path')
                ->where('id',$id)
                ->first();
        //dd($file_name->file_path);
        return response()->download(storage_path("documents/{$file_name->file_path}"));
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect()->back();
       }  
    }


}
