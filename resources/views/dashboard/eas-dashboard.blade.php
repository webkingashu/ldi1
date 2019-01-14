@extends('layouts.app')
@section('title', 'Eas Dashboard')
@section('css')

<!-- <link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
-->
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <section class="content-header">
        <h1> EAS Dashboard</h1>

    </section>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title  blue-bg">
                <span class="label pull-right blue-badge">{{ $eas_master->status_name }}</span></a>
                <h4>EAS</h4>
            </div>
            <div class="ibox-content blue-bg div-flex">
             <div> <h1 class="no-margins font-bold">{{ $eas_master->sanction_total }}</h1>
              <!--   <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> -->
              <strong>Sanction Total</strong></div>
              
          </div>
      </div>
  </div>

  <div class="col-lg-3">
    <div class="ibox float-e-margins">
        <div class="ibox-title  navy-bg">
          @if(!empty($can_create_po_ro) && $can_create_po_ro == 1)
           <a href="{{ url('/purchase-order/create') }}" title="Add New Purchase Order" target="_blank" ><span class="label  pull-right navy-badge">Add New</span></a>
            @endif
           <h4> Purchase Order</h4>
       </div>
       <div class="ibox-content navy-bg div-flex">
         <div> <h1 class="no-margins font-bold">@if(isset($data['po_count']) && !empty($data['po_count'])) {{ $data['po_count'] }} @else 0 @endif</h1>
          <!--   <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> -->
          <strong>Total Purchase Order</strong></div>
          <i class="fa fa-shopping-cart fa-4x"></i>
      </div>
  </div>
</div>

<div class="col-lg-3">
    <div class="ibox float-e-margins">
        <div class="ibox-title  red-bg">
          @if(!empty($can_create_po_ro) && $can_create_po_ro == 1)
           <a href="{{ url('/ro/create') }}" title="Add New Release Order" target="_blank" ><span class="label  pull-right red-badge">Add New</span></a>
            @endif
           <h4> Release Order</h4>
       </div>
       <div class="ibox-content red-bg div-flex">
         <div> <h1 class="no-margins font-bold">@if(isset($data['ro_count']) && !empty($data['ro_count'])) {{ $data['ro_count'] }} @else 0 @endif</h1>
          <!--   <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> -->
          <strong>Total Release Order</strong></div>
          <i class=""></i>
      </div>
  </div>
</div>

<div class="col-lg-3">
    <div class="ibox float-e-margins">
        <div class="ibox-title  lazur-bg">
           @if(!empty($can_create_gar) && $can_create_gar == 1)
           <a href="{{ url('/gar/create') }}" title="Add New GAR Bill" target="_blank" ><span class="label  pull-right lazur-badge">Add New</span></a>
           @endif
           <h4> GAR Bill</h4>
            
       </div>
       <div class="ibox-content lazur-bg div-flex">
         <div> <h1 class="no-margins font-bold">@if(isset($data['gar_count']) && !empty($data['gar_count'])) {{ $data['gar_count'] }} @else 0 @endif</h1>
          <!--   <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> -->
          <strong>Total GAR Bill</strong></div>
          <i class="fa fa-file-o fa-4x"></i>
      </div>
  </div>
</div>



   <!--  <div class="col-lg-4">
        <div class="widget style1 red-bg">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa  fa-5x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <span><h4> Release Order </h4></span>
                    <h2 class="font-bold"> @if(isset($data['ro_count']) && !empty($data['ro_count'])) {{ $data['ro_count'] }} @else 0 @endif</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="widget style1 lazur-bg">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-file-o fa-5x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <span><h4>GAR Bill</h4></span>
                    <h2 class="font-bold"> @if(isset($data['gar_count']) && !empty($data['gar_count'])) {{ $data['gar_count'] }} @else 0 @endif</h2>
                </div>
            </div>
        </div>
    </div> -->



    <div class="ibox float-e-margins col-lg-12">
        <div class="ibox-title">
            <h5><strong>EAS Details</strong></h5>

        </div>
        <div class="ibox-content ">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                    <tbody>
                        <tr>
                            <th> Sanction Title </th><td> {{ $eas_master->sanction_title }} </td>
                            <th> Sanction Purpose </th><td> {{ $eas_master->sanction_purpose }} </td>
                        </tr>
                        <tr>
                            <th> Competent Authority </th><td> {{ ucwords(str_replace('_',' ',$eas_master->competent_authority)) }} </td>
                            <th> Serial Number of Sanction </th><td> {{ $eas_master->serial_no_of_sanction }} </td>
                        </tr>
                        <tr>
                            <th>File Number</th><td> {{ $eas_master->file_number }} </td>
                            <th>Value of Sanction Total</th><td> {{ $eas_master->sanction_total }} </td>
                        </tr>
                        <tr>
                            <th>Budget Code</th><td> {{ $eas_master->budget_head_of_acc }} </td>
                            <th>Validity of Sanction Period</th><td> {{ $eas_master->cfa_dated }} </td>
                        </tr>
                        <tr>
                            <th>Date of Issue</th><td> {{ $eas_master->date_issue }} </td>
                            <th>Name of Payee Agency </th><td> {{ $eas_master->vendor_name }} </td>
                        </tr>

                        <tr>
                            <th>CFA Note Number</th><td> {{ $eas_master->cfa_note_number }} </td>
                            <th>CFA Dated</th><td> {{ $eas_master->cfa_dated }} </td>
                        </tr>
                        <tr>
                            <!-- <th>CFA File Number</th><td> {{ $eas_master->cfa_file_number }} </td> -->
                            @if(isset($eas_master->whether_being_issued_unders) && !empty($eas_master->whether_being_issued_unders))
                            <th>Whether being issued under</th><td> {{ $eas_master->whether_being_issued_unders }} </td>
                            @endif
                            @if(isset($eas_master->status_name) && !empty($eas_master->status_name) )
                            <th>Status</th>
                            <td>
                             @if($eas_master->status_name == "Approved")
                             <span class="label label-primary"> {{ $eas_master->status_name }} </span>
                             @elseif ($eas_master->status_name == "Pending")
                             <span class="label label-warning">  {{ $eas_master->status_name}}</span>
                             @elseif ($eas_master->status_name == "Draft")
                             <span class="label label-info">  {{ $eas_master->status_name}}</span>
                             @elseif ($eas_master->status_name == "Return")
                             <span class="label label-danger">{{ $eas_master->status_name}}</span>
                             @else
                             <span class="label">{{ $eas_master->status_name }}</span>
                             @endif
                         </td>
                         @endif
                     </tr>
                     @if(isset($eas_master->fc_number) && !empty($eas_master->fc_number))
                     <tr>
                        <th>Fc Number</th><td> {{ $eas_master->fc_number }} </td>
                        @endif
                        @if(isset($eas_master->fc_dated) && !empty($eas_master->fc_dated))

                        <th>FC Dated</th><td> {{ $eas_master->fc_dated }} </td>
                        @endif
                    </tr>
                    @if(isset($eas_master->fc_on_page) && !empty($eas_master->fc_on_page) )
                    <tr>
                        <th>FC On Page</th><td> {{ $eas_master->fc_on_page }} </td>
                        @endif
                        @if(isset($eas_master->fc_on_file_no) && !empty($eas_master->fc_on_file_no) )
                        <th>FC On File No</th><td> {{ $eas_master->fc_on_file_no }} </td>
                        @endif
                    </tr>
                </tbody>
            </table>
            <div class="pagination-wrapper"> </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title panel panel-primary">
            <div class="col-xs-4">
                <h5> <i class="fa fa-shopping-cart fa-2x pull-left"></i>PURCHASE ORDER</h5>
                <!-- <a href="{{ url('purchase-order/create') }}" title="View More Purchase Order" target="_blank"><span class="label label-success ">View More <i class="fa fa-arrow-right" aria-hidden="true"></i></span></a> -->
            </div>
            <div class="ibox-tools mb-10 col-md-12">
              @if(!empty($can_create_po_ro) && $can_create_po_ro == 1)
              <a href="{{ url('purchase-order/create') }}" title="Add New Purchase Order" target="_blank"><button class=" btn btn-primary dim btn-xs add-btn" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
              </a>
              @endif
          </div> 
          <div class="row">
           <div class="col-md-3">
            <div class="form-group mb-10">
                <label>Select Status:</label>
                <select class="form-control" name="status" class="status" onchange="po_status(this)">
                   <option value="">Select Status</option>
                   @foreach($status as $value)
                   <option data-entity_name='purchase_order' value="{{ $value->id }}">{{ $value->status_name }}</option>
                   @endforeach
               </select> 
           </div>
       </div>
       <div class="col-md-3 col-md-offset-3">
           <div class="form-group mb-10"> 
               <label>Select From Date:</label>
               <div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>  <input type="text" name="from_date" class="datepicker form-control" readonly="" id="from_date" > </div>
           </div>
       </div>
       <div class="col-md-3">
           <div class="form-group mb-10">
               <label>Select To Date:</label>
               <div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>   <input type="text" name="from_date" class="datepicker form-control" readonly id="to_date" onchange="makeAjaxRequest('purchase_order')"> </div>
           </div>
       </div>
   </div>
</div>


<div class="ibox-content search-right">
    <table class="table table-hover dataTables-example" id="po_table_data">
        <thead>
            <tr>
             <th>Sr. No</th>
             <th>Status</th>
             <th>Title Of Bid</th>
             <th>Subject</th>
             <th>EAS Name</th>
             <th>Vendor Name</th>
             <th>Bid Number</th>
             <!--   <th>Date Of Bid</th> -->

             <th>Actions</th>
         </tr>
     </thead>
     <tbody>
        @if(isset($data['purchase_order_listing']) && count($data['purchase_order_listing'])>0)
        @foreach($data['purchase_order_listing'] as $item)
        <tr>
            <td>{{ $loop->iteration or $item->id }}</td>
            <td> @if($item->status_name == "Approved")
                <span class="label label-primary"> {{ $item->status_name }} </span>
                @elseif ($item->status_name == "Pending Approval")
                <span class="label label-warning">  {{ $item->status_name}}</span> 
                @elseif ($item->status_name == "Draft")
                <span class="label label-info">  {{ $item->status_name}}</span> 
                @elseif ($item->status_name == "Return")
                <span class="label label-success">{{ $item->status_name}}</span> 
                @else
                <span class="label">{{ $item->status_name }}</span> 
            @endif</td>
            <td>{{ $item['title_of_bid'] }}</td>
            <td>{{ $item->subject }}</td>
            <td>{{ $item->sanction_title }}</td>
            <td>{{ $item->vendor_name }}</td>
            <td>{{ $item->bid_number }}</td>
            <!-- <td>{{ $item->date_of_bid }}</td> -->


            <td>
               <div class="row btn-action">  
                @if($item->status_id != $data['entity_details']->final_status)

               
                <a href="{{ url('/purchase-order/' . $item->id) }}" title="Edit Purchase Order"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                

               
                <form method="POST" action="{{ url('purchase-order' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger btn-xs" title="Delete Purchase Order" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                </form>
              

                @else 
               
                <a href="{{ url('/purchase-order/' . $item->id) }}" title="View Purchase Order"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a> 
                
                @endif
            </div>  
        </td>
    </tr>
    @endforeach
    @endif
</tbody>
<tfoot>

</tfoot>
</table>
</div>



</div>
</div>

<?php //dd($data); ?>
<!-- <div class="row"> -->
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title panel panel-danger">
                <h5>RELEASE ORDER</h5>
                <div class="ibox-tools col-md-12">
                  @if(!empty($can_create_po_ro) && $can_create_po_ro == 1)
                    <a href="{{ url('/ro/create') }}" title="Add New Release Order" target="_blank"><button class="btn btn-primary dim btn-xs add-btn" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </a>
                    @endif
                </div>

                <div class="row">
                 <div class="col-md-3">
                    <div class="form-group mb-10">
                        <label>Select Status:</label>
                        <select class="form-control" name="status" class="status" onchange="ro_status(this)">
                         <option value="">Select Status</option>
                         @foreach($status as $value)
                         <option data-entity_name='release_order' value="{{ $value->id }}">{{ $value->status_name }}</option>
                         @endforeach
                     </select> 
                 </div>
             </div>
             <div class="col-md-3 col-md-offset-3">
                 <div class="form-group mb-10"> 
                     <label>Select From Date:</label>
                     <div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>  <input type="text" name="from_date" class="datepicker form-control" readonly="" id="start_date" > </div>
                 </div>
             </div>
             <div class="col-md-3">
                 <div class="form-group mb-10">
                     <label>Select To Date:</label>
                     <div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>   <input type="text" name="from_date" class="datepicker form-control" readonly id="end_date" onchange="makeAjaxRequest('release_order')"> </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="ibox-content search-right">
        <table class="table table-hover no-margins dataTables-example " id="ro_table_data" >
            <thead>
                <tr>
                  <th>Sr NO.</th>
                  <th>Current Status</th>
                  <th>Date of Assignment</th>
                  <th>Date of Forwarding</th>
                  <th>File Number</th>
                  <th>From</th>
                  <th>Vendor Name</th>
                  <th>Proposed Payment Amount</th>

                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
            @if(isset($data['release_order']) && count($data['release_order']) > 0)
            @foreach($data['release_order'] as $item)
            <?php $date = date('d-m-Y', strtotime($item->created_at));
            $status_approved_date = date('d-m-Y', strtotime($item->status_approved_date));
            $location = $item->city_name;
            $department = $item->department_name;
            /*$role = $role_details['role_name'];
            $user = $role_details['user_name'];
            $from = $location . ' - ' . $department . ' - ' . $role . ' - ' . $user;*/
            ?>   
            <tr>
                <td>{{ $loop->iteration or $item->id }}</td>
                <td> @if($item->status_name == "Approved")
                 <span class="label label-primary"> {{ $item->status_name }} </span>
                 @elseif ($item->status_name == "Pending Approval")
                 <span class="label label-warning"> {{ $item->status_name}}</span>
                 @elseif ($item->status_name == "Draft")
                 <span class="label label-info">  {{ $item->status_name}}</span>
                 @elseif ($item->status_name == "Return" || $item->status_name == "DDO Return" || $item->status_name == "PAO Return")
                 <span class="label label-danger">{{ $item->status_name}}</span>
                 @elseif ($item->status_name == "DDO Approved" || $item->status_name == "PAO Approved")
                 <span class="label label-success">{{ $item->status_name}}</span>
                 @else
                 <span class="label">{{ $item->status_name }}</span>
                 @endif
             </td>
             <td>{{$date}}</td>
             <td>{{$status_approved_date}}</td>
             <td>{{$item->file_number}}</td>
             <td>{{$item->vendor_name}}</td>
             <td>{{$item->release_order_amount}}</td>
             <!-- <td>{{$item->status_name}}</td> -->

             <td>
                @if($item->status_id != $data['entity_details']->final_status)
                     
                <a href="{{ url('/ro/' . $item->id) }}" title="Edit RO"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
               
               
                <form method="POST" action="{{ url('/ro' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                   {{ method_field('DELETE') }}
                   {{ csrf_field() }}
                   <button type="submit" class="btn btn-danger btn-xs" title="Delete RO" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
               </form>
             
               @else 
              
               <a href="{{ url('/ro/' . $item->id) }}" title="View RO"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>

           </td>
       
           @endif

       </tr>
       @endforeach
       @endif
   </tbody>
</table>
</div>
</div>
</div>

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title panel panel-info">
         <div class="col-xs-4">
            <h5><i class="fa fa-file-o"></i> GAR</h5> 
        </div>
        <div class="ibox-tools col-md-12">
          @if(!empty($can_create_gar) && $can_create_gar == 1)
            <a href="{{ url('gar/create') }}" title="Add New GAR" target="_blank"><button class="btn btn-primary dim btn-xs add-btn" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
            </a>
            @endif
        </div> 
        <div class="row">
         <div class="col-md-3">
            <div class="form-group mb-10">
                <label>Select Status:</label>
                <select class="form-control" name="status" class="status" onchange="gar_status(this)">
                 <option value="">Select Status</option>
                 @foreach($status as $value)
                 <option data-entity_name='gar' value="{{ $value->id }}">{{ $value->status_name }}</option>
                 @endforeach
             </select> 
         </div>
     </div>
     <div class="col-md-3 col-md-offset-3">
         <div class="form-group mb-10"> 
             <label>Select From Date:</label>
             <div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>  <input type="text" name="from_date" class="datepicker form-control" readonly="" id="starting_date" > </div>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group mb-10">
             <label>Select To Date:</label>
             <div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>   <input type="text" name="from_date" class="datepicker form-control" readonly id="ending_date" onchange="makeAjaxRequest('gar')"> </div>
         </div>
     </div>
 </div>
</div>
<div class="ibox-content search-right">
    <table class="table table-hover no-margins dataTables-example" id="gar_table_data">
        <thead>
           <tr>
            <th>Sr. No.</th>
            <th>Status</th>
            <th>EAS</th>
            <th>Release Order amount</th>
            <th>EAS File Number</th>
            <th>Amount to be Paid</th>
            <th>Actual Payment Amount</th>
            <th>Vendor Name</th>

            <!-- <th>Date of Issue</th> -->

            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $count = 1; 
        ?>
        @if(isset($data['gar']) && count($data['gar']) >0)
        @foreach($data['gar'] as $item)
        <?php ?>
        <tr>
            <td>{{ $loop->iteration or $item->id }}</td>
            <td>@if($item->status_name == "Approved" || $item->status_name == "DDO Approved" || $item->status_name == "PAO Approved" || $item->status_name == "Forwarding Letter Generated")
               <span class="label label-primary"> {{ $item->status_name }} </span>
               @elseif ($item->status_name == "Pending Approval")
               <span class="label label-warning">  {{ $item->status_name}}</span>
               @elseif ($item->status_name == "Draft" || $item->status_name == "Dispatch And Tally Entry Done" || $item->status_name == "Cheque Uploaded" )
               <span class="label label-info">{{ $item->status_name}}</span>
               @elseif ($item->status_name == "Return" || $item->status_name == "DDO Return" || $item->status_name == "PAO Return")
               <span class="label label-danger">{{ $item->status_name}}</span>
               @else
               <span class="label">{{ $item->status_name }}</span>
               @endif
           </td>
           <td>{{ $item->sanction_title }}</td>
           <td>{{ $item->release_order_amount }}</td>
           <td>{{ $item->file_number }}</td>
           <td>{{ $item->amount_paid }}</td>
           <td>{{ $item->actual_payment_amount }}</td>
           <td>{{ $item->vendor_name }}</td>

           <td>

            @if(isset($item->status_id) &&  $item->status_id != $data['entity_details']->final_status)
          
            <a href="{{ url('/gar/' . $item->id . '/edit') }}" title="Edit GAR"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
            
            @endif 
                                       <!--  
                                        <form method="POST" action="{{ url('/gar' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete GAR" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </form>
                                         -->
                                        @if(isset($item->status_id) &&  $item->status_id == $data['entity_details']->final_status)
                                       
                                        <a href="{{ url('/gar/' . $item->id) }}" title="View GAR"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a> 
                                       
                                        @endif 

                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- </div> -->




        </div>
    </div>
    @endsection
    @section('scripts')
    <script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            var eas_id =  "{{ Session::get('eas_id') }}";

            
            //alert(eas_id);
           // var from_date = $('#from_date').val(); 
           // alert()
           // var to_date = $('#to_date').val(); 


           $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yyyy',
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
         //  endDate: "today"

     });

           $('.dataTables-example').DataTable({
            pageLength: 5,
            responsive: true,
        });

       });


        function gar_status (status) 
        {
          var entity_name = "gar";
          var status = status.value;
          makeAjaxRequest(entity_name,status);
          // alert(status);
        }

        function ro_status (status) 
        {
          var entity_name = "release_order";
          var status = status.value;
          makeAjaxRequest(entity_name,status);
        }

        function po_status(status) 
        {
          var entity_name = "purchase_order";
          var status = status.value;
          makeAjaxRequest(entity_name,status);
        }


      function makeAjaxRequest(entity_name=null,status_name=null) {
      
    if(entity_name == 'purchase_order') 
    {
        var from_date = $('#from_date').val(); 
        var to_date = $('#to_date').val(); 
    } else if(entity_name == 'release_order') 
    {
       var from_date = $('#start_date').val(); 
       var to_date = $('#end_date').val(); 
   } else if (entity_name == 'gar')
   {
       var from_date = $('#starting_date').val(); 
       var to_date = $('#ending_date').val();
   }


   $.ajax({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: 'POST',
    url: '{{ url('/') }}/get_status',
    data: {'status_name':status_name,'entity_name':entity_name ,'from_date':from_date ,'to_date':to_date },
    beforeSend: function(){

        $('.div-loader').css("display", "block");
    },
    success: function(data) {
           // alert('innn');
           //console.log(data);
           var table;
           table = $('.dataTables-example').DataTable();
           //table.clear();
            //alert(entity_name);
            if(entity_name == 'purchase_order')
            {
               $("#po_table_data").html(data); 
           } else if(entity_name == 'release_order')
           {
            $("#ro_table_data").html(data); 
        } else if(entity_name == 'gar') 
        {
            $("#gar_table_data").html(data); 
        }

    },complete: function(){
        $('.div-loader').css("display", "none");
    }
});
}


// $("#status").on("change", function(){
//   var status = $("#status").val();
//   alert(status);
//   $.ajax({
//     type: 'POST',
//     url:"{{ url('/') }}/get_status",
//     data:{status : status , _token: "{{csrf_token()}}"},  
//     dataType: "json",

//     success: function (data) {
//      alert('in success');
//         //console.log(data);
//         $("#table_data").html(data);

//       },
//       error: function (data) {
//         alert('Something went wrong,please try again later!!!')
//       }
//     })
// });




$(document).on('click', '.add_po', function (e) {
        // $('.is_paid').click(function () {
            var eas_id = $(this).data('eas_id');
            var checklist_type = "{{ Session::get('checklist_type') }}";
            $.ajax({
                type: 'POST',
                url: "<?php echo url('/') ?>" + "/eas_details/",
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {id:eas_id},
                success: function (data) {

                    swal("Done!", "Your record has been updated.", "success");
                    location.reload();
                },
                error: function (data) {
                    // / alert(data);
                }
            });
        });




    </script>       
    @endsection            
