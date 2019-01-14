<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ForwardingLetter;
use App\Cheque;
use App\ForwardingLetterMapper;
use Validator;
use Auth;
use Toast;
use Illuminate\Support\Facades\Log;
use App\EasLog;
use Redirect;
class ForwardingLetterController extends Controller
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
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

    public function index()
    {
        //try {
    if (roleEntityMapping($this->user_id, 'forwarding_letter', 'can_view')) {
        $user_details = getUserDetails($this->user_id);
                       
        if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){
        $forwarding_letter_generated = ForwardingLetter::select('forwarding_letter_master.total_amount','forwarding_letter_master.date_of_issue','forwarding_letter_master.file_path','forwarding_letter_master.created_by','forwarding_letter_master.id','vendor_master.vendor_name','eas_masters.sanction_title','release_order_master.ro_title','departments.name as department_name','location.location_name','cheque_master.cheque_date')
        ->leftjoin('cheque_master','cheque_master.forwarding_letter_id','=','forwarding_letter_master.id')
        ->leftjoin('gar','cheque_master.id','=','gar.cheque_id')
        ->leftjoin('release_order_master','release_order_master.id','=','gar.ro_id')
        ->leftjoin('eas_masters','eas_masters.id','=','release_order_master.eas_id')
        ->leftjoin('departments','departments.id','=','eas_masters.department_id')
        ->leftjoin('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
        ->leftjoin('location','location.id','=','departments.location_id')
       // ->leftjoin('location', 'location.id','=','forwarding_letter_master.location_id')
        ->whereIn('eas_masters.department_id',$user_details['departments_id'])
        ->orderBy('forwarding_letter_master.id','desc')
        ->get();
       }
       //  dd($forwarding_letter_generated);
        return view('forwarding-letter.list',compact('forwarding_letter_generated'));
        // } catch(\Exception $e) {
        //         Log::critical($e->getMessage());
        //         app('sentry')->captureException($e);
        //         return redirect('/forwarding-letter')->with('danger', 'Something went wrong!');
        //     }

        } else {
            Toast::error('You Dont have permssion to Access Forwarding Letter');
            return Redirect::to('/dashboard');
        }
    }

    public function create()
    {

        if (roleEntityMapping($this->user_id, 'forwarding_letter', 'can_create')) {
        try {
        
         $user_details = getUserDetails($this->user_id);
                       
        if(isset($user_details) && isset($user_details['departments_id']) && !empty($user_details['departments_id'])){
            $cheque_generated =  Cheque::select('cheque_master.cheque_name','cheque_master.cheque_date','cheque_master.id','cheque_master.cheque_number','cheque_master.cheque_amount','gar.cheque_id','release_order_master.eas_id','eas_masters.vendor_id','vendor_master.vendor_name','eas_masters.sanction_title','release_order_master.ro_title','departments.name as department_name','location.location_name')
            ->join('gar', 'gar.cheque_id', '=', 'cheque_master.id')
            ->join('release_order_master', 'release_order_master.id', '=', 'gar.ro_id')
            ->join('eas_masters', 'eas_masters.id', '=', 'release_order_master.eas_id')
            ->join('vendor_master', 'vendor_master.id', '=', 'eas_masters.vendor_id')
            ->leftjoin('departments','departments.id','=','eas_masters.department_id')
            ->leftjoin('location','location.id','=','departments.location_id')
            ->where('cheque_master.forwarding_letter_id' ,NULL)
            ->whereIn('eas_masters.department_id',$user_details['departments_id'])
            ->groupBy('cheque_master.id')
            ->get();
        } 
         //dd($cheque_generated);

        return view('forwarding-letter.create',compact('cheque_generated'));
         } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                return redirect('/forwarding-letter')->with('danger', 'Something went wrong!');
            }

         } else {
            Toast::error('You Dont have permssion to Access Forwarding Letter');
            return Redirect::to('/dashboard');
        }    
    }

   public function store(Request $request)
    {
        if (roleEntityMapping($this->user_id, 'forwarding_letter', 'can_create')) {

        $this->validate($request, [
            'forwarding_letter_date' => 'required', 
            'total_amount' => 'required',
            'selected_cheque_id' => 'required'
        ]);

        // $role_wise_access = $this->RoleWiseAccess($previous_status, $transaction_type, $request);
        // dd($this->user);
        //if (isset($this->user) && $this->user->hasRole('PAO')) {
          
            $common_controller = new CommonController();
            $get_pdf_details =  $common_controller->getPdfDetails($request);
        // dd($get_pdf_details['result']);
             
            if(isset($get_pdf_details) && isset($get_pdf_details['code']) && $get_pdf_details['code'] = 200 && !empty($get_pdf_details)) {
            $generate_forwarding_letter = generatePdf($get_pdf_details, $pdf_type='forwarding_letter',$storage_path='forwarding_letter');
            }

            // if((isset($generate_forwarding_letter) && !empty($generate_forwarding_letter))) {
            //     $data['forwarding_letter'] = $generate_forwarding_letter;
            //    // $data['gar'] = $generate_gar;
            //     $data['code'] = 200;
            //     $data['message'] = "Success";
            //     $data['email_status'] = 0;
            // } else {
            //     $data['code'] = 204;
            //     $data['message'] = "Forwarding Letter Not Generated.";
            //     $data['email_status'] = 0;
            // }
            // dd($data);
           
        // } else {
        //    //  dd('111');
        //     $data['code'] = 204;
        //     $data['message'] = "You are not Authorized to change Status.";
        // }
 
        if (isset($generate_forwarding_letter) && !empty($generate_forwarding_letter)) {
         //   try {
                $date = str_replace('/', '-', $request->forwarding_letter_date);
                $forwarding_letter_date = date('Y-m-d', strtotime($date));

                $user_details = getUserDetails($this->user_id);

                $create_forwarding_letter = ForwardingLetter::create(['total_amount'=>$request->total_amount, 'date_of_issue'=>$forwarding_letter_date, 'file_path'=>$generate_forwarding_letter, 'created_by'=>$this->user_id]);
                
                $cheque_id = explode(",", $request->selected_cheque_id);

                if (isset($create_forwarding_letter)) {
                    foreach ($cheque_id as $key => $value) {
                    
                        $add_forwarding_letter_no = Cheque::where('id', $value)->update(['forwarding_letter_id' => $create_forwarding_letter->id]);
                    
                        if ($add_forwarding_letter_no != 1) {

                            $remove_cheque = ForwardingLetter::where('id',$create_forwarding_letter->id)->delete();
                            Toast::error('Cheque is uploaded but not Mapped with GAR so try again.');
                            return Redirect::to('/upload-cheque');
                        }
                    }

                    if ($add_forwarding_letter_no == 1) {
                        Toast::success('Forwarding Letter created Successfully.');
                        return Redirect::to('/forwarding-letter');
                    }

                    // $add_cheque_no = GAR::update()
                }


                if ($create_forwarding_letter) {
                    Toast::success('Forwarding Letter has been generated Successfully!');
                    return Redirect('forwarding-letter');

                } else {
                    Toast::error('Something went wrong while creating Forwarding Letter!');
                    return Redirect('forwarding-letter');
                }
            // }
            // catch(\Exception $e) {
            //     Log::critical($e->getMessage());
            //     app('sentry')->captureException($e);
            //     return redirect('/forwarding-letter')->with('danger', 'Something went wrong!');
            // }
            //return $data;

        } else {
            Toast::error('Something went wrong while creating Forwarding Letter!');
            return Redirect('forwarding-letter');;
       }

        } else {
            Toast::error('You Dont have permssion to Access Forwarding Letter.');
            return Redirect::to('/dashboard');
        }
    }

    public function destory(Request $request)
    {
        
       if (roleEntityMapping($this->user_id, 'forwarding_letter', 'can_delete')) {
    try {
        if(isset($request->id) && !empty($request->id)) {
            $id = $request->id;
            $delete_data = ForwardingLetter::where('id',$id)->delete();
            $remove_from_cheque = Cheque::where('forwarding_letter_id',$id)->update(['forwarding_letter_id' => NULL]);

            if(isset($delete_data) && !empty($delete_data) && isset($remove_from_cheque) && !empty($remove_from_cheque)) {
                $data['code'] = 200;              
                $data['message'] = "Data deleted Successfully !";

            } else {
                $data['code'] = 204;              
                $data['message']= "Data not deleted!";
            }

        } else {
            $data['code'] = 204;              
            $data['message']= "Forwarding Letter not found!";
        }

        return $data;
         } catch(\Exception $e) {
                Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                return redirect('/forwarding-letter')->with('danger', 'Something went wrong!');
            }

        } else {
            Toast::error('You Dont have permssion to Access Forwarding Letter');
            return Redirect::to('/dashboard');
        }
    }

      public function downloadForwardingLetter($id)
    { 
       try {

           $file_name = ForwardingLetter::select('file_path')
                ->where('id',$id)
                ->first();
        //dd($file_name->file_path);
        return response()->download(storage_path("documents/{$file_name->file_path}"));
        } catch (\Exception $e) {
            
             Log::critical($e->getMessage());
             app('sentry')->captureException($e);
             Toast::error('File Not found.');
             return redirect('/dashboard'); 
       }  
    }


}
