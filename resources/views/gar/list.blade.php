@extends('layouts.app')
@section('title', 'GAR List')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> GAR</strong></h3>

                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('/gar/create') }}" title="Add New EAS"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                        
                    </div>
                   
                </div>
            <div class="ibox-content scroll-hide">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Release Order</th>
                                <th>Release Order amount</th>
                                <th>EAS Title</th>
                                <th>EAS File Number</th>
                                <th>Amount to be Paid</th>
                                <th>Actual Payment Amount</th>
                                <th>Vendor Name</th>
                               
                                <!-- <th>Date of Issue</th> -->
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; 
                            ?>
                            @if(isset($result) && count($result) >0)
                            @foreach($result as $item)
                            <?php ?>
                            <tr>
                                <td>{{ $loop->iteration or $item->id }}</td>
                                <td>{{ $item->ro_title }}</td>
                                <td>{{ $item->release_order_amount }}</td>
                                 <td>{{ $item->sanction_title }}</td>
                                <td>{{ $item->file_number }}</td>
                                <td>{{ $item->amount_paid }}</td>
                                <td>{{ $item->actual_payment_amount }}</td>
                                <td>{{ $item->vendor_name }}</td>
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
                                <td>
                                    
                                        @if(isset($item->status_id) &&  $item->status_id != $entity_details->final_status)
                                        
                                        <a href="{{ url('/gar/' . $item->id) }}" title="Edit GAR"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                        @endif 
                                       <!--
                                        <form method="POST" action="{{ url('/gar' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete GAR" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </form>
                                     -->
                                        @if(isset($item->status_id) &&  $item->status_id == $entity_details->final_status)
                                        
                                        <a href="{{ url('/gar/' . $item->id) }}" title="View GAR"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a> 
                                       
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
        ]
    });
    });

</script>     
@endsection 
