@extends('layouts.app')
@section('title', 'Edit EAS')
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
                <h3 class="col-md-10"><strong> Edit EAS</strong></h3>
                 <div class="ibox-tools col-md-3">
                    <a href="{{ url('/eas/' . $eas_master->id) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
             <div class="ibox-title col-md-12 display-flex">
                    <ul class="breadcrumb">
                        <li><a class="breadcrumb-item" href="{{ url('/eas') }}">{{$eas_master->sanction_title}}</a></li>
                        <li><a class="breadcrumb-item" href="{{ url('/eas/' . $eas_master->id) }}">View EAS</a></li>
                        <li><a class="breadcrumb-item">Edit EAS</a></li>
                    </ul>
            </div>
             
            <div class="ibox-content">
                <form method="POST" action="{{ url('/eas/' . $eas_master->id) }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                     <input type="hidden" name="entity_id" value="{{isset($entity_details->id)?$entity_details->id:''}}"/>
                     <input type="hidden" name="workflow_id" value="{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}"/>
                     <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}"/>
                    @include ('eas.form', ['formMode' => 'edit'])
                    
                  <div class="form-group">
                     <div class="col-sm-4 col-sm-offset-5"> 
                        <input class="btn btn-primary" type="submit"  value="Update"> 
                        <input onclick="backToPrev()" class="btn btn-primary" type="button"  value="Cancel"> 
                     </div>
                </div>
               </form>
                <a data-toggle="modal" href="#add_item_details"></a>
                <div id="add_item_details" class="modal fade" aria-hidden="true" >
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="text-center">
                              <h3 class="m-t-none m-b">Copy To</h3>
                              <a id="add_new_row" class="btn btn-primary col-md-offset-10" style="margin-bottom: 10px;">Add</a> 
                            </div>
                            <form action="{{ url('/add-item-details') }}" method="POST">  
                             
                              {{ csrf_field() }}
                              <input type="hidden" name="eas_id" value="{{isset($eas_master->id)?$eas_master->id:''}}"/>      
                              <table class="table table-bordered" id="edit_table">
                                <thead>
                                  <tr>
                                    <th>Sr.No</th>
                                    <th>Category</th> 
                                    <th>Item</th>
                                    <th>Qty</th> 
                                    <th>Unit Price Excl Tax</th>
                                    <th>Total Price Excl Tax</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>  
                                <tbody>
                                 <tr class="edit_field_group">
                                   <td class="sr_no">1</td>
                                   <td><input class="form-control category" type="text" name="item[0][category]" required></td>
                                   <td><input class="form-control item" type="text" name="item[0][item]" required></td>
                                   <td><input class="form-control qty" type="number" name="item[0][qty]" required></td>
                                   <td><input class="form-control unit_price_tax" type="number" name="item[0][unit_price_tax]" required></td>
                                   <td><input class="form-control total_unit_price_tax" type="hidden" name="item[0][total_unit_price_tax]"><span class="total_unit_price_tax"></span></td>
                                   <td class="placement-for-delete"></td>   
                                 </tr>
                               </tbody>
                               
                               <tfoot>
                                <tr>
                                 <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                                 <td><span id="add_total_price_tax"></span></td>
                               </tr> 
                               
                             </tfoot>
                           </table>
                           
                           <div class="form-group"> 
                            <input class="btn btn-primary btn btn-primary col-md-offset-4" type="submit"  value="Submit">
                            <input class="btn btn-primary btn btn-primary closeModal" type="reset"  value="Cancel" > 
                          </div>
                        </form>   
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
            <a data-toggle="modal" href="#update_item_details"></a>
            <div id="update_item_details" class="modal fade" aria-hidden="true" >
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="text-center">
                          <h3 class="m-t-none m-b">Copy To</h3>
                          <!--  <a id="add_row" class="btn btn-primary">Add</a>  -->
                        </div>
                        <form action="{{ url('/update-item-details') }}" method="POST">  
                         
                          {{ csrf_field() }}
                          <input type="hidden" name="eas_id" value="{{isset($eas_master->id)?$eas_master->id:''}}"/>      
                          <table class="table table-bordered">
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
                              @if(isset($item_details) && !empty($item_details)) 
                              <?php $count = 1;?>
                              @foreach($item_details as $key =>$val)
                              <tr class="field_groups">
                               <td class="sr_no">{{$count++}}</td>
                               <td><input class="form-control category" type="text" name="item[{{$key}}][category]" value="{{isset($val['category'])? $val['category']:''}}"></td>
                               <td><input class="form-control item" type="text" name="item[{{$key}}][item]" value="{{isset($val['item'])? $val['item']:''}}"></td>
                               <td><input class="form-control qty" type="number" name="item[{{$key}}][qty]" value="{{isset($val['qty'])? $val['qty']:''}}"></td>
                               <td><input class="form-control unit_price_tax" type="number" name="item[{{$key}}][unit_price_tax]" value="{{isset($val['unit_price_tax'])? $val['unit_price_tax']:''}}"></td>
                               <td><input class="form-control total_unit_price_tax" type="hidden" name="item[{{$key}}][total_unit_price_tax]"><span class="total_unit_price_tax"></span></td>
                             </tr>
                             @endforeach
                             @endif
                             
                           </tbody>
                         </tbody> 
                         <tfoot>
                          <tr>
                           <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                           <td><span id="edit_total_price_tax"></span></td>
                         </tr> 
                         
                       </tfoot>
                     </table>
                     
                     <div class="form-group"> 
                      <input class="btn btn-primary btn btn-primary col-md-offset-4" type="submit"  value="Update">
                      <input class="btn btn-primary btn btn-primary closeModal" type="reset"  value="Cancel" >
                      <!-- <input class="btn btn-primary btn btn-primary" type="reset"  value="Cancel"> -->
                    </div>
                  </form>   
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> 
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
              <input type="hidden" name="id" value="{{isset($eas_master->id) ? $eas_master->id:''}}"/>
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
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
@include('scripts.add_copy_to_js')
@include('scripts.edit_copy_to_js')
<script type="text/javascript">
  $("#update_copy_to").click(function () {

  var id = '{{isset($eas_master->id)?$eas_master->id:''}}';
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
    });
 


$(".delete_item_details").click(function () {
 var item_id = $(this).data('id');
   swal({
      title: 'Are you want to delete?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm!',
        showCancelButton: true,
    }).then((result) => {
      if (result.value) {
 
   var url = '{{url("/")}}/deleteItemDetails/'+item_id;
  if(item_id) {
   $.ajax({

            type: 'GET',
            url: url,
            data: {"_token": "{{ csrf_token() }}"},
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
                },
            dataType: 'json',
            success: function (data) {
            
              if(data.code == 200) {
              
              swal({
                        title: 'Updated!', 
                        text: data.message,
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
              } else {
            
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
  }
      })
    });   


 
  $(".select2").select2({
            width: '100%'
      })  
//       var incr = 1;
//     $(".add-more-copy_to").click(function(){
      
//         var $self = $('.after-add-more-div');
//        $('.select2').select2('destroy');
//        //$('.select2').find("span").remove();
//         var cloned_data = $('.clone-div').clone().removeClass("clone-div");
//         // var last = $('.form-filled:last');
//         cloned_data.find('.department_id').attr("name", "copy[" + incr + "][department_id]");
//         cloned_data.find('.user_id').attr("name", "copy[" + incr + "][user_id]");
//         cloned_data.find('.add-more-copy_to').hide();
//         cloned_data.find("span.select2 ").remove();
//         cloned_data.find('.remove-button').html('<a class="btn btn-danger">Remove</a>');
//         var append_div = $self.after(cloned_data);
//         $(".select2").select2({
//             width: '100%'
//         })   
//         incr++;
//     });
//     $(document).on('click', '.remove-button', function () {
//     $(this).parent().parent().remove();
// });

//     var incr = 1;
//     $(".add-more-department").click(function(){
//         var $self = $('.after-add-more-row');
//        $('.select2').select2('destroy');
//        //$('.select2').find("span").remove();
//         var cloned_data = $('.clone-div').clone().removeClass("clone-div");
//         // var last = $('.form-filled:last');
//         cloned_data.find('.department_id').attr("name", "copy[" + incr + "][department_id]");
//        // cloned_data.find('select.department_id').attr("data-id",incr);
//         cloned_data.find('.user_id').attr("name", "copy[" + incr + "][user_id]");
//         //cloned_data.find('select.user_id').attr("data-id",incr);
//         cloned_data.find('.add-button').hide();
//         cloned_data.find("span.select2 ").remove();
//         cloned_data.find('.remove-button').html('<a class="btn btn-danger">Remove</a>');
//         var append_div = $self.after(cloned_data);
//         $(".select2").select2({
//             width: '100%'
//         })   
        
//         incr++;
//     });
//     $(document).on('click', '.remove-button', function () {
//     $(this).parent().parent().remove();
// });


 

$(".is_vendor_present").change(function () {
    var checkebox = $('.is_vendor_present:checked').val(); 
    if (checkebox == 'yes') { 
    document.getElementById("is_vendor_present").style.display = 'block';
    } else {
    document.getElementById("is_vendor_present").style.display = 'none'; 
   }
            
});



$('.chosen-select').chosen({width: "100%"});

$(".fa_concurrence").change(function () {

$('input[type="checkbox"]').not(this).prop('checked', false);
    var checkebox = $('.fa_concurrence:checked').val(); 
    if (checkebox == 'fa_concurrence') { 
    document.getElementById("is_delegated_powers").style.display = 'block';
    } else {
    document.getElementById("is_delegated_powers").style.display = 'none'; 
      $("input[name='fa_dated']").val('');
    // $("input[name='fc_on_page']").val('');
    // $("input[name='fc_on_file_no']").val('');
     $("input[name='fa_number']").val('');
   }
   if(checkebox == undefined || checkebox !== 'fa_concurrence' ) {
    $("#delegate_powers").prop( "checked", true );
   } else {
    $("#fa_concurrence").prop( "checked", true );
   }
         
});

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
   $('#sanction_total').after('<span class="check_budget_head" style="color:#cc5965;">EAS Amount is more than the budget allotted. You can still create EAS but approval maybe delayed by authority.</span>');
   
    } else {
      $('.check_budget_head').text('');
        $('#sanction_total').after('<span class="check_budget_head" style="color:#cc5965;">EAS Amount is less than the budget allotted. You may proceed further.</span>');
    
      //$('#sanction_total').after('<span class="check_budget_head" style="color:#000;>EAS Amount is less than the budget allotted. You may proceed further.</span>');
    } 
  }else {
    alert('Budget Code or Value of Sanction Total Required.')
  }
  
});


$(document).bind('keyup blur', '.create_field_group input', function (e) {
    addNewMultiInputs();
  });
     function addNewMultiInputs() {
           var mult = 0;
           // for each row:
           $("tr.create_field_group").each(function () {
               // get the values from this row:
               var $qty = $('.qty', this).val();
               var $unit_price_tax = $('.unit_price_tax', this).val();
               var $total = ($qty * 1) * ($unit_price_tax * 1)
               $('input.total_unit_price_tax',this).val($total.toFixed(2));
               $('.total_unit_price_tax',this).text($total.toFixed(2));

               mult+= parseInt($total);
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

// $(document).bind('keyup blur', '.field_groups input', function (e) {
//     multInputs();
//   });
     
      function multInputs() {
           var mult = 0;
           $("tr.field_group").each(function () {
               // get the values from this row:
               var $qty = $('.qty', this).text();
           
               var $unit_price_tax = $('.unit_price_tax', this).text();
                
               var $total = ($qty * 1) * ($unit_price_tax * 1)
               
               $('input.total_unit_price_tax',this).val($total.toFixed(2));
               $('.total_unit_price_tax',this).text($total.toFixed(2));
          
               mult += $total;
            
           });
           $("#total_price_tax").text(mult.toFixed(2));
       }


//
$(document).bind('keyup blur', '.edit_field_group input', function (e) {
    addMultiInputs();
  });
     
      function addMultiInputs() {
           var mult = 0;
           // for each row:
           $("tr.edit_field_group").each(function () {
               // get the values from this row:
               var $qty = $('.qty', this).val();
              
               var $unit_price_tax = $('.unit_price_tax', this).val();
               
               var $total = ($qty * 1) * ($unit_price_tax * 1)
                
               $('input.total_unit_price_tax',this).val($total.toFixed(2));
               $('.total_unit_price_tax',this).text($total.toFixed(2));
          
               mult += $total;
            
           });
           $("#add_total_price_tax").text(mult.toFixed(2));
       }
  //});

  $("#add_new_row").on("click", function () {

           var $tableBody = $('#edit_table').find("tbody");
           var $trLast = $tableBody.find("tr:last");
           //var checklist_type = "<?php echo session('checklist_type'); ?>";
           if ($trLast.length > 0) {
               var $trNew = $trLast.clone();
               $trNew.find(':text').val('');
              // $trNew.find('span total_unit_price_tax:text').val('');
               $trLast.after($trNew);
               var last = $('.edit_field_group:last');
               var current = $(".edit_field_group").length - 1;

              last.find('.sr_no').text($(".edit_field_group").length);
             // last.find('.category').replaceWith("<input class='form-control' type='text' name='item[" + current + "][category]'>");
             // last.find('.item').replaceWith("<input class='form-control' type='text' name='item[" + current + "][item]'>");
             // last.find('.qty').replaceWith("<input class='form-control qty' type='number' name='item[" + current + "][qty]'>");
             // last.find('.unit_price_tax').replaceWith("<input class='form-control unit_price_tax' type='number' name='item[" + current + "][unit_price_tax]'>");
          
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
      addMultiInputs();
        addNewMultiInputs();
      multInputs();
      calEditTotal();
      
   });
 

$("#department_id").change(function () {
//function getUsersList(){
  var department_id = $(this).val();

  if(department_id) {
    $.ajax({

            type: 'GET',
            url: "<?php echo url('/') ?>/getDepartmentWiseUserList/"+ department_id,
            data: {department_id:department_id, "_token": "{{ csrf_token() }}"},
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
                },
            dataType: 'json',
            success: function (data) {
             $('#users_list').empty();
               if(data.code == 200) {
               //$('#users_list').empty();
               //$('select[name="users_list"]').empty();
                        $.each(data.users, function(key, value) {
                            $('#users_list').append('<option value="'+ value['id'] +'">'+ value['name'] +'</option>');
                        });

              } else {
              alert('Users details not found')
              }
              
           },
               complete: function(){
              $('.div-loader').css("display", "none");
            },
           error: function (data) {
               //$('#vendor_details').hide();
           }
       });

  } else {
    alert("Department Id not found.")
  }
//}            
});

// $('#copy_to').change(function(){

//     if(this.checked)
//         $('#copy_to_div').show();
//     else
//         $('#copy_to_div').hide();

// });

 // $("#show_copy_to").on("click", function () {
 //  $(".select2").select2({
 //            width: '100%'
 //        })  

 //   $('#add_modal_copy_to').modal('show');
 //   }); 

$("#edit_item_popup").on("click", function () {

   //$('#edit_item').hide();
   //$('#add_item').hide();
   $('#update_item_details').modal('show');

   calEditTotal();
   }); 

  function calEditTotal() {
   var mult = 0;
   // for each row:
   $("tr.field_groups").each(function () {
       // get the values from this row:
       var $qty = $('.qty', this).val();
       var $unit_price_tax = $('.unit_price_tax', this).val();
       var $total = ($qty * 1) * ($unit_price_tax * 1)
       $('input.total_unit_price_tax',this).val($total.toFixed(2));
       $('.total_unit_price_tax',this).text($total.toFixed(2));
  
       mult += $total;
    
   });
   $("#edit_total_price_tax").text(mult.toFixed(2));
  }

  $(document).bind('keyup blur', '.field_groups input', function (e) {
     calEditTotal();
  });

  $("#add_item_popup").on("click", function () {
   //$('#edit_item').hide();
  // $('#add_item').hide();
   $('#add_item_details').modal('show');
}); 

 
// $('#copy_to').click(function(){

//     if(this.checked)
//         $('#copy_to_div').show();
//     else
//         $('#copy_to_div').hide();

// });  
</script>
@endsection            
