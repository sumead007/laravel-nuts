@extends('layouts.agent_and_admin.app')

@section('content')



    <script>
        function addPost(event) {
            var position = $(event).data("position");

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

        function resetInput() {
            $('#nameError').text("");
            $('#usernameError').text("");
            $('#telError').text("");
        }
        var countRow = 0;

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
                    var name = $("#name").val();
                    var tel = $("#tel").val();
                    var username = $("#username").val();
                    var id = $("#post_id").val();
                    // console.log(name,username,post_id,tel);
                    resetInput();

                    let _url = '';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: _url,
                        type: "POST",
                        data: {
                            id: id,
                            username: username,
                            tel: tel,
                            name: name,
                            _token: _token,
                        },
                        success: function(res) {
                            // console.log("สำเร็จ");

                            if (id != "") {
                                $("#table_crud #row_" + id + " td:nth-child(2)").html(res.data.name);
                                $("#table_crud #row_" + id + " td:nth-child(3)").html(res.data
                                    .username);
                                $("#table_crud #row_" + id + " td:nth-child(4)").html(res.data.tel);

                                $("#table_random #row_" + id + " td:nth-child(1)").html(res.data.name);
                                $("#table_random #row_" + id + " td:nth-child(2)").html(res.data
                                    .username);
                                $("#table_random #row_" + id + " td:nth-child(3)").html(res.data.tel);
                            } else {
                                $('#table_crud tbody').prepend("<tr id='row_" + res.data.id + "'" +
                                    ">" +

                                    "<th id='td_choese" +
                                    "' class='align-middle' hidden='true'>" +
                                    "<div align='center'>" +
                                    "<input type='checkbox' class='form-check' name='select'" +
                                    "id='select_input' value='" + res.data.id + "'>" +
                                    "</div>" +
                                    "</th>" +

                                    "<td>" + res.data.name + "</td>" +
                                    "<td>" + res.data.username + "</td>" +
                                    "<td>" + res.data.tel + "</td>" +
                                    "<td align='center'>" +
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
                            Swal.fire(
                                'สำเร็จ!',
                                'ข้อมูลของท่านถูกบันทึกเรียบร้อยแล้ว',
                                'success'
                            )
                            $('#post-modal').modal('hide');
                            countRow++;
                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                            $('#nameError').text(err.responseJSON.errors.name);
                            $('#usernameError').text(err.responseJSON.errors.username);
                            $('#telError').text(err.responseJSON.errors.tel);
                        }
                    });
                }
            })

        }

        function editPost(event) {
            var id = $(event).data("id");
            let _url = "/magate/custommer/getData/" + id;
            text_addcus
            $("#text_addcus").html("แก้ไขรายชื่อ");
            resetInput();
            $.ajax({
                url: _url,
                type: "GET",
                success: function(res) {
                    //console.log(_url);
                    if (res) {
                        $("#post_id").val(res.id);
                        $("#name").val(res.name);
                        $("#tel").val(res.tel);
                        $("#username").val(res.username);
                        $('#post-modal').modal('show');
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
                    let _url = "/magate/custommer/delete/" + id;
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

        function modalRandomClick() {
            $('#exampleModalCenter').modal('show');
        }


        function showTable_Crud(e) {
            var name = e.innerHTML;
            // console.log(name);
            if (name == "แสดง") {
                document.getElementById("card_adduser").hidden = false;
                e.innerHTML = "ซ่อน"
            } else {
                document.getElementById("card_adduser").hidden = true;
                e.innerHTML = "แสดง"

            }
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
            var _url = "";
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
                                <thead>
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
                            {{-- {!! $users->links() !!} --}}
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
                            <label for="password">รหัสผ่าน</label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="กรุณากรอกรหัสผ่าน" required>
                                <span id="passwordError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">ยืนยันรหัสผ่าน</label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="password-confirm"
                                    name="password_confirmation" placeholder="กรุณายืนยันรหัสผ่าน" required>
                                <span id="usernameError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="telephone">เบอร์โทรศัพท์</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="telephone" name="telephone"
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
