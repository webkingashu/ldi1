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
                    <h3 class="col-md-10"><strong> EAS</strong></h3>
                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('/eas/create') }}" title="Add New EAS"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
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
                                <th>Department</th>
                                <th>Title of the Sanction</th>
                               <!--  <th>Purpose of Sanction</th> -->
                                <!-- <th>Competent Authority</th> -->
                                <!-- <th>Serial No. of Sanction</th> -->
                                <th>File Number</th>
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
                            <?php $count = 1; 
                            ?>
                            @if(isset($eas_masters) && !empty($eas_masters))
                            @foreach($eas_masters as $item)
                           
                            <?php $competent_authority = ucwords(str_replace('_',' ',$item->competent_authority));?>
                                <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $item->department_name }}</td>
                                <td>{{ $item->sanction_title }}</td>
                                <!-- <td>{{ $item->sanction_purpose }}</td> -->
                                
                                <!-- <td>{{ $competent_authority }}</td> -->
                                <!-- <td>{{ $item->serial_no_of_sanction }}</td> -->
                                <td>{{ $item->file_number }}</td>
                                <td>{{ $item->sanction_total }}</td>
                                <td>{{ $item->budget_code }}</td>
                                <td>{{ $item->validity_sanction_period }}</td>
                                <!-- <td>{{ $item->date_issue }}</td> -->
                                <td>{{ $item->vendor_name }}</td>
                                <td> @if($item->status_name == "Approved")
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
                                      <?php //dd(($item->status_id));?>
                                        @if(($entity_details->final_status) != $item->status_id)

                                        <a href="{{ url('/eas/' . $item->id) }}" title="Edit EAS"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                       
                                       <!--  <form method="POST" action="{{ url('/eas' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
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
                            <?php //exit;?>
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
        ]
    });
    });

</script>     
@endsection 
