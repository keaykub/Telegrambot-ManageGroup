@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <div class="container">
        <div class="mt-4 mb-3">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <text class="text-Dashboard">จัดการผู้ดูแลระบบ</text>
                    <div>
                        <button type="button" class="btn btn-primary"><i class="bi bi-plus-lg"></i>   เพิ่มผู้ดูแล</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body custom-table-card-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>ตำแหน่ง</th>
                            <th>เลขผู้ใช้</th>
                            <th>เวลาสร้าง</th>
                            <th>อัปเดทล่าสุด</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal แก้ไขข้อมูล -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="adminUsername" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="adminUsername" name="adminUsername" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="adminRole" class="form-label">ตำแหน่ง</label>
                        <select class="form-select" id="adminRole" name="adminRole" required>
                            <option value="TESTER">ผู้ทดสอบระบบ[Tester]</option>
                            <option value="ADMIN">ผู้ดูแลระบบ[Admin]</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adminIdTelegram" class="form-label">เลขผู้ใช้</label>
                        <input type="text" class="form-control" id="adminIdTelegram" name="adminIdTelegram" disabled>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "/api/admins",
            "columns": [
            { "data": null,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            { "data": "ADMIN_USERNAME"},
            { "data": "ADMIN_ROLE"},
            { "data": "ADMIN_IDTELEGRAM",
                "render": function (data, type, row) {
                    return (data === 'NO' || data === '') ? 'N/A' : data;
                },
            },
            { "data": "created_at" },
            { "data": "updated_at" },
            { "data": null,
                "render": function (data, type, row) {
                    return '<div class="btn-group-sm d-flex justify-content-end" role="group">' +
                    '<button type="button" class="btn btn-custom-tools dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">' +
                    'เครื่องมือ' +
                    '</button>' +
                    '<ul class="dropdown-menu">' +
                    '<li><a class="dropdown-item action-edit" href="#" data-username="' + row.ADMIN_USERNAME + '" data-role="'+ row.ADMIN_ROLE +'" data-useridtelegram="'+ row.ADMIN_IDTELEGRAM +'" ><i class="bi bi-pencil"></i>  แก้ไขข้อมูล</a></li>' +
                    '<li><a class="dropdown-item action-reduce-days" href="#" data-username="' + row.ADMIN_USERNAME + '"><i class="bi bi-key"></i> เปลี่ยนรหัสผ่าน</a></li>' +
                    '<li><a class="dropdown-item action-reduce-days" href="#" data-username="' + row.ADMIN_USERNAME + '"><i class="bi bi-trash3-fill"></i> ลบข้อมูล</a></li>' +
                    '</ul>' +
                    '</div>';
                },
            },
            ],
            "language": {
                "search": "ค้นหา:",
                "lengthMenu": "แสดง _MENU_ รายการ",
                "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });
    })

    $(document).on("click", ".action-edit", function(){
        let username = $(this).data("username");
        let role = $(this).data("role");
        let useridtelegram = $(this).data("useridtelegram");

        $("#adminUsername").val(username);
        $("#adminRole").val(role);
        $("#adminIdTelegram").val(useridtelegram);

        $("#editModal").modal("show");
    });

    $("#editForm").submit(function(e){
        e.preventDefault();
        let username = $("#adminUsername").val();
        let role = $("#adminRole").val();
        let id = $("#adminId").val();

        let data = {
            user: username,
            role: role,
            id: id,
        };
        console.log(data);
        $.ajax({
            url: "/admins/editadmin",
            type: "POST",
            data: data,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function(response) {
                if (response.status === "success") {
                    console.log("Success: " + response.message);
                    $("#editModal").modal("hide");
                    $("#datatable").DataTable().ajax.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
            }
        });
    });

</script>
@endsection
