@extends('layouts.app')
@section('title', 'Purchase Order')
@section('css')
<?php //dd($_POST); ?>
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />

@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex justify-content-between">
                    <h3 class="col-md-10"><strong>  Purchase Order</strong></h3>
                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('purchase-order/create') }}" title="Add New Purchase Order"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                    </div>
                </div>

                <!-- <div class="ibox-title">
                    <h5><a href="{{ url('purchase-order') }}" title="Back"><button class="btn btn-primary btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a><strong> Purchase Order</strong></h5>
                    <div class="ibox-tools">
                         <a href="{{ url('purchase-order/create') }}" class="btn btn-success btn-sm" title="Add New purchase_order_master">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                    </div>
                 </div> -->

            <div class="ibox-content">
                 @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('danger'))
                        <div class="alert alert-danger">
                            {{ session('danger') }}
                        </div>
                    @endif
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                        <thead>
                            <tr>
                             <th>Sr. No</th>
                             <th>Title Of Bid</th>
                              <th>Department</th>
                             <th>Subject</th>
                             <th>EAS Name</th>
                             <th>Vendor Name</th>
                             <th>Bid Number</th>
                            
                              <th>Status</th>
                             <th>Actions</th>
                         </tr>
                     </thead>
                     <tbody>
                        @if(isset($purchase_order_listing) && count($purchase_order_listing)>0)
                        @foreach($purchase_order_listing as $item)
                        <tr>
                            <td>{{ $loop->iteration or $item->id }}</td>
                            <td>{{ $item->title_of_bid }}</td>
                            <td>{{ $item->department_name }}</td>
                            <td>{{ $item->subject }}</td>
                            <td>{{ $item->sanction_title }}</td>
                            <td>{{ $item->vendor_name }}</td>
                            
                            <td>{{ $item->bid_number }}</td>
                            <!-- <td>{{ $item->date_of_bid }}</td> -->
                            
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
                            <td>
                                 <div class="row btn-action">  
                                        @if($item->status_id != $entity_details->final_status)
                                         
                                            <a href="{{ url('/purchase-order/' . $item->id) }}" title="Edit Purchase Order"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                           
                                              <!--   <form method="POST" action="{{ url('purchase-order' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Purchase Order" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                </form> -->
                                           
                                            @else 
                                            
                                            <a href="{{ url('/purchase-order/' . $item->id) }}" title="View Purchase Order"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a> 
                                           
                                        @endif
                                    </div>  
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                    
                </table>
            </div>

        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
            // { extend: 'copy'},
            // {extend: 'csv'},
            // {extend: 'excel', title: 'ExampleFile'},
            // {extend: 'pdf', title: 'ExampleFile'},

            // {extend: 'print',
            // customize: function (win){
            //     $(win.document.body).addClass('white-bg');
            //     $(win.document.body).css('font-size', '10px');

            //     $(win.document.body).find('table')
            //     .addClass('compact')
            //     .css('font-size', 'inherit');
            //     }
            // }
            ]


        });




    });

  

</script>     
@endsection 