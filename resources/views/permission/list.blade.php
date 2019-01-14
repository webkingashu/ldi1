@extends('layouts.app')
@section('title', 'Permission List')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
<link href="{{asset('css/sweetalert.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong>Permissions</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{url('permission/create')}}" title="Add New Permission"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
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
        <table class="table table-bordered dataTables-example">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Permission Display Name</th>
                    <th>Permission Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1;?>
                @if(isset($data) && count($data) >0)
                @foreach($data as $value)    
                <tr>
                    <td>{{ $count++}}</td>
                    <td>{{$value->display_name}}</td>  
                    <td>{{$value->name}}</td>  
                    <td>
                        <div class="row btn-action"><a href="{{url('permission/'.$value->id.'/edit')}}"><button class="btn btn-primary btn-sm" title="Edit Permission"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>
                            <form method="POST" action="{{ url('/permission' . '/' . $value->id) }}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Permission" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
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
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
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
