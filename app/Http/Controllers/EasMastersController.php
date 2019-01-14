<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  EasMasters
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\EasMastersController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Eas;
use App\Vendor;
use App\User;
use App\Department;
use App\Role;
use App\Entity;
use App\Transaction;
use App\WorkflowName;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Auth;
use Toast;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;
use DB;
use App\RejectComment;
use App\EasLog;
use App\Budget;
use App\ItemDetails;
use App\CopyToMaster;
/**
 * This class provides a all operation to manage the Expenditure Angle Sanction(EAS) data.
 *
 * The EasMastersController is responsible for managing the basic details require for genarating the EAS report.
 * 
 */
class EasMastersController extends Controller
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
     * Display a listing of the Expenditure Angle Sanction(EAS),with Sanction title, Purpose of Sanction etc. Also provide actions to edit,delete.
     *
     * @return \Illuminate\Http\Response

     * This Function Provide the list of all Expenditure Angle Sanction(EAS) Data.
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
     * @return json response for Expenditure Angle Sanction(EAS) list.
     
     */
    public function index(Request $request)
    {  

    if(roleEntityMapping($this->user_id,'eas','can_view')) {
       //
            //$role_details=getUserDetails($this->user->role_id);
           // if(isset($role_details) && $role_details['status'] == 200 ) {
        try { 
                $keyword = $request->get('search');
                 $perPage = 25;
                    if (!empty($keyword)) {

                        $eas_masters = Eas::where('sanction_title', 'LIKE', "%$keyword%")
                            //->orWhere('sanction_purpose', 'LIKE', "%$keyword%")
                            ->orWhere('competent_authority', 'LIKE', "%$keyword%")
                            ->orWhere('department_name', 'LIKE', "%$keyword%")
                            ->orWhere('file_number', 'LIKE', "%$keyword%")
                            ->orWhere('sanction_total', 'LIKE', "%$keyword%")
                            ->orWhere('budget_code', 'LIKE', "%$keyword%")
                            ->orWhere('validity_sanction_period', 'LIKE', "%$keyword%")
                      
                            ->latest()->paginate($perPage);
                    } else {
         
                $user_details = getUserDetails($this->user_id);
                       
                if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){

                    //dd($user_details);
                  // foreach ($user_details['departments_id'] as $value) {
                   // dd($value['id']);
                         $eas_masters = Eas::select('eas_masters.status_id','eas_masters.created_at','eas_masters.file_number','eas_masters.sanction_total','budget_list.budget_code','eas_masters.validity_sanction_period','eas_masters.id','eas_masters.sanction_title','eas_masters.competent_authority','vendor_master.vendor_name','status.status_name','eas_masters.vendor_id','departments.name as department_name')
                        ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                        ->join('departments','departments.id','=','eas_masters.department_id')
                        ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
                        ->join('status','status.id','=','eas_masters.status_id')
                        ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                        ->where(['eas_masters.deleted_at'=> Null,'vendor_master.deleted_at'=>Null,'status.deleted_at'=>Null,'budget_list.deleted_at'=>Null])
                        ->orderBy('eas_masters.id','desc')
                        ->latest()->paginate($perPage);

                       // dd($eas_masters);
                       
                        $entity_details = getStatus('eas');
                       
                  // }
                    
                   }
                   
                  // dd($eas_masters);
                    if(isset($eas_masters) && !empty($eas_masters)) { 
                       
                        return view('/eas.index', compact('eas_masters','entity_details'));
                    } else {
                        
                        return view('/eas.index')->with('danger','Data not found');
                    }
                }    
                  

                } catch (\Exception $e) {
                 Log::critical($e->getMessage());
                 app('sentry')->captureException($e);
                 Toast::error('Something went wrong!');
                 return redirect('/dashboard'); 
            }  
                  
        } else {
           
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/dashboard');
        }
     
    }
    
    /**
     * Show the form for creating a new EAS.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    {
        if(roleEntityMapping($this->user_id,'eas','can_create')) {
            
        try{
                $vendor_name = Vendor::select('id','vendor_name')->where(['deleted_at' => Null, 'vendor_status' => 'Enable'])->get();
                $entity_details = getStatus('eas');
                $last_id = EAS::select('id')->where(['deleted_at' => Null])->orderBy('id','desc')->first();
               // $year = date('Y');
               // $month = date('m');
                if(isset($last_id) && !empty($last_id)) {
                    $id = $last_id['id']+1;
                } else {
                   $id = 1;
               }

               //$year = getFinacialYear();
               // if(isset($month) && $month > 3) {

               //      $year = $year . "-" . $year+=1 ;

               //  } else {

               //      $year=$year-1 . "-" . $year ;
               //  }
            

            $year = getFinacialYear();
            if(isset($year) && !empty($year)){
            $explode_result = explode('-',$year);
            $from_date = $explode_result[0]; 
            $till_date = $explode_result[1];
            } else {
            //$explode_result = explode('-',$year);
            $from_date = ''; 
            $till_date = '';
            } 

            $user_details = getUserDetails($this->user_id);

            if(isset($user_details['departments_id']) && !empty($user_details['departments_id'])) {
               
            $budget_code = Budget::select('budget_code','budget_head_of_acc','id','amount','oh')
                ->where(['from_date'=>$from_date,'till_date'=>$till_date])
                //->whereIn('functional_wing',$user_details['departments_id'])
                ->get()->toArray();
            }
             $users = User::select('users.id','users.name')
                ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                ->whereIn('role_department_mapper.department_id',$user_details['departments_id'])
                ->where(['users.user_status' => 'Enable'])
                ->where('users.id' ,'!=' ,$this->user_id)
                ->distinct()
                ->orderBy('users.id','desc')
                ->get();
             if (isset($user_details) && !empty($user_details) && isset($user_details['departments']) &&  !empty($user_details['departments']) ) {

                $list_of_departments = $user_details['departments'];
             }
                // if (isset($user_details) && !empty($user_details) && isset($user_details['departments']) &&  count($user_details['departments']) > 1) {
                   
                //    $list_of_departments = $user_details['departments'];
                //     $serial_no_of_sanction = '';
                //     $fa_number = '';
                //     $last_id = $last_id['id']+1;
                // } else if (isset($user_details) && !empty($user_details) && isset($user_details['departments']) && count($user_details['departments']) == 1) {
                //     $list_of_departments = $user_details['departments'];
                //     $serial_no_of_sanction = $user_details['departments'][0]['slug'].'/'.$month.'/'.$year.'/'.$id;
                //     $fa_number = 'F.NO.'.$month.'/'.$year.'/'.$id.'-'.$user_details['departments'][0]['slug'];
                // } else {
                //     $serial_no_of_sanction = '';
                //     $fa_number = '';
                // }
                $is_create=1;

            return view('/eas.create',compact('vendor_name','entity_details','is_create','budget_code','list_of_departments','last_id','users'));

            } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
            return redirect('/eas');
            }  
        } else {
           Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/eas');
        }     
    }

    /**
     * Store a newly created EAS in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Expenditure Angle Sanction(EAS) Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Expenditure Angle Sanction(EAS) list.
     * 
     * @var array[] $eas_masters Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $user_details is used to store list of data which include location id, officetype id, department id of loged in user.
     * @var array[] $eas_details to fetch data from request
     * 
     */
    public function store(Request $request)
    {
    //dd($request);
       if(roleEntityMapping($this->user_id,'eas','can_create')) {
      
               if($request->whether_being_issued_under == 'fa_concurrence')
           {
            $this->validate($request,[
                'department'=>'required',
                'sanction_title'   => 'required|unique:eas_masters,sanction_title,NULL,id,deleted_at,NULL',
                'sanction_purpose'    => 'required',
                'competent_authority'   => 'required',
                'serial_no_of_sanction' =>'required|unique:eas_masters,serial_no_of_sanction,NULL,id,deleted_at,NULL',
                'file_number'=>'required|unique:eas_masters,file_number,NULL,id,deleted_at,NULL',
                'sanction_total'   => 'required',
                'budget_code'     => 'required',
                'validity_sanction_period'=> 'required',
                'date_issue'   => 'required',
                'vendor_id' => 'required',
                'cfa_note_number' =>'required|unique:eas_masters,cfa_note_number,NULL,id,deleted_at,NULL',
                'cfa_dated' => 'required',
                'cfa_designation' =>'required', 
                'fa_number' => 'required|unique:eas_masters,fa_number,NULL,id,deleted_at,NULL',
                'fa_dated' => 'required'
            ]);
            } else { 
                // dd($request);
             $this->validate($request,[
               
                'department'=>'required',
                'sanction_title'   => 'required|unique:eas_masters,sanction_title,NULL,id,deleted_at,NULL',
                'sanction_purpose'    => 'required',
                'competent_authority'   => 'required',
                'serial_no_of_sanction' =>'required|unique:eas_masters,serial_no_of_sanction,NULL,id,deleted_at,NULL',
                'file_number'=>'required|unique:eas_masters,file_number,NULL,id,deleted_at,NULL',
                'sanction_total'   => 'required',
                'budget_code'     => 'required',
                'validity_sanction_period' => 'required',
                'date_issue'   => 'required',
                'vendor_id' => 'required',
                'cfa_note_number' =>'required|unique:eas_masters,cfa_note_number,NULL,id,deleted_at,NULL',
                'cfa_dated' => 'required',
                'cfa_designation' =>'required'
            ]);
            }
            

         try { 
        // $requestData = $request->all();
        //$user_details=getUserDetails($this->user_id);
          //  $entity_id = $request->entity_id;

        // $budget_code = Budget::select('amount')
        //         ->where(['id'=>$request->budget_code])
        //         ->first()->amount; 

        // if($request->sanction_total < $budget_code) {
             
            $entity_details = getStatus('eas');
            $get_department_details = $this->getDepartmentDetails($request->department);
            if(isset($get_department_details) && !empty($get_department_details) && $get_department_details['code'] == 200) {
            $serial_no_of_sanction =  $get_department_details['serial_no_of_sanction'];
            $file_number=  $get_department_details['file_number'];
            } else {
            $serial_no_of_sanction = $request->serial_no_of_sanction; 
            $file_number=$request->file_number;
            }  

                    $eas_details = Eas::create([
                    'sanction_title'=>$request->sanction_title,
                    'sanction_purpose'=>trim($request->sanction_purpose),
                    'competent_authority'=>$request->competent_authority,
                    'serial_no_of_sanction'=>$serial_no_of_sanction,
                    'file_number'=>$file_number,
                    'sanction_total'=>$request->sanction_total,
                    'budget_code'=>$request->budget_code,
                    'validity_sanction_period'=>$request->validity_sanction_period,
                    'date_issue'=>$request->date_issue,
                    'vendor_id'=>$request->vendor_id,
                    'cfa_note_number'=>$request->cfa_note_number,
                    'cfa_dated'=>$request->cfa_dated,
                    'cfa_designation'=>$request->cfa_designation,
                    'whether_being_issued_under' => $request->whether_being_issued_under,
                    'fa_number'=>$request->fa_number,
                    'fa_dated'=>$request->fa_dated,
                    'department_id' =>$request->department,
                    'status_id' => $entity_details->default_status,
                    'created_by'=>$this->user_id,'copy_to'=>$request->copy_to]);

                   // dd($eas_details);

                    if(isset($eas_details) && !empty($eas_details))  {

                    if (isset($request->item) && !empty($request->item)) {
 
                        foreach ($request->item as $key => $value) {
                            if (!empty($value['unit_price_tax']) && !empty($value['item']) && !empty($value['category']) && !empty($value['qty']) && !empty($value['total_unit_price_tax'])) {
                              
                           $create_invoice = ItemDetails::create(['eas_id'=>$eas_details->id,'category'=>$value['category'],'item'=>$value['item'],'qty'=>$value['qty'],'unit_price_tax'=>$value['unit_price_tax'],'total_unit_price_tax'=>$value['total_unit_price_tax'] ]);
                       }
                          // dd($create_invoice);
                        }

                      }

                       if (isset($request->copy) && !empty($request->copy)) {
 
                        foreach ($request->copy as $key => $value) {
                              
                           $create_invoice = CopyToMaster::create(['entity_id'=>$entity_details->id,'master_id'=>$eas_details->id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
                          // dd($create_invoice);
                        }

                      }
                     
                     $details = $this->user_details->storeLogDetails($eas_details->id,$request->entity_id,$request->all());
                    

                    if (isset($request->documents_type) && !empty($request->documents_type) && isset($request->file_upload) && $request->hasFile('file_upload')) {
                        $uploadDocuments = $this->user_details->uploadDocuments($request->file_upload,$request->documents_type,$eas_details->id,$this->user_id,$request->entity_id,$request->entity_slug,$request->file_number);
                        
                        if(isset($uploadDocuments) && !empty($uploadDocuments) && $uploadDocuments['code'] == 200)  {
                            
                        Toast::success('Eas added successfully!');
                        return redirect('/eas');
                        } else{
                          Toast::error('Something went wrong while file upload!');
                         return redirect('/eas');
                        }
                    } else {
                        if(isset($eas_details) && !empty($eas_details))  {
                            
                        Toast::success('Eas added successfully!');
                        return redirect('/eas');
                        } else{
                          Toast::error('Something went wrong while adding data!');
                         return redirect('/eas');
                        }
                    }

                    } else {
                         Toast::error('Something went wrong!');
                         return redirect('/eas');
                    }
            // } else {
            //    Toast::error('Eas sanction total amount not greater than Budget Head amount.');
            //   return redirect()->back();
            // }        
                // } else {
                //     Toast::error('Role not found');
                //     return redirect('/eas');
                // } 

            } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);

              Toast::error('Something went wrong!');
              return redirect('/eas');
            }

        } else {
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/eas');
        }
    }

     /**
     * Show a view of  created EAS 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Display the specified EAS in readonly format against particular record
     * Pseudo Steps:<br>
     * 1)  Create view structure to display record of selected Id <br>
     * 2) Fetch records from Model and store in a variable and pass it to  a View<br>
     * 3) Foreach to fetch record
     * @param  int  $id
     * @var string $eas_master To select record from table 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(roleEntityMapping($this->user_id,'eas','can_view')) {
          try {
                //$eas_master = Eas::findOrFail($id);
                $eas_master = Eas::select('eas_masters.eas_pdf','eas_masters.cfa_designation','departments.name as department_name','eas_masters.department_id','eas_masters.created_by','eas_masters.status_id','eas_masters.id','eas_masters.sanction_title','eas_masters.sanction_purpose','eas_masters.competent_authority','eas_masters.serial_no_of_sanction','eas_masters.file_number','eas_masters.sanction_total','eas_masters.date_issue','eas_masters.serial_no_of_sanction','eas_masters.cfa_note_number','eas_masters.cfa_dated','eas_masters.fa_number','eas_masters.fa_dated','status.status_name','budget_list.budget_code','eas_masters.whether_being_issued_under','vendor_master.vendor_name','vendor_master.email','vendor_master.mobile_no','vendor_master.bank_name','vendor_master.bank_branch','vendor_master.bank_acc_no','vendor_master.ifsc_code','vendor_master.bank_code','eas_masters.validity_sanction_period','eas_masters.vendor_id','departments.name as department_name','users.name as assignee','budget_list.id as budget_list_id', 'budget_list.budget_head_of_acc')
                        ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                        ->join('status','status.id','=','eas_masters.status_id')
                        ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
                        ->leftjoin('assignee_mapper','assignee_mapper.master_id','=','eas_masters.id')
                        ->leftjoin('users','assignee_mapper.assignee','=','users.id')
                        ->leftjoin('departments','eas_masters.department_id' ,'=', 'departments.id')
                        ->where('eas_masters.id' ,'=',$id)
                        ->where(['eas_masters.deleted_at'=> Null,'status.deleted_at'=>Null,'budget_list.deleted_at'=>NULL])
                        ->orderBy('assignee_mapper.id','desc')
                       // ->where(['eas_masters.location_id'=>$role_details['location_id'],'eas_masters.department_id'=>$role_details['department_id'],'eas_masters.office_type_id'=>$role_details['office_type_id']])
                        ->first();
               // dd($eas_master);
                $transaction_details = getTransactionDetails($id,$eas_master,$entity_slug="eas");
                $entity_details = $transaction_details['entity_details'];
                $transaction_data = $transaction_details['transaction_data'];
                $documents_details = $transaction_details['documents_details'];
                $added_comment = $transaction_details['added_comment'];
                $user_details = getUserDetails($this->user_id);
                if(isset($user_details) && isset($user_details['roles']) && !empty($user_details['roles'])){
                $trans_permission  = checkRole($user_details['roles'],$entity_details->entity_slug,$transaction_data,$eas_master->created_by);
                } else {
                 $trans_permission = '';
                }    
                $is_show = 1;   
                $assigne = getAssignee($entity_details->id,$id,$eas_master->status_id);

                $users = User::select('users.id','users.name')
                ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                ->where('users.id','!=',$this->user_id)
                ->where(['departments.id'=>$eas_master->department_id,'users.user_status' => 'Enable','users.deleted_at'=>NULL])
                ->where('users.id','!=',$this->user_id)
                //->where(['departments.id'=>$eas_master->department_id,'users.user_status' => 'Enable','roles.name'=>'ADG'])
                ->orderBy('users.id','desc')
                ->get();

                $users_json = json_encode($users);

                $item_details = ItemDetails::select('id','eas_id','category','item','qty','unit_price_tax','total_unit_price_tax')->where(['deleted_at'=>NULL,'eas_id'=>$id])->orderBy('id','asc')->get()->toArray();  

               $copy_to_details = getCopyToDetails($id,$entity_details->id); 

        return view('/eas.show', compact('eas_master','documents_details','transaction_data','trans_permission','added_comment','entity_details','is_show','assigne','users_json','item_details','copy_to_details'));

            } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
              return redirect('/eas');
            } 

        } else {
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/eas');
        }    
    }

     /**
     * Show the form for editing the specified EAS.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $eas_master retrieve data from Model
     * @var string $vendor_name retrieve vendor data from Model
     */

    
    public function edit($id)
    {  
        if(roleEntityMapping($this->user_id,'eas','can_update')) {

             try {
                 $user_details = getUserDetails($this->user_id);
                //  $year = date('Y');
                //  $month = date('m');

                // if(isset($month) && $month > 3) {

                //     $year=$year . "-" . $year+=1 ;

                // } else {

                //     $year=$year-1 . "-" . $year ;
                // }

                  $year = getFinacialYear();
                    if(isset($year) && !empty($year)){
                    $explode_result = explode('-',$year);
                    $from_date = $explode_result[0]; 
                    $till_date = $explode_result[1];
                    } else {
                    //$explode_result = explode('-',$year);
                    $from_date = ''; 
                    $till_date = '';
                 } 
                
                $budget_code = Budget::select('budget_code','budget_head_of_acc','id','amount','oh')
                    ->where(['from_date'=>$from_date,'till_date'=>$till_date])
                   // ->whereIn('functional_wing',$user_details['departments_id'])
                    ->get()->toArray();
                
                //$eas_master = Eas::findOrFail($id);
                $eas_master = Eas::select('eas_masters.id','eas_masters.copy_to','eas_masters.cfa_designation','eas_masters.vendor_id','eas_masters.fa_number','eas_masters.fa_dated','eas_masters.validity_sanction_period','departments.slug as department_slug','departments.name as department_name','eas_masters.department_id','eas_masters.created_by','eas_masters.status_id','eas_masters.id','eas_masters.sanction_title','eas_masters.sanction_purpose','eas_masters.competent_authority','eas_masters.serial_no_of_sanction','eas_masters.file_number','eas_masters.sanction_total','eas_masters.date_issue','eas_masters.serial_no_of_sanction','eas_masters.cfa_note_number','eas_masters.cfa_dated','status.status_name','budget_list.budget_code','eas_masters.whether_being_issued_under','vendor_master.vendor_name','vendor_master.email','vendor_master.mobile_no','vendor_master.bank_name','vendor_master.bank_branch','vendor_master.bank_acc_no','vendor_master.ifsc_code','vendor_master.bank_code','budget_list.id as budget_list_id')
                        ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                        ->join('status','status.id','=','eas_masters.status_id')
                        ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
                        ->join('departments','eas_masters.department_id' ,'=', 'departments.id')
                        ->where('eas_masters.id' ,'=',$id)
                        ->where(['eas_masters.deleted_at'=> Null,'status.deleted_at'=>Null,'budget_list.deleted_at'=>NULL])
                       // ->where(['eas_masters.location_id'=>$role_details['location_id'],'eas_masters.department_id'=>$role_details['department_id'],'eas_masters.office_type_id'=>$role_details['office_type_id']])
                        ->first();
                    // dd($eas_master);
               
                $vendor_name = Vendor::select('id','vendor_name')->where(['deleted_at' => Null,'vendor_status' => 'Enable'])->get();
               
                $transaction_details = getTransactionDetails($id,$eas_master,$entity_slug="eas");
                $entity_details = $transaction_details['entity_details'];
                $transaction_data = $transaction_details['transaction_data'];
                $documents_details = $transaction_details['documents_details'];
                $added_comment = $transaction_details['added_comment'];

                $copy_to_details = CopyToMaster::select('copy_to_details.master_id','copy_to_details.id','copy_to_details.user_id','copy_to_details.department_id','location.location_name','departments.name as department_name','users.name as user_name')
                ->join('users','users.id','=','copy_to_details.user_id')
                ->join('departments','departments.id','=','copy_to_details.department_id')
                ->join('location','location.id','=','departments.location_id')
                ->where(['master_id'=>$id,'entity_id'=>$entity_details->id])
                ->orderBy('id','asc')->get()->toArray();
            
                $item_details = ItemDetails::select('id','eas_id','category','item','qty','unit_price_tax','total_unit_price_tax')->where(['deleted_at'=>NULL,'eas_id'=>$id])->orderBy('id','asc')->get()->toArray();
                 
               // $user_details = getUserDetails($this->user_id);

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
               $is_update_url = 1;

               return view('/eas.edit', compact('eas_master','vendor_name','entity_details','transaction_data','added_comment','documents_details','is_update_url','budget_code','item_details','copy_to_details','list_of_departments','users'));

            } catch (\Exception $e) {
             
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
            return redirect('/eas');
            }   
        } else {
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/eas');
        }     
    }

    /**
     * Update the specified EAS in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Update Expenditure Angle Sanction(EAS) Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Expenditure Angle Sanction(EAS) list.
     * 
     * @var array[] $eas_masters Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $requestData to fetch data from request
     * 
     */
    public function update(Request $request, $id)
    {
        
  // dd($request);
        if(roleEntityMapping($this->user_id,'eas','can_update')) {
            if($request->whether_being_issued_under == 'fa_concurrence')
           {
            $this->validate($request,[
                'sanction_title'   => 'required|unique:eas_masters,sanction_title,'.$id.',id,deleted_at,NULL',
                'sanction_purpose'    => 'required',
                'competent_authority'   => 'required',
                'serial_no_of_sanction'=>'required|unique:eas_masters,serial_no_of_sanction,'.$id.',id,deleted_at,NULL',
                'file_number'=>'required|unique:eas_masters,file_number,'.$id.',id,deleted_at,NULL',
                'sanction_total'   => 'required',
                'budget_code'     => 'required',
                'validity_sanction_period'     => 'required',
                'date_issue'   => 'required',
                'vendor_id' => 'required',
                'cfa_note_number' =>'required|unique:eas_masters,cfa_note_number,'.$id.',id,deleted_at,NULL',
                'cfa_dated' => 'required',
                'cfa_designation' =>'required',  
                'fa_number' => 'required',
                'fa_dated' => 'required'
               // 'fc_on_page' => 'required',
                //'fc_on_file_no' => 'required'
            ]);
            } else { 
                // dd($request);
             $this->validate($request,[
                'whether_being_issued_under'=>'required',
                'sanction_title'   => 'required|unique:eas_masters,sanction_title,'.$id.',id,deleted_at,NULL',
                'sanction_purpose'    => 'required',
                'competent_authority'   => 'required',
                'serial_no_of_sanction'=>'required|unique:eas_masters,serial_no_of_sanction,'.$id.',id,deleted_at,NULL',
                'file_number'=>'required|unique:eas_masters,file_number,'.$id.',id,deleted_at,NULL',
                'sanction_total'   => 'required',
                'budget_code'     => 'required',
                'validity_sanction_period' => 'required',
                'date_issue'   => 'required',
                'vendor_id' => 'required',
                'cfa_note_number' =>'required|unique:eas_masters,cfa_note_number,'.$id.',id,deleted_at,NULL',
                'cfa_dated' => 'required',
                'cfa_designation' =>'required'
            ]);
            }
           
        // try {    
               // $requestData = $request->all();
                $eas_master = Eas::findOrFail($id);
                $eas_updated_data = $eas_master->update($request->all());

                if(isset($eas_updated_data) && !empty($eas_updated_data)) {

                     if (isset($request->item) && !empty($request->item)) {

                   // $delete_invoice = ItemDetails::where(['eas_id'=>$id])->delete();

                        foreach ($request->item as $key => $value) {

                            if (!empty($value['unit_price_tax']) && !empty($value['item']) && !empty($value['category']) && !empty($value['qty']) && !empty($value['total_unit_price_tax'])) {
                                
                              $create_invoice = ItemDetails::create(['eas_id'=>$id,'category'=>$value['category'],'item'=>$value['item'],'qty'=>$value['qty'],'unit_price_tax'=>$value['unit_price_tax'],'total_unit_price_tax'=>$value['total_unit_price_tax']]);
                            }
                          // dd($create_invoice);
                        }

                      }

                       if (isset($request->copy) && !empty($request->copy)) {
 
                        foreach ($request->copy as $key => $value) {

                           if (!empty($value['department_id']) && !empty($value['user_id']) && !empty($request->entity_id) && !empty($id)) {

                                $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
                         }
                          // dd($create_invoice);
                        }
                      }

                     $details = $this->user_details->storeLogDetails($eas_master->id,$request->entity_id,$request->all());

                     if (isset($request->documents_type) && !empty($request->documents_type) && isset($request->file_upload) && $request->hasFile('file_upload')) {
                      
                        $uploadDocuments = $this->user_details->uploadDocuments($request->file_upload,$request->documents_type,$id,$this->user_id,$request->entity_id,$request->entity_slug,$request->file_number);
                        
                        if(isset($uploadDocuments) && !empty($uploadDocuments) && $uploadDocuments['code'] == 200)  {
                            
                        Toast::success('Eas updated successfully!');
                        return redirect('/eas/' .$id);
                        } else{
                          Toast::error('Something went wrong while file upload!');
                         return redirect('/eas/' .$id);
                        }
                    } else {
                        
                        if(isset($eas_updated_data) && !empty($eas_updated_data))  {
                            
                        Toast::success('Eas updated successfully!');
                        return redirect('/eas/' .$id);
                        } else{
                          Toast::error('Something went wrong while updating data!');
                         return redirect('/eas/' .$id);
                        }
                    }
                       
                        
                    //return redirect('/eas')->with('success', 'Eas updated successfully!');
                } else {
                    Toast::error('Something went wrong');
                    return redirect('/eas');
                }  

            // } catch (\Exception $e) {
             
            // Log::critical($e->getMessage());
            //     app('sentry')->captureException($e);
            //      Toast::error('Something went wrong');
            //       return redirect('/eas');
            // }      
        } else {
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/eas');
        }    
    }

    /**
     * Remove the specified EAS from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @param  int  $id
     * @var string $eas to find record against particular id  
     * @var string $result to delete record
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if(roleEntityMapping($this->user_id,'eas','can_delete')) {
         try {      
                if(isset($id) && !empty($id)) { 
                  //  $eas = Eas::delete($id);
                    $eas = Eas::where('id',$id)->delete();
                    if(isset($eas) && !empty($eas)) {
                        Toast::success('Eas deleted successfully!');
                         return redirect('/eas');
                    } else {
                        Toast::error('Something went wrong');
                        return redirect('/eas');
                    }
                } else  {
                    Toast::error('Id not found');
                    return redirect('/eas');
                }
            } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
                Toast::error('Something went wrong!');
              return redirect('/eas');
            }
        } else {
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/eas');
        }         
    }

    public function getVendorDetails($vendor_id)
    {
        try {


        $data['vendor_details'] = Vendor::select('vendor_master.id','vendor_master.bank_acc_no','vendor_master.mobile_no','vendor_master.gstin')
         ->where('vendor_master.id','=',$vendor_id)
         ->where(['vendor_master.deleted_at'=> null,'vendor_master.vendor_status'=>'Enable'])
         ->first();

       // dd($data['vendor_details']);
      if (isset($data) && !empty($data)) {

        $data['code'] = 200;
       
    } else {
      $data['code'] = 204;
    }
    return $data;
} catch (\Exception $e) {

    Log::critical($e->getMessage());
    app('sentry')->captureException($e);
   // Toast::error('Something Went wrong.');
    $data['code'] = 204;
    return $data;
}
}

  public function getDepartmentDetails($department_id)
    {
        //try {

        $last_id = EAS::select('id')->where(['deleted_at' => Null,'department_id'=>$department_id])->orderBy('id','desc')->first();

        $year = getFinacialYear();
        if(isset($year) && !empty($year)){
            $explode_result = explode('-',$year);
            $from_yr = $explode_result[0]; 
            $month = date('M');
        } else {
            $from_yr = ''; 
            $month = date('M');
        }    
            
        if(isset($last_id) && !empty($last_id)) {
            $id = $last_id['id']+1;
         } else {
            $id = 1;
        }

         $departments_details = Department::select('departments.slug')
       //  ->join('departments','departments.id','=','eas_masters.department_id' )
         ->where('departments.id','=',$department_id)
         ->where(['departments.deleted_at'=> null ])->first();

        if(isset($departments_details->slug) && !empty($departments_details->slug)) 
        {
         $serial_no_of_sanction = $departments_details->slug.'/'.$month.'/'.$from_yr.'/'.$id;
         $file_number = 'F.NO.'.$month.'/'.$id.'/'.$from_yr.'-'.$departments_details->slug;
        } 
       
      if (isset($serial_no_of_sanction) && !empty($file_number)) {
        $data['serial_no_of_sanction'] = $serial_no_of_sanction;
        $data['file_number'] = $file_number;
        $data['code'] = 200;
    } else {
      $data['code'] = 204;
    }
    return $data;
    // } catch (\Exception $e) {

    //     Log::critical($e->getMessage());
    //     app('sentry')->captureException($e);
    //      $data['code'] = 204;
    //     return $data;
    // }
}

  
  public function downloadEasPdf($id)
    { 
        try {

         $file_name = EAS::select('eas_pdf')
                ->where('id',$id)
                ->first();
       // dd($file_name );
        return response()->download(storage_path("documents/{$file_name->eas_pdf}"));
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('File not found.');
              return redirect()->back();
       }  
    }

    public function getUsersFromDepartment($department_id) {

        $users = User::select('users.id','users.name')
                ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                ->where(['departments.id'=>$department_id,'users.user_status' => 'Enable'])
                ->where('users.id','!=',$this->user_id)
                ->orderBy('users.id','desc')
                ->get();

        return $users;
    }

     public function deleteItemDetails($id) {
        //try {
     //dd($request);
            if (isset($id) && !empty($id)) {
               
                    $delete = ItemDetails::where('id', $id)->delete();
               
                if (isset($delete) && !empty($delete)) {
                    $data['code'] = 200;
                    $data['message'] = "Successfully Deleted";
                } else {
                    $data['code'] = 204;
                    $data['message'] = "Something went wrong while deleted.";
                }
            } else {
                $data['code'] = 204;
                $data['message'] = "id not found.";
            }
            return $data;
        // }
        // catch(\Exception $e) {
        //     $data['code'] = 204;
        //     $data['message'] = "Something went wrong.";
        //     return $data;
        // }
    }

    public function updateItemDetails(Request $request) {
        //try {
    //dd($request);
            if (isset($request->eas_id) && !empty($request->eas_id)) {
               
                    $delete = ItemDetails::where('eas_id', $request->eas_id)->delete();

                    if (isset($request->item) && !empty($request->item)) {
 
                        foreach ($request->item as $key => $value) {

                            if (!empty($value['unit_price_tax']) && !empty($value['item']) && !empty($value['category']) && !empty($value['qty']) && !empty($value['total_unit_price_tax'])) {
                              
                               $create_item = ItemDetails::create(['eas_id'=>$request->eas_id,'category'=>$value['category'],'item'=>$value['item'],'qty'=>$value['qty'],'unit_price_tax'=>$value['unit_price_tax'],'total_unit_price_tax'=>$value['total_unit_price_tax']]);
                            }   
                          // dd($create_invoice);
                        }

                      }
               
                if (isset($delete) && !empty($delete)) {
                    Toast::success('Item details Successfully Updated.');
                    return redirect()->back();
                } else {
                    Toast::error('Something went wrong while updating!');
              return redirect()->back();
                }
            } else {
                Toast::error('EAS not found');
              return redirect()->back();
            }
            
        // }
        // catch(\Exception $e) {
        //     $data['code'] = 204;
        //     $data['message'] = "Something went wrong.";
        //     return $data;
        // }
    }

    public function addItemDetails(Request $request) {
        //try {
     //dd($request);
            if (isset($request->eas_id) && !empty($request->eas_id)) {
            
                    if (isset($request->item) && !empty($request->item)) {
 
                        foreach ($request->item as $key => $value) {

                            if (!empty($value['unit_price_tax']) && !empty($value['item']) && !empty($value['category']) && !empty($value['qty']) && !empty($value['total_unit_price_tax'])) {

                           $create_item = ItemDetails::create(['eas_id'=>$request->eas_id,'category'=>$value['category'],'item'=>$value['item'],'qty'=>$value['qty'],'unit_price_tax'=>$value['unit_price_tax'],'total_unit_price_tax'=>$value['total_unit_price_tax']]);
                         }
                          // dd($create_invoice);
                        }

                      }
               
                if (isset($create_item) && !empty($create_item)) {
                    Toast::success('Item details Added Successfully.');
                    return redirect()->back();
                } else {
                    Toast::error('Something went wrong while adding!');
              return redirect()->back();
                }
            } else {
                Toast::error('EAS not found');
              return redirect()->back();
            }
            
        // }
        // catch(\Exception $e) {
        //     $data['code'] = 204;
        //     $data['message'] = "Something went wrong.";
        //     return $data;
        // }
    }


}
