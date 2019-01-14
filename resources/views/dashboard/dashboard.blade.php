@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
      <div class="col-lg-3">
        <div class="widget style1  red-bg">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-inr fa-5x"></i>
                </div>
                <div class="col-xs-8 text-right">
                  <h4>EAS </h4>
                  <h2 class="font-bold"> @if(isset($total_eas) && !empty($total_eas)) {{ $total_eas }} @else 0 @endif</h2>
              </div>
          </div>
      </div>
  </div>
  <div class="col-lg-3">
    <div class="widget style1 navy-bg">
        <div class="row">
            <div class="col-xs-4">
                <i class="fa fa-shopping-cart fa-5x"></i>
            </div>
            <div class="col-xs-8 text-right">
                <h4> Purchase Order </h4>
                <h2 class="font-bold"> @if(isset($total_purchase_order) && !empty($total_purchase_order)) {{ $total_purchase_order }} @else 0 @endif</h2>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-3">
    <div class="widget style1 yellow-bg">
        <div class="row">
            <div class="col-xs-4">
                <i class="fa fa-file-o fa-5x"></i>
            </div>
            <div class="col-xs-8 text-right">
                <span><h4> Release Order </h4></span>
                <h2 class="font-bold">@if(isset($total_ro) && !empty($total_ro)) {{ $total_ro }} @else 0 @endif</h2>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="widget style1 lazur-bg">
        <div class="row">
            <div class="col-xs-4">
                <i class="fa fa-file-o fa-5x"></i>
            </div>
            <div class="col-xs-8 text-right">
                <span><h4>GAR Bill</h4></span>
                <h2 class="font-bold">@if(isset($total_gar) && !empty($total_gar)) {{ $total_gar }} @else 0 @endif</h2>
            </div>
        </div>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
             <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> EAS</strong></h3>
               
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/eas/create') }}" title="Add New EAS"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </a>
                </div>
               
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example ">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Title of the Sanction</th>
                                <!-- <th>Purpose of Sanction</th> -->
                                <th>Competent</th>
                                <!-- <th>Sr.No.of Sanction</th> -->
                                <th>File No.</th>
                                <th>Sanction Total</th>
                                <th>Budget Code</th>
                                <th>Validity of Sanction Period</th>
                                <!-- <th>Date of Issue</th> -->
                                <th>Vendor Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1;?>
                            @if(isset($eas_masters) && count($eas_masters) >0)
                            @foreach($eas_masters as $item)
                            <tr>

                                <td>{{ $loop->iteration or $item->id }}</td>
                                <td><a class="eas_id"  data-eas_id="{{$item->id}}" href="{{ url('/dashboard/'. $item->id) }}">{{ $item->sanction_title }}   </a></td>

                                <!--     <td>{{ $item->sanction_purpose }}</td> -->
                                <td>{{ ucwords(str_replace('_',' ',$item->competent_authority)) }}</td>
                                <!-- <td>{{ $item->serial_no_of_sanction }}</td> -->
                                <td>{{ $item->file_number }}</td>
                                <td>{{ $item->sanction_total }}</td>
                                <td>{{ $item->budget_code }}</td>
                                <td>{{ $item->validity_sanction_period }}</td>
                                <!-- <td>{{ $item->date_issue }}</td> -->
                                <td>{{ $item->vendor_name }}</td>
                                <!--    <td>{{ $item->status_name }}</td> -->
                                <td>
                                    @if($item->status_name == "Approved")
                                    <span class="label label-primary"> {{ $item->status_name }} </span>
                                    @elseif ($item->status_name == "Pending Approval")
                                    <span class="label label-warning">  {{ $item->status_name}}</span> 
                                    @elseif ($item->status_name == "Draft")
                                    <span class="label label-info">  {{ $item->status_name}}</span> 
                                    @elseif ($item->status_name == "Return")
                                    <span class="label label-danger">{{ $item->status_name}}</span> 
                                    @else
                                    <span class="label">{{ $item->status_name }}</span> 
                                    @endif
                                </td>
                                <td>

                                    @if($item->status_id !== 3)
                                   
                                    <a href="{{ url('/eas/' . $item->id) }}" title="Edit EAS"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                    
                                    <!-- <form method="POST" action="{{ url('/eas' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete EAS" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </form> -->
                                    
                                    @else 

                                    <a href="{{ url('/eas/' . $item->id) }}" title="View EAS"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a> 

                                    @endif

                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="pagination-wrapper"> {!! $eas_masters->appends(['search' => Request::get('search')])->render() !!} </div>
                </div>
            </div>
        </div>
    </div>
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
            buttons: []
        });

        $(".eas_id").click(function(){
           var eas_id = $(this).data('eas_id');
          
         //$.session.set("eas_id",'12');
             
         });

    });
    
</script>       
@endsection            
