@extends('layouts.app')
@section('title', 'EAS')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
<link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong>EAS</strong></h3>
                    <div class="ibox-tools col-md-4">

                            @if(isset($transaction_data) && !empty($transaction_data))
                            <?php   $count = 0; ?>
                                @foreach($transaction_data as $value) 
                                    @if($count == 0 && $value['to_status_name'] == "Pending Approval")     
                                      <a href="{{ url('/eas/' . $eas_master->id . '/edit') }}" title="Back"><button class="btn btn-primary dim"><i  aria-hidden="true"></i>Edit</button></a>
                                    @endif
                                @endforeach
                            @endif
                      
                        <a href="{{ url('/revision/' . $eas_master->id. '/' . $entity_details->entity_slug) }}" title="Revision History"><button class="btn btn-primary dim">Revision History</button></a>
                        <a href="{{ url('eas') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>

                    </div>
                </div>
                  <div class="ibox-title col-md-12 display-flex">
                    <ul class="breadcrumb">
                        <li><a class="breadcrumb-item" href="{{ url('/eas') }}">{{ $eas_master->sanction_title }}</a></li>
                        <li><a class="breadcrumb-item">View EAS</a></li>
                    </ul>
                  </div>
                

                <div class="ibox-content form-horizontal">
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
                            <tbody>
                                <?php $competent_authority = ucwords(str_replace('_',' ',$eas_master->competent_authority));?>
                                    <!-- <tr>
                                        <th>ID</th><td>{{ $eas_master->id }}</td>
                                    </tr> -->
                                    <tr>
                                        <th> Sanction Title </th><td> {{ $eas_master->sanction_title }} </td>
                                        <th> Sanction Purpose </th><td> {{ $eas_master->sanction_purpose }} </td>
                                    </tr>
                                    <tr>
                                        <th> Competent Authority </th><td> {{ $competent_authority }} </td>
                                        <th> Serial Number of Sanction </th><td> {{ $eas_master->serial_no_of_sanction }} </td>
                                    </tr>
                                    <tr>
                                        <th>File Number</th><td> {{ $eas_master->file_number }} </td>
                                        <th>Value of Sanction Total</th><td> {{ $eas_master->sanction_total }} </td>
                                    </tr>
                                    <tr>
                                        <th>Budget Code</th><td> {{ $eas_master->budget_code }} </td>
                                        <th>Validity of Sanction Period</th><td> {{ $eas_master->cfa_dated }} </td>
                                    </tr>
                                     
                                    <tr>
                                      <th>Vendor Name</th><td> {{$eas_master->vendor_name}} </td>
                                       <th> Email </th><td>{{$eas_master->email}} </td>
                                    </tr>
                                    <tr>
                                      <th>Mobile No. </th><td>{{$eas_master->mobile_no}} </td>
                                      <th> Bank Name</th><td> {{$eas_master->bank_name}} </td>
                                    </tr>
                                    <tr>
                                     <th>Bank branch</th><td> {{$eas_master->bank_branch}}  </td>
                                      <th>Bank Account Number</th><td> {{$eas_master->bank_acc_no}} </td>
                                    </tr>
                                     <tr>
                                      <th> IFSC code </th><td> {{$eas_master->ifsc_code}} </td>
                                      <th> Bank code </th><td>{{$eas_master->bank_code}} </td>
                                    </tr>
                                    <tr>
                                        <th>Date of Issue</th><td> {{ $eas_master->date_issue }} </td>
                                        <th>Name of Payee Agency </th><td> {{ $eas_master->vendor_name }} </td>
                                    </tr>

                                    <tr>
                                        <th>CFA Note Number</th><td> {{ $eas_master->cfa_note_number }} </td>
                                        <th>CFA Dated</th><td> {{ $eas_master->cfa_dated }} </td>
                                    </tr>
                                    @if(isset($eas_master->fa_number) && !empty($eas_master->fa_dated))
                                    <tr>
                                       
                                        <th>FA Number</th><td> {{ $eas_master->fa_number }} </td>
                                      
                                        <th>FA Dated</th><td> {{ $eas_master->fa_dated }} </td>
                                        
                                    </tr>
                                    @endif
                                     <tr>
                                        <th>Department Name</th><td> {{isset($eas_master->department_name) ? $eas_master->department_name : '-'  }} </td>
                                        @if(isset($eas_master->whether_being_issued_under) && !empty($eas_master->whether_being_issued_under))
                                        <?php $whether_being_issued_under = ucwords(str_replace('_',' ',$eas_master->whether_being_issued_under));?>
                                        <th>Whether being issued under</th><td> {{ $whether_being_issued_under}} </td>
                                        @endif
                                       
                                    </tr>
                                    <tr>
                                        <th>Assignee</th><td> {{ isset($eas_master->assignee) ? $eas_master->assignee : '-' }} </td>
                                        
                                        @if(isset($eas_master->status_name) && !empty($eas_master->status_name) )
                                        <th>Status</th>
                                        <td>
                                           @if($eas_master->status_name == "Approved")
                                           <span class="label label-primary"> {{ $eas_master->status_name }} </span>
                                           @elseif ($eas_master->status_name == "Pending Approval")
                                           <span class="label label-warning">  {{ $eas_master->status_name}}</span>
                                           @elseif ($eas_master->status_name == "Draft")
                                           <span class="label label-info">  {{ $eas_master->status_name}}</span>
                                           @elseif ($eas_master->status_name == "Return")
                                           <span class="label label-danger">{{ $eas_master->status_name}}</span>
                                           @else
                                           <span class="label">{{ $eas_master->status_name }}</span>
                                           @endif
                                       </td>
                                       @endif
                                   </tr>
                                   <tr>
                                      @if(isset($eas_master) && !empty($eas_master->eas_pdf) && isset($eas_master->eas_pdf) &&  isset($entity_details->final_status) && ($eas_master->status_id) == $entity_details->final_status) 

                                    <th>Generated EAS</th><td><a class="btn btn-danger btn-sm" href="{{url('/download-eas-pdf/' .$eas_master->id)}}">Download</a></td>
                                     @endif
                                   </tr>
                               </tbody>
                           </table>    
                       </div>
                       <div class="hr-line-dashed"></div> 
                        @include('transactions_view')
                        @if(isset($item_details) && !empty($item_details))             
                <div class="wrapper wrapper-content animated fadeInRight">
                  <div class="row">
                <div class="col-lg-6">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Item Details</h5>
                            <div class="ibox-tools col-md-2">
                            
                          </div>
                        </div>
                        <div class="ibox-content">
                            
                            <table class="table table-bordered" id="previous_table">
                                <thead>
                                <tr>
                                  <th>Sr.No</th>
                                  <th>Category</th> 
                                  <th>Item</th>
                                  <th>Qty</th> 
                                  <th>Unit Price Excl Tax</th>
                                  <th>Total Price Excl Tax</th>
                                  
                              </tr>
                                </thead>
                                 <tbody>
                               
                                <?php $count = 1;?>
                                  @foreach($item_details as $key =>$val)
                                 
                                     <tr class="field_group">
                                     <td class="sr_no">{{$count++}}</td>
                                      <td>{{isset($val['category'])?$val['category']:''}}</td>
                                      <td>{{isset($val['item'])?$val['item']:''}}</td>
                                      <td class="qty">{{isset($val['qty'])?$val['qty']:''}}</td>
                                      <td class="unit_price_tax">{{isset($val['unit_price_tax'])?$val['unit_price_tax']:''}}</td>
                                     <td><span class="total_unit_price_tax"></td> 
                                  </tr>
                                 
                                  @endforeach
                                </tbody> 
                                 <tfoot>
                                  <tr>

                                     <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                                     <td><span id="total_price_tax"></td>
                                    
                                </tr> 
                               
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
               </div>
          </div>
          @endif
            </div>
        </div>
    </div>
</div>
</div> 
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script type="text/javascript">
  $(document).ready(function () {

      multInputs();
    });
   function multInputs() {
           var mult = 0;
           // for each row:
           $("tr.field_group").each(function () {
               // get the values from this row:
               var $qty = $('.qty', this).text();
               var $unit_price_tax = $('.unit_price_tax', this).text();
               var $total = ($qty * 1) * ($unit_price_tax * 1)
               $('.total_unit_price_tax',this).text($total.toFixed(2));

               mult += $total;
            
           });
           $("#total_price_tax").text(mult.toFixed(2));
       }
$(".start").click(function() {

    var created_by = '{{isset($eas_master->created_by)?$eas_master->created_by:''}}';
    var transaction_id = this.id;
    var entity_id =  '{{isset($entity_details->id)?$entity_details->id:''}}';
    var entity_slug =  '{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}';
    var workflow_id ='{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}';
    var url = '{{url("/")}}/updateCurrentTransaction';
    var id = '{{isset($eas_master->id)?$eas_master->id:''}}';
    var status_name = $(this).val();     
    var privious_status = $(this).data('privious_status');            
    var vendor_name = $('#vendor_id :selected').text();
    var file_number =  '{{isset($eas_master->file_number)?$eas_master->file_number:''}}';
    var sanction_total =  '{{isset($eas_master->sanction_total)?$eas_master->sanction_total:''}}';
    var sanction_title =  '{{isset($eas_master->sanction_title)?$eas_master->sanction_title:''}}';
    var final_status =  '{{isset($entity_details->final_status)?$entity_details->final_status:''}}';
    var status_id = '{{isset($eas_master->status_id)?$eas_master->status_id:''}}';
var old_assignee = '{{isset($assigne->old_assignee) ? $assigne->old_assignee:''}}';
console.log(old_assignee,'assigne');
var array = '<?php print_r($users_json);?>';
var options = {};
obj = JSON.parse(array);
$.map(obj, function (o) {
    options[o.id] = o.name;
});

  swal({
            title: "Are you sure want to change status?",
            text: "Once status changed,will not retain again!!",
            type: 'warning',
            //showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!',
            showCancelButton: true,
        }).then(function(result) {
          if(result.value) {

            if(status_name == "Return")  {
            comment(assignee=null);
            } 
            else if(status_name == 'Approve')  {
              updateTransaction(inputValue=null,assignee=null)
            }
            else {
              assignee();
            }
          }
        });

    function assignee(){
       swal({
      title: 'Do you want to assign?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm!',
        showCancelButton: true,
    }).then(function(result) {
      if(result.value){
      swal({
        title: 'Select Assignee',
        input: 'select',
        inputOptions: options,
        inputPlaceholder: 'Select Assignee',
        showCancelButton: true,
        inputValidator: function (assignee) {
           if (assignee != '') {
            return new Promise(function (resolve, reject) {
              if(status_name == "Return")  {
                comment(assignee);
             } else {
              updateTransaction(inputValue=null,assignee)
             }
            })
          
          } else {
            return !assignee && 'Please Select Assignee.';
          }   
        }
      })
      } else {
        if(status_name == "Return")  {
              comment(assignee=null);
            } else {
              updateTransaction(inputValue=null)
             }
      }
})
}
    function comment(assignee){
     
      swal({
                 title: "Please Enter Comment!",
                  text: "Why this EAS Rejected ?",
                  input: 'text',                  
                  showCancelButton: true,
                  inputPlaceholder: "Enter comment",
                inputValidator: function (inputValue) {
                 
                    if (inputValue != '') {
                      return new Promise(function (resolve, reject) {
                      updateTransaction(inputValue,assignee)
                  
                  })
                    } else {
                      return !inputValue && 'Please enter Comment.';
                    }

                  
                }
        })
    }

 function updateTransaction(inputValue=null,assignee=null) {
            if(created_by && transaction_id && entity_id && workflow_id && url ) {

                $.ajax({
                    url: url,
                    dataType: "json",
                    type:"POST",
                    beforeSend: function(){
                    swal.close()
                    $('.div-loader').css("display", "block");
                    },
                    data: {
                        transaction_type : transaction_id,
                        entity_id :entity_id,comment:inputValue,assignee:assignee,old_assignee:old_assignee,
                        workflow_id:workflow_id,created_by:created_by,entity_slug:entity_slug,id:id,status_name:status_name,sanction_total:sanction_total,sanction_title:sanction_title,file_number:file_number,vendor_name:vendor_name,privious_status:privious_status,final_status:final_status,
                        _token: "{{csrf_token()}}"
                    },
                     
                    success: function(data) {
                      //console.log(data)
                      if(data.code == 204){
                        swal({
                            title: 'Status', 
                            text: data.message,
                            type: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                      } else {
                       
                        // if(data.status_id == 3) {
                           if(final_status == data.status_id) {
                            swal({
                                title: "Approved",
                                text: data.message,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1000
                            });
                               window.location.href = "{{ url('/eas') }}/"+id;
                            
                        } else {
                            swal({
                                title: 'Updated!', 
                                text: data.message,
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                             location.reload();
                          }
                        }
                    },
                   complete: function(){
                  $('.div-loader').css("display", "none");
                }
                });
               } else {
                swal({
                    title: 'Status', 
                    text: 'Data Not Found.',
                    type: 'message',
                    showConfirmButton: false,
                    timer: 1500

                });
               }
            }

 });
</script>  
@endsection 
