@extends('layouts.app')
@section('title', 'Generate Cheque')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> List of Cheque</strong></h3>
                    
                    <div class="ibox-tools col-md-2">
                        <a href="{{ url('/upload-cheque') }}" title="Generate Cheque"><button class="btn btn-primary dim" type="button" id="add-cheque"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                    </div>   
                </div>
                <div class="ibox-content scroll-hide">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Department</th>
                                    <th>Location</th>
                                    <th>Eas Title</th>
                                    <th>Ro Title</th>
                                    <th>Name on Cheque</th>
                                    <th>Cheque Number</th>
                                    <th>Date of Issue</th>
                                    <th>Cheque Amount</th>
                                    <th>Download</th>
                                    <!-- <th>Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; 
                                ?>
                                @if(isset($cheques) && count($cheques) >0)
                                @foreach($cheques as $item)
                                <?php ?>
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td>{{ $item->department_name }}</td>
                                    <td>{{ $item->location_name }}</td>
                                    <td>{{ $item->sanction_title }}</td>
                                    <td>{{ $item->ro_title }}</td>
                                    <td>{{ $item->cheque_name }}</td>
                                    <td>{{ $item->cheque_number }}</td>
                                    <td><?php echo (date('d/m/Y',strtotime($item->cheque_date)));?></td>
                                    <td>{{ $item->cheque_amount }}</td>
                                     <!-- <td><a href="<?php echo storage_path();?>\<?php echo $item->file_path;?>"><img src="{{url('/')}}/img/pdf-icon.png" width="40px" height="40px" ></a></td>  -->
                                     <td><a href="{{url('/download-cheque/'.$item->id)}}"><img src="{{url('/')}}/img/pdf-icon.png" width="40px" height="40px"></a></td> 
                                    <!-- <td><a title="Delete Cheque"><button class="btn btn-primary btn-sm cheque_id" value="{{$item->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a></td> -->
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
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
        ]
    });
    });

    
    $(".cheque_id").click(function() {
        var id = $(this).val();
       // console.log(id);
        swal({
            title: "Are you sure want to Delete this record?",
            text: "Once deleted, will not retain again!!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!'
            //showCancelButton: true,
          }).then(function(result) {
        //function () {
             if(result.value){
            $.ajax({
                url: '{{url("/")}}/delete-cheque',
                dataType: "json",
                type:"post",
                beforeSend: function(){
                    swal.close()
                    $('.div-loader').css("display", "block");
                },
                data: {
                    id:id,
                    _token: "{{csrf_token()}}"
                },

                success: function(data) {
                    if(data.code == 200){
                        swal({
                            title: 'Cheque', 
                            text: data.message,
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    } else {
                        swal({
                            title: 'Cheque', 
                            text: data.message,
                            type: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    }
                },complete: function(){
                    $('.div-loader').css("display", "none");
                }
            });
        }
        })
         // }
    })
</script>
    
@endsection 
