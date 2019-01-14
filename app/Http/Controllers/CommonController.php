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
use App\Cheque;
use App\Entity;
use App\AssigeeMapper;
use App\InvoiceDetails;
use App\EasLog;
use Toast;
use Queue;
use App\Jobs\EmailQueue;
use App\ItemDetails;
use App\CopyToMaster;

class CommonController extends Controller {
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function uploadDocuments($file_upload, $documents_type, $master_id, $created_by, $entity_id, $entity_slug,$file_number) {
        try {
        if (isset($file_upload) && !empty($file_upload) && isset($documents_type) && !empty($documents_type) && isset($master_id) && !empty($master_id) && isset($created_by) && !empty($created_by) && isset($entity_slug) && !empty($entity_slug) && isset($entity_id) && !empty($entity_id) && !empty($file_number)) {
          
            $path = storage_path() . "/documents/" . $file_number . "/" . $entity_slug;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            foreach ($documents_type as $key => $value) {
                $original_file_name = strtolower(str_replace([' ', '_'], '-', $file_upload[$key]->getClientOriginalName()));
                $documents = $file_number . "/" . $entity_slug . "/" . $original_file_name;
                $file_upload[$key]->move($path, $documents);
                $insert_details = MediaMaster::create(['entity_id' => $entity_id, 'master_id' => $master_id, 'document_name' => $value, 'file_name' => $documents, 'created_by' => $created_by]);
            }
            if (isset($insert_details)) {
                $data['code'] = 200;
            } else {
                $data['code'] = 204;
            }
        } else {
            $data['code'] = 204;
        }
        return $data;
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
    }
    
    public function getUserDetails($role_id) {
 
        if (isset($role_id) && !empty($role_id)) {
            $user_details = Role::select('roles.*', 'departments.office_type_id', 'departments.city_id as location_id', 'roles.id', 'roles.name', 'users.name', 'users.id', 'departments.slug', 'departments.name as department_name', 'location.city_name')->join('users', 'users.role_id', '=', 'roles.id')->join('departments', 'departments.id', '=', 'roles.department_id')->join('location', 'location.id', '=', 'departments.office_type_id')->where('roles.id', '=', $role_id)->where(['roles.deleted_at' => Null, 'departments.deleted_at' => Null, 'users.user_status' => 'Enable'])->get();
            if (isset($user_details) && !empty($user_details)) {
                foreach ($user_details as $value) {
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
                    $data['department_name'] = $value->department_name;
                    $data['slug'] = $value->slug;
                    $data['status'] = 200;
                }
            } else {
                $data['status'] = 204;
            }
        } else {
            $data['status'] = 204;
        }
        return $data;
    }

    public function getApplicableTransaction($status_id, $workflow_id) {
       try {
        $data = WorkFlowTransactionMapper::select('transactions.id', 'workflow_transaction_mapper.*', 'to_status.status_name as to_status_name', 'transactions.to_status as to_status_id', 'transactions.transaction_name', 'from_status.status_name as privious_status')->join('transactions', 'workflow_transaction_mapper.transaction_id', '=', 'transactions.id')->join('status as to_status', function ($join) {
            $join->on('to_status.id', '=', 'transactions.to_status');
        })->join('status as from_status', function ($join) {
            $join->on('from_status.id', '=', 'transactions.from_status');
        })->where('workflow_transaction_mapper.workflow_id', $workflow_id)->where('transactions.from_status', '=', $status_id)->get();
        //dd($data['body']);
        if (!$data['body']->isEmpty()) {
            $data['code'] = '200';
           
        } else {
            $data['code'] = "204";
            $data['body'] = (object)array();
        }
        return $data;
    } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
 }

    public function updateCurrentTransaction(Request $request) {
        //try {

        $user_details = getUserDetails($this->user_id);
        $transaction_data[] = array('transaction_id' =>$request->transaction_type ,'transaction_name'=>$request->status_name);
        if(isset($user_details) && isset($user_details['roles']) && !empty($user_details['roles'])){
        $role_check = checkRole($user_details['roles'],$request->entity_slug,$transaction_data,$request->created_by);
        }
        
        if(isset($role_check) && !empty($role_check) && $role_check['success'] == true) {

            if (isset($request->status_name) && !empty($request->status_name)) {
                $previous_status = $request->status_name;
            } else {
                $previous_status = '';
            }
            
            $condition_check = $this->checkCondition($this->user, $request);
            if (isset($condition_check) && !empty($condition_check['code']) && $condition_check['code'] == 200) {

                $transaction_type = Transaction::select('transactions.id', 'transaction_name', 'to_status', 'from_status', 'status.status_name')->join('status', 'status.id', '=', 'transactions.to_status')
                ->where(['transactions.id' => $request->transaction_type, 'transactions.deleted_at' => NULL])->first();
                
                if (isset($transaction_type->to_status) && !empty($transaction_type->to_status) && isset($request->entity_slug)) {

                // if(isset($role_wise_access) && !empty($role_wise_access['code']) && $role_wise_access['code'] == 200 ) {
                    $current_date = new DateTime();
                    if(isset($request->comment) && !empty($request->comment)) {

                        $add_comment = RejectComment::create(['comment' => $request->comment,'entity_id' => $request->entity_id, 'workflow_id' => $request->workflow_id, 'created_by' => $this->user_id, 'master_id' => $request->id]);
                    }
                    if(isset($request->assignee) && !empty($request->assignee)) {

                        $add_assignee = AssigeeMapper::create(['entity_id' => $request->entity_id,'master_id' => $request->id,'assignee' => $request->assignee,'created_by' => $this->user_id,'status_id'=>$transaction_type->to_status]);
                    }

                    if(isset($request->final_status) && !empty($request->final_status) && $request->final_status == $transaction_type->to_status) {

                       $get_pdf_details = $this->getPdfDetails($request);
                      
                    }

                    $role_wise_access = $this->getUserDetailsForMail($request);
                  
                    switch ($request->entity_slug) {
                        case 'po':
                           if(isset($get_pdf_details) && $get_pdf_details['code'] = 200 && !empty($get_pdf_details['result'])) {

                              $generate_po = generatePdf($get_pdf_details, $pdf_type='purchase-order',$storage_path='generated_po');

                             // dd( $generate_po,'generate_po');

                                  if(isset($generate_po) && !empty($generate_po)) {

                                    $update_tostatus = PurchaseOrder::where('id', $request->id)->update(['po_pdf'=>$generate_po,'status_id' => $transaction_type->to_status,'status_approved_date' => $current_date]);
                                  } 
                                  // else {
                                  //   $update_tostatus = PurchaseOrder::where('id', $request->id)->update(['status_id' => $transaction_type->to_status,'status_approved_date' => $current_date,'email_status' => $role_wise_access['email_status'] ]);
                                  // }
                              
                             } else {
                            $update_tostatus = PurchaseOrder::where('id', $request->id)->update(['status_id' => $transaction_type->to_status,'status_approved_date' => $current_date ]);
                          }
                         
                        break;
                        case 'eas':

                        if(isset($get_pdf_details) && $get_pdf_details['code'] = 200 && !empty($get_pdf_details['result'])) {
 
                        $generate_eas = generatePdf($get_pdf_details, $pdf_type='eas',$storage_path='generated_eas');
                      //dd($generate_eas,'$generate_eas');
                        if(isset($generate_eas) && !empty($generate_eas)) {

                          $update_tostatus = EAS::where('id', $request->id)->update(['status_id' => $transaction_type->to_status,'status_approved_date' => $current_date,'eas_pdf'=>$generate_eas]);
                        } 
                      } else {
                        $update_tostatus = EAS::where('id', $request->id)->update(['status_id' => $transaction_type->to_status,'status_approved_date' => $current_date]);
                      }
                        break;
                        case 'ro':

                         if(isset($get_pdf_details) && $get_pdf_details['code'] = 200 && !empty($get_pdf_details['result'])) {

                              $generate_ro = generatePdf($get_pdf_details, $pdf_type='release-order',$storage_path='generated_ro');
                             //dd( $generate_ro,'generate_ro');
                                  if(isset($generate_ro) && !empty($generate_ro)) {

                                   $update_tostatus = ReleaseOrder::where('id', $request->id)->update(['ro_pdf'=>$generate_ro,'status_id' => $transaction_type->to_status, 'status_approved_date' => $current_date ]);
                                  } 
                                  // else {
                                  //  $update_tostatus = ReleaseOrder::where('id', $request->id)->update(['status_id' => $transaction_type->to_status, 'status_approved_date' => $current_date,'email_status' => $role_wise_access['email_status'] ]);
                                  // }
                              
                             } else {

                            $update_tostatus = ReleaseOrder::where('id', $request->id)->update(['status_id' => $transaction_type->to_status, 'status_approved_date' => $current_date ]);
                          }

                        break;
                        case 'gar':
                      
                        if(isset($get_pdf_details) && isset($get_pdf_details['code']) && $get_pdf_details['code'] = 200 && !empty($get_pdf_details['result']) && !empty($get_pdf_details['result'])) {
                        
                        $gar = strtolower(str_replace(' ','',$request->gar_type));
                      
                        if(isset($gar) && !empty($gar) && ($gar == 'gar29' || $gar == 'gar3' ||  $gar == 'gar30' || $gar == 'gar37' || $gar == 'gar7'))   {
                           $pdf_type = $gar;
                        } else {
                          $pdf_type = 'gar29';
                        }
                         // dd($pdf_type);
                        $generate_gar = generatePdf($get_pdf_details,$pdf_type,$storage_path='generated_gar');
                        
                         $update_tostatus = GAR::where('id', $request->id)->update(['status_id' => $transaction_type->to_status, 'status_approved_date' => $current_date,'gar_pdf'=>$generate_gar]);
                        } 
                        else {
                            $update_tostatus = GAR::where('id', $request->id)->update(['status_id' => $transaction_type->to_status, 'status_approved_date' => $current_date]);

                        }
                        break;
                    }

                    if (isset($update_tostatus) || isset($add_comment)) {
                        $data['code'] = 200;
                        $data['message'] = "Status successfully updated to " . $transaction_type->status_name;
                        $data['status_id'] = $transaction_type->to_status;
                    } else {
                        $data['code'] = 204;
                        $data['message'] = 'Something Went Wrong While updating Status.';
                    }
                // } else {
                // $data['code']= 204;
                // $data['message']= $role_wise_access['message'];
                // $data['status_id']= $transaction_type->to_status;
                // }
              } else {
                $data['code'] = 204;
                $data['message'] = "Transaction Not found.";
                
              }  
            } else {
                $data['code'] = 204;
                $data['message'] = $condition_check['message'];
            }

        } else {
            
            $data['code'] = 204;
            $data['message'] = "You are not Authorized to Changed Status.";
        }    
       
        return json_encode($data);
       //  } catch (\Exception $e) {
            
       //       Log::critical($e->getMessage());
       //       app('sentry')->captureException($e);
       //       Toast::error('Something went wrong!');
       //       return redirect('/dashboard'); 
       // }  
    }

    public function getUserDetailsForMail($request,$role = null,$email_list=null) {
        try {

        if(empty($email_list)) {
       
        $query = Role::select('users.email')->join('users', 'users.role_id', '=', 'roles.id')->where(['users.id' => $request->created_by]);
                if (isset($role) && !empty($role)) {
                    foreach ($role as $value) {
                        $query->orwhere(['roles.name' => $value]);
                    }
                }
                if (isset($send_email) && !empty($send_email)) {
                    foreach ($send_email as $value) {
                        $query->orwhere(['users.id' => $value]);
                    }
                }
                if (isset($request->assignee) && !empty($request->assignee)) {
                    $query->orwhere(['users.id' => $request->assignee]);
                }
                if (isset($request->old_assignee) && !empty($request->old_assignee) && empty($request->assignee)) {
                    $query->orwhere(['users.id' => $request->old_assignee]);
                }

            $email_list = $query->get()->toArray();
        } 
            $subject = strtoupper($request->entity_slug).' Status'; 
            $mail = sendMail($email_list, $subject, $request);
            $data['code'] = 200;
            $data['email_status'] = $mail['email_status'];
        return $data;

        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
    }

    public function checkCondition($user, $post_data) {
        try {
        $workflow_transaction_id = $post_data->transaction_type;
        if (isset($workflow_transaction_id) && !empty($workflow_transaction_id)) {
            $condition_checker = WorkFlowTransactionConditionValueMapper::select('workflow_transaction_condition_value_mapper.workflow_id', 'workflow_transaction_condition_value_mapper.id', 'workflow_transaction_condition_value_mapper.condition_id', 'workflow_transaction_condition_value_mapper.transaction_id', 'workflow_transaction_condition_value_mapper.variable_type', 'workflow_transaction_condition_value_mapper.value', 'condition_master.condition_name')->leftjoin('condition_master', 'condition_master.id', '=', 'workflow_transaction_condition_value_mapper.condition_id')->where('workflow_transaction_condition_value_mapper.workflow_id', '=', $post_data->workflow_id)->where('transaction_id', '=', $workflow_transaction_id)->get();
            if (count($condition_checker) > 0) {
                $checkValue = $this->checkValue($user, $condition_checker, $post_data);
                if (!empty($checkValue['message']) && $checkValue['code'] == 200) {
                    $data['code'] = 200;
                    $data['message'] = $checkValue['message'];
                } else {
                    $data['code'] = 204;
                    $data['message'] = $checkValue['message'];
                }
            } else {
                $data['code'] = 200;
                $data['message'] = "No Condition Define";
            }
        } else {
            $data['code'] = 204;
            $data['message'] = "Transaction Id not found.";
        }
        // }
        return $data;
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
    }


    public function checkValue($user, $condition_checker, $post_data) {
        try {
        if (count($condition_checker) > 0) {
            foreach ($condition_checker as $condition) {
                switch ($condition->condition_name) {
                    case 'isDiaryRegisterEntryDone':
                        $diary_register_entry = GAR::select('is_diary_register','gar_register_entry')->where('id', $post_data->id)->first();
                        if (isset($diary_register_entry) && $diary_register_entry->is_diary_register == 1 && $diary_register_entry->gar_register_entry == 1) {
                            $data['code'] = 200;
                            $data['message'] = "Diary and GAR Register Entry done.";
                        } else {
                            $data['code'] = 204;
                            $data['message'] = "Diary or GAR Register Entry not done.";
                        }
                        return $data;
                    break;
                    case 'isDispatchEntryDone':
                        $updateEntry = GAR::select('tally_entry', 'is_dispatch_register','is_ecr_entry')->where('id', $post_data->id)->first();
                        if ($updateEntry && $updateEntry->tally_entry == 1 && $updateEntry->is_dispatch_register == 1 && $updateEntry->is_ecr_entry == 1) {
                            $data['code'] = 200;
                            $data['message'] = "Tally Dispatch And EC Register Entry done.";
                        } else {
                            $data['code'] = 204;
                            $data['message'] = "Tally, Dispatch or EC Register Entry not done";
                        }
                        return $data;
                    break;
                    case 'isApproved':
                        if (isset($user) && $user->can('can_approve')) {
                            $data['code'] = 200;
                            $data['message'] = "Successfully Approved";
                        } else {
                            $data['code'] = 204;
                            $data['message'] = "You are not Authorized to Approved.";
                        }
                        return $data;
                    break;
                    case 'isCheck':
                        $data['code'] = 200;
                        $data['message'] = "Successfully isCheck";
                        return $data;
                    break;
                    case 'isReject':
                        if (isset($user) && $user->can('can_reject')) {
                            $data['code'] = 200;
                            $data['message'] = "Successfully Reject";
                        } else {
                            $data['code'] = 204;
                            $data['message'] = "You are not Authorized to Reject.";
                        }
                        return $data;
                    break;
                    case 'isValidate':
                        $data['code'] = 200;
                        $data['message'] = "Successfully isValidate";
                        return $data;
                    break;
                    case 'Addition':
                        $data['code'] = 200;
                        $data['message'] = "Successfully Added";
                        return $data;
                    break;
                    case 'Compare':
                        $data['code'] = 200;
                        $data['message'] = "Successfully Compare";
                        return $data;
                    break;
                }
            }
        }
    } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
    }
    public function getVendorDetails(Request $request) {
      try {
        $ro_final_status = getStatus('ro');
        $gar_final_status = getStatus('gar');
        $eas_final_status = getStatus('eas');

        // $eas_total = GAR::select(DB::raw("SUM(release_order_amount) as eas_total"))

        //         ->where(['eas_id'=>$request->eas_id,'deleted_at'=>NULL,'status_id'=>$gar_final_status->final_status])
        //         ->first()->eas_total;

        $eas_total = GAR::select(DB::raw("SUM(release_order_master.release_order_amount) as eas_total"))
                ->leftjoin('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
                ->leftjoin('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
                ->where(['release_order_master.eas_id'=>$request->eas_id,'eas_masters.deleted_at'=>NULL,'release_order_master.status_id'=>$ro_final_status->final_status])
                ->first();        

        $vendor_details = Eas::select('eas_masters.vendor_id', 'vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch', 'eas_masters.budget_code', 'vendor_master.bank_code', 'vendor_master.bank_acc_no', 'eas_masters.file_number', 'eas_masters.sanction_total')->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')->where(['vendor_master.deleted_at' => NULL,'vendor_master.vendor_status' => 'Enable', 'eas_masters.deleted_at' => NULL, 'eas_masters.id' => $request->eas_id, 'eas_masters.status_id' => $eas_final_status->final_status])->first();
        //dd($vendor_details);
    
        $ro_details = ReleaseOrder::select('release_order_master.id', 'release_order_master.ro_title', 'release_order_master.release_order_amount')->join('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')->where(['release_order_master.deleted_at' => NULL, 'eas_masters.deleted_at' => NULL, 'release_order_master.eas_id' => $request->eas_id, 'release_order_master.status_id' => $ro_final_status->final_status])->get();

        $diary_register_no = DiaryRegister::select('diary_register_no')->orderBy('created_at', 'desc')->first();

        if (isset($vendor_details) && !empty($vendor_details) && isset($ro_details) && !empty($ro_details)) {
            $data['code'] = 200;
            $data['status'] = 'Success';
            $data['sanction_total'] = (isset($vendor_details->sanction_total) ? $vendor_details->sanction_total : '');
            $data['vendor_name'] = (isset($vendor_details->vendor_name) ? $vendor_details->vendor_name : '');
            $data['mobile_no'] = (isset($vendor_details->mobile_no) ? $vendor_details->mobile_no : '');
            $data['bank_name'] = (isset($vendor_details->bank_name) ? $vendor_details->bank_name : '');
            $data['ifsc_code'] = (isset($vendor_details->ifsc_code) ? $vendor_details->ifsc_code : '');
            $data['bank_branch'] = (isset($vendor_details->bank_branch) ? $vendor_details->bank_branch : '');
            $data['budget_code'] = (isset($vendor_details->budget_code) ? $vendor_details->budget_code : '');
            $data['bank_code'] = (isset($vendor_details->bank_code) ? $vendor_details->bank_code : '');
            $data['bank_acc_no'] = (isset($vendor_details->bank_acc_no) ? $vendor_details->bank_acc_no : '');
            $data['file_number'] = (isset($vendor_details->file_number) ? $vendor_details->file_number : '');
            $data['diary_register_no'] = (isset($diary_register_no->diary_register_no) ? $diary_register_no->diary_register_no : '');
            $data['vendor_id'] = (isset($vendor_details->vendor_id) ? $vendor_details->vendor_id : '');
            $data['eas_total'] = (isset($eas_total) ? $eas_total : 0);
            $data['result'] = $ro_details;
        } else {
            $data['code'] = 204;
            $data['satus'] = 'failed';
        }
        return $data;
    } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
    }


    public function getRoDetails(Request $request) {
       try {
        $ro_final_status = getStatus('ro');
        $ro_details = ReleaseOrder::select('eas_masters.vendor_id','release_order_master.status_approved_date', 'release_order_master.release_order_amount','budget_list.budget_code','budget_list.amount','eas_masters.sanction_total','release_order_master.eas_id','vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch','vendor_master.bank_code', 'vendor_master.bank_acc_no', 'eas_masters.file_number')
        ->leftjoin('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
        ->leftjoin('budget_list', 'budget_list.id', '=', 'eas_masters.budget_code')
        ->leftjoin('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
       // ->where(['vendor_master.deleted_at'=>NULL,'eas_masters.deleted_at'=>NULL,'release_order_master.deleted_at' => NULL,'release_order_master.id' => $request->ro_id ])
        ->where(['release_order_master.id' => $request->ro_id ])
        ->first();

        $diary_register_no = DiaryRegister::select('diary_register_no')->orderBy('created_at', 'desc')->first();

        $eas_total = GAR::select(DB::raw("SUM(release_order_master.release_order_amount) as eas_total"))
        ->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
        ->join('eas_masters', 'release_order_master.eas_id', '=', 'eas_masters.id')
        ->where(['release_order_master.eas_id'=>$ro_details['eas_id'],'eas_masters.deleted_at'=>NULL,'release_order_master.status_id'=>$ro_final_status->final_status])
        ->first();

        if (isset($ro_details) && !empty($ro_details)) {
            $data['code'] = 200;
            $data['status'] = 'Success';
            $data['release_order_amount'] = $ro_details->release_order_amount;
            $data['amount'] = $ro_details->amount;
            $data['budget_code'] = $ro_details->budget_code;
            $data['sanction_total'] = $ro_details->sanction_total;
            $data['status_approved_date'] = date('d-m-Y', strtotime($ro_details->status_approved_date));
            $data['vendor_name'] = (isset($ro_details->vendor_name) ? $ro_details->vendor_name : '');
            $data['mobile_no'] = (isset($ro_details->mobile_no) ? $ro_details->mobile_no : '');
            $data['bank_name'] = (isset($ro_details->bank_name) ? $ro_details->bank_name : '');
            $data['ifsc_code'] = (isset($ro_details->ifsc_code) ? $ro_details->ifsc_code : '');
            $data['bank_branch'] = (isset($ro_details->bank_branch) ? $ro_details->bank_branch : '');
            $data['budget_code'] = (isset($ro_details->budget_code) ? $ro_details->budget_code : '');
            $data['bank_code'] = (isset($ro_details->bank_code) ? $ro_details->bank_code : '');
            $data['bank_acc_no'] = (isset($ro_details->bank_acc_no) ? $ro_details->bank_acc_no : '');
            $data['file_number'] = (isset($ro_details->file_number) ? $ro_details->file_number : '');
            $data['diary_register_no'] = (isset($diary_register_no->diary_register_no) ? $diary_register_no->diary_register_no : '');
            $data['vendor_id'] = (isset($ro_details->vendor_id) ? $ro_details->vendor_id : '');
            $data['eas_total'] = (isset($eas_total->eas_total) ? $eas_total->eas_total : 0);

        } else {
            $data['code'] = 204;
            $data['satus'] = 'failed';
        }
        return $data;
    } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
 }


    public function removefile(Request $request) {
        try {
            if (isset($request->id) && !empty($request->id)) {
                if (isset($request->type) && $request->type == 'gar') {
                    $deletefile = GAR::where('id', $request->id)->update(['uploaded_cheque' => NULL]);  
                } else {
                    // dd('i2');
                    $deletefile = MediaMaster::where('id', $request->id)->delete();
                }
                if (isset($deletefile) && !empty($deletefile)) {
                    $data['code'] = 200;
                    $data['message'] = "Successfully Deleted";
                } else {
                    $data['code'] = 204;
                    $data['message'] = "Something went wrong while deleting.";
                }
            } else {
                $data['code'] = 204;
                $data['message'] = "id not found.";
            }
            return $data;
        }
        catch(\Exception $e) {
            $data['code'] = 204;
            $data['message'] = "Something went wrong.";
            return $data;
        }
    }
    // public function sendMail($previous_status,$changed_status,$entity_name,$vendor_name,$status_changed_by,$send_email_to) {


    public function getPdfDetails($request) {

       //dd($request);
     // try {
        if(isset($request->entity_slug) && !empty($request->entity_slug))
        {

            switch($request->entity_slug) {

            case 'eas':
                      
            $result = Eas::select('eas_masters.status_id','eas_masters.file_number','departments.name as department_name','location.location_name',
              'eas_masters.sanction_purpose','eas_masters.sanction_total','budget_list.budget_code','eas_masters.validity_sanction_period','eas_masters.id','eas_masters.sanction_title','eas_masters.competent_authority','vendor_master.vendor_name','eas_masters.vendor_id','eas_masters.cfa_dated','eas_masters.cfa_designation','eas_masters.cfa_note_number','eas_masters.fa_number','eas_masters.fa_dated','eas_masters.whether_being_issued_under','eas_masters.date_issue','departments.slug','eas_masters.serial_no_of_sanction','budget_list.budget_head_of_acc','eas_masters.date_issue','budget_list.broad_description')
              ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
              ->join('departments','departments.id','=','eas_masters.department_id')
              ->join('location','location.id','=','departments.location_id')
              ->join('budget_list','budget_list.id','=','eas_masters.budget_code')
              ->join('status','status.id','=','eas_masters.status_id')
              ->where('eas_masters.id' ,'=',$request->id)
              ->where(['eas_masters.deleted_at'=> NULL])->first();

            $result['item_details'] = ItemDetails::select('id','eas_id','category','item','qty','unit_price_tax','total_unit_price_tax')->where(['deleted_at'=>NULL,'eas_id'=>$request->id])->orderBy('id','asc')->get()->toArray();  

             $result['sanction_total_in_word'] = number_to_word($result->sanction_total);
             //dd( $result['sanction_total_in_word']);

            $result['copy_to_details'] = getCopyToDetails($request->id,$request->entity_id);
       
            break;

            case 'po':
                      
            $result = PurchaseOrder::select('purchase_order_masters.eas_id','purchase_order_masters.subject','purchase_order_masters.subject','purchase_order_masters.email_users','status.status_name','eas_masters.sanction_total','vendor_master.vendor_name','purchase_order_masters.date_of_bid','purchase_order_masters.id','purchase_order_masters.fa_date','eas_masters.fa_number','departments.slug as dept_slug','departments.name as department_name','eas_masters.file_number','vendor_master.address as vendor_address', 'purchase_order_masters.bid_number')
                    ->join('status','status.id','=','purchase_order_masters.status_id')
                    ->leftjoin('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
                    ->leftjoin('departments','departments.id','=','eas_masters.department_id')
                    ->leftjoin('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                    ->where('purchase_order_masters.id' ,'=',$request->id)
                    ->where(['purchase_order_masters.deleted_at'=> NULL,'status.deleted_at'=>NULL,'vendor_master.deleted_at'=>NULL,'eas_masters.deleted_at'=>NULL])->first();

            $result['item_details'] = ItemDetails::select('id','eas_id','category','item','qty','unit_price_tax','total_unit_price_tax')->where(['deleted_at'=>NULL,'eas_id'=>$result->eas_id])->orderBy('id','asc')->get()->toArray();  
           
            $result['total_price_tax'] = ItemDetails::select(DB::raw("SUM(total_unit_price_tax) as total_price_tax"))->where(['deleted_at'=>NULL,'eas_id'=>$result->eas_id])->first();  
           // dd($result['total_price_tax']['total_price_tax']);  
               //  ->first();
            $result['copy_to_details'] = getCopyToDetails($request->id,$request->entity_id);
             
            break;

             case 'ro':
        
            $result = ReleaseOrder::select('eas_masters.department_id','eas_masters.serial_no_of_sanction','release_order_master.id','release_order_master.ro_title','release_order_master.release_order_amount','eas_masters.sanction_total','release_order_master.email_users','vendor_master.vendor_name', 'vendor_master.mobile_no','vendor_master.bank_name','vendor_master.ifsc_code', 'vendor_master.bank_branch','vendor_master.bank_code','vendor_master.bank_acc_no','departments.name as department_name','departments.slug as dept_slug','release_order_master.copy_to','users.name','budget_list.budget_code','budget_list.budget_head_of_acc','budget_list.broad_description','departments.name as department_name','eas_masters.file_number','location.office_address','eas_masters.date_issue','eas_masters.validity_sanction_period')
            ->join('eas_masters','eas_masters.id' ,'=', 'release_order_master.eas_id')
            ->join('budget_list','budget_list.id' ,'=', 'eas_masters.budget_code')
           ->join('departments','eas_masters.department_id' ,'=', 'departments.id')
           ->join('location', 'departments.location_id', '=', 'location.id')
           ->leftjoin('users','release_order_master.copy_to' ,'=', 'users.id')
           ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
           ->where('release_order_master.id' ,'=',$request->id)
           ->where(['release_order_master.deleted_at'=> NULL,'vendor_master.deleted_at'=>NULL])->first();

          if(isset($result) && !empty($result)) {

           $result['invoice_details'] = InvoiceDetails::select('id','ro_id','invoice_no','agency_name','qty','period','amount_payment','sla_amount','applicable_taxes','withheld_amount','net_payable_amount')->where(['deleted_at'=>NULL,'ro_id'=>$request->id])->orderBy('id','asc')->get()->toArray();
        
          $result['finacial_year'] = getFinacialYear();
          $result['sanction_total_in_word'] = number_to_word($result->sanction_total);
          $result['release_order_amount_in_word'] = number_to_word($result->release_order_amount);
      
          $result['dd_name'] = User::select('users.name as users_name')
            ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
            ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
            ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
            ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
            ->where('departments.id', '=',$result->department_id)
            ->where('roles.name', '=','DD')
            ->where(['departments.deleted_at' => Null])
            ->first();
            //dd($result['dd_name']->users_name);
          $result['period'] = date_diff(($result->date_issue),($result->validity_sanction_period));
          $result['total_payable_amount'] = InvoiceDetails::select(DB::raw("SUM(net_payable_amount) as total_payable_amount"))->where(['deleted_at'=>NULL,'ro_id'=>$request->id])->first();  
           // dd($result['total_payable_amount']['total_payable_amount']);
          }
              //  ->first();
           $result['copy_to_details'] = getCopyToDetails($request->id,$request->entity_id);
        
            break;

            case 'gar':

            $result = GAR::select('gar.is_ecr_entry','gar.gar_register_entry','gar.gar_pdf','gar.ld_amount','gar.with_held_amount','gar.id','gar.ro_id', 'gar.release_order_amount', 'gar.is_diary_register', 'gar.gar_bill_type', 'gar.amount_paid','gar.actual_payment_amount', 'gar.copy_to', 'gar.status_id', 'gar.email_users', 'gar.is_dispatch_register', 'gar.tally_entry', 'gar.amount_used_till_date', 'gar.created_by','gar.tds_amount', 'gar.other_amount', 'gar.gst_amount', 'gar.forwarding_letter', 'gar.gst_tds_amount','budget_list.budget_shortcode','budget_list.amount','vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch','vendor_master.bank_code', 'vendor_master.bank_acc_no')
                    ->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
                    ->leftjoin('eas_masters','eas_masters.id' ,'=', 'release_order_master.eas_id')
                    ->leftjoin('budget_list', 'budget_list.id', 'eas_masters.budget_code')
                    ->leftjoin('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                    ->where('gar.id' ,'=',$request->id)
                    ->where(['gar.deleted_at'=> NULL,'vendor_master.deleted_at'=>NULL,'eas_masters.deleted_at'=>NULL])
                    ->first();

                  //  ->first();
            $result['copy_to_details'] = getCopyToDetails($request->id,$request->entity_id);

           // dd($result);
            break;

            case 'cheque':
            $cheque_id = explode(",", $request->selected_cheque_id);
            $total_amount = 0;
            foreach ($cheque_id as $key => $value) {
                // dd($value);
                $result[] = Cheque::select('cheque_master.*','gar.cheque_id','release_order_master.eas_id','eas_masters.vendor_id','vendor_master.vendor_name','vendor_master.vendor_name', 'vendor_master.mobile_no', 'vendor_master.bank_name', 'vendor_master.ifsc_code', 'vendor_master.bank_branch','vendor_master.bank_code', 'vendor_master.bank_acc_no')
                ->leftjoin('gar', 'gar.cheque_id', '=', 'cheque_master.id')
                ->leftjoin('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
                ->leftjoin('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')
                ->leftjoin('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                ->where('cheque_master.forwarding_letter_id' ,NULL)
                ->where('cheque_master.id',$value)
                ->groupBy('cheque_master.id')
                ->first();

                $total_amount = (Cheque::select('cheque_amount')->where('cheque_master.id',$value)->first()->cheque_amount) + $total_amount;
               //  $eas_total = GAR::select(DB::raw("SUM(cheque_amount) as eas_total"))
               // ->where(['eas_id'=>$request->eas_id,'deleted_at'=>NULL,'status_id'=>$gar_final_status->final_status])
               // ->first()->eas_total;

               
            }
            
            // dd($total_amount);

                // dd($result);
            
            break;
            
            
            }
        }

            
                if(isset($result) && !empty($result)) {
                    $data['code'] = 200;
                    $data['result'] = $result;
                    if (isset($total_amount) && $total_amount > 0) {
                        $data['total_amount'] = $total_amount;
                    }
                } else {
                    $data['code'] = 204;
                }       
            
        return $data;
       //  } catch (\Exception $e) {
            
       //       Log::critical($e->getMessage());
       //       app('sentry')->captureException($e);
       //       Toast::error('Something went wrong!');
       //       return redirect('/dashboard'); 
       // }  

    }

    public function downloadFile($entity_id,$master_id)
    { 
        try {

         $file_name = MediaMaster::select('file_name')
                ->where('media_master.entity_id',$entity_id)
                ->where('media_master.master_id',$master_id) 
                ->first();

        return response()->download(storage_path("documents/{$file_name->file_name}"));
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
            return redirect()->back();

       }  
    }

    public function storeLogDetails($id,$entity_id,$requestData)
    {
        try {
          $data = json_encode($requestData);
    
          $log_details = EasLog::create([
                            'entity_id'   => $id,
                            'entity_type_id' => $entity_id,
                            'detail_data' => $data]);
          return $log_details;
          } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  

    }

    public function revisionLog($id,$entity_slug)
    {
   
     if(isset($entity_slug) && !empty($entity_slug)) {

        switch($entity_slug) {

            case 'eas':

                try {
                    $entity_type_id = Entity::select('id')->where(['deleted_at' => Null,'type_name' =>"EAS"])->first(); 
                
                    $eas_title = Eas::select('sanction_title')->where('id',$id)->get();

                    $revisions = EasLog::select('log_details.id','log_details.entity_id','log_details.entity_type_id','log_details.created_at','users.name')
                            ->join('eas_masters','eas_masters.id','=','log_details.entity_id')
                            ->join('users','users.id','=','eas_masters.created_by')
                            ->where('log_details.entity_id',$id)
                            ->where('log_details.entity_type_id',$entity_type_id->id)
                            ->orderBy('log_details.created_at','desc')->get();
                   // dd($entity_slug);
                   
                    if(!empty($revisions) && !empty($revisions)) {
                       return view('eas.revision',compact('revisions','id','entity_slug')); 
                   }else{
                         Toast::error('No data found.');
                        return Redirect::to('/eas'); 
                    }
                } catch (\Exception $e) {
                 
                      Log::critical($e->getMessage());
                      app('sentry')->captureException($e);

                      Toast::error('Something went wrong!');
                      return redirect('/eas');
                }
            break;

            case 'po':

                try {
                    $entity_type_id = Entity::select('id')->where(['deleted_at' => Null,'type_name' =>"PO"])->first();
                    $revisions = EasLog::select('log_details.id','log_details.entity_id','log_details.entity_type_id','log_details.created_at','users.name')
                            ->join('purchase_order_masters','purchase_order_masters.id','=','log_details.entity_id')
                            ->join('users','users.id','=','purchase_order_masters.created_by')
                            ->where('log_details.entity_id',$id)
                            ->where('log_details.entity_type_id',$entity_type_id->id)
                            ->orderBy('log_details.created_at','desc')->paginate(10);
           
                    if(!empty($revisions) && count($revisions)>0) {
                      return view('purchase_order.revision',compact('revisions','id','entity_slug'));
                    }else{
                         Toast::error('No data found.');
                        return Redirect::to('purchase-order'); 
                    }
             } catch (\Exception $e) {

                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something Went wrong.');
                return redirect('purchase-order'); 
          } 
        break;

        case 'ro':

            try {
                $entity_type_id = Entity::select('id')->where(['deleted_at' => Null,'type_name' =>"RO"])->first();
             
                $revisions = EasLog::select('log_details.id','log_details.entity_id',
                        'log_details.entity_type_id','log_details.created_at','users.name')
                        ->join('release_order_master','release_order_master.id','=','log_details.entity_id')
                        ->join('users','users.id','=','release_order_master.created_by')
                        ->where('log_details.entity_id',$id)
                        ->where('log_details.entity_type_id',$entity_type_id->id)
                        ->orderBy('log_details.created_at','desc')->paginate(10);
                   if(!empty($revisions) && count($revisions)>0) {
                        return view('/ro.revision',compact('revisions','id','entity_slug'));
                    }else{
                         Toast::error('No data found.');
                        return Redirect::to('/ro'); 
                    }
            } catch (\Exception $e) {
                    Log::critical($e->getMessage());
                    app('sentry')->captureException($e);
                    Toast::error('Something went wrong!');
                      return redirect('/release-order');
            } 
        break;

        case 'gar':

           try {
                $entity_type_id = Entity::select('id')->where(['deleted_at' => Null,'type_name' =>"GAR"])->first();
             
                $revisions = EasLog::select('log_details.id','log_details.entity_id','log_details.entity_type_id','log_details.created_at','users.name')
                        ->join('gar','gar.id','=','log_details.entity_id')
                        ->join('users','users.id','=','gar.created_by')
                        ->where('log_details.entity_id',$id)
                        ->where('log_details.entity_type_id',$entity_type_id->id)
                        ->orderBy('log_details.created_at','desc')->paginate(10);

                if(!empty($revisions) && count($revisions)>0) {

                return view('gar.revision',compact('revisions','id','entity_slug'));
            } else {
               
                Toast::error('No data found.');
                return Redirect::to('gar');       
            }
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
              return redirect('gar');
            } 
            break;

           }

        }
    }

    public function logView(Request $request)
    {

    $entity_slug = $request->entity_slug;
        
    if(isset($entity_slug) && !empty($entity_slug)) {

            $id = $request->get('id');
            $data = EasLog::select('detail_data')->where('id',$id)->first();
            $old_data = json_decode($data->detail_data);
            $entityId = $request->get('entityId');
        switch($entity_slug) {

        case 'eas':
            try {
               
                $eas_latest_data = Eas::select('eas_masters.sanction_title','eas_masters.sanction_purpose','eas_masters.competent_authority','eas_masters.serial_no_of_sanction','eas_masters.file_number','eas_masters.sanction_total','eas_masters.date_issue','eas_masters.budget_code','eas_masters.validity_sanction_period','vendor_master.vendor_name','vendor_master.email','vendor_master.mobile_no','vendor_master.bank_name','vendor_master.bank_branch','vendor_master.bank_acc_no','vendor_master.ifsc_code','vendor_master.bank_code','eas_masters.cfa_note_number','eas_masters.cfa_designation','eas_masters.cfa_dated','eas_masters.fa_number','eas_masters.fa_dated','eas_masters.whether_being_issued_under','status.status_name as Status')
                            ->join('status','status.id','=','eas_masters.status_id')
                            ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                            ->where('eas_masters.id',$entityId)
                            ->first()
                            ->toArray();
                $eas_id = Eas::select('id')
                            ->where('id',$entityId)
                            ->first();
                $eas_data = Eas::select('eas_masters.sanction_title','eas_masters.sanction_purpose','eas_masters.competent_authority','eas_masters.serial_no_of_sanction','eas_masters.file_number','eas_masters.sanction_total','eas_masters.date_issue','eas_masters.budget_code','eas_masters.validity_sanction_period','eas_masters.cfa_note_number','eas_masters.cfa_designation','eas_masters.cfa_dated','eas_masters.fa_number','eas_masters.fa_dated','eas_masters.whether_being_issued_under')
                            ->where('eas_masters.id',$entityId)
                            ->first()
                            ->toArray();          
                         // dd($eas_latest_data);

                 $latest_collection = collect($eas_data);
                 $old_collection = collect($old_data);
                 //dd($collection1);

                    $diff = $latest_collection->diffAssoc($old_collection);

                    $differ = $diff->all(); 

                return view('eas.view',compact('eas_id','old_data','eas_latest_data','entity_slug','differ'));
            } catch (\Exception $e) {
                 
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);

                  Toast::error('Something went wrong!');
                  return redirect('/eas');
            }

        break;

        case 'po':
           try {
           
            $po_latest_data = PurchaseOrder::select('vendor_master.vendor_name','vendor_master.address as vendor_address','purchase_order_masters.subject','purchase_order_masters.bid_number','purchase_order_masters.date_of_bid','purchase_order_masters.title_of_bid','status.status_name as Status')
                ->join('eas_masters', 'eas_masters.id', '=', 'purchase_order_masters.eas_id')
                ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                ->join('status','status.id','=','purchase_order_masters.status_id')
                ->where('purchase_order_masters.id',$entityId)->first()->toArray();

                $po_id = PurchaseOrder::select('id')
                            ->where('id',$entityId)
                            ->first();

            $po_data = PurchaseOrder::select('purchase_order_masters.title_of_bid','purchase_order_masters.subject','purchase_order_masters.date_of_bid','purchase_order_masters.bid_number')
                ->where('purchase_order_masters.id',$entityId)->first()->toArray();

                 $latest_collection = collect($po_data);
                 $old_collection = collect($old_data);

                    $diff = $latest_collection->diffAssoc($old_collection);

                    $differ = $diff->all(); 

            return view('purchase_order.view',compact('po_id','old_data','po_latest_data','entity_slug','differ'));
         } catch (\Exception $e) {

        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went wrong.');
        return redirect('purchase-order'); 
    }
        break;

        case 'ro':
            try {
          
            $ro_latest_data = ReleaseOrder::select('release_order_master.ro_title as 
               Release Order Title','eas_masters.sanction_title as EAS Name','vendor_master.vendor_name','vendor_master.mobile_no','vendor_master.bank_name','vendor_master.bank_branch','vendor_master.ifsc_code as IFSC Code','eas_masters.budget_code','eas_masters.sanction_total as Total Sanctioned amount','vendor_master.bank_code','vendor_master.bank_acc_no as Current/ Cash Credit Account No','release_order_master.release_order_amount','status.status_name as Status')
                     ->join('eas_masters','eas_masters.id','=','release_order_master.eas_id')
                     ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id')
                     ->join('status','status.id','=','release_order_master.status_id')
                     ->where('release_order_master.id',$entityId)->first()->toArray();

                     $ro_id = ReleaseOrder::select('id')
                            ->where('id',$entityId)
                            ->first();

                     $ro_data = ReleaseOrder::select('release_order_master.id','release_order_master.status_id','release_order_master.ro_title','release_order_master.release_order_amount','release_order_master.created_by')
                     ->where('release_order_master.id',$entityId)->first()->toArray();

                 $latest_collection = collect($ro_data);
                 $old_collection = collect($old_data);

                    $diff = $latest_collection->diffAssoc($old_collection);

                    $differ = $diff->all(); 
           
            return view('/ro.view',compact('ro_id','old_data','ro_latest_data','entity_slug','differ'));
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
              return redirect('/release-order');
            }
        break;

        case 'gar':
               try {
          
            $gar_latest_data = GAR::select('eas_masters.sanction_title as 
               EAS Title', 'release_order_master.ro_title as Ro Title', 'vendor_master.vendor_name', 'vendor_master.mobile_no as Vendor Contact Number', 'vendor_master.bank_name','vendor_master.bank_branch', 'vendor_master.ifsc_code as IFSC Code',  'eas_masters.budget_code',  'vendor_master.bank_code','gar.gst_amount as GST Amount','gar.tds_amount as TDS Amount','gar.gst_tds_amount as TDS On GST Amount','gar.ld_amount as LD Or Penalty Amount','gar.with_held_amount as Withheld Amount','gar.other_amount','vendor_master.bank_acc_no as Current/ Cash Credit Account No', 'gar.release_order_amount',  'status.status_name as Status')
                ->join('release_order_master', 'release_order_master.id', 'gar.ro_id')->leftjoin('eas_masters', 'eas_masters.id', 'release_order_master.eas_id')->leftjoin('vendor_master', 'vendor_master.id', 'eas_masters.vendor_id')->leftjoin('status', 'status.id', 'gar.status_id')->where('gar.id',$entityId)->first()->toArray();

                 $gar_id = GAR::select('id')
                            ->where('id',$entityId)
                            ->first();
                 $gar_data = GAR::select('gar.gst_tds_amount','gar.ld_amount','gar.tds_amount','gar.gst_amount','gar.with_held_amount', 'gar.release_order_amount', 'gar.actual_payment_amount','gar.other_amount')
                          ->where('gar.id',$entityId)->first()->toArray();


                 $latest_collection = collect($gar_data);
                 $old_collection = collect($old_data);

                    $diff = $latest_collection->diffAssoc($old_collection);

                    $differ = $diff->all(); 

            return view('gar.view',compact('gar_id','old_data','gar_latest_data','entity_slug','differ'));
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
              return redirect('gar');
            }
        break;

        }
    }
    
}
function number_to_word( $num = '' )
{
    $num    = ( string ) ( ( int ) $num );

    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );

        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );

        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');

        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');

        $list3  = array('','thousand','million','billion','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');

        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );

        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';
            if( $tens < 20 )
            {
                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            }
            else
            {
                $tens   = ( int ) ( $tens / 10 );
                $tens   = ' ' . $list2[$tens] . ' ';
                $singles    = ( int ) ( $num_part % 10 );
                $singles    = ' ' . $list1[$singles] . ' ';
            }
            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }

        $commas = count( $words );
        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }
        $words  = implode( ', ' , $words );
        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , trim_all( ucwords( $words ) ) ) , ', ' );
        if( $commas )
        {
            $words  = str_replace_last( ',' , ' and' , $words );
        }

        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}

 public function updateCopyTo(Request $request) {
        //try {
    
           if (isset($request) && !empty($request)) {

                  $delete = CopyToMaster::where(['master_id'=>$request->id,'entity_id'=>$request->entity_id])->delete();
                    foreach ($request->department_id as $key => $value) {

                       $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$request->id,'department_id'=>$value,'user_id'=>$request->user_id[$key]]);
                      
                    }
               // dd($create_invoice);
      
                if (isset($create_invoice) && !empty($create_invoice)) {
                    $data['code'] = 200;
                    $data['message'] = "Successfully Updated";
                } else {
                    $data['code'] = 204;
                    $data['message'] = "Something went wrong while updating.";
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

     public function addCopyTo(Request $request) {
        //try {
       //  dd($request);
           if (isset($request) && !empty($request->department_id) && !empty($request->user_id) && !empty($request->id) && !empty($request->entity_id)) {


                       $create_copy_to = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$request->id,'department_id'=>$request->department_id,'user_id'=>$request->user_id]);
                      
                    //}
               // dd($create_invoice);
      
                if (isset($create_copy_to) && !empty($create_copy_to)) {
                      Toast::success('Copy To Successfully Added.');
                    return redirect()->back();
                } else {
                     Toast::error('Something went wrong while adding!');
                    return redirect()->back();
                }
            } else {
                 Toast::error('Department and User is required.');
                return redirect()->back();
            }
       
        // }
        // catch(\Exception $e) {
        //     $data['code'] = 204;
        //     $data['message'] = "Something went wrong.";
        //     return $data;
        // }
    }

    public function deleteCopyTo($id) {
        //try {
     //dd($request);
            if (isset($id) && !empty($id)) {
               
                    $delete = CopyToMaster::where('id', $id)->delete();
               
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

     public function getDepartmentWiseUserList($department_id)
    {
        //try {
        $users = User::select('users.id','users.name')
                ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                ->where(['departments.id'=>$department_id,'users.user_status' => 'Enable'])
                ->distinct()
                ->orderBy('users.id','desc')
                ->get()->toArray();
       
      if (isset($users) && !empty($users)) {
        $data['users'] = $users;
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
 
}
