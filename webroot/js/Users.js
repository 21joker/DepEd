$(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content') || $('[name="_csrfToken"]').val();
    var isEnroll = $('#users-table').data('enroll') === 1 || new URLSearchParams(window.location.search).get('enroll') === '1';
    var resetMode = false;
    getUsers();

    $('#add').on('click',function () {
        $('#modal-title').html(isEnroll ? 'Create User Account' : 'Add User');
        if (!isEnroll) {
            $('#manage-password-fields').show();
            resetMode = false;
            $('#users-form')
                .children('label, input, select, textarea, .form-group')
                .show();
            $('#password').val('');
            $('#old-password').val('');
            $('#new-password').val('');
        }
        $('#users-modal').modal('show');
    });

    $('#users-table').on('click','.edit',function (e) {
        e.preventDefault();
        if (isEnroll) {
            return;
        }
        var id = $(this).data('id');
        $('#modal-title').html('Update User');
        $.ajax({
            url: '/usermngt/Users/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data, textStatus, jqXHR){
                if(data!=''){
                    $('#username').val(data.username);
                    $('#original-username').val(data.username);
                    $('#password').val('');
                    $('#retype-password').val('');
                    $('#first-name').val(data.first_name || '');
                    $('#middle-initial').val(data.middle_initial || '');
                    $('#last-name').val(data.last_name || '');
                    $('#email-address').val(data.email_address || '');
                    $('#level-of-governance').val(data.level_of_governance || '');
                    $('#role').val(data.role);
                    $('#id').val(data.id);
                    $('#manage-password-fields').hide();
                    resetMode = false;
                    $('#manage-password-fields').prevAll('label, input, select, textarea').show();
                    $('#manage-password-fields').nextAll('label, input, select, textarea').show();
                    $('#users-form')
                        .children('label, input, select, textarea, .form-group')
                        .show();
                    $('#password').val('');
                    $('#old-password').val('');
                    $('#new-password').val('');
                    $('#users-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                alert(errorThrown || 'error');
            });
    });

    $('#users-table').on('click', '.reset-password', function (e) {
        e.preventDefault();
        if (isEnroll) {
            return;
        }
        var id = $(this).data('id');
        $('#modal-title').html('Reset Password');
        $.ajax({
            url: '/usermngt/Users/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data, textStatus, jqXHR){
                if(data!=''){
                    $('#username').val(data.username);
                    $('#original-username').val(data.username);
                    $('#password').val('');
                    $('#retype-password').val('');
                    $('#first-name').val(data.first_name || '');
                    $('#middle-initial').val(data.middle_initial || '');
                    $('#last-name').val(data.last_name || '');
                    $('#email-address').val(data.email_address || '');
                    $('#level-of-governance').val(data.level_of_governance || '');
                    $('#role').val(data.role);
                    $('#id').val(data.id);
                    $('#manage-password-fields').show();
                    resetMode = true;
                    $('#users-form')
                        .find('label, input, select, textarea, .form-group')
                        .not('#manage-password-fields, #manage-password-fields *')
                        .hide();
                    $('#users-modal').modal('show');
                    $('#old-password').focus();
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                alert(errorThrown || 'error');
            });
    });

    $('#users-form').on('submit',function (e) {
        e.preventDefault();
        if (isEnroll) {
            var password = $('#password').val();
            var retype = $('#retype-password').val();
            if (password !== retype) {
                alert('Password does not match.');
                return;
            }
            var username = $('#username-display').val();
            var email = $('#email-address').val();
            if (username) {
                $('#username').val(username);
            } else if (email) {
                $('#username').val(email);
            }
            var role = $('#role-display').val();
            if (role) {
                $('#role').val(role);
            } else {
                $('#role').val('User');
            }
        }
        if (!isEnroll) {
            var usernameValue = $('#username').val();
            if (!usernameValue) {
                var originalUsername = $('#original-username').val();
                if (originalUsername) {
                    $('#username').val(originalUsername);
                }
            }
            if (resetMode) {
                var oldPassword = $('#old-password').val();
                var newPassword = $('#new-password').val();
                if (!oldPassword || !newPassword) {
                    alert('Old and New password are required.');
                    return;
                }
                $('#password').val(newPassword);
            } else {
                $('#password').val('');
            }
        }
        var fd = new FormData(this);
        var id = $('#id').val();
        var url;
        if(id != ''){
            url = '/usermngt/Users/edit/'+ id;
        }else{
            url = '/usermngt/Users/add';
        }
        $.ajax({
            processData: false,
            contentType:false,
            data: fd,
            url: url,
            type: "POST",
            dataType: 'json'
        })
        .done(function(data, textStatus, jqXHR){
            if(data.status=='success'){
                $('#users-modal').modal('hide');
                getUsers();
            }
            if (data.status === 'error' && data.errors) {
                alert(data.message + "\n" + JSON.stringify(data.errors));
            } else {
                alert(data.message);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            alert(errorThrown || 'error');
        });
    });

    $('#users-table').on('click', '.delete', function (e) {
        e.preventDefault();
        if (isEnroll) {
            return;
        }
        var id=$(this).data('id');
        if(confirm('Are you sure you want to delete this record?')){
            $.ajax({
                url: '/usermngt/Users/delete/'+ id,
                type: "DELETE",
                dataType: 'json',
                headers : csrfToken ? { 'X-CSRF-Token': csrfToken } : {},
            })
                .done(function(data, textStatus, jqXHR){
                    getUsers();
                    alert(data.message || data.status || 'done');
                })
                .fail(function(jqXHR, textStatus, errorThrown){
                    alert(errorThrown || 'error');
                });

        }
    });
});

function getUsers() {
    var isEnroll = $('#users-table').data('enroll') === 1 || new URLSearchParams(window.location.search).get('enroll') === '1';
    var columns;
    if (isEnroll) {
        columns = [
            {data: "id"},
            {data: "username"},
            {data: "role"},
            {data: "created"},
            {data: "modified"},
        ];
    } else {
        columns = [
            {data: "id"},
            {
                data: null,
                render: function (data) {
                    var parts = [];
                    if (data.first_name) { parts.push(data.first_name); }
                    if (data.middle_initial) { parts.push(data.middle_initial); }
                    if (data.last_name) { parts.push(data.last_name); }
                    return parts.join(' ') || data.username || '';
                }
            },
            {data: "email_address"},
            {data: "level_of_governance"},
            {
                data: null,
                render: function (data) {
                    return '<button class="btn btn-sm btn-primary edit" data-id="' + data.id + '">Edit Account</button>' +
                        ' <button class="btn btn-sm btn-warning reset-password" data-id="' + data.id + '">Reset Password</button>' +
                        ' <button class="btn btn-sm btn-danger delete" data-id="' + data.id + '">Delete</button>';
                }
            }
        ];
    }

    var table = $("#users-table").dataTable({
        "responsive": true,
        "autoWidth": false,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": '/usermngt/Users/getUsers'
        },
        "columns": columns,
        "drawCallback": function () {
            if (isEnroll) {
                $('#users-table th').filter(function () {
                    return $(this).text().trim().toLowerCase() === 'options';
                }).remove();
                $('#users-table td').has('.edit, .delete').remove();
            }
        }
    });
}
