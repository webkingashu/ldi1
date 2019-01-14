<?php 
$url = Request::path();
$users = getUserDetails(auth()->user()->id);
//dd($users);
?>
<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element"><a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span>
					<img alt="image" class="img-circle" src="{{url('/')}}/img/images.png">
				</span>
				<span class="clear"> <span class="block m-t-xs"><strong class="font-bold">
					@if (Auth::check()) {{Auth::user()->name}}@endif </strong>
				</span> <span class="text-muted text-xs block">@if (Auth::check()) 
					Designation : {{@$users['designation']->designation}}<br>
					Location : {{@$users['designation']->location_name}}
					<!-- @foreach(auth()->user()->roles as $role)
					{{ $role->display_name }}
					@endforeach  -->
					@endif
				</a>
			</div>
		</li>
		
		<li {{ (strstr($url, 'dashboard')) ? 'class=active' : '' }}>
			<a href="{{ url('dashboard') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
		</li>
	
		
		<li {{ (strstr($url, 'vendor')) ? 'class=active' : '' }}>
			<a href="{{ url('vendor') }}"><i class="fa fa-industry"></i> <span class="nav-label">Vendor</span></a>
		</li>
	
		@if(isset($users['entities'])&& !empty($users['entities']) && in_array('eas',$users['entities']))
		<li {{ (strstr($url, 'eas')) ? 'class=active' : '' }}>
			<a href="{{ url('eas') }}"><i class="fa fa-inr"></i> <span class="nav-label">EAS</span></a>
		</li>
        @endif
       @if(isset($users['entities'])&& !empty($users['entities']) && in_array('po',$users['entities']))
		<li {{ (strstr($url, 'purchase-order')) ? 'class=active' : '' }}>
			<a href="{{ url('purchase-order') }} ">
				<!-- <i class="fa fa-shopping-cart" ></i>  -->
			<span class="nav-label">Purchase Order</span></a>
		</li>
		@endif
		@if(isset($users['entities'])&& !empty($users['entities']) &&  in_array('ro',$users['entities']))
		<li {{ (strstr($url, 'ro')) ? 'class=active' : '' }}>
			<a href="{{ url('ro') }}">
				<!-- <i class="fa fa-th-large"></i>  -->
			<span class="nav-label">Release Order</span></a>
		</li>
		@endif
		<!-- <li {{ (strstr($url, 'manage-profile')) ? 'class=active' : '' }} >
			<a href="{{ url('manage-profile') }}"><i class="fa fa-users"></i><span class="nav-label">Manage Profile</span></a>
		</li> -->
	@if(isset($users['entities'])&& !empty($users['entities']) &&  in_array('gar',$users['entities'])) 
       <li>
		<a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Gar</span> <span class="fa arrow"></span></a>
		<ul <?php echo ((strstr($url, 'gar')) || (strstr($url, 'gar-register')) || (strstr($url, 'ec-register')) || (strstr($url, 'diary-register')) || (strstr($url, 'dispatch-register'))) ?  "class='nav nav-second-level collapse in'": "class='nav nav-second-level '" ?> >
			<li {{ (strstr($url, 'gar')) ? 'class=active' : '' }}><a href="{{ url('/gar') }}">Gar List</a></li>
			<li {{ (strstr($url, 'gar-register')) ? 'class=active' : '' }}><a href="{{ url('/gar-register') }}">GAR Register</a></li>
			<li {{ (strstr($url, 'ec-register')) ? 'class=active' : '' }}><a href="{{ url('/ec-register') }}">EC Register</a></li>
			<li {{ (strstr($url, 'diary-register')) ? 'class=active' : '' }}><a href="{{ url('/diary-register') }}">Diary Register</a></li>
			<li {{ (strstr($url, 'dispatch-register')) ? 'class=active' : '' }}><a href="{{ url('/dispatch-register') }}">Dispatch Register</a></li>
			</ul>
		</li>
	@endif	

	
        @if(isset($users['entities'])&& !empty($users['entities']) &&  in_array('cheque',$users['entities']))
		<li {{ (strstr($url, 'list-cheque')) ? 'class=active' : '' }}>
			<a href="{{ url('list-cheque') }}"><i class="fa fa-money"></i> <span class="nav-label">Cheque</span></a>
		</li>
		@endif
		@if(isset($users['entities'])&& !empty($users['entities']) && in_array('forwarding_letter',$users['entities']))
		<li {{ (strstr($url, 'forwarding-letter')) ? 'class=active' : '' }}>
			<a href="{{ url('forwarding-letter') }}"><i class="fa fa-book"></i> <span class="nav-envelope">Forwarding Letter</span></a>
		</li>
		@endif
		@if(isset($users['entities'])&& !empty($users['entities']) && in_array('budget',$users['entities']))
		<li {{ (strstr($url, 'budget')) ? 'class=active' : '' }}>
			<a href="{{ url('budget') }}"><i class="fa fa-money"></i> <span class="nav-envelope">budget</span></a>
		</li>
		@endif


	
		

		<!-- <li>
			<a href="#"><i class="fa fa-user"></i> <span class="nav-label">User Management</span> <span class="fa arrow"></span></a>
			<ul <?php echo ((strstr($url, 'entity')) || (strstr($url, 'users')) || (strstr($url, 'roles')) || (strstr($url, 'permission')) || (strstr($url, 'register'))) ?  "class='nav nav-second-level collapse in'": "class='nav nav-second-level '" ?> >
				<li {{ (strstr($url, 'entity')) ? 'class=active' : '' }}> <a href="{{ url('/entity') }}">Entity</a></li>
				<li {{ (strstr($url, 'users')) || (strstr($url, 'register')== 0) ? 'class=active' : '' }}><a href="{{ url('/users') }}">Users</a></li>
				<li {{ (strstr($url, 'roles')) ? 'class=active' : '' }}> <a href="{{ url('/roles') }}">Roles</a></li>
				<li {{ (strstr($url, 'permission')) ? 'class=active' : '' }}><a href="{{ url('/permission') }}">Permission</a></li>
			</ul>
		</li> -->
				<!-- <li {{ (strstr($url, 'password/reset')) ? 'class=active' : '' }}>
					<a href="{{ url('/password/reset') }}"><i class="fa fa-key"></i> <span class="nav-label">Change Password</span></a>
				</li> -->


				
				<!-- <li {{ (strstr($url, 'diary-register/create')) ? 'class=active' : '' }}>
					<a href="{{ url('/diary-register/create') }}"><i class="fa fa-book"></i> <span class="nav-label">Diary Register</span></a>
				</li>
				<li {{ (strstr($url, 'dispatch-register/create')) ? 'class=active' : '' }}>
					<a href="{{ url('/dispatch-register/create') }}"><i class="fa fa-truck"></i> <span class="nav-label">Dispatch Register</span></a>
				</li>
				<li {{ (strstr($url, 'forwarding-letter/list')) ? 'class=active' : '' }}>
					<a href="{{ url('/forwarding-letter/list') }}"><i class="fa fa-mail-forward"></i> <span class="nav-label">Forwarding Letter</span></a>
				</li> -->
					
				
				<!--  -->
				@if(isset($users['entities'])&& !empty($users['entities']) && in_array('user',$users['entities']))
					<li>
				<a href="#"><i class="fa fa-user"></i> <span class="nav-label">User Management</span> <span class="fa arrow"></span></a>
				<ul <?php echo ((strstr($url, 'office-type')) || (strstr($url, 'users')) || (strstr($url, 'roles')) || (strstr($url, 'permission')) || (strstr($url, 'departments'))) ?  "class='nav nav-second-level collapse in'": "class='nav nav-second-level '" ?> >
					
					<li {{ (strstr($url, 'users')) || (strstr($url, 'register')== 0) ? 'class=active' : '' }}><a href="{{ url('/users') }}">Users</a></li>
					<li {{ (strstr($url, 'office-type')) ? 'class=active' : '' }}><a href="{{ url('/office-type') }}">Office Type</a></li>
					<li {{ (strstr($url, 'roles')) ? 'class=active' : '' }}><a href="{{ url('/roles') }}">Role</a></li>
					<li {{ (strstr($url, 'location')) ? 'class=active' : '' }}><a href="{{ url('/location') }}">Location</a></li>
					<li {{ (strstr($url, 'departments')) ? 'class=active' : '' }}><a href="{{ url('/departments') }}">Department</a></li>
		  		</ul>
		     	</li>
                
				@endif

				@if(isset($users['entities'])&& !empty($users['entities']) && in_array('workflow',$users['entities']))
				
				<li>
					<a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Workflow Masters</span> <span class="fa arrow"></span></a>
					<ul <?php echo ((strstr($url, 'role-dept-entity')) ||(strstr($url, 'status')) || (strstr($url, 'entity')) || (strstr($url, 'conditions')) || (strstr($url, 'transaction')) || (strstr($url, 'conditions')) || (strstr($url, 'workflow'))) ?  "class='nav nav-second-level collapse in'": "class='nav nav-second-level '" ?> >
						<li {{ (strstr($url, 'status')) ? 'class=active' : '' }}><a href="{{ url('/status') }}">Status</a></li>
						<li {{ (strstr($url, 'entity')) ? 'class=active' : '' }}> <a href="{{ url('/entity') }}">Entity</a></li>
						<li {{ (strstr($url, 'conditions')) ? 'class=active' : '' }}><a href="{{ url('/conditions') }}">Conditions</a></li>
						<li {{ (strstr($url, 'transaction')) ? 'class=active' : '' }}> <a href="{{ url('/transaction') }}">Transaction</a></li>
						<li {{ (strstr($url, 'workflow')) ? 'class=active' : '' }}> <a href="{{ url('/workflow/list') }}">Workflow</a></li>
						<li {{ (strstr($url, 'role-dept-entity')) ? 'class=active' : '' }}><a href="{{ url('/role-dept-entity') }}">Role Department Entity Mapping </a></li>

					</ul>
				</li>
		        @endif
					
				</ul>
			</ul>
		</div>
	</nav>
