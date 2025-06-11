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

    //Update CMS Page status
    $(document).on('click', '.updateCmsPageStatus', function() {
    var icon = $(this).children("i");
    var currentStatus = icon.attr('data-status');
    var pageId = $(this).attr('data-page-id');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '/admin/update-cms-page-status',
        data: {
            status: currentStatus,
            page_id: pageId
        },
        success: function(response) {
            var element = $('#page-' + response.page_id);
            if(response.status == 0) {
                element.html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
            } else {
                element.html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
            }
        },
        error: function() {
            alert("Error updating status");
        }
    });
});

});