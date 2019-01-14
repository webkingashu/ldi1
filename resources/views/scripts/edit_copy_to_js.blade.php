<script type="text/javascript">
   $(".select2").select2({
            width: '100%'
        }) 



$(".delete_copy_to").click(function () {
 var copy_to_id = $(this).data('id');
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
 
   var url = '{{url("/")}}/deleteCopyTo/'+copy_to_id;
  if(copy_to_id) {
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
//   }
// });

 $(".edit_copy_to").on("click", function () {

    $(".select2").select2({
            width: '100%'
        })  

    var copy_to_id = $(this).data('id');
   
    $('#copy_to_id').val(copy_to_id);
     $('#modal_copy_to').modal('show');
 });  

  $("#show_copy_to").on("click", function () {
  $(".select2").select2({
            width: '100%'
        })  

   $('#add_modal_copy_to').modal('show');
   }); 
</script>