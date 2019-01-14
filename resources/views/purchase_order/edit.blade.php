@extends('layouts.app')
@section('title', 'Edit Purchase Order')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="ibox">
      <div class="ibox-title col-md-12 display-flex">
        <h3 class="col-md-10"><strong> Edit Purchase Order</strong></h3>
        <div class="ibox-tools col-md-2">
            <a href="{{ url('purchase-order/' .$purchase_order_master->id) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
        </div>
      </div>
      <div class="ibox-title col-md-12 display-flex">
                    <ul class="breadcrumb">
                        <li><a class="breadcrumb-item" href="{{ url('purchase-order') }}">Purchase Order</a></li>
                        <li><a class="breadcrumb-item" href="{{ url('purchase-order/' .$purchase_order_master->id) }}">View Purchase Order</a></li>
                        <li><a class="breadcrumb-item">Edit Purchase Order</a></li>
                    </ul>
        </div>

      <div class="ibox-content" style="margin-bottom: 50px;">
        <form method="POST" action="{{ url('purchase-order/'.$purchase_order_master->id) }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
          {{ method_field('PATCH') }}
          {{ csrf_field() }}
          <input type="hidden" name="entity_id" value="{{isset($entity_details->id)?$entity_details->id:''}}">
           <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}">
          <input type="hidden" name="workflow_id" value="{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}">
        
          @include ('purchase_order.form', ['formMode' => 'edit'])
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
              <input type="hidden" name="id" value="{{isset($purchase_order_master->id) ? $purchase_order_master->id:''}}"/>
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


@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
@include('scripts.add_copy_to_js')
@include('scripts.edit_copy_to_js')
<script>
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
                $('input.total_unit_price_tax',this).text($total.toFixed(2));
                $('.total_unit_price_tax',this).text($total.toFixed(2));

               mult += $total;
            
           });
           $("#total_price_tax").text(mult.toFixed(2));
       }

  // $("#add_row").on("click", function () {

  //          var $tableBody = $('#previous_table').find("tbody");
  //          var $trLast = $tableBody.find("tr:last");
  //          //var checklist_type = "<?php echo session('checklist_type'); ?>";
  //          if ($trLast.length > 0) {
  //              var $trNew = $trLast.clone();
  //              $trNew.find(':text').val('');
  //             // $trNew.find('span total_unit_price_tax:text').val('');
  //              $trLast.after($trNew);
  //              var last = $('.field_group:last');
  //              var current = $(".field_group").length - 1;

  //           last.find('.sr_no').text($(".field_group").length);
  //           last.find('input.category').attr("name", "item[" + current + "][category]");
  //           last.find('input.item').attr("name", "item[" + current + "][item]");
  //           last.find('input.qty').attr("name", "item[" + current + "][qty]");
  //           last.find('input.unit_price_tax').attr("name", "item[" + current + "][unit_price_tax]");
  //           last.find('input.total_unit_price_tax').attr("name", "item[" + current + "][total_unit_price_tax]");
  //           //last.find('span.total_unit_price_tax').attr("");
  //           last.find('.placement-for-delete').html('<a class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash-o"></i></a>');
  //       }

  //   });

  //  $(document).on("click", ".remove_row", function () {
   
  //    $(this).closest('tr').remove();
  //    //calTotaUnitPrice();
  //     multInputs();
   
  //  });

  $(document).ready(function () {

    //  multInputs();
      var checkbox = $('#copy_to:checked').val();
    if(checkbox == 1) {
       $('#mail-users').show();
    }
    
       $('.date_added').datepicker({
            format:'dd/mm/yyyy',
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
         //  endDate: "today"

        });
         $('#date_of_bid').datepicker({
            format:'dd/mm/yyyy',
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            endDate: "today"

        });

  $('.chosen-select').chosen({width: "100%"});
  });
   
   
  $("#eas").change(function () {
   $('#invoice').empty();
      var eas_id = document.getElementById("eas").value;
        $.ajax({

            type: 'POST',
            url: "<?php echo url('/') ?>/vendor_details/"+ eas_id,
            data: {id:eas_id, "_token": "{{ csrf_token() }}"},
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
                },
            dataType: 'json',
            success: function (data) {
              $('#edit_item').hide(); 
              if(data.code == 200 ){
                var vendor_address =  data.address;
               $("#vendor_address").val(vendor_address);
               var vendor_name =  data.vendor_name;
               $("#vendor_name").val(vendor_name);
               $("#vendor_email").val(data.vendor_email);
                $('#invoice_details').show();
                 var num = 1;
                  $.each(data.item_details, function(k, v) {
                  
                   $('#invoice').append('<tr><td>'+(num++)+'</td><td><span class="category">'+v['category']+'</span></td><td><span class="item">'+v['item']+'</span></td><td><span class="qty">'+v['qty']+'</span></td><td><span class="unit_price_tax">'+v['unit_price_tax']+'</span</td><td><span class="total_unit_price_tax">'+v['total_unit_price_tax']+'</span></td></tr>')
                  });
               
                     var mult = 0;
             // for each row:
               $("tr").each(function () {
                   // get the values from this row:
                   var $qty = $('.qty', this).text();
                 
                   var $unit_price_tax = $('.unit_price_tax', this).text();
                   
                   var $total = ($qty * 1) * ($unit_price_tax * 1)
                   $('input.total_unit_price_tax',this).text($total.toFixed(2));
                   $('.total_unit_price_tax',this).text($total.toFixed(2));
                   
                   mult += $total;
                
               });
              $('#invoice').append('<tr><td colspan="5" style="text-align: right;"><strong>Total</strong></td><td class="total-combat">'+(mult.toFixed(2))+'</td></tr>');

              } else {
                $('#edit_item').show(); 
                 $('#invoice_details').hide();
                 alert('data not found.')
              }
               
           },
               complete: function(){
              $('.div-loader').css("display", "none");
            },
           error: function (data) {
               // alert(data);
           }
       });
});
$("#copy_to").change(function () {
  // $('input[type="checkbox"]').not(this).prop('checked', false);
   var checkebox = $('#copy_to:checked').val();
   if (checkebox == 1) {
   document.getElementById("mail-users").style.display = 'block';
   } else {
   document.getElementById("mail-users").style.display = 'none';

  }
});


</script>     
@endsection 
