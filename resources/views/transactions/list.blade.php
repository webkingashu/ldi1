@extends('layouts.app')
@section('title', 'Transaction')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />

@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Transaction</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/transaction/create') }}" title="Add New Transaction"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </a>
                </div>
            </div>

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
             <table class="table table-bordered dataTables-example">
                <thead>
                    <tr>            
                        <th>Sr.No.</th>
                        <th>Transaction Name</th>
                        <th>From </th>
                        <th>To</th>
                        <!-- <th>Role</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1;?>
                    @foreach($transactions as $key => $value)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$value->transaction_name}}</td>
                        <td>{{$value->from_status_name}}</td>
                        <td>{{$value->to_status_name}}</td>
                        <!-- <td>{{$value->name}}</td> -->
                        <td class="row-actions">
                            <a href="{{ url('/transaction/'.$value->id.'/edit')}}"><button class="btn btn-primary btn-sm action-button" title="Edit Transaction"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>
                            <a href="" class="danger delete_entity_type" data-id="{{$value->id}}"> <button type="submit" class="btn btn-danger btn-sm action-button" title="Delete Transaction" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> </button></a>
                        </td>
                    </tr>
                    @endforeach
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
