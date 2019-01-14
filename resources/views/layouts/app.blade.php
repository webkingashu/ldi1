<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UIDAI - @yield('title') </title>
  <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">
  <link href="{!! asset('font-awesome/css/font-awesome.css') !!}" rel="stylesheet">
  <link href="{!! asset('css/animate.css') !!}" rel="stylesheet">
  <link href="{!! asset('css/style.css') !!}" rel="stylesheet">
  <link rel="stylesheet" href="{!! asset('css/vendor.css') !!}" />
  <link rel="stylesheet" href="{!! asset('css/app.css') !!}" />
  <link rel="stylesheet" href="{!! asset('css/main.css') !!}" />
  <link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
  <link rel="stylesheet" href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" />
  @yield('css')
</head>
<body>
  <!-- Wrapper-->
  <div id="wrapper">
   <!-- Navigation -->
   @include('layouts.navigation')
   <!-- Page wraper -->
   <div id="page-wrapper" class="gray-bg">
    <!-- Page wrapper -->
    @include('layouts.topnavbar')
    <!-- Main view  -->
    @yield('content')

    <div class="div-loader">
     <div class="loader"></div>
   </div>
   @include('toast::messages-jquery')
   <!-- Footer -->
   @include('layouts.footer')
 </div>
 <!-- End page wrapper-->
</div>
<!-- End wrapper-->
<script src="{!! asset('js/jquery-3.1.1.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/plugins/metisMenu/jquery.metisMenu.js') !!}"></script>
<script src="{!! asset('js/plugins/slimscroll/jquery.slimscroll.min.js') !!}"></script>
<script src="{!! asset('js/inspinia.js') !!}"></script>
<script src="{!! asset('js/plugins/pace/pace.min.js') !!}"></script>
<script src="{!! asset('js/app.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/jquery.validate.min.js') !!}"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script type="text/javascript">
  $('.form').validate({
   rules: {
     'documents_type[]': {
       required :{
        depends: function(element) {
          var file_uploads = $('#file_uploads').prop('files')[0];

          if( file_uploads != '' &&  file_uploads != undefined){

           return true;
         } else {

           return false;
         }
       }
     }
   },
 },

});
  var incr = 1;
  $(".add-more-document").click(function(){
        //var html = $(".copy").html();
        incr ++;

        var html = '<div class="input-group control-group-'+incr+'" style="margin-top:20px;" ><input type="text" placeholder="Document type" class="form-control" rows="5" name="documents_type[]" id="document_type-'+incr+'" /></div>';

        $('.after-add-more-document').after(html);
        $(".doc-demo").select2();

        var html2 = '<div class="fileinput fileinput-new input-group control-group-'+incr+'" after-add-doc" data-provides="fileinput"><div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i><span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="file_upload[]" id="documents-'+incr+'" onchange="show(this)" /></span><div class="input-group-btn"> <button class="btn btn-danger remove" data-doc_id="'+incr+'" type="button" style="height: 36px;"><i class="glyphicon glyphicon-plus"></i> Remove</button> </div><a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a></div>';
        $(".after-add-doc").after(html2);
      });


  $("body").on("click",".remove",function(){
    $(this).parents(".control-group").remove();

    var doc_id = $(this).data('doc_id');
        //alert(vtag);
        //$(this).closest(".control-group2").remove();
        $(".control-group-"+doc_id).remove();
        //incr --;

      });
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(600, function(){
      $(this).remove(); 
    });
  }, 4000);
  $('.textonly').bind('keyup blur',function(){
    var node = $(this);
              node.val(node.val().replace(/[^A-Za-z_\s-/]/,'') ); }   // (/[^a-z]/g,''
              );
              // allow only  Number 0 to 9
              $('.numberonly').bind('keyup blur',function(){
                var node = $(this);
                  node.val(node.val().replace(/[^0-9_\s-/]/,'') ); }   // (/[^a-z]/g,''
                  );
              $('.textnumberonly').bind('keyup blur',function(){
                var node = $(this);
              node.val(node.val().replace(/[^A-Za-z_\s-/0-9]/,'') ); }   // (/[^a-z]/g,''
              );

              function removefile(id,type=null) {

               swal({
                title: "Are you sure want to delete ?",
                text: "Once status changed,will not retain again!!",
                type: "warning",
               // showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm!',
                showCancelButton: true,
                 }).then(function(result) {
                if(result.value) {
                  if(id) {
                 $.ajax({
                  url: "{{ url('/') }}/removefile",
                  dataType: "json",
                  type:"POST",
                  data: {
                    id : id,type:type,
                    _token: "{{csrf_token()}}"
                  },

                  success: function(data) {
                    if(data.code == 200){

                     swal({
                      title: "Status",
                      text: data.message,
                      type: "success",
                      showConfirmButton: false,
                      timer: 1500
                    });
                     location.reload();

                   } else {

                    swal({
                      title: 'Status', 
                      text: data.message,
                      type: 'error',
                      showConfirmButton: false,
                      timer: 1500
                    });
                    location.reload();

                         //window.location.href = "{{ url('/eas') }}/"+id;

                       } 
                     }

                   });
               }
                }
              });
            } 
        
             // },
             //  function () {
               
             // // });  


             // }
             function show(input) {

                var validExtensions = ['jpg','png','jpeg','pdf','xls','xlsx','doc','docx']; //array of valid extensions
                var fileName = input.files[0].name;
                var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
                if ($.inArray(fileNameExt, validExtensions) == -1) {
                  input.type = ''
                  input.type = 'file'
                  $('#user_img').attr('src',"");
                    //alert("Only these file types are accepted : "+validExtensions.join(', '));
                    $('.errorshow').show();
                    $('.errorshow').text('Only these file types are accepted: jpeg,png,jpg,pdf,xls,xlsx,doc,docx.');
                  }
                  else
                  {
                    if (input.files && input.files[0]) {
                      var filerdr = new FileReader();
                      filerdr.onload = function (e) {
                        $('#user_img').attr('src', e.target.result);
                      }
                      $('.errorshow').hide();
                      filerdr.readAsDataURL(input.files[0]);
                    }
                  }
                }
                var elements = document.getElementsByClassName("decimal"); 
                if(elements){
                  for (var i=0; i<elements.length; i++){
                    elements[i].addEventListener("keyup", decimal);
                  }
                }
                function decimal(e) {
                  var val = this.value;
                  var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
                  var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
                  if (re.test(val)) {
            //do something here

          } else {
            val = re1.exec(val);
            if (val) {
              this.value = val[0];
            } else {
              this.value = "";
            }
          }
        }

        

        function ro_details()
        {
          var eas_id =  "{{ Session::get('eas_id') }}";
              // $('#eas').change(function() {
                //var eas_id = $(this).val();
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

                   } else {
                  // / alert('Vendor details not found')
                }
              },
              complete: function(){
                $('.div-loader').css("display", "none");
              }
            });

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
                 alert('Eas not found.')
               }

//});
}
  function backToPrev()
  {
    var form_reset =  document.referrer;
    //console.log(form_reset);
    window.location.href = form_reset;
  }
   $(".closeModal").on("click", function () {
   $('.modal').modal('hide');
  //}
}); 


</script>
@section('scripts')
@show
</body>
</html>