<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Tabler Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <!-- โหลด DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- โหลด DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .backgroundPage-custom {
            background-color: #0e1d2c;
            height: 100vh;
        }

        .navbar-custom {
            background-color: #142a3f;
            color: white;
        }

        .navbar-toggler {
            background-color: #3e5369;
            border: none;
        }

        .dropdown-menu {
            background-color: #ffffff;
            border: none;
        }

        .nav-link {
            color: rgb(233, 226, 226);
            font-size: 1rem;
            padding-bottom: 4px;
        }

        .navtext-white {
            color: rgb(233, 226, 226);
            font-size: 1rem;
        }

        .nav-link:focus,
        .nav-link.active {
            color: #ffffff !important;
            border-bottom: 2px solid;
            border-bottom-color: #ffffff;
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .nav-link.dropdown-toggle {
            text-decoration: none; /* ไม่มีเส้นใต้สำหรับ dropdown */
        }

        .nav-link.dropdown-toggle:hover,
        .nav-link.dropdown-toggle:focus,
        .nav-link.dropdown-toggle.active {
            text-decoration: none;
            border-bottom: none;
        }

        .custom-chartmain {
            background-color: #142a3f;
            border-radius: 10px;
            margin: 0 auto;
            width: 80%;
        }

        .text-Dashboard {
            color: white;
            font-size: 1.5em;
        }

        .custom-status-icon-Inactive {
            color: #0aac0a;
            font-size: 0.6em;
        }

        .custom-status-icon-Closed {
            color: #ff0000;
            font-size: 0.6em;
        }

        .custom-status-icon-Active {
            color: #ffcc00;
            font-size: 0.6em;
        }

        .custom-table-main {
            background-color: #142a3f;
            border-radius: 10px;
            margin: 0 auto;
            width: 80%;
        }

        .custom-table-body {
            background-color: #142a3f;
            border-radius: 10px;
        }

        .bg-custom-headtable {
            background-color: #142a3f;
        }

        .custom-table-responsive {
            background-color: #142a3f
        }

        .custom-table-card-body {
            background-color: #142a3f;
        }

        .table-responsive {
            min-height: 600px;
            overflow-y: auto;
        }

        #datatable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            max-width: 100%;
        }

        #datatable th {
            text-align: center;
            background-color: #142a3f;
            font: bold 14px Verdana, Arial, Helvetica, sans-serif;
            color: white;
            opacity: 0.8;
        }

        #datatable td {
            background-color: #193344;
            font: 15px Verdana, Arial, Helvetica, sans-serif;
            color: white;
        }

        .dataTables_filter label {
            color: #a79999;
            font-size: 14px;
        }

        .dataTables_length label {
            color: #a79999; /* เปลี่ยนเป็นสีขาว */
            font-size: 14px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 4px;
            padding: 4px 10px;
            margin-bottom: 4px;
            background-color: #142a3f;
        }

        .dataTables_info {
            color: #a79999 !important;
        }

        .btn-custom-tools {
            background-color: #44474b;
            color: #e2d6d6;
            border: 1px solid #242527;
            border-radius: 5px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #142a3f;
            color: rgb(255, 255, 255) !important;
            padding: 4px 10px;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: rgb(86, 88, 206); /* สีที่ต้องการ */
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):hover {
            background-color: #1a2a3a !important; /* สีที่ต้องการตอน hover */
            color: rgb(255, 255, 255) !important;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: rgb(86, 88, 206) !important; /* คงสีเดิม */
            color: white !important;
            cursor: default;
        }
    </style>
</head>
<body>
    <div class="w-100 backgroundPage-custom">
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container">
                <a class="navbar-brand text-white" href="#">
                    <img src="{{ asset('images/telegrambot-3.webp') }}" alt="Logo" width="30" height="30" class="rounded d-inline-block align-text-top">
                    TelegramBot
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavMain, #navbarNavRight" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavMain">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link navtext-white {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-door-fill"></i> แดชบอร์ด
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navtext-white {{ Request::is('admin/manageusers') ? 'active' : '' }}" href="{{ route('admin.manageusers') }}">
                                <i class="bi bi-person-lines-fill"></i> จัดการผู้ใช้งาน
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navtext-white {{ Request::is('admin/manageadmins') ? 'active' : '' }}" href="{{ route('admin.manageadmins') }}">
                                <i class="bi bi-person-fill-gear"></i> จัดการผู้ดูแลระบบ
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle navtext-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-binoculars-fill"></i> ฟังก์ชันเสริม
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item set-webhook" href="#">Set Webhook</a></li>
                                <li><a class="dropdown-item delete-webhook" href="#">Delete Webhook</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavRight">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-fill"></i> Tester
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.querySelector('.delete-webhook').addEventListener('click', function(){
            fetch('/delete-webhook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if(response.ok){
                    Swal.fire({
                        title: "Webhook ถูกลบเรียบร้อย",
                        icon: "success",
                        draggable: true
                    });
                }else{
                    throw new Error('Something went wrong')
                }
            }).catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`)
            })
        })

        document.querySelector('.set-webhook').addEventListener('click', function() {
            Swal.fire({
                title: 'Set Webhook',
                html: '<input type="text" class="form-control" id="webhookUrl" placeholder="URL Webhook">',
                showCancelButton: true,
                confirmButtonText: 'Set Webhook',
                preConfirm: () => {
                    const webhookUrl = Swal.getPopup().querySelector('#webhookUrl').value
                    if (!webhookUrl) {
                        Swal.showValidationMessage('กรุณากรอก URL Webhook')
                    }
                    fetch('/set-webhook', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ webhookUrl: webhookUrl })
                    }).then(response => {
                        if(!response.ok){
                            throw new Error('Something went wrong')
                        }
                    }).catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`)
                    })
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Webhook ถูกตั้งค่าเรียบร้อย",
                        icon: "success",
                        draggable: true
                    });
                }
            })
        })
    </script>
</body>
</html>
