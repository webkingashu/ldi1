@extends('layouts.app')
@section('title', ' Add EAS')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@section('content')
<?php
// dd($list_of_departments);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Add EAS</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('eas') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
            <div class="ibox-title col-md-12 display-flex">
              <ul class="breadcrumb">
                  <li><a class="breadcrumb-item" href="{{ url('/eas') }}">EAS</a></li>
                  <li><a class="breadcrumb-item">Add EAS</a></li>
              </ul>
            </div>
           <!--  <div class="ibox-title">
               <h3><a href="{{ url('eas') }}" title="Back"><button class="btn btn-primary btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a><strong> Add EAS</strong></h3> 

           </div>  --> 
            <div class="ibox-content">
                <form method="POST" action="{{ url('/eas') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                   
                    <input type="hidden" name="entity_id" value="{{isset($entity_details->id)?$entity_details->id:''}}"/>
                     <input type="hidden" name="workflow_id" value="{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}"/>
                      <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}"/>
   
                    @include ('eas.form', ['formMode' => 'create'])
                    
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
//Item details js start
$(document).bind('keyup blur', '.create_field_group input', function (e) {
    multInputs();
  });
     
      function multInputs() {
           var mult = 0;
           // for each row:
           $("tr.create_field_group").each(function () {
               // get the values from this row:
               var $qty = $('.qty', this).val();
               var $unit_price_tax = $('.unit_price_tax', this).val();
               var $total = ($qty * 1) * ($unit_price_tax * 1)
               $('input.total_unit_price_tax',this).val($total.toFixed(2));
               $('.total_unit_price_tax',this).text($total.toFixed(2));

               mult += $total;
            
           });
           $("#create_total_price_tax").text(mult.toFixed(2));
       }
  //});

  $("#add_row").on("click", function () {

           var $tableBody = $('#previous_table').find("tbody");
           var $trLast = $tableBody.find("tr:last");
           //var checklist_type = "<?php echo session('checklist_type'); ?>";
           if ($trLast.length > 0) {
               var $trNew = $trLast.clone();
               $trNew.find(':text').val('');
              // $trNew.find('span total_unit_price_tax:text').val('');
               $trLast.after($trNew);
               var last = $('.create_field_group:last');
               var current = $(".create_field_group").length - 1;

            last.find('.sr_no').text($(".create_field_group").length);
            last.find('input.category').attr("name", "item[" + current + "][category]");
            last.find('input.item').attr("name", "item[" + current + "][item]");
            last.find('input.qty').attr("name", "item[" + current + "][qty]");
            last.find('input.unit_price_tax').attr("name", "item[" + current + "][unit_price_tax]");
            last.find('input.total_unit_price_tax').attr("name", "item[" + current + "][total_unit_price_tax]");
            //last.find('span.total_unit_price_tax').attr("");
            last.find('.placement-for-delete').html('<a class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash-o"></i></a>');
        }

    });

   $(document).on("click", ".remove_row", function () {
   
     $(this).closest('tr').remove();
     //calTotaUnitPrice();
      multInputs();
   
   });



//end Item table js

    //   $(".select2").select2({
    //     placeholder: "Select Name",
    //     allowClear: true
    // });

  

$(".is_vendor_present").change(function () {
    var checkebox = $('.is_vendor_present:checked').val(); 
    if (checkebox == 'yes') { 
    document.getElementById("is_vendor_present").style.display = 'block';
    } else {
    document.getElementById("is_vendor_present").style.display = 'none'; 
   }
            
});
$(document).ready(function () {
  
    $('.date_added').datepicker({
        format: 'dd/mm/yyyy',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    }).attr('readonly', 'readonly');

     var checkebox = $('.fa_concurrence:checked').val(); 

    if (checkebox == 'fa_concurrence') { 
    document.getElementById("is_delegated_powers").style.display = 'block';
    } else {
    document.getElementById("is_delegated_powers").style.display = 'none'; 
   }

   $(".select2").select2({
            width: '100%'
        }) 
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

$("#vendor_id").change(function () {
  var vendor_id = $(this).val();
  if(vendor_id) {
   $.ajax({

            type: 'GET',
            url: "<?php echo url('/') ?>/get-vendor-details/"+ vendor_id,
            data: {vendor_id:vendor_id, "_token": "{{ csrf_token() }}"},
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
                },
            dataType: 'json',
            success: function (data) {
              console.log(data)
              if(data.code == 200) {
               $('#vendor_details').show();
               $("#vendor_bank_acc_no").val(data.vendor_details['bank_acc_no']);
               $("#vendor_gstin").val(data.vendor_details['gstin']);
               $("#vendor_mobile_no").val(data.vendor_details['mobile_no']);
              } else {
              alert('Vendor details not found')
              }
              
           },
               complete: function(){
              $('.div-loader').css("display", "none");
            },
           error: function (data) {
               $('#vendor_details').hide();
           }
       });
    } else {
      alert('Vendor Id not found')
    }
  //}
});

$("#department").change(function () {

  var department_id = $(this).val();

  if(department_id) {
    $.ajax({

            type: 'GET',
            url: "<?php echo url('/') ?>/get-deptartment-details/"+ department_id,
            data: {department_id:department_id, "_token": "{{ csrf_token() }}"},
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
                },
            dataType: 'json',
            success: function (data) {
              console.log(data);
               if(data.code == 200) {
               $('#serial_no_of_sanction').val(data.serial_no_of_sanction);
               $('#file_number').val(data.file_number);
               $('#users_list').empty();
               //$('select[name="users_list"]').empty();
                        $.each(data.users, function(key, value) {
                            $('#users_list').append('<option value="'+ key +'">'+ value +'</option>');
                        });

              } else {
              alert('Vendor details not found')
              }
              
           },
               complete: function(){
              $('.div-loader').css("display", "none");
            },
           error: function (data) {
               $('#vendor_details').hide();
           }
       });

  } else {
    alert("Department Id not found.")
  }          
});


// $(".department_id").change(function () {
// //function getUsersList(){
//   var data_id =  $(this).data('id');
//   var department_id = $('option:selected', this).val();

//   if(department_id) {
//     $.ajax({

//             type: 'GET',
//             url: "<?php echo url('/') ?>/getDepartmentWiseUserList/"+ department_id,
//             data: {department_id:department_id, "_token": "{{ csrf_token() }}"},
//             beforeSend: function(){
//                 swal.close()
//                 $('.div-loader').css("display", "block");
//                 },
//             dataType: 'json',
//             success: function (data) {

//             // $('.users_list').attr('id',data_id).empty();
//              $('.user_id').find($("select[data-id='" + data_id +"']")).empty();
//                if(data.code == 200) {
//                //$('#users_list').empty();
//                //$('select[name="users_list"]').empty();
//                         $.each(data.users, function(key, value) {
//                         $('.user_id').find($("[data-id='" + data_id +"']")).append('<option value="'+ value['id'] +'">'+ value['name'] +'</option>');
//                         });

//               } else {
//               alert('Users details not found')
//               }
              
//            },
//                complete: function(){
//               $('.div-loader').css("display", "none");
//             },
//            error: function (data) {
//                //$('#vendor_details').hide();
//            }
//        });

//   } else {
//     alert("Department Id not found.")
//   }
// //}            
// });


$("#budget_code").change(function () {
  var budget_amount = $('#budget_code option:selected').attr('budget_amount');
   $('#budget_head_amount').text('Budget Head Amount is ' +budget_amount);
   $('#budget_head_amount').append('<input type="hidden" name="budget_head_amount" value='+budget_amount+'>');
  
  });
//}

$("#check_budget_head").click(function(){
 // $(document).bind('keyup blur change click', '.check_budget_head', function () {
  var sanction_total = $("#sanction_total").val();
  var budget_amount = $('#budget_code option:selected').attr('budget_amount');
 
if(sanction_total &&  budget_amount) {
  if(parseInt(sanction_total)  > parseInt(budget_amount)) {
      $('.check_budget_head').text(''); 
   $('#sanction_total').after('<span class="check_budget_head" style="color:#cc5965;font-weight:bold;">EAS Amount is more than the budget allotted. You can still create EAS but approval maybe delayed by authority.</span>');
   
    } else {
      $('.check_budget_head').text('');
        $('#sanction_total').after('<span class="check_budget_head" style="color:#008000;font-weight:bold;">EAS Amount is less than the budget allotted. You may proceed further.</span>');
    
      //$('#sanction_total').after('<span class="check_budget_head" style="color:#000;>EAS Amount is less than the budget allotted. You may proceed further.</span>');
    } 
  }else {
    alert('Budget Code or Value of Sanction Total Required.')
  }
  
});

</script>      
@endsection
