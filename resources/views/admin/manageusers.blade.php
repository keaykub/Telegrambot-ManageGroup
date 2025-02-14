@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="container">
        <div class="mt-4 mb-3">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <text class="text-Dashboard">จัดการผู้ใช้งาน</text>
                    <div>
                        <button type="button" class="btn btn-secondary delday-all" style="margin-right: 10px;"><i class="bi bi-clock-history"></i> ลดวันทั้งหมด</button>
                        <button type="button" class="btn btn-primary plusday-all"><i class="bi bi-plus-lg"></i>   เพิ่มวันทั้งหมด</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body custom-table-card-body">
            <div class="table-responsive"> {{-- custom-table-responsive --}}
                <table id="datatable" class="table table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>รหัสเข้ากลุ่ม</th>
                            <th>เลขผู้ใช้งาน</th>
                            <th>ชื่อผู้ใช้งาน</th>
                            <th>สถานะ</th>
                            <th>ประเภท</th>
                            <th>วันเข้ากลุ่ม</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "/api/users",
                "columns": [
                    { "data": null,
                        "render": function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "CTEG_CODEID"},
                    { "data": "CTEG_USERID",
                        "render": function (data) {
                            return (data === 'NULL' || data === null || data === undefined || data === '') ? 'N/A' : data;
                        }
                     },
                    { "data": "CTEG_USERNAME",
                        "render": function (data) {
                            return (data === 'NULL' || data === null || data === undefined || data === '') ? 'N/A' : data;
                        }
                    },
                    { "data": "CTEG_CODESTATUS",
                        "render": function (data){
                            if (data === 'INACTIVE') {
                                return '<i class="bi bi-circle-fill custom-status-icon-Inactive"></i> ออนไลน์';
                            } else if (data === 'CLOSED') {
                                return '<i class="bi bi-circle-fill custom-status-icon-Closed"></i> ออฟไลน์';
                            } else {
                                return '<i class="bi bi-circle-fill custom-status-icon-Active"></i> พร้อมใช้งาน';
                            }
                        }
                    },
                    { "data": "CTEG_CODEDAY",
                        "render": function(data){
                            if(data === "H1"){
                                return "1 ชั่วโมง";
                            }else if(data === "D1"){
                                return "1 วัน";
                            }else if(data === "D7"){
                                return "7 วัน";
                            }else if(data === "D30"){
                                return "30 วัน";
                            }
                        }
                    },
                    { "data": "CTEG_CODE_JOINDATE" },
                    { "data": null,
                        "render": function(data, type, row) {
                            return '<div class="btn-group-sm d-flex justify-content-end" role="group">' +
                            '<button type="button" class="btn btn-custom-tools dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">' +
                            'เครื่องมือ' +
                            '</button>' +
                            '<ul class="dropdown-menu">' +
                            '<li><a class="dropdown-item action-plus-days" href="#" data-code="' + row.CTEG_CODEID + '"><i class="bi bi-calendar-plus-fill"></i>  เพิ่มวันใช้งาน</a></li>' +
                            '<li><a class="dropdown-item action-reduce-days" href="#" data-code="' + row.CTEG_CODEID + '"><i class="bi bi-calendar-x-fill"></i> ลดวันใช้งาน</a></li>' +
                            '<li><a class="dropdown-item action-kick-user" href="#" data-code="' + row.CTEG_CODEID + '" data-userid="' + row.CTEG_USERID + '"><i class="bi bi-ban-fill"></i>  เตะออกกลุ่ม</a></li>' +
                            '<li><a class="dropdown-item action-delete" href="#" data-code="' + row.CTEG_CODEID + '"><i class="bi bi-trash3-fill"></i> ลบข้อมูล</a></li>' +
                            '</ul>' +
                            '</div>';
                            }
                        }
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
        });

        $(document).on("click",".action-plus-days", function(e){
            e.preventDefault();
            var code = $(this).data("code");

            Swal.fire({
                title: "เพิ่มวันใช้งาน",
                text: "กรุณากรอกจำนวนวันที่ต้องการเพิ่ม",
                input: "number",
                inputAttributes: {
                    min: 1,
                    max: 365,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                showLoaderOnConfirm: true,
                preConfirm: (days) => {
                    return fetch(`/admin/plusdays/`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ code: code, days: days })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`เกิดข้อผิดพลาด: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("เพิ่มวันใช้งานแล้ว!", "วันใช้งานถูกเพิ่มเรียบร้อย", "success");
                    $('#datatable').DataTable().ajax.reload();
                }
            });
        });

        $(document).on("click",".action-reduce-days", function(e){
            e.preventDefault();
            var code = $(this).data("code");

            Swal.fire({
                title: "ลดวันใช้งาน",
                text: "กรุณากรอกจำนวนวันที่ต้องการลด",
                input: "number",
                inputAttributes: {
                    min: 1,
                    max: 365,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                showLoaderOnConfirm: true,
                preConfirm: (days) => {
                    return fetch(`/admin/reducedays`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ code: code, days: days })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`เกิดข้อผิดพลาด: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("ลดวันใช้งานแล้ว!", "วันใช้งานถูกลดลงเรียบร้อย", "success");
                    $('#datatable').DataTable().ajax.reload();
                }
            });
        });

        $(document).on("click",".action-kick-user", function(e){
            e.preventDefault();
            var code = $(this).data("code");
            var userid = $(this).data("userid");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            Swal.fire({
                title: "ยืนยันการเตะออกกลุ่ม?",
                text: "คุณต้องการเตะผู้ใช้รหัส " + userid + " ใช่หรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ใช่, เตะเลย!",
                cancelButtonText: "ยกเลิก"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/admin/kickuser",
                        type: "POST",
                        data: { code: code, userid: userid },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        success: function(response) {
                            Swal.fire("เตะออกแล้ว!", "ผู้ใช้ถูกเตะออกจากกลุ่มเรียบร้อย", "success");
                            $('#datatable').DataTable().ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถเตะผู้ใช้ได้", "error");
                        }
                    });
                }
            });
        })

        $(document).on("click", ".action-delete", function (e) {
            e.preventDefault();
            var code = $(this).data("code");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            Swal.fire({
                title: "ยืนยันการลบ?",
                text: "คุณต้องการลบข้อมูลรหัส " + code + " ใช่หรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ใช่, ลบเลย!",
                cancelButtonText: "ยกเลิก"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/admin/deleteuser",
                        type: "POST",
                        data: { code: code },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        success: function(response) {
                            Swal.fire("ลบแล้ว!", "ข้อมูลถูกลบเรียบร้อย", "success");
                            $('#datatable').DataTable().ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถลบข้อมูลได้", "error");
                        }
                    });
                }
            });
        });

        document.querySelector(".delday-all").addEventListener("click", function(e){
            e.preventDefault();
            Swal.fire({
                title: "ลดวันทั้งหมด",
                text: "กรุณากรอกจำนวนวันที่ต้องการลด",
                input: "number",
                inputAttributes: {
                    min: 1,
                    max: 365,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                showLoaderOnConfirm: true,
                preConfirm: (days) => {
                    return fetch(`/admin/reducedaysall`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ days: days })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`เกิดข้อผิดพลาด: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("ลดวันทั้งหมดแล้ว!", "วันใช้งานถูกลดลงเรียบร้อย", "success");
                    $('#datatable').DataTable().ajax.reload();
                }
            });
        });

        document.querySelector(".plusday-all").addEventListener("click", function(e){
            e.preventDefault();
            Swal.fire({
                title: "เพิ่มวันทั้งหมด",
                text: "กรุณากรอกจำนวนวันที่ต้องการเพิ่ม",
                input: "number",
                inputAttributes: {
                    min: 1,
                    max: 365,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                showLoaderOnConfirm: true,
                preConfirm: (days) => {
                    return fetch(`/admin/plusdaysall`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ days: days })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`เกิดข้อผิดพลาด: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("เพิ่มวันทั้งหมดแล้ว!", "วันใช้งานถูกเพิ่มเรียบร้อย", "success");
                    $('#datatable').DataTable().ajax.reload();
                }
            });
        });

    </script>
@endsection
