
  swal({
            title: "Are you sure want to change status?",
            text: "Once status changed,will not retain again!!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!',
            showCancelButton: true,
        }).then(function(result) {
          if(result.value) {
            assignee();
          }
        });

    function assignee(){
       swal({
      title: 'Are want to assigne?',
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
      console.log('comment')
      swal({
                 title: "Please Enter Comment!",
                  text: "Why this PO Rejected ?",
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

        if(created_by && transaction_id && entity_id && workflow_id && url && vendor_name ) {

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
                    entity_id :entity_id,comment:inputValue,
                    workflow_id:workflow_id,assignee:assignee,old_assignee:old_assignee,
                    created_by:created_by,vendor_name:vendor_name,
                    entity_slug:entity_slug,eas_id:eas_id,sanction_title:sanction_title,
                    id:id,privious_status:privious_status,subject:subject,
                  status_name :status_name,final_status:final_status,vendor_email:vendor_email,
                    _token: "{{csrf_token()}}"
                },
                 
                success: function(data) {
                  if(data.code == 204){
                    swal({
                        title: 'Status', 
                        text: data.message,
                        type: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                  } else {
                   
                    if(data.status_id == final_status) {
                      swal({
                            title: "Approved",
                            text: data.message,
                            type: "success",
                            showConfirmButton: false,
                           timer: 1000
                        });
                       
                           window.location.href = "{{ url('/purchase-order') }}/"+id;
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
    
</script>     
@endsection 