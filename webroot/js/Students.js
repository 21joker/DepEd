$(function () {
    getStudents();

    $("#add").on("click", function () {
        $("#modal-title").html("Add Student");
        $("#students-modal").modal("show");
    });

    $("#students-table").on("click", ".message", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $("#student-id").val(id);
        $("#message-modal").modal("show");
    });

    $("#message-form").on("submit", function (e) {
        e.preventDefault();
        var fd = new FormData(this);
        var id = $("#student-id").val();
        $.ajax({
            processData: false,
            contentType: false,
            data: fd,
            url: "/usermngt/Students/sendMessage/" + id,
            type: "POST",
            dataType: "json",
        })
            .done(function (data, textStatus, jqXHR) {
                alert(data.status, "<br> ", data.message);
                $("#message-modal").modal("hide");
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert("error: ", errorThrown);
            });
    });

    $("#students-table").on("click", ".edit", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $("#modal-title").html("Update Student");
        $.ajax({
            url: "/usermngt/Students/edit/" + id,
            type: "GET",
            dataType: "json",
        })
            .done(function (data, textStatus, jqXHR) {
                if (data != "") {
                    $("#student-lastname").val(data.student.lastname);
                    $("#student-firstname").val(data.student.firstname);
                    $("#student-middlename").val(data.student.middlename);
                    $("#username").val(data.username);
                    $("#password").val("");
                    $("#role").val(data.role);
                    $("#id").val(data.id);
                    $("#students-modal").modal("show");
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert("error", errorThrown);
            });
    });

    $("#students-form").on("submit", function (e) {
        e.preventDefault();
        var fd = new FormData(this);
        var id = $("#id").val();
        var url;
        if (id != "") {
            url = "/usermngt/Students/edit/" + id;
        } else {
            url = "/usermngt/Students/add";
        }
        $.ajax({
            processData: false,
            contentType: false,
            data: fd,
            url: url,
            type: "POST",
            dataType: "json",
        })
            .done(function (data, textStatus, jqXHR) {
                alert(data.status, ": ", data.message);
                $("#students-modal").modal("hide");
                getStudents();
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert("error: ", errorThrown);
            });
    });

    $("#students-table").on("click", ".delete", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "/usermngt/Students/delete/" + id,
                type: "DELETE",
                dataType: "json",
                headers: {
                    "X-CSRF-Token": $('[name="_csrfToken"]').val(),
                },
            })
                .done(function (data, textStatus, jqXHR) {
                    getStudents();
                    alert(data.status, data.message);
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    alert("error", errorThrown);
                });
        }
    });
});

function getStudents() {
    $("#students-table").dataTable({
        responsive: true,
        autoWidth: false,
        destroy: true,
        order: [[0, "asc"]],
        ajax: {
            url: "/usermngt/Students/getStudents",
        },
        columns: [
            { data: "id" },
            { data: "lastname" },
            { data: "firstname" },
            { data: "middlename" },
            { data: "email" },
            {
                data: null,
                render: function (data) {
                    return data.user.username;
                },
            },
            {
                data: null,
                render: function (data) {
                    return data.user.role;
                },
            },
            {
                data: null,
                render: function (data) {
                    var option =
                        '<div style="text-align:center;">' +
                        '<a href="" class="message" data-toggle="tooltip" ' +
                        'data-placement="bottom" title="Edit User" data-id="' +
                        data.id +
                        '"><i ' +
                        'class="fa fas fa-envelope text-success"></i></a> | ' +
                        '<a href="" class="edit" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Edit User" data-id="' +
                        data.user.id +
                        '"><i' +
                        ' class="fa fas fa-pen"></i></a> | <a href="" class="delete text-danger" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Delete User" data-id="' +
                        data.user.id +
                        '"><i' +
                        ' class="fa fa fa-trash"></i></a></div>';
                    return option;
                },
            },
        ],
    });
}
