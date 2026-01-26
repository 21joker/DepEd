$(function () {
    getGrades();

    $("#add").on("click", function () {
        $("#modal-title").html("Add Student");
        $("#grades-modal").modal("show");
    });

    $("#grades-table").on("click", ".edit", function (e) {
        e.preventDefault();
        const id = $(this).data("id");

        $("#modal-title").html("Update Grade");

        $.getJSON("/usermngt/grades/edit/" + id, function (data) {
            $("#student-id").val(data.student_id);
            $("#english").val(data.english);
            $("#science").val(data.science);
            $("#math").val(data.math);
            $("#filipino").val(data.filipino);
            $("#mapeh").val(data.mapeh);
            $("#id").val(data.id);
            $("#grades-modal").modal("show");
        });
    });

    $("#grades-form").on("submit", function (e) {
        e.preventDefault();
        var fd = new FormData(this);
        var id = $("#id").val();
        var url;
        if (id != "") {
            url = "/usermngt/Grades/edit/" + id;
        } else {
            url = "/usermngt/Grades/add";
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
                $("#grades-modal").modal("hide");
                getGrades();
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert("error: ", errorThrown);
            });
    });

    $("#grades-table").on("click", ".delete", function (e) {
        e.preventDefault();
        const id = $(this).data("id");

        if (confirm("Delete this record?")) {
            $.ajax({
                url: "/usermngt/grades/delete/" + id,
                type: "DELETE",
                dataType: "json",
                headers: {
                    "X-CSRF-Token": $('[name="_csrfToken"]').val(),
                },
            }).done(function (res) {
                alert(res.message);
                getGrades();
            });
        }
    });
});

function getGrades() {
    $("#grades-table").DataTable({
        responsive: true,
        autoWidth: false,
        destroy: true,
        order: [[0, "asc"]],
        ajax: {
            url: "/usermngt/grades/get-grades",
            dataSrc: "data",
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "english" },
            { data: "science" },
            { data: "math" },
            { data: "filipino" },
            { data: "mapeh" },
            { data: "average" },
            {
                data: null,
                render: function (data) {
                    return `
                        <div class="text-center">
                            <a href="#" class="edit" data-id="${data.id}">
                                <i class="fas fa-pen"></i>
                            </a> |
                            <a href="#" class="delete text-danger" data-id="${data.id}">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    `;
                },
            },
        ],
    });
}
