<?php
/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  Transaction
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\TransactionController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Transaction;
use App\TransactionApprover;
use App\WorkFlowTransactionMapper;
use App\WorkFlowName;
use App\Status;
use App\Role;
use Auth;
use Toast;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;
use DB;
/**
 * This class provides a all operation to manage the Transaction data.
 *
 * The TransactionController is responsible for managing the basic details of Transaction which require for Workflow.
 * 
 */

class TransactionController extends Controller
{
    public $user_id,$user;
     public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->user= Auth::user();
        $this->user_id=Auth::id();
       
        return $next($request);
        });
    }
    /**
     * Display a listing of the Vendor,with Vendor Name, Contact Number etc. Also provide actions to edit,delete.
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
     * @return json response for Vendor list.
     
     */
    public function index()
    {
    try {
            $transactions = Transaction::select('transactions.id','transaction_name','from_status','to_status','transactions.created_by','to_status.status_name as to_status_name','from_status.status_name as from_status_name','roles.name')
           ->join('roles','roles.id','=','transactions.role_id')
            ->join('status as to_status', function($join) {
                $join->on('to_status.id', '=', 'transactions.to_status');
            })
            ->join('status as from_status', function($join) {
                $join->on('from_status.id', '=', 'transactions.from_status');
            })
            ->orderBy('transactions.id','desc')
         //   ->where(['transactions.deleted_at'=>NULL,'status.deleted_at'=>NULL])
            ->get();

            return view('transactions.list', compact('transactions'));

        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return Redirect::to('/transaction');
        } 
    }

     /**
     * Show the form for creating a new Vendor.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    { 
        try{
            $get_status = Status::orderBy('id','asc')->where('deleted_at',null)->get();
            $role = Role::orderBy('id','asc')->where('deleted_at',null)->get();
            return view('transactions.add',compact('get_status','role'));
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return Redirect::to('/transaction');
        }  
    }

    /**
     * Store a newly created Vendor in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Vendor Data,with manadatory fields.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Vendor list.
     * 
     * @var array[] $result Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $user_details is used to store list of data which include location id, officetype id, department id of loged in user.
     * @var array[] $vender_data to fetch data from request
     * 
     */
    public function store(Request $request)
    {
       
    $validator = Validator::make($request->all(), [
        'transaction_name'=>'required',
        'from_status'=> 'required',
        'to_status'=> 'required',
        'role_id'=>'required'
    ]);
        try { 
             if ($validator->fails()) {
                return Redirect::to('transaction/create')
                ->withErrors($validator)
                ->withInput();
            } else {
               $insert_transaction = Transaction::create([
                'transaction_name'=>$request->transaction_name,
                'from_status'=>$request->from_status,
                'to_status'=>$request->to_status,
                //'role_id'=>$request->role_id,
                'created_by'=>$this->user_id,
            ]);
               
                if(isset($insert_transaction) && !empty($insert_transaction->id) ) {
                   foreach ($request->role_id as $key => $value) {

                   $mapper_insert = DB::table('transaction_role_mapper')->insert(['transaction_id'=>$insert_transaction->id,'role_id'=>$value,'created_by'=>$this->user_id]);
                     
                   }
               // dd($mapper_insert);
                    Toast::success('Transaction Added Successfully');
                  return Redirect::to('/transaction');
                } else {
                    Toast::error('Something Went Wrong.');
                   return Redirect::to('/transaction');
                }
            }
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something Went Wrong.');
            return Redirect::to('/transaction');
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
        //
    }

     /**
     * Show the form for editing the specified Vendor.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var string $result retrieve data from Model
     */
    public function edit($id)
    {
    try{   
            if(isset($id) && !empty($id))  {

            $get_status = Status::orderBy('status_name','asc')->where('deleted_at',null)->get();
            $role = Role::orderBy('name','asc')->where('deleted_at',null)->get();
            $transaction = Transaction::find($id);
            
            $transaction_role = DB::table('transaction_role_mapper')->select('role_id')->orderBy('id','asc')->where(['deleted_at'=>null,'transaction_id'=>$transaction->id])->get(['role_id']);
           
            foreach($transaction_role as  $val)
            {

                $assigned_role[] = $val->role_id;
            }
        
            $is_update_url = 1;

                if(isset($transaction) && !empty($transaction)) {

                  return view('transactions.add',compact('transaction','get_status','role','is_update_url','assigned_role'));

                } else {
                    Toast::error('Data Not found');
                    return Redirect::to('/transaction'); 
                }

            } else {
                Toast::error('Id not found.');
              return Redirect::to('/transaction');
            }
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
             Toast::error('Something went wrong.');
            return Redirect::to('/transaction');
        } 
    }

   /**
     * Update the specified Vendor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Vendor Data,with manadatory fields..
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success vendor list.
     * 
     * @var array[] $result Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $request to fetch data from request
     * 
     */
    public function update(Request $request, $id)
    {
     try {   
        $get_status = Status::orderBy('status_name','asc')->where('deleted_at',null)->get();
        $role = Role::orderBy('name','asc')->where('deleted_at',null)->get();
        $transaction = Transaction::find($id);
        $is_update_url = 1;

        $update_transaction = Transaction::where('id',$id)->update(['transaction_name'=>$request->transaction_name,
        'from_status'=>$request->from_status,
        'to_status'=>$request->to_status,
        //'role_id'=>$request->role_id,
        'created_by'=>$this->user_id
        ]);

            if(isset($update_transaction) && !empty($update_transaction)) { 
                $delete_role = DB::table('transaction_role_mapper')->where(['transaction_id'=>$id])->delete();  
                 foreach ($request->role_id as $key => $value) {
                
                   $mapper_insert = DB::table('transaction_role_mapper')->insert(['transaction_id'=>$id,'role_id'=>$value,'created_by'=>$this->user_id]);
                }   

                Toast::success('Transaction Successfully Updated.');
              return Redirect::to('/transaction'); 

            } else {
                Toast::error('Something went wrong while updating data.');
               return Redirect::to('/transaction'); 
            }
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong.');
            return Redirect::to('/transaction');
        } 
    }

    /**
     * Remove the specified Transaction from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @param  int  $id
     * @var string $id to find record against particular id  
     * @var string $result to delete record
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
