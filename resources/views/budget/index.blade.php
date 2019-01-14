@extends('layouts.app')
@section('title', 'Budget')
@section('css')

<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />

@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                   <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong>Budget</strong></h3>
                    <div class="ibox-tools col-md-3">
                    <form method="GET" action="{{ url('/budget/edit') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                    <!-- <a href="{{ url('/budget/create') }}" title="Add New Budget"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </a>
                    <a href="{{ url('/budget/edit') }}" title="Edit Budget"><button class="btn btn-primary dim" type="submit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    </a> -->
                    <select name="year" id="year">
                    @foreach ($year_list as $value)
                       <!-- @if ($value->from_date == $year)
                        <option selected value="{{ $value->from_date }}">{{ $value->from_date}}-{{ $value->till_date }}</option>
                       @else
                       <option value="{{ $value->from_date }}">{{ $value->from_date}}-{{ $value->till_date }}</option>
                       @endif -->
                       <option value="{{ $value->from_date}}-{{ $value->till_date }}">{{ $value->from_date}}-{{ $value->till_date }}</option>
                    @endforeach 
                    <i class="fa fa-plus" aria-hidden="true"></i></select>
                     </form>
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
                       <!--  <a href="{{ url('/budget/create') }}" class="btn btn-success btn-sm" title="Add New Budget">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a> -->

                        <!-- <form method="GET" action="{{ url('/budget') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form> -->
<!-- 
                        <br/>
                        <br/> -->
                        <div class="table-responsive">
                        <table id="show" class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th><th>Division</th><th>OH</th><th>Budget Code</th><th>Budget Code(HEAD OF ACCOUNTS)</th><th>Broad description</th><th>Budget Amount</th><th>Effective Financial Year</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($budget) && count($budget) > 0)
                                @foreach($budget as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->name }}-{{ $item->location_name }}</td>
                                        <td>{{ $item->oh }}</td>
                                        <td>{{ $item->budget_code }}</td>
                                        <td>{{ $item->budget_head_of_acc }}</td>
                                        <td>{{ $item->broad_description }}</td>
                                        <!-- <td>{{ $item->amount }}</td> -->
                                        <td> {{$item->amount}} ({{ucwords(getAmountInWords($item->amount)) }})</td>
                                        <td>{{ $item->from_date }}-{{ $item->till_date }}</td>

                                        <!-- <td>
                                            <a href="{{ url('/budget/' . $item->id) }}" title="View Budget"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/budget/' . $item->id . '/edit') }}" title="Edit Budget"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/budget' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Budget" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td> -->
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

$("#year").on("change", function(){
  var selected = $(this).val();
  makeAjaxRequest(selected);
});

function makeAjaxRequest(year){
  $.ajax({
     headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    type: 'POST',
    url: '<?php echo route('budget-year'); ?>',
    data: {'year':year},
    success: function(data) {
         console.log(data);
         var table;
         table = $('#show').DataTable();
         table.clear();

                if (data == "err") {
                    alert("Something Happened Wrong! Please Try Again.");
                } else {
                    var sr_no = 1;
                    $.each(data, function (i, obj) {
                        console.log(obj);
                     table.row.add([sr_no,obj.name+'-'+obj.location_name,obj.oh,obj.budget_code,obj.budget_head_of_acc,obj.broad_description,obj.amount+' ('+obj.amount_in_words.charAt(0).toUpperCase() + obj.amount_in_words.substr(1).toLowerCase()+')',obj.from_date+'-'+obj.till_date]);
                     sr_no++;
                    });
                   table.draw();
                }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    }
  });
}


</script>     
@endsection 


