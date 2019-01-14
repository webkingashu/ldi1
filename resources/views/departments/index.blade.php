@extends('layouts.app')
@section('title', 'Department')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
   
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
             <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> Department</strong></h3>
                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('/departments/create') }}" title="Add New Department"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
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
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($departments as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <!-- <a href="{{ url('/admin/departments/' . $item->id) }}" title="View department"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> -->
                                           <div class="row btn-action">
                                            <a href="{{ url('/departments/' . $item->id.'/edit') }}" title="Edit Department"><button class="btn btn-primary btn-sm "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                            <form method="POST" action="{{ url('/departments' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Department" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $departments->appends(['search' => Request::get('search')])->render() !!} </div>
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
