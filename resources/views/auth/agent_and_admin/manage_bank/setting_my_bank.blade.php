@extends('layouts.agent_and_admin.app')

@section('content')

    <script>
        function addPost(event) {
            clear_ms_error();
            $("#form_first")[0].reset();
            $("#text_addcus").html("เพิ่มธนาคารของฉัน");
            $('#post-modal').modal('show');
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
                    let _url = "{{ route('admin.manage_bank.setting_my_bank.store') }}";
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
                                $("#table_crud #row_" + id + " td:nth-child(2)").html(res.data
                                    .number_account);
                                $("#table_crud #row_" + id + " td:nth-child(3)").html(res.data
                                    .name_account);
                                $("#table_crud #row_" + id + " td:nth-child(4)").html(res.data
                                    .name_bank);
                            } else {
                                console.log(res)
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

                                    "<td class='align-middle'>" + res.data.number_account +
                                    "</td>" +
                                    "<td class='align-middle'>" + res.data.name_account + "</td>" +
                                    "<td class='align-middle'>" + res.data.name_bank + "</td>" +
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
                            Swal.fire(
                                'สำเร็จ!',
                                'ข้อมูลของท่านถูกบันทึกเรียบร้อยแล้ว',
                                'success'
                            )

                            $('#post-modal').modal('hide');
                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                            Swal.fire(
                                'ไม่สำเร็จ!',
                                'กรุณาลองใหม่อีกครั้ง หรือรีเฟชหน้าเว็บนี้ใหม่อีกครั้ง',
                                'error'
                            )
                            clear_ms_error();
                            $('#number_accountError').text(err.responseJSON.errors.number_account);
                            $('#name_accountError').text(err.responseJSON.errors.name_account);
                            $('#name_bankError').text(err.responseJSON.errors.name_bank);
                        }
                    });
                }
            })

        }

        function clear_ms_error() {
            $('#number_accountError').text("");
            $('#name_accountError').text("");
            $('#name_bankError').text("");
        }

        function editPost(event) {
            $('#post-modal').modal('show');
            clear_ms_error();
            var id = $(event).data("id");
            let _url = "/admin/get_api/bank_organizations/" + id;
            $("#text_addcus").html("แก้ไขธนาคารของฉัน");
            $("#form_first")[0].reset();
            $.ajax({
                url: _url,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    //console.log(_url);
                    if (res) {
                        $("#post_id").val(res.id);
                        $("#number_account").val(res.number_account);
                        $("#name_account").val(res.name_account);
                        $("#name_bank").val(res.name_bank);
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
                    let _url = "/admin/manage_bank/delete/" + id;
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
            var _url = "{{ route('admin.manage_bank.delete_all') }}";
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
                        <h3 class="card-title">ตั้งค่าธนาคารของฉัน</h3>
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
                                    onclick="addPost(event.target)">เพิ่มธนาคาร</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap p-0" id="table_crud">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th id="th_choese" hidden>เลือก</th>
                                        <th scope="col">เลขที่บัญชี</th>
                                        <th scope="col">ชื่อบัญชี</th>
                                        <th scope="col">ชื่อธนาคาร</th>
                                        <th scope="col">เพิ่มข้อมูลเมื่อ</th>
                                        <th scope="col">อื่นๆ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bank_organizations as $bank_organization)

                                        <tr align="center" id="row_{{ $bank_organization->id }}">
                                            <th id="td_choese" class="align-middle" hidden>
                                                <div align="center">
                                                    <input type="checkbox" class="form-check" name="select"
                                                        data-cusm_id="{{ $bank_organization->id }}" id="select_input"
                                                        value="{{ $bank_organization->id }}">
                                                </div>
                                            </th>
                                            <td class="align-middle">
                                                {{ $bank_organization->number_account }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $bank_organization->name_account }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $bank_organization->name_bank }}
                                            </td>
                                            <td class="align-middle">
                                                {{ Carbon\Carbon::parse($bank_organization->created_at)->locale('th')->diffForHumans() }}
                                            </td>
                                            <td class="align-middle" align="center">
                                                <a href="javascript:void(0)" class="btn btn-warning"
                                                    data-id="{{ $bank_organization->id }}"
                                                    onclick="editPost(event.target)" id='btn_edit'>แก้ไข</a>
                                                <a href="javascript:void(0)" class="btn btn-danger"
                                                    data-id="{{ $bank_organization->id }}"
                                                    onclick="deletePost(event.target)" id='btn_delete'>ลบ</a>
                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {!! $bank_organizations->links() !!}
                        </div>
                    </div>

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
                            <label for="number_account">เลขที่บัญชี</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="number_account" name="number_account" required
                                    placeholder="กรุณากรอกเลขที่บัญชี">
                                <span id="number_accountError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name_account">ชื่อบัญชี</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name_account" name="name_account"
                                    placeholder="กรุณากรอกชื่อบัญชี" required>
                                <span id="name_accountError" class="alert-message text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name_bank">เลือกธนาคาร</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="name_bank" name="name_bank" required>
                                    <option value="" selected disabled>กรุณาเลือกธนาคารของท่าน</option>
                                    <option value="ธนาคารกรุงเทพ">ธนาคารกรุงเทพ</option>
                                    <option value="ธนาคารกรุงไทย">ธนาคารกรุงไทย</option>
                                    <option value="ธนาคารกรุงศรีอยุธยา">ธนาคารกรุงศรีอยุธยา</option>
                                    <option value="ธนาคารกสิกรไทย">ธนาคารกสิกรไทย</option>
                                    <option value="ธนาคารเกียรตินาคินภัทร">ธนาคารเกียรตินาคินภัทร</option>
                                    <option value="ธนาคารซีไอเอ็มบี">ธนาคารซีไอเอ็มบี</option>
                                    <option value="ธนาคารทหารไทยธนชาต">ธนาคารทหารไทยธนชาต</option>
                                    <option value="ธนาคารทิสโก้">ธนาคารทิสโก้</option>
                                    <option value="ธนาคารไทยพาณิชย์">ธนาคารไทยพาณิชย์</option>
                                    <option value="ธนาคารธนชาต">ธนาคารธนชาต</option>
                                    <option value="ธนาคารยูโอบี">ธนาคารยูโอบี</option>
                                    <option value="ธนาคารแลนด์ แอนด์ เฮ้าส์">ธนาคารแลนด์ แอนด์ เฮ้าส์</option>
                                    <option value="ธนาคารสแตนดาร์ดชาร์เตอร์ด (ไทย)">ธนาคารสแตนดาร์ดชาร์เตอร์ด (ไทย)</option>
                                    <option value="ธนาคารไอซีบีซี (ไทย)">ธนาคารไอซีบีซี (ไทย)</option>
                                </select>
                                <span id="name_bankError" class="alert-message text-danger"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="createPost()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
@endsection
