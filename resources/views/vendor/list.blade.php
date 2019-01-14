@extends('layouts.app')
@section('title', 'Vendor List')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> Vendor</strong></h3>
                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('/vendor/create') }}" title="Add New vendor"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Vendor Name</th>
                                    <th>Email</th>
                                    <th>Mobile Number</th>
                                    <th>Address</th> 
                                    <th>Bank Account Number</th>
                                    <th>Bank Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody><?php $count = 1; 
                            ?>
                                @if(!empty($result) && count($result) > 0 )
                                    @foreach($result as $value)
                                        <tr>
                                            <td>{{$count++}}</td>
                                            <td>{{$value->vendor_name}}</td>
                                            <td>{{$value->email}}</td>
                                            <td>{{$value->mobile_no}}</td>
                                            <td>{{$value->address}}</td> 
                                            <td>{{$value->bank_acc_no}}</td>
                                            <td>{{$value->bank_name}}</td>  
                                            <td>{{$value->vendor_status}}</td>                 
                                            <td class="project-actions">
                                                
                                                <a href="{{ url('/vendor/' . $value->id . '/edit') }}" title="Edit vendor"><button class="btn btn-primary btn-sm action-button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            
                                              <!--   <form method="POST" action="{{ url('/vendor' . '/' . $value->id) }}" accept-charset="UTF-8" style="display:inline">
                                                <input type="hidden" name="vendor_status" value="{{$value->vendor_status}}">
                                                {{ method_field('PATCH') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm action-button" title="Disable Vendor" onclick="return confirm(&quot;Confirm Disable?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                                                </form>
                                            -->
                                             
                                                <form method="POST" action="{{ url('/vendor' . '/' . $value->id) }}" accept-charset="UTF-8" style="display:inline">
                                                <input type="hidden" name="vendor_status" value="{{$value->vendor_status}}">
                                                
                                                {{ csrf_field() }}
                                                @if($value->vendor_status == 'Enable')
                                                 <button type="submit" class="btn btn-danger btn-sm action-button" title="Disable Vendor" onclick="return confirm(&quot;Confirm Disable?&quot;)">Disable</button>
                                                @endif
                                                @if($value->vendor_status == 'Disable')
                                                <button type="submit" class="btn btn-danger btn-sm action-button" title="Enable Vendor" onclick="return confirm(&quot;Confirm Enable?&quot;)">Enable</button>
                                                
                                                 @endif   
                                                 </form>
                                                <!-- <a href="{{url('/vendor/delete/'.$value->id)}}" title="delete vendor"><button class="btn btn-primary btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a> -->
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
<script src="{!! asset('js/plugins/metisMenu/jquery.metisMenu.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/slimscroll/jquery.slimscroll.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/inspinia.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/pace/pace.min.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
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

                    // {
                    //     // extend: 'print',
                    //  customize: function (win){
                    //         $(win.document.body).addClass('white-bg');
                    //         $(win.document.body).css('font-size', '10px');

                    //         $(win.document.body).find('table')
                    //                 .addClass('compact')
                    //                 .css('font-size', 'inherit');
                    // }
                    // }
                ]

            });

        });
</script> 
    
@endsection            