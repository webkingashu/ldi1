@extends('layouts.app')
@section('title', 'Edit Release Order')
@section('css')
<link rel="stylesheet" href="{!! asset('css/sweetalert.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Edit RO</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/ro/' . $ro->id) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
             <div class="ibox-title col-md-12 display-flex">
                    <ul class="breadcrumb">
                        <li><a class="breadcrumb-item" href="{{ url('/ro') }}">Release Order</a></li>
                        <li><a class="breadcrumb-item" href="{{ url('/ro/' . $ro->id) }}">View Release Order</a></li>
                        <li><a class="breadcrumb-item">Edit Release Order</a></li>
                    </ul>
              </div>
             
            <div class="ibox-content">
                <form method="POST" action="{{ url('/ro/' . $ro->id) }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                     <input type="hidden" name="entity_id" value="{{isset($entity_details->id)?$entity_details->id:''}}"/>
                     <input type="hidden" name="workflow_id" value="{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}"/>
                     <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}"/>
                     
                    @include ('ro.form', ['formMode' => 'edit'])
                     <div class="form-group">
                     <div class="col-sm-4 col-sm-offset-5"> 
                        <input class="btn btn-primary" type="submit"  value="Update"> 
                       <input onclick="backToPrev()" class="btn btn-primary" type="button"  value="Cancel"> 
                     </div>
                </div>
               </form>
               <a data-toggle="modal" href="#add_modal_copy_to"></a>
<div id="add_modal_copy_to" class="modal fade" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="text-center">
              <h3 class="m-t-none m-b">Copy To</h3>
            </div>
            <form action="{{ url('/add-copy') }}" method="POST">  
             
              {{ csrf_field() }}    
              <input type="hidden" name="entity_id" value="{{isset($entity_details->id) ? $entity_details->id:''}}"/>
              <input type="hidden" name="id" value="{{isset($ro->id) ? $ro->id:''}}"/>
              <div class="form-group"> 
                <div class="col-sm-5">
                  <select name="department_id" class="form-control select2 update_department" required>
                   <option disabled="disabled" selected>Select Department</option>
                   @if(isset($list_of_departments))
                   @foreach($list_of_departments as $value) 
                   <option @if(old('department') == $value['id']) selected @endif  <?php if(isset($value['id']) && isset($copy_to_val['department_id']) && $copy_to_val['department_id'] == $value['id']) { echo 'selected';} ?>  value="{{ $value['id']}}" slug="{{ $value['slug']}}">{{ $value['name']}} - ({{$value['location_name']}})</option>
                   @endforeach
                   @endif
                 </select>
               </div>
               <div class="col-sm-5">
                 <select  class="select2 update_user" name="user_id" data-placeholder="Select User" required>
                  <option disabled="disabled" selected="selected">Select Users</option>
                  @if(isset($users) && !empty($users))
                  @foreach($users as $value) 
                  <option @if(old('users') == $value['id']) selected @endif    value="{{ $value['id']}}" >{{ $value['name']}}</option>
                  @endforeach
                  @endif
                </select>
              </div>

                                  <!-- <div class="col-sm-2">
                                     <button class="btn btn-success add-more-copy_to" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                      <div class="remove-button">
                                   </div>
                                 </div> -->
                               </div>
                               
                               <div class="form-group" > 
                                <input class="btn btn-primary btn btn-primary col-md-offset-4" type="submit"  value="Submit" style="margin-top: 20px;">
                                <input class="btn btn-primary btn btn-primary closeModal" type="reset"  value="Cancel" style="margin-top: 20px;">
                              </div>
                            </form>   
                          </div>

                        </div>
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
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/sweetalert/sweetalert.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
@include('scripts.add_copy_to_js')
@include('scripts.edit_copy_to_js')s
<script type="text/javascript">
$("#update_copy_to").click(function () {

  var id = '{{isset($purchase_order_master->id)?$purchase_order_master->id:''}}';
  var url = '{{url("/")}}/updateCopyTo';
  var entity_id =  '{{isset($entity_details->id)?$entity_details->id:''}}';

  var department_id = [];
  $.each($(".update_department option:selected"), function(){            
      department_id.push($(this).val());
  });
   var user_id = [];
  $.each($(".update_user option:selected"), function(){            
      user_id.push($(this).val());
  });

  if(user_id && department_id && user_id) {
   $.ajax({

            type: 'POST',
            url: url,
            data: {id:id, department_id:department_id,user_id:user_id,entity_id:entity_id,"_token": "{{ csrf_token() }}"},
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
                },
            dataType: 'json',
            success: function (data) {
             
              if(data.code == 200) {
              $('#modal_copy_to').modal('hide');
              swal({
                        title: 'Updated!', 
                        text: data.message,
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
              } else {
              $('#modal_copy_to').modal('show');
              alert(data.message)
              }
              
           },
               complete: function(){
              $('.div-loader').css("display", "none");
            },
           error: function (data) {
               
           }
       });
    } else {
      alert('Please fill required field.')
    }
  //}
});
$('#is_invoice_present').change(function(){
  if(this.checked)
      
      $('#invoice_table').show();
  else
      $('#invoice_table').hide();

});
  $("#add_row").on("click", function () {

           var $tableBody = $('#previous_table').find("tbody");
           var $trLast = $tableBody.find("tr:last");
           //var checklist_type = "<?php echo session('checklist_type'); ?>";
           if ($trLast.length > 0) {
               var $trNew = $trLast.clone();
               $trNew.find(':text').val('');
               $trLast.after($trNew);
               var last = $('.field_group:last');
               var current = $(".field_group").length - 1;

            last.find('.sr_no').text($(".field_group").length);
            last.find('input.invoice_no').attr("name", "invoice[" + current + "][invoice_no]");
            last.find('input.agency_name').attr("name", "invoice[" + current + "][agency_name]");
            last.find('input.qty').attr("name", "invoice[" + current + "][qty]");
            last.find('input.period').attr("name", "invoice[" + current + "][period]");
            last.find('input.amount_payment').attr("name", "invoice[" + current + "][amount_payment]");
            last.find('input.sla_amount').attr("name", "invoice[" + current + "][sla_amount]");
            last.find('input.applicable_taxes').attr("name", "invoice[" + current + "][applicable_taxes]");
            last.find('input.withheld_amount').attr("name", "invoice[" + current + "][withheld_amount]");
            last.find('input.net_payable_amount').attr("name", "invoice[" + current + "][net_payable_amount]");
            last.find('.placement-for-delete').html('<a class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash-o"></i></a>');
        }

    });

   $(document).on("click", ".remove_row", function () {
   
     $(this).closest('tr').remove();
     calNetPayableAmount();
   
   });
function calNetPayableAmount() {

   var counttoll = 0;
         $(".net_payable_amount").each(function () {
 
       if (!isNaN(parseFloat($(this).val()))) {
           counttoll = parseFloat(parseFloat(counttoll || 0) + parseFloat($(this).val()));
       }
       });

     $("#total_payable_amount").text(counttoll.toFixed(2));

}
$(document).bind('keyup blur', '.net_payable_amount', function (e) {

          calNetPayableAmount();
 });
$(".is_vendor_present").change(function () {
    var checkebox = $('.is_vendor_present:checked').val(); 
    if (checkebox == 'yes') { 
    document.getElementById("is_vendor_present").style.display = 'block';
    } else {
    document.getElementById("is_vendor_present").style.display = 'none'; 
   }
            
});
$(document).ready(function () {
 var is_invoice_present = $('#is_invoice_present:checked').val();
 //alert(is_invoice_present)
  if(is_invoice_present == 1){
      
      $('#invoice_table').show();
 }

    calNetPayableAmount();
  var advance_ro = $('#advance-ro:checked').val();
  var email_users = $('#copy_to:checked').val();

  if(advance_ro == 1)
  {
      $('#diary-number').show();

  }
  else
  {
    $('#diary-number').hide();
  }
  if(email_users == 1)
  {

     $('#mail-users').show();
    
  }
  else
  {
    $('#mail-users').hide();
  }

    $('.date_added').datepicker({
        dateFormat: 'dd-mm-yyyy',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    }).attr('readonly', 'readonly');
 });

$('.chosen-select').chosen({width: "100%"});

$(".fa_concurrence").change(function () {

$('input[type="checkbox"]').not(this).prop('checked', false);
    var checkebox = $('.fa_concurrence:checked').val(); 
    if (checkebox == 'fa_concurrence') { 
    document.getElementById("is_delegated_powers").style.display = 'block';
    // $(".delegate_powers").attr('checked',false);
    // $(".fa_concurrence").attr('checked',true);
    } else {
    document.getElementById("is_delegated_powers").style.display = 'none'; 
    // $(".delegate_powers").attr('checked',true);
    // $(".fa_concurrence").attr('checked',false);
   }
   if(checkebox == undefined || checkebox !== 'fa_concurrence' ) {
    $("#delegate_powers").prop( "checked", true );
   } else {
    $("#fa_concurrence").prop( "checked", true );
   }
         
});

 $('#advance-ro').change(function(){
  
        if(this.checked)
            
            $('#diary-number').show();
        else
            $('#diary-number').hide();

    });
     
     $('#copy_to').change(function(){
        if(this.checked)
            
            $('#mail-users').show();
        else
            $('#mail-users').hide();

    });
$('.invoice').click(function() {

   var invoice_id = $(this).data('invoice_id');
   var form_type = $(this).data('form_type');
  // $(this).closest('tr').remove();

  if(invoice_id) {
    $.ajax({
       url:"{{url('/')}}/updateInvoiceDetails",
       dataType: "json",
       type:"POST",
       data: {
           invoice_id : invoice_id,form_type:form_type,
           _token: "{{csrf_token()}}"
       },
       success: function(data) {
           console.log(data);
       }
   });
  }
});

$('#eas').change(function() {
   var eas_id = $(this).val();

  if(eas) {


    $.ajax({
       url:"{{url('/')}}/getVendorDetails",
       dataType: "json",
       type:"GET",
       data: {
           eas_id : eas_id,
           _token: "{{csrf_token()}}"
       },
       success: function(data) {
           console.log(data);
           if((data) && data.code == 200) {

             var bank = data.bank_name+'-'+data.bank_branch;
             $("#vendor_name").val(data.vendor_name);
             $("#vendor_contact_number").val(data.mobile_no);
             $("#bank_name").val(bank);
             $("#ifsc_code").val(data.ifsc_code);
             $("#total_sanction_amount").val(data.sanction_total);
             $("#budget_code").val(data.budget_code);
             $("#bank_acc_no").val(data.bank_acc_no);
             $("#bank_code").val(data.bank_code);

           } else {
               alert('Vendor details not found')
           }
       }
   });
  
$.ajax({

       url:"{{url('/')}}/getVendorDetails",
       dataType: "json",
       type:"GET",
       data: {
           eas_id : eas_id,
           _token: "{{csrf_token()}}"
       },
       success: function(data) {
        $('#release_amount').empty();
           if((data) && data.code == 200) {
     
            var num = 1;

                  $.each(data.result, function(k, v) {
                  
                   $('#release_amount').append('<tr><td>'+(num++)+'</td><td>'+v['ro_title']+'</td><td class="combat">'+v['release_order_amount']+'</td></tr>')
                  });
               
                    var sum = 0
                    $('tr').find('.combat').each(function () {

                      var combat = $(this).text();

                      if (!isNaN(combat) && combat.length !== 0) {
                       
                        sum += parseFloat(combat);
                      }
                    });  
                    $('.total-combat', this).html(sum);
               
                 $('#release_amount').append('<tr><td colspan="2" class="text-right ">Total</td><td class="total-combat">'+sum+'</td></tr>');
           } else {
               $('#release_amount').append('<tr><td>data not found</td></tr>')
           }
       }
    });
 
  } else {
   alert('Eas not found.')
  }

});
  
</script>      
@endsection            
