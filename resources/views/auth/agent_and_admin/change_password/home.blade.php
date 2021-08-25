@extends('layouts.agent_and_admin.app')

@section('content')

    <script>
        function createPost() {
            Swal.fire({
                title: 'คุณแน่ใจใช่หรือไม่?',
                text: "คุณต้องการบันทีกใช่หรือไม่?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: "ยกเลิก"
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('#form_first')[0];
                    var data = new FormData(form);
                    let _url = "{{ route('admin.change_password.store') }}";
                    $.ajax({
                        enctype: 'multipart/form-data',
                        processData: false, // Important!
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        url: _url,
                        type: "POST",
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            // console.log("สำเร็จ");
                            Swal.fire(
                                'สำเร็จ!',
                                'ข้อมูลของท่านถูกบันทึกเรียบร้อยแล้ว',
                                'success'
                            )
                            clear_ms_error();

                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                            Swal.fire(
                                'ไม่สำเร็จ!',
                                'กรุณาลองใหม่อีกครั้ง หรือรีเฟชหน้าเว็บนี้ใหม่อีกครั้ง',
                                'error'
                            )
                            clear_ms_error();
                            $('#usernameError').text(err.responseJSON.errors.username);
                            $('#password_oldError').text(err.responseJSON.errors.password_old);
                            $('#passwordError').text(err.responseJSON.errors.password);
                        }
                    });
                }
            })

        }

        function clear_ms_error() {
            $('#usernameError').text("");
            $('#password_oldError').text("");
            $('#passwordError').text("");
        }
    </script>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">เปลี่ยนรหัสผ่าน</h3>
                    </div>
                    <div class="card-body">

                        <form name="form_first" id="form_first" class="form-horizontal">

                            <div class="form-group" @if (Auth::guard('admin')->user()->position == 1) hidden @endif>
                                <label for="username">ชื่อผู้ใช้งาน</label>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="username" name="username" required
                                            placeholder="กรุณากรอกชื่อ" readonly
                                            value="{{ Auth::guard('admin')->user()->username }}">
                                        <span id="usernameError" class="alert-message text-danger"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-primary"
                                            onclick="$('#username').attr('readonly', false)">แก้ไขชื่อผู้ใช้งาน</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_old">รหัสผ่านเก่า</label>
                                <div class="col-sm-12">
                                    <input type="password" class="form-control" id="password_old" name="password_old"
                                        placeholder="กรุณากรอกรหัสผ่านเก่า" required>
                                    <span id="password_oldError" class="alert-message text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">รหัสผ่านใหม่ <span id="recommend_password"
                                        class="alert-message text-danger"></span></label>
                                <div class="col-sm-12">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="กรุณากรอกรหัสผ่านใหม่" required>
                                    <span id="passwordError" class="alert-message text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">ยืนยันรหัสผ่านใหม่ <span id="recommend_confirm_password"
                                        class="alert-message text-danger"></span></label>
                                <div class="col-sm-12">
                                    <input type="password" class="form-control" id="password-confirm"
                                        name="password_confirmation" placeholder="กรุณายืนยันรหัสผ่านใหม่" required>
                                    <span id="usernameError" class="alert-message text-danger"></span>
                                </div>
                            </div>

                            {{-- @if (Auth::guard('admin')->user()->position == 0)
                                <div class="form-group">
                                    <label for="admin_id">ตัวแทน</label>
                                    <div class="col-sm-12">
                                        <select name="admin_id" id="admin_id" class="select2">
                                            <option value="" disabled selected>กรุณาเลือกตัวแทน</option>
                                        </select>
                                        <span id="admin_idError" class="alert-message text-danger"></span>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" class="form-control" id="admin_id" name="admin_id"
                                    value="{{ Auth::guard('admin')->user()->id }}">
                            @endif --}}
                            <div align="right">
                                <button type="button" class="btn btn-primary" onclick="createPost()">บันทึก</button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
