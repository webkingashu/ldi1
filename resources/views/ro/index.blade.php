@extends('layouts.app')
@section('title', 'Add Release Order')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
    <div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong>  Release Order</strong></h3>
                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('/ro/create') }}" title="Add New RO"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                    </div>
                </div>
        <div class="ibox-content scroll-hide">
            <table class="table table-striped table-bordered table-hover dataTables-example">
                <thead>
                <tr>
                    <th>Sr NO.</th>
                    <th>Date of Assignment</th>
                    <th>Ro Title</th>
                    <th>Department</th>
                    <th>File Number</th>
                    <th>EAS Title</th>
                    <th>Vendor Name</th>
                    <th>Proposed Payment Amount</th>
                    <th>Current Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($releaseOrder) && count($releaseOrder) > 0)
                @foreach($releaseOrder as $item)
               <?php $date = date('d-m-Y', strtotime($item->created_at));
                //$status_approved_date = date('d-m-Y', strtotime($item->status_approved_date));
               
                ?>   
                <tr>
                    <td>{{ $loop->iteration or $item->id }}</td>
                    <td>{{$date}}</td>
                    <td>{{$item->ro_title}}</td>
                     <td>{{$item->department_name}}</td>
                    <td>{{$item->file_number}}</td>
                    <td>{{$item->sanction_title}}</td>
                    <td>{{$item->vendor_name}}</td>
                    <td>{{$item->release_order_amount}}</td>
                    <!-- <td>{{$item->status_name}}</td> -->
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
                    <td>
                    @if($item->status_id != $entity_details->final_status)
      
                     <a href="{{ url('/ro/' . $item->id) }}" title="Edit RO"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                      
                    <!--  <form method="POST" action="{{ url('/ro' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                       {{ method_field('DELETE') }}
                       {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete RO" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                     </form> -->
                   
                    @else 
                      
                        <a href="{{ url('/ro/' . $item->id) }}" title="View RO"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                    
                    </td>
                   
                    @endif
                                    
                </tr>
                @endforeach
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> </div>
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
        //     { extend: 'copy'},
        //     {extend: 'csv'},
        //     {extend: 'excel', title: 'ExampleFile'},
        //     {extend: 'pdf', title: 'ExampleFile'},
        //     {extend: 'print',
        //     customize: function (win){
        //         $(win.document.body).addClass('white-bg');
        //         $(win.document.body).css('font-size', '10px');
        //         $(win.document.body).find('table')
        //         .addClass('compact')
        //         .css('font-size', 'inherit');
        //     }
        // }
        ]
    });
    });

</script>    
@endsection            