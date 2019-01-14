@extends('layouts.app')
@section('title', 'Add Release Order')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@section('content')
<div class="row">
  <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title col-md-12 display-flex">
            <h3 class="col-md-10"><strong> Add RO</strong></h3>
            <div class="ibox-tools col-md-2">
                <a href="{{ url('/ro') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
        </div>
        <div class="ibox-title col-md-12 display-flex">
          <ul class="breadcrumb">
              @if(Session::get('eas_id'))
              @section('breadcrumbs')
              <li class="title"><a class="breadcrumb-item" href="{{ url('/eas/'. Session::get('eas_id')) }}">{{ Session::get('eas_title') }}</a></li>
              @show
              @endif
              <li><a class="breadcrumb-item" href="{{ url('/ro') }}">Release Order</a></li>
              <li><a class="breadcrumb-item">Add Release Order</a></li>
              
          </ul>
        </div>
        <div class="ibox-content">
            <form method="POST" action="{{ url('/ro') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                {{ csrf_field() }}
               
                <input type="hidden" name="entity_id" value="{{isset($entity_details->id)?$entity_details->id:''}}"/>
                 <input type="hidden" name="workflow_id" value="{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}"/>
                  <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}"/>
                @include ('ro.form', ['formMode' => 'create'])
            </form>
        </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
@include('scripts.add_copy_to_js')
<script type="text/javascript">


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
$('.chosen-select').chosen({width: "100%"});


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

$(document).ready(function () {

    var eas_id =  "{{ Session::get('eas_id') }}";
    if(eas_id != '')
    {
      ro_details();
    }
});


$('#eas').change(function() {
   var eas_id = $(this).val();
   $("#release_order_amount").val('');
  if(eas) {
    $.ajax({
       url:"{{url('/')}}/getVendorDetails",
       dataType: "json",
       type:"GET",
      beforeSend: function(){
      $('.div-loader').css("display", "block");
      },
       data: {
           eas_id : eas_id,
           _token: "{{csrf_token()}}"
       },
       success: function(data) {
           if((data) && data.code == 200) {
             var bank = data.bank_name+'-'+data.bank_branch;
             $("#vendor_name").val(data.vendor_name);
             $("#vendor_contact_number").val(data.mobile_no);
             $("#bank_name").val(bank);
             $("#eas_santation_total").val(data.sanction_total);
             $("#ifsc_code").val(data.ifsc_code);
             $("#total_sanction_amount").val(data.sanction_total);
             $("#budget_code").val(data.budget_code);
             $("#bank_acc_no").val(data.bank_acc_no);
             $("#bank_code").val(data.bank_code);
             $.ajax({

       url:"{{url('/')}}/getVendorDetails",
       dataType: "json",
       type:"GET",
        beforeSend: function(){
      $('.div-loader').css("display", "block");
      },
       data: {
           eas_id : eas_id,
           _token: "{{csrf_token()}}"
       },
       success: function(data) {
        console.log(data);
        $('#release_amount').empty();
           if((data) && data.code == 200) {

            var num = 1;

                  $.each(data.result, function(k, v) {
                  
                   $('#release_amount').append('<tr ><td>'+(num++)+'</td><td>'+v['ro_title']+'</td><td class="combat">'+v['release_order_amount']+'</td></tr>')
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
               $('#release_amount').append('<tr>data not found</tr>')
           }
       },
       complete: function(){
        $('.div-loader').css("display", "none");
      }

    });

           } else {
               //alert('Vendor details not found')
           }
       },
       complete: function(){
        $('.div-loader').css("display", "none");
      }
   });
  

 
  } else {
   alert('Eas not found.')
  }

});

// $(function() {
//   $('#upload_file').submit(function(e) {
   
//     e.preventDefault();
//     $.ajaxFileUpload({
//       url       :'CommonController/upload_file', 
//       secureuri   :false,
//       fileElementId :'file_name',
//       dataType    : 'JSON',
//        beforeSend: function(){
//       $('.div-loader').css("display", "block");
//       },
//       data      : {
//         'title'       : $('#title').val()
//       },
//       success : function (data, status)
//       {
//         if(data.status != 'error')
//         {
//           $('#files').html('<p>Reloading files...</p>');
//           refresh_files();
//           $('#title').val('');
//         }
//         alert(data.msg);
//       },
//        complete: function(){
//         $('.div-loader').css("display", "none");
//       }
//     });
//     return false;
//   });
// });

$("#release_order_amount").blur(function(){
  var total_sanction_amount = $("#total_sanction_amount").val();
  var total = $(".total-combat").text();
  var release_order_amount = $("#release_order_amount").val();
  var adding_amount = (parseInt(release_order_amount) + parseInt(total));

  if(adding_amount  > total_sanction_amount) {
    alert('Release Order Amount not greater than Eas sanction total amount.');
    $("#release_order_amount").val('');
  } 
});

</script>    
@endsection
