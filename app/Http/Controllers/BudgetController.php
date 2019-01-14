<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Budget;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Toast;
use Carbon;
use Auth;
use Redirect;
class BudgetController extends Controller
{

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        if (roleEntityMapping($this->user_id, 'budget', 'can_view')) {
      //try {
            $keyword = $request->get('search');
            $perPage = 25;

            $year = date('Y');
           // dd($year);
           /* $month = date('m');
            // dd($month);

            if(isset($month) && $month > 3) {

                $year=$year . "-" . $year+=1 ;
                // dd($year);
            } else {

                $year=$year-1 . "-" . $year ;
            }
     */
        try {
            if (!empty($keyword)) {
                $budget = Budget::where('id', 'LIKE', "%$keyword%")
                    ->orWhere('functional_wing', 'LIKE', "%$keyword%")
                    ->orWhere('amount', 'LIKE', "%$keyword%")
                    ->orWhere('oh', 'LIKE', "%$keyword%")
                    ->orWhere('broad_description', 'LIKE', "%$keyword%")
                    ->orWhere('from_date', 'LIKE', "%$keyword%")
                    ->orWhere('till_date', 'LIKE', "%$keyword%")
                    ->latest()->paginate($perPage);
            } else {

                $budget = Budget::select('budget_list.*','departments.name','location.location_name')
                    ->join('departments','departments.id','=','budget_list.functional_wing')
                    ->join('location','location.id','=','departments.location_id')
                    ->get();


                $year_list = Budget::select('from_date','till_date')->distinct()->get();

            }

            return view('budget.index', compact('budget','year_list'))->with('year',$year);
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 

        } else {
    Toast::error('You are not Authorized to perform this Action.');
    return Redirect::to('/dashboard');
} 

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {

            $departments = Department::select('id','name')->where(['deleted_at'=>null])->get();

            return view('budget.create',compact('departments'));
            
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $this->validate($request, [
    			'functional_wing' => 'required|max:255',
    			'amount' => 'required|max:255',
    			'year' => 'required|max:255'
    		]);
          
            $requestData = $request->all();
            $budget_details = Budget::create($requestData);

        if(isset($budget_details) && !empty($budget_details)) {

            Toast::success('Budget added successfully!');
            return redirect('budget');
        } else {
            Toast::error('Something went wrong!');
            return redirect('budget');
        } 

    } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {

            $budget = Budget::select('budget_list.*','departments.name')->join('departments','departments.id','=','budget_list.functional_wing')->where('budget_list.id','=',$id)->orderBy('id')->get();

            return view('budget.show', compact('budget'));

         } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        
            
           $perPage = 25;
           $year = $request->get('year');

         try {
            $budget = Budget::select('budget_list.*','departments.name')->join('departments','departments.id','=','budget_list.functional_wing')->where('budget_list.year','=',$year)
                 ->orderBy('budget_list.year')->latest()->paginate($perPage);
        
            return view('budget.edit', compact('budget'));

         } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
       try {

             for ($i=0; $i<count($request->budget_id); $i++) {

                 $budget = Budget::where('id',$request->budget_id[$i])
                      ->update(['amount' => $request->amount[$i]]);

                } 
            if(isset($budget) && !empty($budget)) {

                Toast::success('Budget updated successfully!');
                return redirect('budget');
            } else {
                Toast::error('Something went wrong!');
                return redirect('budget');
            } 

         } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {

            Budget::destroy($id);   

            return redirect('budget')->with('flash_message', 'Budget deleted!');

         } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 
    }

    public function budgetList(Request $request)
    {
        try { 

           $year=$request->get('year');
           $explode_result = explode('-',$year);
           $from_date = $explode_result[0];
           $till_date = $explode_result[1];
              //dd($explode_result);
                if(isset($year) && !empty($year)) {
               
                $budget = Budget::select('budget_list.budget_code','budget_list.budget_head_of_acc', 'budget_list.oh','budget_list.broad_description', 'budget_list.amount','budget_list.from_date','budget_list.till_date','departments.name','location.location_name')
                    ->join('departments','departments.id','=','budget_list.functional_wing')
                    ->join('location','location.id','=','departments.location_id')
                    ->where(['budget_list.from_date'=>$from_date,'budget_list.till_date'=>$till_date])
                    ->get();   

                foreach ($budget as $key => $value) {

                           $budget[$key]['amount_in_words'] = getAmountInWords($value->amount);
                }
            
                       
                }
           
                   return $budget;

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        Toast::error('Something went wrong');
         return redirect('budget');
        } 
    }
}
