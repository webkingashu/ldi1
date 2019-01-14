@extends('layouts.app')
@section('title', 'Dispatch')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />

@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> Dispatch Register</strong></h3>
                    <div class="ibox-tools col-md-2">
                        <!-- <a href="{{ url('/eas/create') }}" title="Add New EAS"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a> -->
                    </div>
                </div>

                <div class="ibox-content scroll-hide">
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
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                  
                                    <th>Sr. No.</th>
                                    <th>File Number</th>
                                    <th>Vendor Name </th>
                                    <th>Release Title</th>
                                    <th>Release Order Amount</th>
                                    <th>Amount to be paid</th>
                                    <th>Dispatch Register Number</th>
                                    <th>Date of Receiving</th>
                                    <th>Date of Forwarding</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; 
                                ?>
                                @if(isset($dispatch_register) && !empty($dispatch_register) && count($dispatch_register) >0)
                                @foreach($dispatch_register as $item)

                                <tr>
                                   

                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td>{{ $item->file_number}}</td>
                                    <td>{{ $item->vendor_name}}</td>
                                    <td>{{ $item->ro_title}}</td>
                                    <td>{{ $item->release_order_amount }}</td>
                                    <td>{{ $item->amount_paid }}</td>
                                    <td>{{ $item->dispatch_register_no }}</td>
                                    <td>{{ $item->date_of_receiving }}</td>
                                    <td>{{ $item->date_of_forwarding }}</td>
                                  
                                    
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $dispatch_register->appends(['search' => Request::get('search')])->render() !!} </div>
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
            pageLength: 10,
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
