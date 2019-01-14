 <script type="text/javascript">
 $(".select2").select2({
            width: '100%'
        }) 


$('#copy_to').click(function(){

    if(this.checked)
        $('#copy_to_div').show();
    else
        $('#copy_to_div').hide();

});  

  var incr = 1;
    $(".add-more-department").click(function(){
        var $self = $('.after-add-more-row');
       $('.select2').select2('destroy');
       //$('.select2').find("span").remove();
        var cloned_data = $('.clone-div').clone().removeClass("clone-div");
        // var last = $('.form-filled:last');
        cloned_data.find('.department_id').attr("name", "copy[" + incr + "][department_id]");
       // cloned_data.find('select.department_id').attr("data-id",incr);
        cloned_data.find('.user_id').attr("name", "copy[" + incr + "][user_id]");
        //cloned_data.find('select.user_id').attr("data-id",incr);
        cloned_data.find('.add-button').hide();
        cloned_data.find("span.select2 ").remove();
        cloned_data.find('.remove-button').html('<a class="btn btn-danger">Remove</a>');
        var append_div = $self.after(cloned_data);
        $(".select2").select2({
            width: '100%'
        })   
        
        incr++;
    });
    $(document).on('click', '.remove-button', function () {
    $(this).parent().parent().remove();
});
</script>