<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Department;
use App\Role;
use App\Transaction;
use App\WorkFlowTransactionMapper;
use App\WorkFlowTransactionConditionValueMapper;
use App\Status;
use App\PermissionMapper;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Auth;
use App\Eas;
use App\ReleaseOrder;
use App\PurchaseOrder;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;
use DB;
use App\User;
use Mail;
use App\GAR;
use datetime;
use App\DispatchRegister;
use App\DiaryRegister;
use App\RejectComment;
use App\MediaMaster;

class CommonController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function uploadDocuments($file_upload,$documents_type,$master_id,$request->created_by,$entity_id,$entity_slug)
    {
         
    if(isset($file_upload) && !empty($file_upload) && isset($documents_type) &&  !empty($documents_type) && isset($master_id) &&  !empty($master_id) && isset($request->created_by) &&  !empty($request->created_by)  && isset($entity_slug) &&  !empty($entity_slug)  && isset($entity_id) &&  !empty($entity_id)) { 

          $year = date('Y');
          $month = date('m');
          $path = public_path()."/documents/". $year . "/" . $month;
          if (!is_dir($path)) {
                mkdir($path, 0777, true);
          }

        foreach ($documents_type as $key => $value) {

          $original_file_name = $entity_slug.'-'.strtolower(str_replace([' ', '_'], '-', $file_upload[$key]->getClientOriginalName()));
          $documents =  "documents/". $year . "/" . $month."/".$original_file_name;
          $file_upload[$key]->move($path, $documents);
          $insert_details= MediaMaster::create(['entity_id'=>$entity_id,'master_id'=>$master_id,'document_name'=>$value,'file_name'=>$documents,'created_by'=>$request->created_by]);
           
        }
          if(isset($insert_details)) {
            $data['code']=200;
          } else {
            $data['code']=204;
          }

      } else {
        
        $data['code']=204;
      }  

      return $data;
    }

    public function getUserDetails($role_id)
    {

      if(isset($role_id) && !empty($role_id))
      {
        $user_details = Role::select('roles.*','departments.office_type_id','departments.city_id as location_id','roles.id','roles.name','users.name','users.id','departments.slug','departments.name as department_name','location.city_name')
        ->join('users','users.role_id', '=','roles.id')
        ->join('departments','departments.id' ,'=', 'roles.department_id')
        ->join('location','location.id', '=','departments.office_type_id')
        ->where('roles.id', '=',$role_id)
        ->where(['roles.deleted_at' => Null, 'departments.deleted_at'=>Null])
        ->get();

            if(isset($user_details) && !empty($user_details))
            {   
                foreach ($user_details as $value) 
                {
                   $data['role_id'] = $role_id;
                   $data['role_name'] = $value->display_name;
                   $data['user_id'] = $value->id;
                   $data['user_name'] = $value->name;
                   $data['location_id'] = $value->location_id;
                   $data['office_type_id'] = $value->office_type_id;
                   $data['department_id'] = $value->department_id; 
                   $data['city_name'] = $value->city_name;
                   $data['location_id'] = $value->location_id;
                   $data['office_type_id'] = $value->office_type_id;
                   $data['department_id'] = $value->department_id; 
                   $data['slug'] = $value->slug; 
                   $data['status']=200; 
                }   
            } else {
              $data['status']=204;
            }  
        } else {
          $data['status']=204;
        }
       return $data;  
    }

     public function getApplicableTransaction($status_id,$workflow_id)
    { 

        $data['body'] = WorkFlowTransactionMapper::select('transactions.id','workflow_transaction_mapper.*','to_status.status_name as to_status_name','transactions.to_status as to_status_id','transactions.transaction_name')
            ->join('transactions','workflow_transaction_mapper.transaction_id','=','transactions.id')
            ->join('status as to_status', function($join) 
            {
                $join->on('to_status.id', '=', 'transactions.to_status');
            })
            ->where('workflow_transaction_mapper.workflow_id',$workflow_id)
            ->where('transactions.from_status','=',$status_id)
            ->get();
     
           if(!$data['body']->isEmpty())
           {
                $data['status_code'] = '200';
                $data['message'] = 'success';
               
            }
            else 
            {
                $data['status_code'] =  "204";
                $data['message'] = "no data available";
                $data['body'] =(object) array();
            }
           
        return $data;
    }
    public function updateCurrentTransaction(Request $request) 
    {

     if(isset($request->status_name) && !empty($request->status_name)) {
      $previous_status = $request->status_name;
     }else {
      $previous_status ='';
     }

    $request->created_by = $request->created_by;
    
    $condition_check = $this->checkCondition($this->user,$request);
  
    if (!empty($condition_check['code']) && $condition_check['code'] == 200) {
        
      //   if(isset($request->comment) && !empty($request->comment) && $request->entity_slug !== "gar" && $request->entity_slug !== "ro" && $request->entity_slug !== "po") {

      //   $add_comment = DB::table('reject_comment')->insert(['comment'=>$request->comment,'entity_id'=>$request->entity_id,'workflow_id'=>$request->workflow_id,'created_by'=>$this->user_id,'master_id'=>$request->id]);
      // }

      
      $role_details=$this->user_details->getUserDetails($this->user->role_id);
      
      $transaction_type = Transaction::select('transactions.id','transaction_name','to_status','from_status','status.status_name')
      ->join('status','status.id','=','transactions.to_status')
      ->where(['transactions.id'=>$request->transaction_type,'transactions.deleted_at'=>NULL])
      ->first();
      
    
      if(isset($transaction_type->to_status) && !empty($transaction_type->to_status) && isset($request->entity_slug))    
      {
        switch ($request->entity_slug) {
          case 'po':
        
           $role_wise_access = $this->RoleWiseAccess($previous_status,$transaction_type,$request,$request->created_by);
           //dd($role_wise_access);
           if($role_wise_access['code'] == 200 ) {

           
             $add_comment = RejectComment::create(['comment'=>$request->comment,'entity_id'=>$request->entity_id,'workflow_id'=>$request->workflow_id,'created_by'=>$this->user_id,'master_id'=>$request->id]);
               $update_tostatus = PurchaseOrder::where('id',$request->id)->update(['status_id'=>$transaction_type->to_status]);

             $data['message']="Status successfully updated to ".$transaction_type->status_name;
             $data['code']= 200;
             $data['status_id']= $transaction_type->to_status;
           } else {
              $data['code']= 204;
              $data['message']= $role_wise_access['message'];
              $data['status_id']= $transaction_type->to_status;
            }
            return json_encode($data);  
         // $changed_status_name = PurchaseOrder::select('purchase_order_masters.*','status.status_name')
         // ->join('status','status.id','=','purchase_order_masters.status_id')
         // ->where('purchase_order_masters.id','=',$request->id)
         // ->where(['purchase_order_masters.location_id'=>$role_details['location_id'],'purchase_order_masters.department_id'=>$role_details['department_id'],'purchase_order_masters.office_type_id'=>$role_details['office_type_id']])
         // ->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null])
         // ->first();

         // if(isset($changed_status_name->email_users) && !empty($changed_status_name->email_users))
         // {
         //   $email_users_list = $changed_status_name->email_users;
         //   $email_users_list_array = explode(',',$email_users_list);

         //   foreach($email_users_list_array as $value)
         //   {

         //     $users = User::select('id','name','email','role_id')
         //     ->where('id','=',$value)
         //     ->where(['deleted_at'=>NULL])
         //     ->first();

         //     if(isset($users->email) && !empty($users->email))
         //     {
         //         $send_email_to = $users->email;
         //         $changed_status = $transaction_type->status_name;
         //         $entity_name = "Purchase Order";
         //         $vendor_name = $changed_status_name->vendor_name;
         //         $status_changed_by = Auth::user()->name;

         //         $check_email_status = $this->sendMail($previous_status,$changed_status,$entity_name,$vendor_name,$status_changed_by,$send_email_to);

         //         if(!empty($check_email_status['code']) && $check_email_status['code'] == 200)
         //         {
         //            $data['code']= 200;
         //             $data['message']= "Status successfully updated to ".$transaction_type->status_name;
         //             $data['status_id']= $transaction_type->to_status;
         //         }
         //     }
         //   }
         // }

          break;
          
          case 'eas':
         
          $role_wise_access = $this->RoleWiseAccess($previous_status,$transaction_type,$request,$request->created_by);
         
           if($role_wise_access['code'] == 200 ) {

             $update_tostatus = EAS::where('id',$request->id)->update(['status_id'=>$transaction_type->to_status]);

             $add_comment = RejectComment::create(['comment'=>$request->comment,'entity_id'=>$request->entity_id,'workflow_id'=>$request->workflow_id,'created_by'=>$this->user_id,'master_id'=>$request->id]);

             $data['message']="Status successfully updated to ".$transaction_type->status_name;
             $data['code']= 200;
             $data['status_id']= $transaction_type->to_status;
           } else {
              $data['code']= 204;
              $data['message']= $role_wise_access['message'];
              $data['status_id'] = $transaction_type->to_status;
            }
            return json_encode($data);  
          break;

          case 'ro':
            $current_date =  new DateTime();
            $role_wise_access = $this->RoleWiseAccess($previous_status,$transaction_type,$request,$request->created_by); 
             if($role_wise_access['code'] == 200 ) {

              $update_tostatus = ReleaseOrder::where('id',$request->id)->update(['status_id'=>$transaction_type->to_status,'status_approved_date'=>$current_date]);

                $add_comment = RejectComment::create(['comment'=>$request->comment,'entity_id'=>$request->entity_id,'workflow_id'=>$request->workflow_id,'created_by'=>$this->user_id,'master_id'=>$request->id]);

                $data['message']="Status successfully updated to ".$transaction_type->status_name;
                $data['code']= 200;
                $data['status_id']= $transaction_type->to_status;
             } else {
            $data['code']= 204;
            $data['message']= $role_wise_access['message'];
            $data['status_id']= $transaction_type->to_status;
          }
          return json_encode($data);  
          break;
          
          case 'gar':
          
          $role_wise_access = $this->RoleWiseAccess($previous_status,$transaction_type,$request,$request->created_by); 
//dd($role_wise_access);
            $update_tostatus = GAR::where('id',$request->id)->update(['status_id'=>$transaction_type->to_status]);
         // $data['code']= 200;
          if($role_wise_access['code'] == 200 ) {

             $update_tostatus = GAR::where('id',$request->id)->update(['status_id'=>$transaction_type->to_status]);

            $add_comment = RejectComment::create(['comment'=>$request->comment,'entity_id'=>$request->entity_id,'workflow_id'=>$request->workflow_id,'created_by'=>$this->user_id,'master_id'=>$request->id]);

            $data['message']="Status successfully updated to ".$transaction_type->status_name;
            $data['code']= 200;
            $data['status_id']= $transaction_type->to_status;    
          } else {
            $data['code']= 204;
            $data['message']= $role_wise_access['message'];
            $data['status_id']= $transaction_type->to_status;
          }
          return json_encode($data);  
          break;
          }

        if($update_tostatus || $add_comment) {
          $data['code']= 200;
          $data['message']= "Status successfully updated to ".$transaction_type->status_name;
          $data['status_id']= $transaction_type->to_status;
        } else {
          $data['code']= 204;
          $data['message']= $role_wise_access['message'];
        }
      } else {
        $data['code']= 204;
        $data['message']= "Transaction Not found.";
      }
    } else {
      $data['code']= 204;
      $data['message']= $condition_check['message'];
    }
    return json_encode($data);  
  }

  public function RoleWiseAccess($previous_status,$transaction_type,$request,$request->created_by)
  {
   //dd($previous_status);
    switch ($previous_status) {
      case 'Pending Review':

      if($request->entity_slug == 'eas') {
          
        if(isset($this->user) && $this->user->hasRole('dd_hr_and_coordination_division') || isset($this->user) && $this->user->hasRole('so_hr_and_coordination_division') && $request->created_by == $this->user->id ) {
          $data['code']= 200;
          $data['message']="success";
         } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Changed Status.";

         }
       }
  
       if($request->entity_slug == 'po') {
           
         if(isset($this->user) && $this->user->hasRole('dd_hr_and_coordination_division') || isset($this->user) && $this->user->hasRole('so_hr_and_coordination_division') && $request->created_by == $this->user->id ) {
          $data['code']= 200;
          $data['message']="success";
         } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Changed Status.";
         }
       }

       if($request->entity_slug == 'gar') {
       if(isset($this->user) && $this->user->hasRole('Acctt')) {
           $diary_register_entry = GAR::select('is_diary_register')->where('id',$request->id)->first();
       
         if($diary_register_entry && $diary_register_entry->is_diary_register == 1) {
            $getUser = Role::select('users.email')
              ->join('users','users.role_id', '=','roles.id')
              ->where(['users.id'=>$request->created_by])->orwhere(['roles.display_name'=>'DDO'])
              ->get();
               if($getUser) {
              $data['code']= 200;
             $data['message']="success";
            } else {
              $data['code']= 204;
              $data['message']="failed.";
            }
          } else {
            $data['code']= 204;
            $data['message']="Diary Register Enrty Not Done.";
          }
      } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Changed Status.";
      } 
     }
     if($request->entity_slug == 'ro') {

       if ((isset($this->user) && $this->user->hasRole('dd_hr_and_coordination_division')) || (isset($this->user) && $this->user->hasRole('so_hr_and_coordination_division')) && $request->created_by == $this->user->id) {
        // print_r("Here");exit;
        $getUser = Role::select('users.email')
          ->join('users','users.role_id', '=','roles.id')
          ->where(['users.id'=>$request->created_by])->orwhere(['roles.display_name'=>'DDO'])
          ->get();
            if($getUser) {
          $data['code']= 200;
         $data['message']="success";
        }
        } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Changed Status.";
      } 
    } 
     return $data;
      break;

      case 'Draft':
       if($request->entity_slug == 'po') {
          
         if(isset($this->user) && $this->user->hasRole('dd_hr_and_coordination_division') || isset($this->user) && $this->user->hasRole('so_hr_and_coordination_division') && $request->created_by == $this->user->id ) {
          $data['code']= 200;
          $data['message']="success";
         } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Changed Status.";

         }
       }

        if((isset($this->user) && $this->user->hasRole('dd_hr_and_coordination_division')) || (isset($this->user) && $this->user->hasRole('so_hr_and_coordination_division'))) {
        $getUser = Role::select('users.email')
        ->join('users','users.role_id', '=','roles.id')
        ->where(['users.id'=>$request->created_by])
        ->get();
        if($getUser) {
        $data['code']= 200;
        $data['message']="Success";
        } else {
        $data['code']= 204;
        $data['message']="failed.";
        }
      } else {
        $data['code']= 204;
        $data['message']="You are not Authorized to Approved.";
      }    
      return $data;
      break;

      case 'Return':
      if($request->entity_slug == 'po') {

  
      if(isset($this->user) && ($this->user->hasRole('dd_hr_and_coordination_division') &&  $request->created_by != $this->user->id) || ($this->user->hasRole('adg_hr_and_coordination_division') && $request->created_by != $this->user->id)) {
     
             $getUser = User::select('users.email','eas_masters.created_by')
           // ->join('users','users.role_id', '=','roles.id')
            ->join('eas_masters','eas_masters.created_by','=','users.id' )
            ->where(['eas_masters.id'=>$request->eas_id])
            ->get();
         // dd( $getUser );
            if($getUser) {
            $data['code']= 200;
            $data['message']="Success";
            } else {
            $data['code']= 204;
            $data['message']="failed.";
            }
          } else {
           
          $data['code']= 204;
          $data['message']="You are not Authorized to Approved.";
          } 
      }
      if($request->entity_slug == 'eas') {
    
        if(isset($this->user) && ($this->user->hasRole('dd_hr_and_coordination_division') &&  $request->created_by != $this->user->id) || ($this->user->hasRole('adg_hr_and_coordination_division') &&  $request->created_by != $this->user->id)) {
     
             $getUser = User::select('users.email','eas_masters.created_by')
            ->join('eas_masters','eas_masters.created_by','=','users.id' )
            ->where(['eas_masters.id'=>$request->eas_id])
            ->get();
         // dd( $getUser );
            if($getUser) {
            $data['code']= 200;
            $data['message']="Success";
            } else {
            $data['code']= 204;
            $data['message']="failed.";
            }
          } else {
           
          $data['code']= 204;
          $data['message']="You are not Authorized to Approved.";
          } 
      }
      if($request->entity_slug == 'ro') {
        if(isset($this->user) && ($this->user->hasRole('dd_hr_and_coordination_division') &&  $request->created_by != $this->user->id) || ($this->user->hasRole('adg_hr_and_coordination_division') &&  $request->created_by != $this->user->id)) {
        $getUser = Role::select('users.email')
        ->join('users','users.role_id', '=','roles.id')
        ->where(['users.id'=>$request->created_by])
        ->get();
        if($getUser) {
        $data['code']= 200;
        $data['message']="Success";
        } else {
        $data['code']= 204;
        $data['message']="failed.";
        }
      } else {
        $data['code']= 204;
        $data['message']="You are not Authorized to Approved.";
      }
    }      
      return $data;
      break;

      case 'Approve':
      if($request->entity_slug == 'eas') {

       if(isset($this->user) && ($this->user->hasRole('dd_hr_and_coordination_division') &&  $request->created_by != $this->user->id) || ($this->user->hasRole('adg_hr_and_coordination_division') &&  $request->created_by != $this->user->id)) {
     
             $getUser = User::select('users.email','eas_masters.created_by')
            ->join('eas_masters','eas_masters.created_by','=','users.id' )
            ->where(['eas_masters.id'=>$request->eas_id])
            ->get();
         // dd( $getUser );
            if($getUser) {
            $data['code']= 200;
            $data['message']="Success";
            } else {
            $data['code']= 204;
            $data['message']="failed.";
            }
          } else {
           
          $data['code']= 204;
          $data['message']="You are not Authorized to Approved.";
          } 
      }
      if($request->entity_slug == 'po') {

      if(isset($this->user) && ($this->user->hasRole('dd_hr_and_coordination_division') &&  $request->created_by != $this->user->id) || ($this->user->hasRole('adg_hr_and_coordination_division') && $request->created_by != $this->user->id)) {

             $getUser = User::select('users.email','eas_masters.created_by')
            ->join('eas_masters','eas_masters.created_by','=','users.id' )
            ->where(['eas_masters.id'=>$request->eas_id])
            ->get();
         // dd( $getUser );
            if($getUser) {
            $data['code']= 200;
            $data['message']="Success";
            } else {
            $data['code']= 204;
            $data['message']="failed.";
            }
          } else {
          $data['code']= 204;
          $data['message']="You are not Authorized to Approved.";
          } 
      }
      if($request->entity_slug == 'ro') {

      if(isset($this->user) && ($this->user->hasRole('dd_hr_and_coordination_division') &&  $request->created_by != $this->user->id) || ($this->user->hasRole('adg_hr_and_coordination_division') &&  $request->created_by != $this->user->id)) {
        $getUser = Role::select('users.email')
        ->join('users','users.role_id', '=','roles.id')
        ->where(['users.id'=>$request->created_by])
        ->get();
        if($getUser) {
        $data['code']= 200;
        $data['message']="Success";
        } else {
        $data['code']= 204;
        $data['message']="failed.";
        }
      } else {
        $data['code']= 204;
        $data['message']="You are not Authorized to Approved.";
      }  
    }    
      return $data;
      break;

      case 'DDO Return':
       if(isset($this->user) && $this->user->hasRole('DDO')) {
           $getUser = Role::select('users.email')
          ->join('users','users.role_id', '=','roles.id')
          ->where(['users.id'=>$request->created_by])
          ->get();
          if($getUser) {
          $data['code']= 200;
          $data['message']="Success";
          } else {
            $data['code']= 204;
            $data['message']="failed.";
          }
       } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Return.";
       } 
      return $data;
      break;

      case 'DDO Approve':
        if(isset($this->user) && $this->user->hasRole('DDO')) {
        $getUser = Role::select('users.email')
        ->join('users','users.role_id', '=','roles.id')
        ->where(['users.id'=>$request->created_by])
        ->get();
        if($getUser) {
        $data['code']= 200;
        $data['message']="Success";
        } else {
        $data['code']= 204;
        $data['message']="failed.";
        }
      } else {
        $data['code']= 204;
        $data['message']="You are not Authorized to Approved.";
      }    
      return $data;
      break;

      case 'Dispatch And Tally Entry Done':

       if(isset($this->user) && $this->user->hasRole('Acctt')) {
          $updateEntry = GAR::select('tally_entry','is_dispatch_register')->where('id',$request->id)->first();
     
          if($updateEntry && $updateEntry->tally_entry == 1 && $updateEntry->is_dispatch_register == 1) {
           $getUser = Role::select('users.email')->join('users','users.role_id', '=','roles.id')
           ->where(['roles.display_name'=>'PAO'])->orwhere(['users.id'=>$request->created_by])->orwhere(['roles.display_name'=>'DDO'])->get();
           $data['code']= 200;
           $data['message']="Tally Enrty Or Dispatch Entry Successfully Done.";
          } else {
            $data['code']= 204;
            $data['message']="Tally Enrty Or Dispatch Entry not done.";
          }
      } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Changed Status.";
      } 

     return $data;
      break;
      
      case 'AAO Return':
      if(isset($this->user) && $this->user->hasRole('AAO')) {
          $getUser =Role::select('users.email')->join('users','users.role_id', '=','roles.id')
           ->where(['roles.display_name'=>'PAO'])->orwhere(['users.id'=>$request->created_by])->get();
             if($getUser) {
            $data['code']= 200;
           $data['message']="Success";
          } else {
            $data['code']= 204;
            $data['message']="failed.";
          }
        } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Return.";
        } 
       return $data;
      break;
      
      case 'AAO Approve':
      if(isset($this->user) && $this->user->hasRole('AAO')) {
      $getUser = Role::select('users.email')->join('users','users.role_id', '=','roles.id')
       ->where(['roles.display_name'=>'PAO'])->orwhere(['users.id'=>$request->created_by])->orwhere(['roles.display_name'=>'DDO'])->get();
         if($getUser) {
        $data['code']= 200;
       $data['message']="Success";
      } else {
        $data['code']= 204;
        $data['message']="failed.";
      }
      } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Approve.";
       } 
       return $data;
      break;
    
      case 'PAO Return':
      if(isset($this->user) && $this->user->hasRole('PAO')) {
      $getUser = Role::select('users.email')->join('users','users.role_id', '=','roles.id')
       ->where(['roles.display_name'=>'DDO'])->orwhere(['users.id'=>$request->created_by])->orwhere(['roles.display_name'=>'DDO'])->get();
       if($getUser) {
        $data['code']= 200;
       $data['message']="Success";
      } else {
        $data['code']= 204;
        $data['message']="failed.";
      }
      } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Return.";
       } 
       return $data;
      break;

      case 'Cheque Upload':
     
      if(isset($this->user) && $this->user->hasRole('PAO')) {

        $cheque_exits = GAR::select('uploaded_cheque')->where(['id'=>$request->id,'deleted_at'=>NULL])->first();
        // dd(count($cheque_exits));
      if (isset($cheque_exits) && !empty($cheque_exits)){
        $data['code'] = 200;
        $data['message']="Success";
      } else {
        $data['code']= 204;
        $data['message']="Upload Cheque not done.";
      }
      } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to change Status.";
       } 
       
       return $data;
      break;

       case 'Generate Forwarding Letter':
     
      if(isset($this->user) && $this->user->hasRole('PAO')) {

        $forwarding_exits = GAR::select('forwarding_letter')->where(['id'=>$request->id,'deleted_at'=>NULL])->first();
         
      if (isset($forwarding_exits) && !empty($forwarding_exits) ){
        $data['code'] = 200;
        $data['message']="Success";
      } else {
        $data['code']= 204;
        $data['message']="Forwarding letter not generated.";
      }
      } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to change Status.";
       } 
       //dd(($data));
       return $data;
      break;
      
      
      case 'PAO Approve':
       if(isset($this->user) && $this->user->hasRole('PAO')) {
        $getUser = Role::select('users.email')->join('users','users.role_id', '=','roles.id')
         ->where(['roles.display_name'=>'AAO'])->orwhere(['users.id'=>$request->created_by])->orwhere(['roles.display_name'=>'DDO'])->get();
           if($getUser) {
          $data['code']= 200;
         $data['message']="Success";
        } else {
          $data['code']= 204;
          $data['message']="failed.";
        }
      } else {
         $data['code']= 204;
         $data['message']="You are not Authorized to Approve.";
       } 
       return $data;
      break;
    }    
  }

  
  // public function sendMail($previous_status,$changed_status) {

  // $admin_subject = "UIDAI";

  //  $send_mail = Mail::send('mail.mail',
  //    [
  //      'email' => $send_email_to,
  //      'previous_status' => $previous_status,
  //      'changed_status' => $changed_status,
  //      'entity_name' => $entity_name,
  //      'vendor_name' => $vendor_name,
  //      'status_changed_by' => $status_changed_by
  //    ],
  //    function ($message) use ($admin_subject,$send_email_to) {
  //     $message->to($send_email_to)->subject($admin_subject);
  //     $message->from('roshani@choicetechlab.com', 'UIDAI');
  //   });

  // }


   public function getUserDetailsForMail(){
    
   }

  public function checkCondition($user,$post_data)
  { 

   $workflow_transaction_id = $post_data->transaction_type;

    // if(!empty($post_data->created_by) && !empty($user->id) && $post_data->created_by == $user->id) {

    //   $data['code']= 204;
    //   $data['message']= "You are not Authorized to perform this action.";
    // } else {

   if (isset($workflow_transaction_id) && !empty($workflow_transaction_id))  {

    $condition_checker = WorkFlowTransactionConditionValueMapper::select('workflow_transaction_condition_value_mapper.workflow_id','workflow_transaction_condition_value_mapper.id','workflow_transaction_condition_value_mapper.condition_id','workflow_transaction_condition_value_mapper.transaction_id','workflow_transaction_condition_value_mapper.variable_type','workflow_transaction_condition_value_mapper.value','condition_master.condition_name')
    ->leftjoin('condition_master','condition_master.id','=','workflow_transaction_condition_value_mapper.condition_id')
    ->where('workflow_transaction_condition_value_mapper.workflow_id','=',$post_data->workflow_id)
    ->where('transaction_id','=',$workflow_transaction_id)
    ->get();
        // dd($user->can('can_approve'));
    
    if (count($condition_checker) > 0) {
      $checkValue = $this->checkValue($user,$condition_checker,$post_data);
      if(!empty($checkValue['message']) && $checkValue['code'] == 200){
        $data['code']= 200;
        $data['message']= $checkValue['message'];
      } else {
        $data['code']= 204;
        $data['message']= $checkValue['message'];
      }
    } else {
      $data['code'] = 200;
      $data['message']= "No Condition Define";
    }    
  } else {
    $data['code'] = 204;
    $data['message']= "Transaction Id not found.";
  }
      // }
  return $data;
}
public function checkValue($user,$condition_checker,$post_data) 
{
  //dd($post_data);

  if (count($condition_checker) > 0) {   
    foreach ($condition_checker as $condition) {
      switch ($condition->condition_name) {
        case 'isApproved':
        
        if(isset($user) &&  $user->can('can_approve')) {
          $data['code']= 200;
          $data['message']= "Successfully Approved";
        } else {
          $data['code']= 204;
          $data['message']= "You are not Authorized to Approved.";
        }
        return $data;
        break;
        case 'isCheck':
        $data['code']= 200;
        $data['message']= "Successfully isCheck";
        return $data;
        break;
        case 'isReject':
        if(isset($user) &&  $user->can('can_reject')) {
          $data['code']= 200;
          $data['message']= "Successfully Reject";
        } else {
          $data['code']= 204;
          $data['message']= "You are not Authorized to Reject.";
        }
        return $data;
        break;
        case 'isValidate':
         $data['code']= 200;
        $data['message']= "Successfully isValidate";
        return $data;
        break;
         case 'Addition':
         $data['code']= 200;
          $data['message']= "Successfully Added";
          return $data;
        break;
         case 'Compare':
        $data['code']= 200;
          $data['message']= "Successfully Compare";
          return $data;
        break;
        }
      }  
    }
  }

public function getVendorDetails(Request $request) {

  $vendor_details = Eas::select('eas_masters.vendor_id','vendor_master.vendor_name','vendor_master.mobile_no','vendor_master.bank_name','vendor_master.ifsc_code','vendor_master.bank_branch','eas_masters.budget_code','vendor_master.bank_code','vendor_master.bank_acc_no','eas_masters.file_number','eas_masters.sanction_total')
  ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
  ->where(['vendor_master.deleted_at'=>NULL,'eas_masters.deleted_at'=>NULL,'eas_masters.id'=>$request->eas_id,'eas_masters.status_id'=>3])->first();

  $ro_details = ReleaseOrder::select('release_order_master.id','release_order_master.ro_title','release_order_master.release_order_amount')
  ->join('eas_masters','release_order_master.eas_id','=','eas_masters.id')
  ->where(['release_order_master.deleted_at'=>NULL,'eas_masters.deleted_at'=>NULL,'release_order_master.eas_id'=>$request->eas_id ,'release_order_master.status_id'=>28])->get();

  $diary_register_no = DiaryRegister::select('diary_register_no')->orderBy('created_at', 'desc')->first();

  if(isset($vendor_details) && !empty( $vendor_details) && isset($ro_details) && !empty( $ro_details)) {
   $data['code'] = 200;
   $data['status'] ='Success';
   $data['sanction_total'] = (isset($vendor_details->sanction_total) ? $vendor_details->sanction_total:'');
   $data['vendor_name'] = (isset($vendor_details->vendor_name) ? $vendor_details->vendor_name:'');
   $data['mobile_no'] = (isset($vendor_details->mobile_no) ? $vendor_details->mobile_no:'');
   $data['bank_name'] = (isset($vendor_details->bank_name) ? $vendor_details->bank_name:'');
   $data['ifsc_code'] = (isset($vendor_details->ifsc_code) ? $vendor_details->ifsc_code:'');
   $data['bank_branch'] = (isset($vendor_details->bank_branch) ? $vendor_details->bank_branch:'');
   $data['budget_code'] = (isset($vendor_details->budget_code) ? $vendor_details->budget_code:'');
   $data['bank_code'] = (isset($vendor_details->bank_code) ? $vendor_details->bank_code:'');
   $data['bank_acc_no'] = (isset($vendor_details->bank_acc_no) ? $vendor_details->bank_acc_no:'');
   $data['file_number'] = (isset($vendor_details->file_number) ? $vendor_details->file_number:'');
   $data['diary_register_no'] = (isset($diary_register_no->diary_register_no)?$diary_register_no->diary_register_no : '');
   $data['vendor_id'] = (isset($vendor_details->vendor_id)?$vendor_details->vendor_id : '');
   $data['result'] = $ro_details;
 } else {
  $data['code'] =204;
  $data['satus'] = 'failed';
}
return $data;
}

public function getRoDetails(Request $request) {
   
  $ro_details = ReleaseOrder::select('release_order_master.status_approved_date','release_order_master.release_order_amount')
  ->where(['release_order_master.deleted_at'=>NULL,'release_order_master.id'=>$request->ro_id])->first();
        
  if(isset($ro_details) && !empty( $ro_details)) {
   $data['code'] = 200;
   $data['status'] ='Success';
    $data['release_order_amount'] = $ro_details->release_order_amount;
   // $data['current_account_number'] = $ro_details->current_account_number;
   $data['status_approved_date'] = date('d-m-Y',strtotime($ro_details->status_approved_date));
 } else {
  $data['code'] =204;
  $data['satus'] = 'failed';
}
return $data;
}

  public function removefile(Request $request)
  {
    
   try {      
          if(isset($request->id) && !empty($request->id)) { 

             if($request->type == 'gar') { 
            
              $deletefile = GAR::where('id',$request->id)->update(['uploaded_cheque'=>NULL]);
               // dd($request->id);
             }  else {
              // dd('i2');
               $deletefile = MediaMaster::where('id',$request->id)->delete();
             } 
              if(isset($deletefile) && !empty($deletefile)) {
                $data['code'] = 200;
                $data['message'] = "Document successfully deleted";
              } else {
                $data['code'] = 204;
                 $data['message'] ="Something went wrong while deleting." ;
              }
            } else  {
              $data['code'] = 204;
              $data['message'] = "id not found.";
            }
          return $data;
      } catch (\Exception $e) {
        $data['code'] = 204;
         $data['message'] = "Something went wrong.";
        return $data;
      }
            
  }

  // public function sendMail($previous_status,$changed_status,$entity_name,$vendor_name,$status_changed_by,$send_email_to) {
   public function sendMail(){
   $admin_subject = "UIDAI";
   $send_email_to = array('supriya@choicetechlab.com');
   $send_mail = Mail::send('mail.mail',
     [
       'email' => '$send_email_to',
       'previous_status' =>' $previous_status',
       'changed_status' => '$changed_status',
       'entity_name' => '$entity_name',
       'vendor_name' => '$vendor_name',
       'status_changed_by' => '$status_changed_by'
     ],
     function ($message) use ($admin_subject,$send_email_to) {
      $message->to($send_email_to)->subject($admin_subject);
      $message->from('roshani@choicetechlab.com', 'UIDAI');
    });


   if( count(Mail::failures()) > 0 ) {

        foreach(Mail::failures() as $email_address) {
            echo " - $email_address <br />";
            $data['code']= 204;
             $data['message']= "Something went wrong while updation data.".$email_address;
         }
       } else {

           $data['code']= 200;
           $data['message']= "Email sent Successfully!!";
       }
       return $data;

 }




}
