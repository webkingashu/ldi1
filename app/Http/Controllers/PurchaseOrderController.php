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
use App\PurchaseOrder;
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
use App\ItemDetails;
use App\CopyToMaster;
/**
 * This class provides all operations to manage the PurchaseOrder data 
 *
 */

class PurchaseOrderController extends Controller
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
    public function index(Request $request)
    {  
      //  $request->session()->forget('eas_id');
        if(roleEntityMapping($this->user_id,'po','can_view')) {

          try {
           //$role_details=getUserDetails($this->user_id);
           $keyword = $request->get('search');
           $perPage = 25;

                 if (!empty($keyword)) {
                    $purchase_order_masters = PurchaseOrder::where('vendor_name', 'LIKE', "%$keyword%")
                    ->orWhere('vendor_address', 'LIKE', "%$keyword%")
                    ->orWhere('subject', 'LIKE', "%$keyword%")
                    ->orWhere('bid_number', 'LIKE', "%$keyword%")
                    ->orWhere('date_of_bid', 'LIKE', "%$keyword%")
                    ->orWhere('title_of_bid', 'LIKE', "%$keyword%")
                    ->orWhere('department_name', 'LIKE', "%$keyword%")
                    ->latest()->paginate($perPage);
                } else {

                 $user_details = getUserDetails($this->user_id);
                       
                if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){
    
                    $purchase_order_listing = PurchaseOrder::select('purchase_order_masters.title_of_bid','purchase_order_masters.id','purchase_order_masters.subject','purchase_order_masters.eas_id','purchase_order_masters.date_of_bid','purchase_order_masters.status_id','status.status_name','eas_masters.sanction_title','purchase_order_masters.created_at','purchase_order_masters.bid_number','vendor_master.vendor_name','departments.name as department_name') 
                    ->join('status','status.id','=','purchase_order_masters.status_id')
                    ->join('eas_masters','eas_masters.id','=','purchase_order_masters.eas_id')
                    ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                    ->join('departments','departments.id','=','eas_masters.department_id')
                    ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                   // ->where(['purchase_order_masters.deleted_at'=>Null,'status.deleted_at'=>Null,'vendor_master.deleted_at'=>NULL])
                    ->orderBy('purchase_order_masters.id','desc')
                    ->latest()->paginate($perPage);
                    //dd($purchase_order_listing);
                  }  
                }
        //dd($purchase_order_listing);
        $entity_details = getStatus('po');
        if(isset($purchase_order_listing) && !empty($purchase_order_listing)) {

            return view('purchase_order.index', compact('purchase_order_listing','entity_details'));
        } else {
            return view('purchase_order.index')->with('danger','Data Not found');
        }
    } catch (\Exception $e) {

        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
        return redirect('/dashboard'); 

    }

} else {
    Toast::error('You are not Authorized to perform this Action.');
    return Redirect::to('/dashboard');
}  

}

    /**
     * To create new PurchaseOrder 
     *
     * @return \Illuminate\Http\Response
     * 
     * @var string $keyword Returns the user input for search.
     * @var int $perPage To store number of entries for listing
     * @var string $eas Vendor Id is mapped in EAS master table. Sanction title is listed in dropdown,on selection vendor name and address is fetch from eas table against selected EAS
     * @var bool $roles Returns the data from Role table.
     *
     */
    public function create()
    {
        if(roleEntityMapping($this->user_id,'po','can_create')) {
            try {
                //$role_details=getUserDetails($this->user->role_id);
               /* if(isset($role_details) && !empty($role_details))
                {*/
                    $user_details = getUserDetails($this->user_id);
                    //$users = User::select('name', 'id')->where('deleted_at', null)->orderBy('id','desc')->get();
                    $eas_final_status = getStatus('eas');

                    $eas = Eas::select('eas_masters.sanction_title','eas_masters.id','vendor_master.vendor_name','vendor_master.address')
                    ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id' )
                    ->where('eas_masters.status_id','=', $eas_final_status->final_status)
                    ->whereIn('eas_masters.department_id',$user_details['departments_id'])
                    ->where(['eas_masters.deleted_at'=> null,'vendor_master.deleted_at'=> null ])
                    ->get();
                    $is_create = 1; 
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
                   if (isset($user_details) && !empty($user_details) && isset($user_details['departments']) &&  !empty($user_details['departments']) ) {

                     $list_of_departments = $user_details['departments'];
                   }
                    $entity_details = getStatus('po');
               /* } else {
                    Toast::error('Something went wrong!');
                    return redirect('purchase-order');
                }*/
               
                return view('purchase_order.create', compact('users','list_of_departments','users','eas','entity_details','is_create'));
            } catch (\Exception $e) {

                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong');
                return redirect('purchase-order'); 
            }

        } else {
            Toast::error('You are not Authorized to perform this Action.');
            return Redirect::to('/purchase-order');
        }     


    }

     /**
     * To store new PurchaseOrder 
     *
     * @return \Illuminate\Http\Response
     * 
     * @var array $requestData Returns the user inputs from post data
     * @var array $purchase_array To assign value from requested data to columns and insert array
     * @var string $eas Vendor Id is mapped in EAS master table. Sanction title is listed in dropdown,on selection vendor name and address is fetch from eas table against selected EAS
     * @var bool $roles Returns the data from Role table.
     *
     */
     public function store(Request $request)
     { 

      //  dd($request);

        if(roleEntityMapping($this->user_id,'po','can_create')) {

            if(isset($request->copy_to)) {

              $this->validate($request, [
                'eas_id' => 'required',
                'vendor_name' => 'required',
                'subject' => 'required',
                'bid_number' => 'required|unique:purchase_order_masters,bid_number,NULL,id,deleted_at,NULL',
                'date_of_bid' => 'required',
                'title_of_bid' => 'required',
                 //   'email_users' => 'required'

            ]);
          } else {
            $this->validate($request, [
                'eas_id' => 'required',
                'vendor_name' => 'required',
                'subject' => 'required',
                'bid_number' => 'required|unique:purchase_order_masters,bid_number,NULL,id,deleted_at,NULL',
                'date_of_bid' => 'required',
                'title_of_bid' => 'required'
            ]);
        }

        try {
            $requestData = $request->all();
            $entity_id = $request->entity_id;

                //User can send email to multiple roles.Multiple select dropdown is used to select multiple roles and id is stored in email_usrs columns
                //Key of $requestData['email_users'] is retrieved from request and saved in database using comma separated values by imploding the values
            if(isset($requestData['email_users']) && !empty($requestData['email_users'])) {
                $email_mail = $requestData['email_users'];
                $send_email = implode(",", $email_mail);
            }else {
                $send_email= '';
            }
            $user_details = getUserDetails($this->user->role_id);

            $workflow_name = WorkflowName::select('default_status','id')
            ->where('id','=',$request->workflow_id)
            ->where('deleted_at',NULL)
            ->first();

            $purchase_order = PurchaseOrder::create(['eas_id' => $request->eas_id,
                'vendor_name' => $request->vendor_name,
                'vendor_address' => trim($request->vendor_address),
                'subject' => $request->subject,
                'bid_number' => $request->bid_number,
                'date_of_bid' => $request->date_of_bid,
                'title_of_bid' => $request->title_of_bid,
                'copy_to' => $request->copy_to,
                'email_users' => $send_email,
                'status_id' => $workflow_name['default_status'],
               'copy_to'=>$request->copy_to,
                'created_by'=>$this->user_id,
                'fa_date'=>$request->fa_date,'vendor_email'=>$request->vendor_email]);

            if(isset($purchase_order) && !empty($purchase_order))
            {   
                // if (isset($request->item) && !empty($request->item)) {
 
                //         foreach ($request->item as $key => $value) {
                              
                //            $create_invoice = ItemDetails::create(['eas_id'=>$purchase_order->id,'category'=>$value['category'],'item'=>$value['item'],'qty'=>$value['qty'],'unit_price_tax'=>$value['unit_price_tax'],'created_by'=>$this->user->id]);
                //           // dd($create_invoice);
                //         }

                //       }

                      if (isset($request->copy) && !empty($request->copy)) {
 
                        foreach ($request->copy as $key => $value) {
                              
                           $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$purchase_order->id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
                          // dd($create_invoice);
                        }

                      }

               $details = $this->user_details->storeLogDetails($purchase_order->id,$entity_id,$requestData);

               if (isset($request->documents_type) && !empty($request->documents_type) && isset($request->file_upload) && $request->hasFile('file_upload')) { 

                  $file_number = Eas::select('eas_masters.file_number')
                  ->join('purchase_order_masters','purchase_order_masters.eas_id','=','eas_masters.id')
                  ->where('eas_masters.id','=',$request->eas_id)
                  ->first();
//dd($file_number->file_number);
                  $uploadDocuments = $this->user_details->uploadDocuments($request->file_upload,$request->documents_type,$purchase_order->id,$this->user_id,$request->entity_id,$request->entity_slug,$file_number->file_number);

                  if(isset($uploadDocuments) && !empty($uploadDocuments) && $uploadDocuments['code'] == 200)  {

                    Toast::success('Purchase Order Added Successfully!');
                    return redirect('purchase-order');
                } else{
                  Toast::error('Something went wrong while file upload!');
                  return redirect('/purchase-order');
              }
          } else {
            Toast::success('Purchase Order Added Successfully!');
            return redirect('purchase-order');
        }
    }
    }catch (\Exception $e) {

        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
       Toast::error('Something went wrong!');
        return redirect('purchase-order'); 
    } 

     } else {
     Toast::error('You are not Authorized to perform this Action.');
     return Redirect::to('/purchase-order');
 } 
//}
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if(roleEntityMapping($this->user_id,'po','can_view')) {
            try {

                               
           // dd($users_json);
                $purchase_order_master = PurchaseOrder::select('purchase_order_masters.created_by','purchase_order_masters.title_of_bid','purchase_order_masters.id','purchase_order_masters.subject','purchase_order_masters.eas_id','purchase_order_masters.date_of_bid','purchase_order_masters.status_id','purchase_order_masters.bid_number','status.status_name','vendor_master.vendor_name','vendor_master.address as vendor_address','purchase_order_masters.po_pdf','eas_masters.department_id','users.name as assignee')
                ->join('eas_masters', 'eas_masters.id', '=', 'purchase_order_masters.eas_id')
                ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
                ->join('status','status.id','=','purchase_order_masters.status_id')
                ->leftjoin('assignee_mapper','assignee_mapper.master_id','=','purchase_order_masters.id')
                ->leftjoin('users','assignee_mapper.assignee','=','users.id')
                ->where('purchase_order_masters.id' ,'=',$id)
                ->where(['purchase_order_masters.deleted_at'=> Null,'status.deleted_at'=>Null,'vendor_master.deleted_at'=>NULL])
                ->orderBy('assignee_mapper.id','desc')
                ->first();

                $users = $users = User::select('users.id','users.name')
                ->join('user_role_mapper', 'user_role_mapper.user_id', '=', 'users.id')
                ->join('role_department_mapper', 'role_department_mapper.id', '=', 'user_role_mapper.role_dept_mapper_id')
                ->join('roles', 'roles.id', '=', 'role_department_mapper.role_id')
                ->join('departments', 'departments.id', '=', 'role_department_mapper.department_id')
                ->where(['departments.id'=>$purchase_order_master->department_id,'users.user_status' => 'Enable','users.deleted_at'=>NULL])
                ->where('users.id','!=',$this->user_id)
                ->orderBy('id','desc')
                ->get();

                $users_json = json_encode($users);

                $transaction_details = getTransactionDetails($id,$purchase_order_master,$entity_slug="po");

                $entity_details = $transaction_details['entity_details'];
                $transaction_data = $transaction_details['transaction_data'];
                $documents_details = $transaction_details['documents_details'];
                $added_comment = $transaction_details['added_comment'];
                $user_details = getUserDetails($this->user_id);
               
                if(isset($user_details) && isset($user_details['roles']) && !empty($user_details['roles'])){
                    $trans_permission  = checkRole($user_details['roles'],$entity_details->entity_slug,$transaction_data,$purchase_order_master->created_by);
                } else {
                   $trans_permission = '';
               }    
               $is_show = 1;  
 ///dd($trans_permission);
               $item_details = ItemDetails::select('id','eas_id','category','item','qty','unit_price_tax','total_unit_price_tax')->where(['deleted_at'=>NULL,'eas_id'=>$purchase_order_master->eas_id])->orderBy('id','asc')->get()->toArray();      

               $assigne = getAssignee($entity_details->id,$id,$purchase_order_master->status_id);

           // dd($assigne->old_assignee);
               return view('/purchase_order.show', compact('users','users_json','purchase_order_master','entity_details','documents_details','added_comment','transaction_data','is_show','trans_permission','assigne','item_details'));

           } catch (\Exception $e) {

            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            return redirect('/purchase-order')->with('danger', 'Something went wrong!');
        } 
    } else {
     Toast::error('You are not Authorized to perform this Action.');
     return Redirect::to('/purchase-order');
 }

}

    /**
     * To edit the records of PurchaseOrder 
     *
     * @return \Illuminate\Http\Response
     * 
     * @var int $purchase_order_master Returns the id of current user
     * @var array $purchase_array To assign value from requested data to columns and insert array
     * @var string $eas Vendor Id is mapped in EAS master table. Sanction title is listed in dropdown,on selection vendor name and address is fetch from eas table against selected EAS
     * @var bool $roles Returns the data from Role table.
     *
     */
    public function edit($id)
    {

        if(roleEntityMapping($this->user_id,'po','can_update')) {
            try {
            // $roles = Role::select('name', 'id')->where('deleted_at', null)->get();
            // $role_details=getUserDetails($this->user->role_id);

            // $users = User::select('name', 'id')->where('deleted_at', null)->where(['users.user_status' => 'Enable'])->orderBy('id','desc')->get();

              // $purchase_order_master = PurchaseOrder::findOrFail($id);
             $purchase_order_master = PurchaseOrder::select('purchase_order_masters.copy_to','purchase_order_masters.fa_date','purchase_order_masters.created_by','purchase_order_masters.title_of_bid','purchase_order_masters.id','purchase_order_masters.subject','purchase_order_masters.eas_id','purchase_order_masters.date_of_bid','purchase_order_masters.status_id','purchase_order_masters.bid_number','status.status_name','vendor_master.vendor_name','vendor_master.address as vendor_address')
             ->join('eas_masters', 'eas_masters.id', '=', 'purchase_order_masters.eas_id')
             ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
             ->join('status','status.id','=','purchase_order_masters.status_id')
             ->where('purchase_order_masters.id' ,'=',$id)
             ->where(['purchase_order_masters.deleted_at'=> Null,'status.deleted_at'=>Null,'vendor_master.deleted_at'=>NULL])
             ->first();

           // $item_details = ItemDetails::select('id','eas_id','category','item','qty','unit_price_tax','total_unit_price_tax')->where(['deleted_at'=>NULL,'eas_id'=>$purchase_order_master->eas_id])->orderBy('id','asc')->get()->toArray();  
            //dd($item_details);
             $mail = $purchase_order_master['email_users'];
             $selected_mail_users = explode(",", $mail);
            
             $is_update_url = 1;

             $transaction_details = getTransactionDetails($id,$purchase_order_master,$entity_slug="po");
             $entity_details = $transaction_details['entity_details'];
            //$transaction_data = $transaction_details['transaction_data'];
             $documents_details = $transaction_details['documents_details'];
             $added_comment = $transaction_details['added_comment'];
             $eas_final_status = getStatus('eas');
             $user_details = getUserDetails($this->user_id);
             $eas = Eas::select('eas_masters.sanction_title','eas_masters.id','vendor_master.vendor_name','vendor_master.address')
             ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id' )
             ->where('eas_masters.status_id','=', $eas_final_status->final_status)
             ->whereIn('eas_masters.department_id',$user_details['departments_id'])
             ->where(['eas_masters.deleted_at'=> null,'vendor_master.deleted_at'=> null ])
              ->get();   
            $user_details = getUserDetails($this->user_id);
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
                   if (isset($user_details) && !empty($user_details) && isset($user_details['departments']) &&  !empty($user_details['departments']) ) {

                     $list_of_departments = $user_details['departments'];
                   } 
               $copy_to_details = CopyToMaster::select('copy_to_details.master_id','copy_to_details.id','copy_to_details.user_id','copy_to_details.department_id','location.location_name','departments.name as department_name','users.name as user_name')
                ->join('users','users.id','=','copy_to_details.user_id')
                ->join('departments','departments.id','=','copy_to_details.department_id')
                ->join('location','location.id','=','departments.location_id')
                ->where(['master_id'=>$id,'entity_id'=>$entity_details->id])
                ->orderBy('id','asc')->get()->toArray();

             return view('purchase_order.edit', compact('users','list_of_departments','purchase_order_master','users','selected_mail_users','eas','entity_details','transaction_data','added_comment','is_update_url','documents_details','copy_to_details'));
         } catch (\Exception $e) {

            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong!');
            return redirect('purchase-order'); 
        }
    } else {    
        Toast::error('You are not Authorized to perform this Action.');
        return Redirect::to('/purchase-order');
    }    
}

   /**
     * Update the specified record of PurchaseOrder 
     *
     * @return \Illuminate\Http\Response
     * @var array $requestData Returns the user inputs from post data
     * @var int $purchase_order_master Returns the id of current user
     * @var array $update_data To assign value from requested data to columns and update array
     * @var string $eas Vendor Id is mapped in EAS master table. Sanction title is listed in dropdown,on selection vendor name and address is fetch from eas table against selected EAS
     * @var bool $roles Returns the data from Role table.
     *
     */
   public function update(Request $request, $id)
   {

    if(roleEntityMapping($this->user_id,'po','can_update')) {

        if(isset($request->copy_to)) {

          $this->validate($request, [
            'eas_id' => 'required',
            'vendor_name' => 'required',
            'subject' => 'required',
            'bid_number' => 'required|unique:purchase_order_masters,bid_number,'.$id.',id,deleted_at,NULL',
            'date_of_bid' => 'required',
            'title_of_bid' => 'required',
                    //'email_users' => 'required'

        ]);
      } else {
        $this->validate($request, [
            'eas_id' => 'required',
            'vendor_name' => 'required',
            'subject' => 'required',
            'bid_number' => 'required|unique:purchase_order_masters,bid_number,'.$id.',id,deleted_at,NULL',
            'date_of_bid' => 'required',
            'title_of_bid' => 'required'
        ]);
    }
    try {
        $requestData = $request->all();
        if(isset($requestData['email_users']) && !empty($requestData['email_users'])) {
            $email_mail = $requestData['email_users'];
            $send_email = implode(",", $email_mail);
        }else {
            $send_email = ''; 
        }

        if(isset($requestData['eas_id']) && !empty($requestData['eas_id'])) {
            $eas_id = $requestData['eas_id'];
        } else {
            $eas_id = '';
        }

        $purchase_order = PurchaseOrder::findOrFail($id);

        $update_data = array(
            'eas_id' => $eas_id,
            'vendor_name' => $request->vendor_name,
            'vendor_address' => trim($request->vendor_address),
            'subject' => $request->subject,
            'bid_number' => $request->bid_number,
            'date_of_bid' => $request->date_of_bid,
            'title_of_bid' => $request->title_of_bid,
            'copy_to' => $request->copy_to,
            'email_users' => $send_email,
            'fa_date'=>$request->fa_date,
            'vendor_email'=>$request->vendor_email
        );

        $purchase_order_data = $purchase_order->update($update_data);
//dd($purchase_order);

        //  if (isset($request->item) && !empty($request->item)) {

        // $delete_invoice = ItemDetails::where(['eas_id'=>$id])->delete();

        //     foreach ($request->item as $key => $value) {
                  
        //        $create_invoice = ItemDetails::create(['eas_id'=>$id,'category'=>$value['category'],'item'=>$value['item'],'qty'=>$value['qty'],'unit_price_tax'=>$value['unit_price_tax'],'created_by'=>$this->user->id]);
        //       // dd($create_invoice);
        //     }

        //   }

          if (isset($request->copy) && !empty($request->copy)) {
 
            foreach ($request->copy as $key => $value) {
                  
               $create_invoice = CopyToMaster::create(['entity_id'=>$request->entity_id,'master_id'=>$id,'department_id'=>$value['department_id'],'user_id'=>$value['user_id']]);
              // dd($create_invoice);
            }

          }

        if (isset($purchase_order_data) && !empty($purchase_order_data)) {

           if (isset($request->documents_type) && !empty($request->documents_type) && isset($request->file_upload) && $request->hasFile('file_upload')) { 

               $file_number = Eas::select('eas_masters.file_number')
               ->join('purchase_order_masters','purchase_order_masters.eas_id','=','eas_masters.id')
               ->where('eas_masters.id','=',$request->eas_id)
               ->first();

               $details = $this->user_details->storeLogDetails($purchase_order->id,$requestData['entity_id'],$requestData);

               $uploadDocuments = $this->user_details->uploadDocuments($request->file_upload,$request->documents_type,$id,$this->user_id,$request->entity_id,$request->entity_slug,$file_number->file_number);

               if(isset($uploadDocuments) && !empty($uploadDocuments) && $uploadDocuments['code'] == 200)  {

                 Toast::success('Purchase Order Updated Successfully!');
                 return redirect('purchase-order/' .$id);
             } else{
              Toast::error('Something went wrong while file upload!');
              return redirect('purchase-order/' .$id);
          }
      }else {
        Toast::success('Purchase Order Updated Successfully!');
        return redirect('purchase-order/' .$id);
    }        

} else {
    Toast::error('Something went wrong while updating.');
    return redirect('purchase-order');
}   

} catch (\Exception $e) {

    Log::critical($e->getMessage());
    app('sentry')->captureException($e);
    Toast::error('Something went wrong');
    return redirect('purchase-order'); 
}

} else {    
    Toast::error('You are not Authorized to perform this Action.');
    return Redirect::to('/purchase-order');
}     
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var array $update_data To assign value from requested data to columns and update array
     * @var string $eas Vendor Id is mapped in EAS master table. Sanction title is listed in dropdown,on selection vendor name and address is fetch from eas table against selected EAS
     * @var bool $roles Returns the data from Role table.
     *
     */
    public function destroy($id)
    {

        if(roleEntityMapping($this->user_id,'po','can_delete')) {
            try {
              if (isset($id) && !empty($id)) {
                $deleted= PurchaseOrder::destroy($id);
            } else {
                Toast::error('Id not found!');
                return redirect('purchase-order');
            }
            if($deleted) {
                Toast::success('Purchase Order Deleted Successfully!');
                return redirect('purchase-order');
            } else {
              Toast::error('Something Went wrong.');
              return redirect('purchase-order');  
          }

      } catch (\Exception $e) {

        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something Went wrong.');
        return redirect('purchase-order'); 
    }

} else {
    Toast::error('You are not Authorized to perform this Action.');
    return Redirect::to('/purchase-order');
}    
}

    /**
     * To fetch vendor details for selected EAS.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var array $update_data To assign value from requested data to columns and update array
     * @var string $eas Vendor Id is mapped in EAS master table. Sanction title is listed in dropdown,on selection vendor name and address is fetch from eas table against selected EAS
     * @var bool $roles Returns the data from Role table.
     *
     */
    public function vendorDetails($id)
    {
        try {
         
        $eas = Eas::select('eas_masters.id','vendor_master.vendor_name','vendor_master.address','vendor_master.email as vendor_email')
         ->join('vendor_master','vendor_master.id','=','eas_masters.vendor_id' )
         ->where('eas_masters.id','=',$id)
         ->where(['eas_masters.deleted_at'=> null,'vendor_master.deleted_at'=> null ])->get();

        $data['item_details'] = ItemDetails::select('id','eas_id','category','item','qty','unit_price_tax','total_unit_price_tax')->where(['deleted_at'=>NULL,'eas_id'=>$id])->orderBy('id','asc')->get()->toArray(); 

               //to retrieve vendor_name, address from EAS 
         foreach ($eas as $value) {
          $data['vendor_name'] = $value['vendor_name'];
          $data['address'] = $value['address'];
          $data['vendor_email'] = $value['vendor_email'];
      }

      if (isset($data) && !empty($data)) {
        $data['code'] = 200;
       
    } else {
 $data['code'] = 204;
    }
 return $data;
} catch (\Exception $e) {

    Log::critical($e->getMessage());
    app('sentry')->captureException($e);
    Toast::error('Something Went wrong.');
    return redirect('purchase-order'); 
}
}

 public function downloadPoPdf($id)
    { 
        try {

         $file_name = PurchaseOrder::select('po_pdf')
                ->where('id',$id)
                ->first();
       // dd($file_name );
        return response()->download(storage_path("documents/{$file_name->po_pdf}"));
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('File not found.');
             return redirect()->back();
       }  
    }

}
