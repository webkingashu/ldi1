@extends('layouts.app')
@section('title', 'EAS')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />

@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> EC Register</strong></h3>
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
                                    <th>Release Title</th>
                                    <th>Release Order Amount</th>
                                    <th>Bill Number</th>
                                    <th>Budget Head</th>
                                    <th>Budget Head Amount</th>
                                    <th>Nature of Expense</th>
                                    <th>Budget Head Balance</th>
                                    <th>Date of Er Issue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; 
                                ?>
                                @if(isset($ec_register) && !empty($ec_register) && count($ec_register) >0)
                                @foreach($ec_register as $item)

                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                     <td>{{ $item->ro_title }}</td>
                                    <td>{{ $item->release_order_amount }}</td>
                                    <td>{{ $item->bill_no }}</td>
                                    <td>{{ $item->budget_head_of_acc }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->nature_of_expense }}</td>
                                    <td>{{ $item->budget_head_balance }}</td>
                                    <td>{{ $item->date_of_er_issue }}</td>
                                    
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $ec_register->appends(['search' => Request::get('search')])->render() !!} </div>
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
