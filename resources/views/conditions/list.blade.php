@extends('layouts.app')
@section('title', 'Conditions List')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> Conditions</strong></h3>
                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('/conditions/create') }}" title="Add New Conditions"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                    </div>
                </div>
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
            <div class="ibox-content">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                    <tr>
                        <th>Condition Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    @if(!empty($data) && count($data) > 0 )
                    @foreach($data as  $value)
                    <tr>
                        <td>{{$value->condition_name}}</td>           
                        <td class=" btn-action">
                            <a href="{{url('conditions/'.$value->id.'/edit')}}" title="Edit Condition"><button class="btn btn-primary btn-sm action-button"><i class="fa fa-pencil-square-o"></i></button></a>
                            
                            <form method="POST" action="{{ url('conditions' . '/' . $value->id) }}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm action-button" title="Delete Condition" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
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
