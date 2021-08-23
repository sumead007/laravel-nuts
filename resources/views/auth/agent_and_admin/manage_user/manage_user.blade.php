@extends('layouts.agent_and_admin.app')

@section('content')

    <script>
        function addPost(event) {
            var position = $(event).data("position");
            clear_ms_error();
            $("#recommend_password").html("")
            $("#recommend_confirm_password").html("")
            $("#text_addcus").html("เพิ่มรายชื่อ");
            $('#post-modal').modal('show');
            $("#form_first")[0].reset();

            if (position == 0) get_agent();
        }

        function get_agent() {
            var id = $(event).data("id");
            let _url = "{{ route('admin.get_api.get_agent') }}";
            $.ajax({
                url: _url,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    //console.log(_url);
                    if (res) {
                        // console.log(res);
                        $("#admin_id option").remove();
                        $("#admin_id").append('<option value="" disabled selected>' + 'กรุณาเลือกตัวแทน ' +
                            '</option>');
                        res.data.forEach(value => {
                            var o = new Option(value.username, value.id);
                            $("#admin_id").append(o);
                            // console.log(value);
                        });


                    }
                }
            });
        }

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
                    var id = $("#post_id").val();


                    let _url = "{{ route('admin.manage_user.store') }}";
                    let _token = $('meta[name="csrf-token"]').attr('content');
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

                            if (id != "") {
                                $("#table_crud #row_" + id + " td:nth-child(2)").html(res.data.name);
                                $("#table_crud #row_" + id + " td:nth-child(3)").html(res.data
                                    .username);
                                $("#table_crud #row_" + id + " td:nth-child(4)").html(res.data
                                    .telephone);

                                $("#table_crud #row_" + id + " td:nth-child(5)").html(res.data.money);
                                if (res.auth_position == 0) $("#table_crud #row_" + id +
                                    " td:nth-child(7)").html(res.admin_data.username);
                            } else {
                                // console.log(res)
                                //ถ้าเป็นเจ้าของจะไม่แสดงคอลัมเอเจน
                                if (res.auth_position == 0) {
                                    $('#table_crud tbody').prepend("<tr align='center' id='row_" + res
                                        .data
                                        .id + "'" +
                                        ">" +

                                        "<th id='td_choese" +
                                        "' class='align-middle' hidden='true'>" +
                                        "<div align='center'>" +
                                        "<input type='checkbox' class='form-check' name='select'" +
                                        "id='select_input' value='" + res.data.id + "'>" +
                                        "</div>" +
                                        "</th>" +

                                        "<td class='align-middle'>" + res.data.name + "</td>" +
                                        "<td class='align-middle'>" + res.data.username + "</td>" +
                                        "<td class='align-middle'>" + res.data.telephone + "</td>" +
                                        "<td class='align-middle'>" + res.data.money + "</td>" +
                                        "<td class='align-middle'>" + res.data.created_at_2 +
                                        "</td>" +
                                        "<td class='align-middle'>" + res.admin_data.username +
                                        "</td>" +
                                        "<td class='align-middle' align='center'>" +
                                        "<a href='javascript:void(0)' class='btn btn-warning'" +
                                        "data-id='" + res.data.id +
                                        "' onclick='editPost(event.target)' id='btn_edit'>แก้ไข</a> " +
                                        " <a href='javascript:void(0)' class='btn btn-danger'" +
                                        "data-id='" + res.data.id +
                                        "' onclick='deletePost(event.target)' id='btn_delete'>ลบ</a>" +
                                        "</td>" +
                                        "</tr>"
                                    );
                                } else {
                                    $('#table_crud tbody').prepend("<tr align='center' id='row_" + res
                                        .data
                                        .id + "'" +
                                        ">" +

                                        "<th id='td_choese" +
                                        "' class='align-middle' hidden='true'>" +
                                        "<div align='center'>" +
                                        "<input type='checkbox' class='form-check' name='select'" +
                                        "id='select_input' value='" + res.data.id + "'>" +
                                        "</div>" +
                                        "</th>" +

                                        "<td class='align-middle'>" + res.data.name + "</td>" +
                                        "<td class='align-middle'>" + res.data.username + "</td>" +
                                        "<td class='align-middle'>" + res.data.telephone + "</td>" +
                                        "<td class='align-middle'>" + res.data.money + "</td>" +
                                        "<td class='align-middle'>" + res.data.created_at_2 +
                                        "</td>" +
                                        "<td class='align-middle' align='center'>" +
                                        "<a href='javascript:void(0)' class='btn btn-warning'" +
                                        "data-id='" + res.data.id +
                                        "' onclick='editPost(event.target)' id='btn_edit'>แก้ไข</a> " +
                                        " <a href='javascript:void(0)' class='btn btn-danger'" +
                                        "data-id='" + res.data.id +
                                        "' onclick='deletePost(event.target)' id='btn_delete'>ลบ</a>" +
                                        "</td>" +
                                        "</tr>"
                                    );
                                }

                            }
                            Swal.fire(
                                'สำเร็จ!',
                                'ข้อมูลของท่านถูกบันทึกเรียบร้อยแล้ว',
                                'success'
                            )

                            $('#post-modal').modal('hide');
                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                            clear_ms_error();
                            $('#nameError').text(err.responseJSON.errors.name);
                            $('#usernameError').text(err.responseJSON.errors.username);
                            $('#passwordError').text(err.responseJSON.errors.password);
                            $('#telephoneError').text(err.responseJSON.errors.telephone);
                            $('#moneyError').text(err.responseJSON.errors.money);
                            $('#admin_idError').text(err.responseJSON.errors.admin_id);
                        }
                    });
                }
            })

        }

        function clear_ms_error() {
            $('#nameError').text("");
            $('#usernameError').text("");
            $('#passwordError').text("");
            $('#telephoneError').text("");
            $('#moneyError').text("");
            $('#admin_idError').text("");
        }

        function editPost(event) {
            clear_ms_error();
            var id = $(event).data("id");
            let _url = "/admin/get_api/get_user/" + id;
            $("#text_addcus").html("แก้ไขรายชื่อ");
            $("#form_first")[0].reset();
            $("#recommend_password").html("*ถ้าต้องการที่จะเปลี่ยนรหัสผ่านใหม่กรุณากรอกช่องนี้")
            $("#recommend_confirm_password").html("*ถ้าต้องการที่จะเปลี่ยนรหัสผ่านใหม่กรุณากรอกช่องนี้")
            get_agent();
            $.ajax({
                url: _url,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    console.log(res);
                    if (res) {
                        $("#post_id").val(res.id);
                        $("#name").val(res.name);
                        $("#telephone").val(res.telephone);
                        $("#username").val(res.username);
                        $("#money").val(res.money);
                        // $("#admin_id").val(res.admin_id);
                        $("#admin_id").val(res.admin_id).trigger('change');
                        $('#post-modal').modal('show');

                        //ปุ่มแบน
                        $("#btn_user_status").remove();
                        if (res.status == 0) {
                            var r = $(
                                "<button type='button' class='btn btn-outline-danger mr-auto' id='btn_user_status' onclick='user_status(" +
                                res.id + "," + '0' + ")'>แบน</button>"
                            );
                            $(".modal-footer").prepend(r);
                        } else {
                            var r = $(
                                "<button type='button' class='btn btn-outline-danger mr-auto' id='btn_user_status' onclick='user_status(" +
                                res.id + "," + '1' + ")'>ปลดแบน</button>"
                            );
                            $(".modal-footer").prepend(r);
                        }
                    }
                }
            });
        }

        function deletePost(event) {
            Swal.fire({
                title: 'คูณแน่ใจใช่หรือไม่?',
                text: "คุณต้องการลบข้อมูลใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(event).data("id");
                    let _url = "/admin/manage_user/delete/" + id;
                    let _token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: _url,
                        type: "DELETE",
                        data: {
                            _token: _token,
                        },
                        success: function(res) {
                            console.log(res);
                            if (res.code == '200') {
                                $("#row_" + id).remove();
                                Swal.fire(
                                    'สำเร็จ!',
                                    'ข้อมูลถูกลบเรียบร้อยแล้ว',
                                    'success'
                                )
                            } else {
                                Swal.fire(
                                    'ไม่สำเร็จ!',
                                    res.error,
                                    'error'
                                )
                            }

                        }

                    });
                }
            })

        }
    </script>

    <script>
        function showInputChouse(event) {
            var create_new_post = document.getElementById("create-new-post");
            var btn_chouse = document.getElementById("btn_chouse");
            var delete_select = document.getElementById("delete_select");
            var chk = btn_chouse.getAttribute("status");
            var reset_select = document.getElementById("reset_select");
            var select_all = document.getElementById("select_all");
            if (chk == 0) {
                document.getElementById("th_choese").hidden = false;
                $("[id='td_choese']").prop('hidden', false);
                $("[id='th_choese']").prop('hidden', false);
                $("[id='btn_delete']").prop('hidden', true);

                // console.log("fwf")
                //ปุ่มยกเลิก
                btn_chouse.innerHTML = "ยกเลิก";
                btn_chouse.setAttribute("status", "1");
                btn_chouse.setAttribute("class", "btn btn-warning");
                //ปุ้มเพิ่มรรายชื่อ
                create_new_post.hidden = true;
                //ปุ่มลบทั้งหมด
                delete_select.hidden = false;
                //reset
                reset_select.hidden = false;
                //เลือกทั้งหมด
                select_all.hidden = false;

            } else {

                document.getElementById("th_choese").hidden = true;
                $("[id='td_choese']").prop('hidden', true);
                $("[id='th_choese']").prop('hidden', true);
                $("[id='btn_delete']").prop('hidden', false);

                //เลือก
                btn_chouse.innerHTML = "เลือก";
                btn_chouse.setAttribute("status", "0");
                btn_chouse.setAttribute("class", "btn btn-info");
                //ปุ้มเพิ่มรรายชื่อ
                create_new_post.hidden = false;
                //ปุ่มลบทั้งหมด
                delete_select.hidden = true;
                //reset
                reset_select.hidden = true;
                //เลือกทั้งหมด
                select_all.hidden = true;
                this.reset_select();
            }

            console.log(chk);

        }

        function select_delete() {
            var arr = [];
            var _url = "{{ route('admin.manage_user.delete_all') }}";
            let _token = $('meta[name="csrf-token"]').attr('content');
            $("input:checkbox[name=select]:checked").each(function() {
                arr.push({
                    id: $(this).val()
                });
            });
            var filtarr = arr.filter(function(el) {
                return el != null;
            });
            //  console.log(arr);

            if (filtarr.length > 0) {
                Swal.fire({
                    title: 'คุณแน่ใจใช่หรือไม่?',
                    text: "คุณต้องการลบข้อมูลที่เลือกใช่หรือไม่?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ตกลง!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "post",
                            url: _url,
                            data: {
                                _token: _token,
                                pass: filtarr,
                            },
                            success: function(res) {
                                console.log("Sucess");
                                if (res.code == '200') {
                                    var response = res.data;
                                    // console.log(response[0].id);
                                    for (let i = 0; i < response.length; i++) {
                                        $("#row_" + response[i].id).remove();
                                        // console.log(response[i].id)
                                    }
                                    Swal.fire(
                                        'สำเร็จ!',
                                        res.message,
                                        'success'
                                    )
                                }
                            },
                            error: function(err) {
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    "เกิดข้อผิดพลาดบางอย่าง",
                                    'error',
                                )
                            }
                        });
                    }
                })
            } else {
                Swal.fire(
                    'มีข้อผิดพลาด!',
                    "คุณยังไม่ได้เลือกรายการ",
                    'warning'
                )
            }

        }

        function select_all() {
            $("[id='select_input']").prop('checked', true);
        }

        function reset_select() {
            $("[id='select_input']").prop('checked', false);
        }
    </script>

    <script>
        function user_status(user_id, status_old) {
            Swal.fire({
                title: 'คูณแน่ใจใช่หรือไม่?',
                text: "คุณต้องการลบข้อมูลใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(user_id, status_old);
                    let _url = "{{ route('admin.status_user') }}";
                    let _token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: _url,
                        type: "POST",
                        data: {
                            user_id: user_id,
                            status_old: status_old,
                            _token: _token,
                        },
                        success: function(res) {
                            console.log(res);
                            Swal.fire(
                                'สำเร็จ!',
                                'ข้อมูลอัพเดทเรียบร้อยแล้ว',
                                'success'
                            )
                            $('#post-modal').modal('hide');

                        },
                        error: function(res) {
                            Swal.fire(
                                'ไม่สำเร็ค!',
                                'กรุณาลองรีเฟชหน้าเว็บใหม่อีกครั้ง',
                                'error'
                            )
                            $('#post-modal').modal('hide');

                        }

                    });
                }
            })
        }
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">จัดการลูกค้า</h3>
                    </div>
                    <div class="card-body">

                        <div class="mb-3 d-flex justify-content-between">
                            <div>
                                <a href="javascript:void(0)" class="btn btn-success" hidden="true" id="select_all"
                                    onclick="select_all()">เลือกทั้งหมด</a>
                                <a href="javascript:void(0)" class="btn btn-info" hidden="true" id="reset_select"
                                    onclick="reset_select()">รีเซต</a>
                            </div>
                            <div align="right">
                                <button class="btn btn-info" status="0" onclick="showInputChouse(event)"
                                    id="btn_chouse">เลือก</button>
                                <a href="javascript:void(0)" class="btn btn-danger" hidden="true" id="delete_select"
                                    onclick="select_delete()">ลบข้อมูลที่เลือก</a>

                                <a href="javascript:void(0)" class="btn btn-success " id="create-new-post"
                                    data-position="{{ Auth::guard('admin')->user()->position }}"
                                    onclick="addPost(event.target)">เพิ่มรายชื่อ</a>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap p-0" id="table_crud">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th id="th_choese" hidden>เลือก</th>
                                        <th scope="col">ชื่อ</th>
                                        <th scope="col">ชื่อผู้ใช้</th>
                                        <th scope="col">เบอร์โทร</th>
                                        <th scope="col">จำนวนเงิน</th>
                                        <th scope="col">วันที่สมัคร</th>
                                        @if (Auth::guard('admin')->user()->position == 0)
                                            <th scope="col">ตัวแทน</th>
                                        @endif
                                        <th scope="col">อื่นๆ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        {{-- @if (Auth::guard('admin')->user()->id == $user->admin->admin_id) --}}
                                        <tr align="center" id="row_{{ $user->id }}">
                                            <th id="td_choese" class="align-middle" hidden>
                                                <div align="center">
                                                    <input type="checkbox" class="form-check" name="select"
                                                        data-cusm_id="{{ $user->id }}" id="select_input"
                                                        value="{{ $user->id }}">
                                                </div>
                                            </th>
                                            <td class="align-middle">
                                                {{ $user->name }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $user->username }}

                                            </td>
                                            <td class="align-middle">
                                                {{ $user->telephone }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $user->money }}
                                            </td>
                                            <td class="align-middle">
                                                {{ Carbon\Carbon::parse($user->created_at)->locale('th')->diffForHumans() }}
                                            </td>
                                            @if (Auth::guard('admin')->user()->position == 0)
                                                <td class="align-middle">
                                                    {{ $user->admin_username }}
                                                </td>
                                            @endif
                                            <td class="align-middle" align="center">
                                                <a href="javascript:void(0)" class="btn btn-warning"
                                                    data-id="{{ $user->id }}" onclick="editPost(event.target)"
                                                    id='btn_edit'>แก้ไข</a>
                                                <a href="javascript:void(0)" class="btn btn-danger"
                                                    data-id="{{ $user->id }}" onclick="deletePost(event.target)"
                                                    id='btn_delete'>ลบ</a>
                                            </td>
                                        </tr>
                                        {{-- @endif --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {!! $users->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- modal image preview --}}
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
    {{-- modal store --}}
    <div class="modal fade" id="post-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="text_addcus">เพิ่มรายชื่อ</h4>
                </div>
                <div class="modal-body">
                    <form name="form_first" id="form_first" class="form-horizontal">
                        <input type="hidden" name="post_id" id="post_id">
                        <div class="form-group">
                            <label for="name">ชื่อ</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="กรุณากรอกชื่อ">
                                <span id="nameError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username">ชื่อผู้ใช้</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="กรุณากรอกชื่อผู้ใช้" required>
                                <span id="usernameError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">รหัสผ่าน <span id="recommend_password"
                                    class="alert-message text-danger"></span></label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="กรุณากรอกรหัสผ่าน" required>
                                <span id="passwordError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">ยืนยันรหัสผ่าน <span id="recommend_confirm_password"
                                    class="alert-message text-danger"></span></label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="password-confirm"
                                    name="password_confirmation" placeholder="กรุณายืนยันรหัสผ่าน" required>
                                <span id="usernameError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="telephone">เบอร์โทรศัพท์</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="telephone" name="telephone"
                                    placeholder="กรุณากรอกเบอร์โทร" required>
                                <span id="telephoneError" class="alert-message text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="money">จำนวนเงิน (ค่าเริ่มต้น)</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="money" name="money"
                                    placeholder="กรุณากรอกจำนวนเงิน" value="0">
                                <span id="moneyError" class="alert-message text-danger"></span>
                            </div>
                        </div>
                        @if (Auth::guard('admin')->user()->position == 0)
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
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-outline-danger mr-auto" id="btn_user_status"
                        onclick="user_status(event.target)">ปิดบัญชีนี้</button> --}}
                    <button type="button" class="btn btn-primary" onclick="createPost()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>




    <script>
        $(function() {
            $('.pop').on('click', function() {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');
            });
        });
    </script>

@endsection
