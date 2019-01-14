<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category  Role
 * @author  Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license  https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\RolesController
 * @link  https://choicetechlab.com/
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\Entity;
use App\EntityRoleMapper;
use Illuminate\Http\Request;
use App\Department;
use App\PermissionMapper;
use App\PermissionRole;
use App\RoleDepartmentMapper;
use Auth;
use Toast;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;
/**
 * This class provides a all operation to manage the Role data.
 *
 * The RolesController is responsible for managing the basic details require for genarating the Role report.
 * 
 */

class RolesController extends Controller
{
    public $user_id,$user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
     * Display a listing of the Roles, with name, department id, etc. Also provide actions to edit,delete.
     *
     * @return \Illuminate\Http\Response

     * This Function Provide the list of all Roles Data.
     *  Pseudo Steps: <br>
     * 1)Create view to list down the coulmns.<br>
     * 2) Create Model and add table,list down table coulmns, and use soft delete class<br>
     * 3) Retreive records in Controller by accessing Model with scope resolution operator.<br>
     * 4) Store result in variable and pass the variable to view of listing.<br>
     * 5) Foreach this varaible in listing View to fetch each record from table with actions to be performed.<br>
     * 
     * @param mixed[] $request Request structure to get the post data for pagination like limit and offset.
     * 
     * @var array $roles to fetch data from model
     * @var int $limit Should contain a number for limit of record by default it is 10.
     * @var int $offset Should contain a number for offset of record by default it is 0.
     * 
     * @return json response for Roles list.
     
     */
    public function index(Request $request)
    {   
    try {  
            $keyword = $request->get('search');
            $perPage = 25;
            if (!empty($keyword)) {
                $roles = Role::where('name', 'LIKE', "%$keyword%")
                    ->orWhere('department_id', 'LIKE', "%$keyword%")
                    ->orWhere('created_by', 'LIKE', "%$keyword%")
                    ->orWhere('created_at', 'LIKE', "%$keyword%")
                    ->orWhere('updated_at', 'LIKE', "%$keyword%")
                    ->orWhere('deleted_at', 'LIKE', "%$keyword%")
                    ->latest()->paginate($perPage);
            } else {

                $roles = Role::select('roles.*')
                // ->join('departments','departments.id','=','roles.department_id')
                ->where(['roles.deleted_at'=> Null])
                ->latest()->paginate($perPage);     
            }
            return view('roles.index', compact('roles'));
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/roles');
        }      
    }

    
    /**
     * Show the form for creating a new Role.
     *
     * @return \Illuminate\Http\Response
     * Create form to submit record through post 
     */
    public function create()
    {
        try {  
            $departments = Department::select('name','id')->get()->toArray();
            // dd($departments);
           return view('roles.create',compact('departments'));
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/roles');
        } 
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * This Function Use To Store Role Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Roles list.
     * 
     * @var array[] $role Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $requestData to fetch data from request
     * 
     */
    public function store(Request $request)
    {
        // dd($request);    
        $this->validate($request,[
            'name'=> 'required',
            'department_id'=> 'required' 
        ]);
        
        try {

            $role = Role::create(['name'=> $request->name,'created_by'=>$this->user_id]);
            // dd($role);
            if (isset($role)) {
                $departments = $request->department_id;
                // dd($departments);
                foreach ($departments as $key => $value) {
                    $add_role_dept_mapper = RoleDepartmentMapper::create(['role_id'=>$role->id,'department_id'=>$value]);
                }
                if (isset($add_role_dept_mapper)) {
                    Toast::success('Role added successfully!');
                    return redirect('/roles');
                } else {
                    Toast::error('Unable to add role please try again later!');
                    return redirect('/roles');
                }

            } else {
                Toast::error('Unable to add role please try again later!');
                return redirect('/roles');
            }

        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/roles');
        }     
    }


    /**
     * Show the form for editing the specified Role.
     * Pseudo step : 1) Retreive data from table against particular id <br> 
     * 2) pass this variable to view <br> 
     * 3) in Value attribute mention the coulmn name to fetch record
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @var array $role find role id from Model
     * @var array $department_id used to store department id
     * @var string $department_name retrieve data from Model
     */
    public function edit($id)
    {
      try {
            $role=Role::select('id','name')->where(['id'=>$id])->first();
            $departments = Department::select('name','id')->get()->toArray();
            $role['department'] = RoleDepartmentMapper::select('role_department_mapper.department_id','departments.name')
            ->join('departments','departments.id','=','role_department_mapper.department_id')
            ->where('role_department_mapper.role_id','=',$id)
            ->get()
            ->toArray();
            // dd($departments);
           return view('roles.edit', compact('role','departments'));

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        return redirect('/roles')->with('danger', 'Something went wrong');
        }     
    }

    /**
     * Update the specified Role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Update Role Data,with manadatory fields for Report Generation.
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success Role list.
     * 
     * @var array[] $update_office_type Should contain list of data return by the query. 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $requestData to fetch data from request
     * @var array[] $role to find id from model
     * 
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $this->validate($request,[
            'name'=> 'required',
            'department_id'=> 'required' 
        ]);

         try {   

            $role = Role::where('id',$id)->update(['name'=>$request->name]);

            if(isset($role)) {
                $delete_mapper = RoleDepartmentMapper::where('role_id','=',$id)->delete();
                $departments = $request->department_id;
                // dd($departments);
                foreach ($departments as $key => $value) {
                    $add_role_dept_mapper = RoleDepartmentMapper::create(['role_id'=>$id,'department_id'=>$value]);
                }
                if (isset($add_role_dept_mapper)) {
                    Toast::success('Role Updated successfully!');
                    return redirect('/roles');
                } else {
                    Toast::error('Unable to update role please try again later!');
                    return redirect('/roles');
                }
                
            } else {
                Toast::error('Something went wrong');
                return redirect('/roles');
            }   
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
                app('sentry')->captureException($e);
                Toast::error('Something went wrong');
                return redirect('/roles');
        }      
    }
    
    /**
     * Remove the specified Role from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @var string $delete_role to find and delete record against particular id  
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    try {
            if(isset($id) && !empty($id)) {

                $delete_role = Role::where('id',$id)->delete();
                
                if(isset($delete_role)) { 
                  Toast::success('Role deleted successfully!');
                  return redirect('/roles');

                } else {
                    Toast::error('Something went wrong while deleteing data.');
                   return redirect('/roles'); 
                }
            } else {
                Toast::error('Id not found.');
                return redirect('/roles');
           }
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/roles');
        }  
    }

    public function roleDeptEntityView()
    {
      try {
            $entity_list = Entity::select('id','type_name')->where('deleted_at',NULL)->get();
            //dd($entity_list);
            $permission_list = Permission::select('id','name','display_name')->where('deleted_at',NULL)->get();
            $role_mapper= RoleDepartmentMapper::select('role_department_mapper.id','roles.name as role_name','roles.id as role_id','role_department_mapper.department_id','departments.name as departments_name')
            ->join('departments','departments.id','=','role_department_mapper.department_id')
            ->join('roles','roles.id','=','role_department_mapper.role_id')
            //->where('role_department_mapper.department_id','=',$value['id'])
            ->get()
            ->toArray();
          //dd( $role_mapper);
             
         

           return view('roles.roles_dept_entity_mapping', compact('role_mapper','entity_list','permission_list'));

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        return redirect('/roles')->with('danger', 'Something went wrong');
        }     
    }

    public function roleDeptEntityStore(Request $request)
    {
         
        // $this->validate($request,[
        //     'role_dept_mapper_id'=> 'required',
        //     'entity_id'=>'required',
        //     'permissions'=>'required'
        // ]);
        
        try {
         
             if (isset($request->role) && count($request->role) > 0) {
                    foreach ($request->role as $role_key => $val) {

                        foreach ($val['permissions'] as $key => $value) {
                           
                            $data_insert = PermissionRole::create(['role_dept_mapper_id'=> $val['role_dept_mapper_id'],'entity_id'=> $val['entity_id'], 'permission_id'=> $value]);

                        }
                        
                    }

                    if(isset($data_insert) && !empty($data_insert)){
                      Toast::success('Data successfully Added.');
                        return redirect('/role-dept-entity');
                    } else {
                        Toast::error('Unable to add role please try again later!');
                        return redirect('/role-dept-entity');
                    }


            } else {
                Toast::error('Unable to add role please try again later!');
                return redirect('/role-dept-entity');
            }

        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
            Toast::error('Something went wrong');
            return redirect('/roles');
        }     
    }

    public function roleDeptEntityList()
    {
      try {
            // $entity_list = Entity::select('id','type_name')->where('deleted_at',NULL)->get();
            // $permission_list = Permission::select('id','name','display_name')->where('deleted_at',NULL)->get();

            // $role_mapper= RoleDepartmentMapper::select('role_department_mapper.id','entity_type.type_name','roles.id as role_id','roles.name as role_name','departments.name as department_name','role_department_mapper.id')
            // ->join('permission_role','permission_role.role_dept_mapper_id','=','role_department_mapper.id')
            // ->join('roles','roles.id','=','role_department_mapper.role_id')
            // ->join('departments','departments.id','=','role_department_mapper.department_id')
            // ->join('entity_type','entity_type.id','=','permission_role.entity_id')
            // //->where('role_department_mapper.department_id','=',$value['id'])
            //  //->groupBy('role_id')
            // // ->groupBy('department_name')
            // ->get()
            // ->toArray();
        
             
            // foreach ($role_mapper as $key => $value) {
            //     // dd($value);
            //    // $id = $value['id'];
            //     //$data[$id]['role'] = $value['role_name']." (".$value['department_name'].")";
            //     $data[$key]['role'] = $value['role_name'];
            //     $data[$key]['role_id'] = $value['role_id'];
            //     $data[$key]['department_name'] = $value['department_name'];
            //     $data[$key]['type_name'] = $value['type_name'];
            //     // $data[$id]['id'] = $value['id'];
            //     // if (isset($data[$id]['type_name'])) {
            //     //     $data[$id]['type_name'] = $value['type_name'].",".$value['type_name'];
            //     // } else {
            //     //     $data[$id]['type_name'] = $value['type_name'];
            //     // }
            // }

         $role_list=PermissionRole::select('role_department_mapper.role_id','roles.name as role_name')
         ->join('role_department_mapper','permission_role.role_dept_mapper_id','=','role_department_mapper.id')
         ->join('roles','roles.id','=','role_department_mapper.role_id')
         ->where(['roles.deleted_at'=>NULL])->groupBy('role_id')->get(['role_id','role_name']);
         
           
            // foreach($assigned_permissions as $key => $val)
            // {
            //     $assigned_permissions_array[] = $val['permission_id'];
            //     $assigned_enitity_array[] = $val['entity_id'];
            // }
            // //dd($assigned_enitity_array);
            // if(isset($assigned_enitity_array)) { 
            // $entity_data = array_unique($assigned_enitity_array);
            // }
            // dd( $data);
    
           return view('roles.roles_dept_entity_mapping_list', compact('role_list'));

        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        return redirect('/roles')->with('danger', 'Something went wrong');
        }     
    }

     public function roleDeptEntityEdit($id)
    {
        //dd($id);
     try {
             $role_mapper= RoleDepartmentMapper::select('role_department_mapper.id','roles.name as role_name','roles.id as role_id','role_department_mapper.department_id','departments.name as departments_name')
            ->join('departments','departments.id','=','role_department_mapper.department_id')
            ->join('roles','roles.id','=','role_department_mapper.role_id')
            //->where('role_department_mapper.department_id','=',$value['id'])
            ->get()
            ->toArray();
            $entity_list = Entity::select('id','type_name')->where('deleted_at',NULL)->get();
           // $entity_data = EntityRoleMapper::select('*')->where('role_id',$id)->get();
            $permission_list = Permission::select('id','name','display_name')->where('deleted_at',NULL)->get();

            $assigned_permissions = PermissionRole::select('permission_role.id','role_department_mapper.role_id','permission_role.permission_id','permission_role.entity_id')
         ->join('role_department_mapper','permission_role.role_dept_mapper_id','=','role_department_mapper.id')
         ->join('roles','roles.id','=','role_department_mapper.role_id')
         ->where(['roles.deleted_at'=>NULL,'role_department_mapper.role_id'=>$id])->get(['permission_id','entity_id']);

          //dd($assigned_permissions);
           
            foreach($assigned_permissions as $key => $val)
            {
                $assigned_permissions_array[] = $val['permission_id'];
                $assigned_enitity_array[] = $val['entity_id'];
            }
            //dd($assigned_enitity_array);
            if(isset($assigned_enitity_array)) { 
            $entity_data = array_unique($assigned_enitity_array);
            }
           return view('roles.roles_dept_entity_mapping', compact('role_mapper','entity_list','permission_list','assigned_permissions_array','entity_data'));
        } catch (\Exception $e) {
        Log::critical($e->getMessage());
        app('sentry')->captureException($e);
        return redirect('/roles')->with('danger', 'Something went wrong');
        }     
    }
}
