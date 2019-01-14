<?php

use App\Role;
use App\Transaction;
use App\WorkFlowTransactionMapper;
use App\MediaMaster;
use App\Entity;
use App\RejectComment;
use App\AssigeeMapper;
use App\User;
use App\CopyToMaster;

//if (!function_exists('roleEntityMapping')) {
    /**
     * Get exception response
     *
     * @param null $e
     *
     * @return void
     */
function getStatus($entity_slug) {
    try {

    $status = Entity::select('final_status','default_status','id','workflow_id','entity_slug')->where(['deleted_at' => Null, 'entity_slug' => $entity_slug])->first();

    return $status;
    } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return false;
       }   

    }

function getTransactionDetails($id,$entity_master,$entity_slug) {
  //try {
    $data['entity_details'] = Entity::select('id','workflow_id','entity_slug','final_status')->where(['deleted_at' => Null,'entity_slug' =>$entity_slug])->first();
  
    $data['added_comment'] = RejectComment::select('reject_comment.comment','reject_comment.id','reject_comment.created_at','users.name')
    ->join('users','users.id','=','reject_comment.created_by')
    //->join('roles','roles.id','=','users.role_id')
    ->join('entity_type','entity_type.id','=','reject_comment.entity_id')
    ->where(['master_id'=>$id,'entity_id'=>$data['entity_details']->id])
    ->get()->toArray();
   
    $data['documents_details'] = MediaMaster::select('media_master.file_name','media_master.document_name','media_master.master_id','media_master.id','media_master.entity_id')
        //->leftjoin('media_master','media_master.master_id','=','eas_masters.id')
        ->join('entity_type','entity_type.id','=','media_master.entity_id')
        ->where(['media_master.master_id'=>$id,'media_master.entity_id'=>$data['entity_details']->id,'media_master.deleted_at'=>null])
        ->get()->toArray();
                               
    $data['transaction_data'] = getApplicableTransaction($entity_master->status_id,$data['entity_details']->workflow_id);
    return $data;
// } catch (\Exception $e) {
            
//              Log::critical($e->getMessage());
//              app('sentry')->captureException($e);
//              Toast::error('Something went wrong!');
//              return redirect('/dashboard'); 
//        }  
} 

function getUserDetails($user_id) {
     //try {   
        if (isset($user_id) && !empty($user_id)) {

            $user_details['entity'] = User::select('entity_type.entity_slug')
           // ->join('users','users.role_id', '=','roles.id')
            ->join('user_role_mapper','user_role_mapper.user_id' ,'=', 'users.id')
            ->join('role_department_mapper','user_role_mapper.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
            ->join('permission_role','permission_role.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
            ->join('entity_type','entity_type.id' ,'=', 'permission_role.entity_id')
            ->join('permissions','permissions.id' ,'=', 'permission_role.permission_id')
            ->where('users.id', '=',$user_id)
            ->where('permissions.name', '=','can_view')
            ->distinct()
            ->where(['users.deleted_at' => Null,'entity_type.deleted_at' => Null])
            ->get('entity_slug');
     //dd($user_id);
           //  $array = User::select('entity_type.id','entity_type.entity_slug')
           // // ->join('users','users.role_id', '=','roles.id')
           //  ->join('user_role_mapper','user_role_mapper.user_id' ,'=', 'users.id')
           //  ->join('role_department_mapper','user_role_mapper.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
           //  ->join('permission_role','permission_role.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
           //  ->join('entity_type','entity_type.id' ,'=', 'permission_role.entity_id')
           //  ->join('permissions','permissions.id' ,'=', 'permission_role.permission_id')
           //  ->where('users.id', '=',$user_id)
           //  ->where('permissions.name', '=','can_view')
           //  ->distinct()
           //  ->where(['users.deleted_at' => Null,'entity_type.deleted_at' => Null])
           //  ->get()->mapWithKeys(function ($item) {
           //  return [$item['id'] => $item['entity_slug'] ];
           //  })->toArray();
           //  //dd($array);
          
             foreach($user_details['entity'] as $key => $val)
            {
                $user_details['entities'][] = $val['entity_slug'];
            }
            //dd($user_details['entities']);

          $user_details['designation'] = User::select('users.designation','location.location_name')
            ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
            ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
            ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
            ->join('location', 'departments.location_id', '=', 'location.id')
            ->where('users.id', '=', $user_id)
            ->where(['departments.deleted_at' => Null])
            ->distinct()
            ->first();
            ///dd( $user_details['designation']);

            $user_details['departments'] = User::select('departments.id','departments.slug','departments.name','users.id as user_id','location.location_name')
            ->leftjoin('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
            ->leftjoin('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
            ->leftjoin('departments', 'departments.id', '=', 'role_department_mapper.department_id')
            ->leftjoin('location', 'departments.location_id', '=', 'location.id')
            ->where('users.id', '=', $user_id)
            ->where(['departments.deleted_at' => Null])
           // ->distinct()
            ->get()->toArray();
          //  dd($user_details['departments']);
             foreach($user_details['departments'] as $key => $val)
            {
                $user_details['departments_id'][] = $val['id'];
            }
            //dd($user_details);
            $user_details['roles'] = User::select('users.id as user_id','roles.id as role_id','roles.name as role_name')
            ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
            ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
            ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
            ->where('users.id', '=', $user_id)
           // ->groupBy('role_id')
            ->where(['roles.deleted_at' => Null])
            ->distinct()
            ->get()->toArray();
                
       
            if (isset($user_details) && !empty($user_details)) {
              
                $data = $user_details;
                
            } else {
                $data= '';
            }
        } else {
              $data= '';
        }
    
        return $data;
      // } catch (\Exception $e) {
            
      //        Log::critical($e->getMessage());
      //        app('sentry')->captureException($e);
      //        Toast::error('Something went wrong!');
      //        return redirect('/dashboard'); 
      //  }  
    }

function getApplicableTransaction($status_id, $workflow_id) {
    //  try {
        $data = WorkFlowTransactionMapper::select('transactions.id','workflow_transaction_mapper.created_at','workflow_transaction_mapper.role_id','workflow_transaction_mapper.transaction_id','workflow_transaction_mapper.workflow_id','to_status.status_name as to_status_name', 'transactions.to_status as to_status_id', 'transactions.transaction_name', 'from_status.status_name as privious_status')
        ->join('transactions', 'workflow_transaction_mapper.transaction_id', '=', 'transactions.id')
        ->join('status as to_status', function ($join) {
            $join->on('to_status.id', '=', 'transactions.to_status');
        })->join('status as from_status', function ($join) {
            $join->on('from_status.id', '=', 'transactions.from_status');
        })
        ->where('workflow_transaction_mapper.workflow_id', $workflow_id)
        ->where('transactions.from_status', '=', $status_id)
        ->get()
        ->toArray();
    // dd($data);
    
        return $data;
    // } catch (\Exception $e) {
            
    //          Log::critical($e->getMessage());
    //          app('sentry')->captureException($e);
    //          Toast::error('Something went wrong!');
    //          return redirect('/dashboard'); 
    //    }  
}


 function roles_list($entity,$transaction){
     //try {
        $data = Transaction::select('transaction_role_mapper.role_id')
        ->leftjoin('transaction_role_mapper', 'transaction_role_mapper.transaction_id', '=', 'transactions.id')
        ->where('transaction_role_mapper.transaction_id', '=', $transaction)->pluck('role_id');

        foreach($data as $key => $val)
        {

            $assigned_role[] = $val;
        }

       return $assigned_role;
   // } catch (\Exception $e) {
            
   //           Log::critical($e->getMessage());
   //           app('sentry')->captureException($e);
   //           Toast::error('Something went wrong!');
   //           return redirect('/dashboard'); 
   //     }  
  }

    function checkRole($user_details,$entity,$transaction_data,$created_by) {
      //try {

      if(isset($transaction_data) && !empty($transaction_data)) {
      
        foreach($transaction_data as $value) {
          
          $roles_list = roles_list($entity,$value['transaction_id']);
 
              foreach ($user_details as $key => $roles) {
              
               if($value['transaction_name'] == 'Forward') {

                    if (isset($roles['role_id']) && in_array($roles['role_id'],$roles_list) && ($created_by == $roles['user_id'])) {
                       // $data['success'] = true;
                    //$data['transaction'] = $value['transaction_name'];
                       $result[$key] = 200;
                      
                       // $data[$key]['success'] = true;
                      
                    } else {
                         //$data['success'] = false;
                        //$data['transaction'] = $value['transaction_name'];
                        $result[$key] = 204;
                       
                        // $data['success'] = false;
                        
                    }
  
                  } else if($value['transaction_name'] == 'Return' || $value['transaction_name'] == 'Approve') {
                 
                   if (isset($roles) && (in_array($roles['role_id'],$roles_list))  && ($created_by != $roles['user_id'])) {

                       // $data[$value['transaction_name']]['code'] = 200;
                       // $data['success'] = true;
                     $result[$key] = 200;
                    } else {
                        // $data[$value['transaction_name']]['code'] = 204;
                        // $data['success'] = false;
                         $result[$key] = 204;
                    }
                } else {
                   if (isset($roles) && (in_array($roles['role_id'],$roles_list)) ) {
                       // $data[$value['transaction_name']]['code'] = 200;
                       // $data['success'] = true;
                     $result[$key] = 200;
                    } else {
                         $result[$key] = 204;
                        // $data[$value['transaction_name']]['code'] = 204;
                        // $data['success'] = false;
                    }
                } 
               // $data['success'] = true; 
               // $result[$key] = 200; 
            } 
            if(isset($result) && in_array(200,$result)) {

             $data['success'] = true;
             $data[$value['transaction_name']]['code'] = 200;
           
            } else {
             $data[$value['transaction_name']]['code'] = 204;
             $data['success'] = false;
            }  

        }
      } else {

       $data['success'] = false;
      }   
  
        return $data;  
      // } catch (\Exception $e) {
            
      //        Log::critical($e->getMessage());
      //        app('sentry')->captureException($e);
      //        Toast::error('Something went wrong!');
      //        return false;
      //  }  
    }
    
      function roleEntityMapping($user_id,$entity,$permission) 
    {

    	 try {

	         if(isset($user_id) && !empty($user_id) && isset($entity) && !empty($entity) && isset($permission) && !empty($permission))
		       {
              
		        $user_details = User::select('users.id')
		       // ->join('users','users.role_id', '=','roles.id')
		        ->join('user_role_mapper','user_role_mapper.user_id' ,'=', 'users.id')
                ->join('role_department_mapper','user_role_mapper.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
                // ->join('roles','roles.id' ,'=', 'role_department_mapper.role_id')
                // ->join('departments','departments.id' ,'=', 'role_department_mapper.department_id')
		        ->join('permission_role','permission_role.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
		        ->join('entity_type','entity_type.id' ,'=', 'permission_role.entity_id')
		        ->join('permissions','permissions.id' ,'=', 'permission_role.permission_id')
		        ->where('users.id', '=',$user_id)
		        ->where('entity_type.entity_slug',$entity)
		        ->where('permissions.name', '=',$permission)
		        ->where(['users.deleted_at' => Null,'entity_type.deleted_at' => Null])
		        ->first();
		       // dd($user_details);

		        if(isset($user_details) && !empty($user_details)) {
		           return true;
		        } else {
             //  return true;
		         return false;
		        }
		    } else {
		    	return false;
		    } 
          
       } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
            return false;
       }  
    }

    function generatePdf($data, $pdf_type,$storage_path) {
       // try {
        // $data['result']['id'] = 1;
       // dd($data);
        if(isset($data) && isset($data['result']) && !empty($data['result']) && isset($data['result']['id']) &&  !empty($data['result']['id'])) {
         $id = $data['result']['id'];
        } else {
          $id = "";
        }
     
        $year = date('Y');
        $month = date('m');
        $pdf = PDF::loadView('pdf.' . $pdf_type, compact('data'))->setPaper('A4', 'portrait');
        if (isset($id)) {
          $file_name = trim(strtolower(str_replace(" ", "-", $id . "_" . time() . "_" . $pdf_type)));
        } else {
          $file_name = trim(strtolower(str_replace(" ", "-", time() . "_" . $pdf_type)));
        }
        //dd($pdf);
        if (isset($file_name) && !empty($file_name) && isset($pdf) && !empty($pdf)) {
            $year = date('Y');
            $month = date('m');
            $path = storage_path() . "/documents/" . $storage_path . "/" . $year . "/" . $month;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $pdf->save($path . '/' . $file_name . '.pdf');
            $return_path = $storage_path . "/" . $year . "/" . $month . "/" . $file_name . '.pdf';
            if (file_exists($path . '/' . $file_name . '.pdf')) {
                return $return_path;
            } else {
                return false;
            }
        } else {
            return false;
        }
      // } catch (\Exception $e) {
            
      //        Log::critical($e->getMessage());
      //        app('sentry')->captureException($e);
      //        Toast::error('Something went wrong!');
      //        return redirect('/dashboard'); 
      //  }  
    }

     function sendMail($email_list, $subject, $request) {
    //dd($request['otp']);
      try {
        $send_mail = Mail::send('mail.mail', ['request' => $request, 'changed_by' => Auth::user()->name], function ($message) use ($subject, $email_list) {
            foreach ($email_list as $key => $to):
                $message->to($to['email'])->subject($subject);
               // $message->to('supriya@choicetechlab.com')->subject($subject);
                $message->from('noreply@choicetechlab.com', 'UIDAI');
            endforeach;
            //   $message->to($email_list)->subject($subject);
            
        });
        if (count(Mail::failures()) > 0) {
            foreach (Mail::failures() as $email_address) {
                echo " - $email_address <br />";
                $data['code'] = 200;
                $data['email_status'] = 0;
                $data['message'] = "Something went wrong while updation data." . $email_address;
            }
        } else {
            $data['code'] = 200;
            $data['email_status'] = 1;
            $data['message'] = "Email sent Successfully!!";
        }
        //dd($data);
        return $data;
      } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return redirect('/dashboard'); 
       }  
    }



    function getAssignee($entity_id,$id,$status_id){
      try {
        

      $get_assigne = AssigeeMapper::select('id','assignee as old_assignee')->orderBy('id','desc')->where(['entity_id'=>$entity_id,'master_id'=>$id])
      //->orwhere(['status_id'=>$status_id])
      ->first();

    // dd($get_assigne);
      return $get_assigne;
      } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('Something went wrong!');
             return false;
       }  
    }
     
//}

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

        $list2  = array('','ten','twenty','thirty','fourty','fifty','sixty',
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
        $words  = trim( str_replace(' ,' , ',' , trim( ucwords( $words ) ) ) , ', ' );
         //$words  = str_replace([' ','   ','   '],' ',$words);
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

    function getFinacialYear() {
    try {

      $year = date('Y');
      $month_no = date('m');
      $date = date('d');
           // dd($date);
      if(isset($month_no) && $month_no > 3 && isset($date) && $date >= 31) {

        $year = $year . "-" . $year+=1 ;

      } else {

        $year=$year-1 . "-" . $year ;
      }

      return $year;
    } catch (\Exception $e) {

     Log::critical($e->getMessage());
     app('sentry')->captureException($e);
     Toast::error('Something went wrong!');
     return false;
   }   

 }

 function getAmountInWords($val)
 {

    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $amount =  $f->format(round($val/100000))." Lakhs";
    return $amount;

 }

 function getCopyToDetails($id,$entity_id)
 {
  try {

  return CopyToMaster::select('copy_to_details.master_id','copy_to_details.id','copy_to_details.user_id','copy_to_details.department_id','location.location_name','departments.name as department_name','users.name as user_name','users.designation')
                ->join('users','users.id','=','copy_to_details.user_id')
                ->join('departments','departments.id','=','copy_to_details.department_id')
                ->join('location','location.id','=','departments.location_id')
                ->where(['master_id'=>$id,'entity_id'=>$entity_id])
                ->orderBy('id','asc')->get()->toArray();
     
      }
            catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                return false;
    }
 }
