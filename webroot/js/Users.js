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
            $('#reset-mode').val('0');
            $('#users-form')
                .children('label, input, select, textarea, .form-group')
                .show();
            $('#password').val('');
            $('#reset-email').val('');
        } else {
            $('#users-form')[0].reset();
            $('#username-display-enroll').val('');
            $('#first-name-enroll').val('');
            $('#middle-initial-enroll').val('');
            $('#last-name-enroll').val('');
            $('#suffix-enroll').val('');
            $('#degree-enroll').val('');
            $('#rank-enroll').val('');
            $('#position-enroll').val('');
            $('#email-address-enroll').val('');
            $('#level-of-governance-enroll').val('');
            $('#role-display-enroll').val('User');
            $('#password-enroll').val('');
            $('#retype-password-enroll').val('');
            $('#username').val('');
            $('#role').val('');
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
            url: '/usermngt/Users/edit/' + id + '?_=' + Date.now(),
            type: "GET",
            dataType: 'json'
        })
            .done(function(data, textStatus, jqXHR){
                if(data!=''){
                    $('#username-manage').val(data.username);
                    $('#original-username').val(data.username);
                    $('#password').val('');
                    $('#retype-password').val('');
                    $('#first-name-manage').val(data.first_name || '');
                    $('#middle-initial-manage').val(data.middle_initial || '');
                    $('#last-name-manage').val(data.last_name || '');
                    $('#suffix-manage').val(data.suffix || '');
                    $('#degree-manage').val(data.degree || '');
                    $('#rank-manage').val(data.rank || '');
                    $('#position-manage').val(data.position || '');
                    $('#email-address-manage').val(data.email_address || '');
                    $('#reset-email, [name="reset_email"]').val(data.email_address || '');
                    $('#level-of-governance-manage').val(data.level_of_governance || '');
                    $('#role-manage').val(data.role);
                    $('#id').val(data.id);
                    $('#manage-password-fields').hide();
                    resetMode = false;
                    $('#reset-mode').val('0');
                    $('#manage-password-fields').prevAll('label, input, select, textarea').show();
                    $('#manage-password-fields').nextAll('label, input, select, textarea').show();
                    $('#users-form')
                        .children('label, input, select, textarea, .form-group')
                        .show();
                    $('#password').val('');
                    $('#reset-email').val('');
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
            url: '/usermngt/Users/edit/' + id + '?_=' + Date.now(),
            type: "GET",
            dataType: 'json'
        })
            .done(function(data, textStatus, jqXHR){
                if(data!=''){
                    $('#username-manage').val(data.username);
                    $('#original-username').val(data.username);
                    $('#password').val('');
                    $('#retype-password').val('');
                    $('#first-name-manage').val(data.first_name || '');
                    $('#middle-initial-manage').val(data.middle_initial || '');
                    $('#last-name-manage').val(data.last_name || '');
                    $('#suffix-manage').val(data.suffix || '');
                    $('#degree-manage').val(data.degree || '');
                    $('#rank-manage').val(data.rank || '');
                    $('#position-manage').val(data.position || '');
                    $('#email-address-manage').val(data.email_address || '');
                    $('#reset-email, [name="reset_email"]').val(data.email_address || '');
                    $('#level-of-governance-manage').val(data.level_of_governance || '');
                    $('#role-manage').val(data.role);
                    $('#id').val(data.id);
                    $('#manage-password-fields').show();
                    resetMode = true;
                    $('#reset-mode').val('1');
                    $('#users-form')
                        .find('label, input, select, textarea, .form-group')
                        .not('#manage-password-fields, #manage-password-fields *')
                        .hide();
                    var resetEmailValue = data.email_address || data.email || '';
                    $('#users-modal').modal('show');
                    $('#reset-email, [name="reset_email"]').val(resetEmailValue);
                    setTimeout(function () {
                        $('#reset-email, [name="reset_email"]').val(resetEmailValue);
                    }, 0);
                    $('#reset-email').focus();
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                alert(errorThrown || 'error');
            });
    });

    $('#users-table').on('click', '.view-user', function (e) {
        e.preventDefault();
        if (isEnroll) {
            return;
        }
        var id = $(this).data('id');
        $.ajax({
            url: '/usermngt/Users/edit/' + id + '?_=' + Date.now(),
            type: "GET",
            dataType: 'json'
        })
            .done(function(data, textStatus, jqXHR){
                if (!data) {
                    return;
                }
                var fullName = buildDisplayName(data);

                $('#view-username').text(data.username || '—');
                $('#view-fullname').text(fullName || data.username || '—');
                $('#view-suffix').text(data.suffix || '—');
                $('#view-degree').text(data.degree || '�');
                if ($('#view-rank').length) {
                    $('#view-rank').text(data.rank || '�');
                }
                $('#view-position').text(data.position || '—');
                $('#view-email').text(data.email_address || '—');
                $('#view-level').text(data.level_of_governance || '—');
                $('#view-role').text(data.role || '—');
                $('#view-created').text(data.created || '—');
                $('#view-modified').text(data.modified || '—');
                $('#users-view-modal').modal('show');
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                alert(errorThrown || 'error');
            });
    });

    $('#users-form').on('submit',function (e) {
        e.preventDefault();
        if (isEnroll) {
            var password = $('#password-enroll').val();
            var retype = $('#retype-password-enroll').val();
            if (password !== retype) {
                alert('Password does not match.');
                return;
            }
            var username = $('#username-display-enroll').val();
            var email = $('#email-address-enroll').val();
            if (username) {
                $('#username').val(username);
            } else if (email) {
                $('#username').val(email);
            }
            var role = $('#role-display-enroll').val();
            if (role) {
                $('#role').val(role);
            } else {
                $('#role').val('User');
            }
        }
        if (!isEnroll) {
            var usernameValue = $('#username-manage').val();
            if (!usernameValue) {
                var originalUsername = $('#original-username').val();
                if (originalUsername) {
                    $('#username-manage').val(originalUsername);
                }
            }
            if (resetMode) {
                var resetEmail = $('#reset-email').val();
                if (!resetEmail) {
                    alert('Email address is required.');
                    return;
                }
                $('#password').val('');
            } else {
                $('#password').val('');
            }
        }
        var fd = new FormData(this);
        var fieldMap = isEnroll ? {
            first_name: '#first-name-enroll',
            middle_initial: '#middle-initial-enroll',
            last_name: '#last-name-enroll',
            suffix: '#suffix-enroll',
            degree: '#degree-enroll',
            rank: '#rank-enroll',
            position: '#position-enroll',
            email_address: '#email-address-enroll',
            level_of_governance: '#level-of-governance-enroll',
            role: '#role',
            username: '#username'
        } : {
            first_name: '#first-name-manage',
            middle_initial: '#middle-initial-manage',
            last_name: '#last-name-manage',
            suffix: '#suffix-manage',
            degree: '#degree-manage',
            rank: '#rank-manage',
            position: '#position-manage',
            email_address: '#email-address-manage',
            level_of_governance: '#level-of-governance-manage',
            role: '#role-manage',
            username: '#username-manage'
        };
        Object.keys(fieldMap).forEach(function (name) {
            var el = document.querySelector(fieldMap[name]);
            if (el) {
                fd.set(name, el.value || '');
            }
        });
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
                if (isEnroll) {
                    $('#users-form')[0].reset();
                    $('#username-display-enroll').val('');
                    $('#first-name-enroll').val('');
                    $('#middle-initial-enroll').val('');
                    $('#last-name-enroll').val('');
                    $('#suffix-enroll').val('');
                    $('#degree-enroll').val('');
                    $('#rank-enroll').val('');
                    $('#position-enroll').val('');
                    $('#email-address-enroll').val('');
                    $('#level-of-governance-enroll').val('');
                    $('#role-display-enroll').val('User');
                    $('#password-enroll').val('');
                    $('#retype-password-enroll').val('');
                    $('#username').val('');
                    $('#role').val('');
                }
                getUsers();
            }
            if (data && data.status === 'error' && data.errors) {
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

    $('#users-modal').on('hidden.bs.modal', function () {
        if (!isEnroll) {
            return;
        }
        var form = $('#users-form')[0];
        if (form) {
            form.reset();
        }
        $('#username-display-enroll').val('');
        $('#first-name-enroll').val('');
        $('#middle-initial-enroll').val('');
        $('#last-name-enroll').val('');
        $('#suffix-enroll').val('');
        $('#degree-enroll').val('');
        $('#rank-enroll').val('');
        $('#position-enroll').val('');
        $('#email-address-enroll').val('');
        $('#level-of-governance-enroll').val('');
        $('#role-display-enroll').val('User');
        $('#password-enroll').val('');
        $('#retype-password-enroll').val('');
        $('#username').val('');
        $('#role').val('');
        $('#id').val('');
        $('#original-username').val('');
    });
});

function buildDisplayName(data) {
    var parts = [];
    if (data.first_name) { parts.push(data.first_name); }
    if (data.middle_initial) { parts.push(data.middle_initial); }
    if (data.last_name) { parts.push(data.last_name); }
    if (data.suffix) { parts.push(data.suffix); }
    if (data.degree) { parts.push(data.degree); }
    if (data.rank) { parts.push(data.rank); }
    return parts.join(' ').trim();
}
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
                    return buildDisplayName(data) || data.username || '';
                }
            },
            {data: "email_address"},
            {data: "level_of_governance"},
            {
                data: null,
                render: function (data) {
                    return '<button class="btn btn-sm btn-info view-user" data-id="' + data.id + '">View</button>' +
                        ' <button class="btn btn-sm btn-primary edit" data-id="' + data.id + '">Edit Account</button>' +
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



