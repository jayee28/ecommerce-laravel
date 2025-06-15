$(document).ready(function () {
    //Check admin password is correct or not
    $('#current_password').keyup(function () {
        var current_pwd = $('#current_password').val();
        // alert(current_pwd);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/check-current-password',
            data: { current_password: current_pwd },
            success: function (response) {
                if (response == "false") {
                    $('#verifyCurrentPwd').html("Current password is incorrect");
                } else if (response == "true") {
                    $('#verifyCurrentPwd').html("Current password is correct");
                }
            }, error: function () {
                alert("Error");
            }
        });
    });

    //Update CMS Page status
    $(document).on('click', '.updateCmsPageStatus', function () {
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
            success: function (response) {
                var element = $('#page-' + response.page_id);
                if (response.status == 0) {
                    element.html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                } else {
                    element.html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                }
            },
            error: function () {
                alert("Error updating status");
            }
        });
    });

    //Confirm Delete CMS Page
    // $(document).on("click",'.confirmDelete',function(){
    //     Swal.fire({
    //         title: 'Error!',
    //         text: 'Do you want to continue',
    //         icon: 'error',
    //         confirmButtonText: 'Cool'
    //     })
    //     return false;
    //     // alert("testing");
    //     // return false;
    //     var name = $(this).attr('name');
    //     if(confirm('Are you sure to delete this '+name+ '?')){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // });

    //Confirm Deletion CMS Page using sweet alert2
    $(document).on("click", '.confirmDelete', function () {
        var record = $(this).attr('record');
        var recordId = $(this).attr('recordid');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
                window.location.href = '/admin/delete-'+record+'/'+recordId;
            }
        });
    });

    //Update sub admin status
    $(document).on('click', '.updateSubadminStatus', function () {
        var icon = $(this).children("i");
        var currentStatus = icon.attr('data-status');
        var subadminId = $(this).attr('data-subadmin-id');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/admin/update-subadmin-status',
            data: {
                status: currentStatus,
                subadmin_id: subadminId
            },
            success: function (response) {
                var element = $('#subadmin-' + response.subadmin_id);
                if (response.status == 0) {
                    element.html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                } else {
                    element.html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                }
            },
            error: function () {
                alert("Error updating status");
            }
        });
    });

});