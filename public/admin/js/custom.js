$(document).ready(function(){
    //Check admin password is correct or not
    $('#current_password').keyup(function(){
        var current_pwd = $('#current_password').val();
        // alert(current_pwd);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url:'/admin/check-current-password',
            data:{current_password:current_pwd},
            success:function(response){
                if(response=="false"){
                    $('#verifyCurrentPwd').html("Current password is incorrect");
                }else if(response=="true"){
                    $('#verifyCurrentPwd').html("Current password is correct");
                }
            },error:function(){
                alert("Error");
            }
        });
    });
});