@extends('layouts.app')
@section('title', 'Forwarding Letter')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
<link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
@endsection
@section('content')
<?php 
// dd($forwarding_letter_generated);  ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">

            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Forwarding Letter</strong></h3>
                
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/forwarding-letter/create') }}" title="Generate Forwarding Letter"><button class="btn btn-primary dim" type="button" id="add-forwarding-letter"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </a>
                    
                </div>   
            </div>                   
            <div class="ibox-content">
                <table class="table table-bordered dataTables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Department</th>
                            <th>Location</th>
                            <th>Eas Title</th>
                            <th>Ro Title</th>
                            <th>Date of Issue</th>
                            <th style="width: 16%;">Amount (In Rupees)</th>
                            <th>Download</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; ?>
                            @if(isset($forwarding_letter_generated) && count($forwarding_letter_generated) >0)
                            @foreach($forwarding_letter_generated as $item)
                        <tr>
                            <td>{{ $loop->iteration or $item->id }}</td>
                            <td>{{ $item->department_name }}</td>
                            <td>{{ $item->location_name }}</td>
                            <td>{{ $item->sanction_title }}</td>
                            <td>{{ $item->ro_title }}</td>
                            <td><?php echo (date('d/m/Y',strtotime($item->date_of_issue)));?></td>
                            <td>{{ $item->total_amount }}</td>
                            <td><a href="{{url('/download-forwardingletter/'.$item->id)}}"><img src="{{url('/')}}/img/pdf-icon.png" width="40px" height="40px"></a></td> 
                            <td><a  title="Delete Forwarding Letter" ><button class="btn btn-primary btn-sm fwd_id" value="{{$item->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a></td>
                             @endforeach
                            @endif
                        </tr>
                        
                    </tbody>
                </table> 
            <div class="hr-line-dashed" style="height: 1px;margin: 20px 0;border-top: transparent;"></div>
       </div>
   </div>
</div>
</div>
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script type="text/javascript">
//     $('a[data-href]').on('click', function(e) {
//     e.preventDefault();
//     window.location.href = $(this).data('href');
// });
$(document).ready(function(){
        $('.dataTables').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
        ]
    });
    });
    $(".fwd_id").click(function() {
        var id = $(this).val();
        console.log(id);
        // swal({
        //     title: "Are you sure want to Delete this record?",
        //     text: "Once deleted, will not retain again!!",
        //     type: "warning",
        //     showCancelButton: true,
        //     confirmButtonColor: "#DD6B55",
        //     confirmButtonText: "Yes",
        //     cancelButtonText: "No",
        //     closeOnConfirm: false
        // },
        // function () {
         swal({
            title: "Are you sure want to Delete this record",
            text: "Once status changed,will not retain again!!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!',
            showCancelButton: true,
        }).then(function(result) {
          if(result.value) {    
            $.ajax({
                url: '{{url("/")}}/forwarding-letter-delete',
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
                            title: 'Forwarding Letter', 
                            text: data.message,
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    } else {
                        swal({
                            title: 'Forwarding Letter', 
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
    });
        //)}
    //);
    
</script>

@endsection            



