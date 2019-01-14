@extends('layouts.app')
@section('title', 'Add Purchase Order')
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
            <h3 class="col-md-10"><strong> Add Purchase Order</strong></h3>
            <div class="ibox-tools col-md-2">
                <a href="{{ url('purchase-order') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
          </div>
          
          <div class="ibox-title col-md-12 display-flex">
              <ul class="breadcrumb">
               @if(Session::get('eas_id'))
                @section('breadcrumbs')
                 <li><a class="breadcrumb-item" href="{{ url('/eas/'. Session::get('eas_id')) }}">{{ Session::get('eas_title') }}</a></li> 
                 @show
                 @endif 
                 <li><a class="breadcrumb-item" href="{{ url('purchase-order') }}">Purchase Order</a></li>
                 <li><a class="breadcrumb-item">Add Purchase Order</a></li>
               
              </ul>
          </div>
          
           <!--   <?php //dd($workflow_mapping); ?> -->
           
           <div class="ibox-content" style="margin-bottom: 50px;">
            <form method="POST" action="{{ url('purchase-order') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="entity_id" value="{{isset($entity_details->id)?$entity_details->id:''}}"/>
                <input type="hidden" name="workflow_id" value="{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}"/>
                <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}"/>

                @include ('purchase_order.form', ['formMode' => 'create'])
            </form>
        </div>
    </div>
</div>
</div>

@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
@include('scripts.add_copy_to_js')
<script>
 //$(document).ready(function () {

  // $(document).bind('keyup blur', '.field_group input', function (e) {
  //   multInputs();
  // });
     
      function multInputs() {
           var mult = 0;
           // for each row:
           $("tr.field_group").each(function () {
               // get the values from this row:
               var $qty = $('.qty', this).val();
               var $unit_price_tax = $('.unit_price_tax', this).val();
               var $total = ($qty * 1) * ($unit_price_tax * 1)
               $('input.total_unit_price_tax',this).val($total.toFixed(2));
               $('.total_unit_price_tax',this).text($total.toFixed(2));

               mult += $total;
            
           });
            $('#invoice').append('<tr><td colspan="5" style="text-align: right;"><strong>Total</strong></td><td class="total-combat">'+(mult.toFixed(2))+'</td></tr>');
           //$("#total_price_tax").text(mult.toFixed(2));
       }
  //});

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

      
        var eas_id =  "{{ Session::get('eas_id') }}";
     
        eas();
        //alert(eas_id);
        $('.date_added').datepicker({
            format: 'dd/mm/yyyy',
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
         //  endDate: "today"
        });
        $('#date_of_bid').datepicker({
            format: 'dd/mm/yyyy',
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            endDate: "today"

        });
        
    
        $('.chosen-select').chosen({width: "100%"});

        

           
    function eas() 
    {
        var eas_id = document.getElementById("eas").value;

        $.ajax({

            type: 'POST',
            url: "<?php echo url('/') ?>/vendor_details/"+ eas_id,
            data: {id:eas_id, "_token": "{{ csrf_token() }}"},
            // beforeSend: function(){
            //     swal.close()
            //     $('.div-loader').css("display", "block");
            //     },
            dataType: 'json',
            success: function (data) {
               var vendor_address =  data.address;
               $("#vendor_address").val(vendor_address);

               var vendor_name =  data.vendor_name;
               $("#vendor_name").val(vendor_name);
               $("#vendor_email").val(data.vendor_email);
           },
            //    complete: function(){
            //   $('.div-loader').css("display", "none");
            // },
           error: function (data) {
               // alert(data);
           }
       });
    }
   
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

                  //multInputs();
               
                 //    var sum = 0
                 //    $('tr').find('.combat').each(function () {

                 //      var combat = $(this).text();

                 //      if (!isNaN(combat) && combat.length !== 0) {
                       
                 //        sum += parseFloat(combat);
                 //      }
                 //    });  
                 //    $('.total-combat', this).html(sum);
                 
                 // $('#invoice').append('<tr><td colspan="5" style="text-align: right;"><strong>Total</strong></td><td class="total-combat">'+sum+'</td></tr>');

              } else {
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


    $('#copy_to').change(function(){
        if(this.checked)
            $('#mail-users').fadeIn('slow');
        else
            $('#mail-users').fadeOut('slow');

    });

</script>     
@endsection 
