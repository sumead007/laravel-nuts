@extends('layouts.agent_and_admin.app')

@section('content')

    <script>
        async function processAccetpe(event) {
            var type = $(event).data("type");

            if (type == 1) {

                await Swal.fire({
                    title: 'คุณแน่ใจใช่หรือไม่',
                    input: 'text',
                    icon: 'warning',
                    inputLabel: 'คุณต้องการยกเลิกรายการนี้ใช่หรือไม่',
                    inputPlaceholder: 'สาเหตุ?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก'
                }).then(function(result) {
                    // console.log(result.value)
                    if (result.isConfirmed) {
                        var id = $(event).data("id");
                        var cusm_id = $(event).data("cusm_id");
                        var note = result.value == "" ? "-" : result.value;
                        let _url = "/admin/top_up/accept/" + id + "/" + cusm_id + "/" + type + "/" + note;
                        let _token = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            url: _url,
                            type: "post",
                            data: {
                                _token: _token,
                            },
                            success: function(res) {
                                console.log(res);
                                if (res.code == '200') {
                                    $("#row_" + id).remove();
                                    Swal.fire(
                                        'สำเร็จ!',
                                        'ข้อมูลถูกยืนยันเรียบร้อยแล้ว',
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
                });
            } else {
                Swal.fire({
                    title: 'คูณแน่ใจใช่หรือไม่?',
                    text: "คุณต้องการจะยืนยันการโอนเงินนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var id = $(event).data("id");
                        var cusm_id = $(event).data("cusm_id");
                        let _url = "/admin/top_up/accept/" + id + "/" + cusm_id + "/" + type + "/" + "-";
                        let _token = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            url: _url,
                            type: "post",
                            data: {
                                _token: _token,
                            },
                            success: function(res) {
                                console.log(res);
                                if (res.code == '200') {
                                    $("#row_" + id).remove();
                                    Swal.fire(
                                        'สำเร็จ!',
                                        'ข้อมูลถูกยืนยันเรียบร้อยแล้ว',
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


        }
    </script>

    <script>
        function select_all() {
            $("[id='select_input']").prop('checked', true);
        }

        function reset_select() {
            $("[id='select_input']").prop('checked', false);

        }

        function showInputChouse(event) {
            var btn_chouse = document.getElementById("btn_chouse");
            var accept_select = document.getElementById("accept_select");
            var cancel_select = document.getElementById("cancel_select");
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
                btn_chouse.setAttribute("class", "btn btn-outline-danger");
                //ปุ้มเพิ่มรรายชื่อ

                //ปุ่มยืนยันทั้งหมด
                accept_select.hidden = false;
                //ปุ่มยกเลิกทั้งหมด
                cancel_select.hidden = false;
                //reset
                reset_select.hidden = false;
                //เลือกทั้งหมด
                select_all.hidden = false;

            } else {
                processBtnCancel();
            }

            console.log(chk);
        }

        function processBtnCancel() {
            var btn_chouse = document.getElementById("btn_chouse");
            var accept_select = document.getElementById("accept_select");
            var cancel_select = document.getElementById("cancel_select");
            var chk = btn_chouse.getAttribute("status");
            var reset_select = document.getElementById("reset_select");
            var select_all = document.getElementById("select_all");
            document.getElementById("th_choese").hidden = true;
            $("[id='td_choese']").prop('hidden', true);
            $("[id='th_choese']").prop('hidden', true);
            $("[id='btn_delete']").prop('hidden', false);

            //เลือก
            btn_chouse.innerHTML = "เลือก";
            btn_chouse.setAttribute("status", "0");
            btn_chouse.setAttribute("class", "btn btn-info");
            //ปุ่มยืนยันทั้งหมด
            accept_select.hidden = true;
            //ปุ่มยกเลิกทั้งหมด
            cancel_select.hidden = true;
            //reset
            reset_select.hidden = true;
            //เลือกทั้งหมด
            select_all.hidden = true;
            this.reset_select();
        }

        function select_delete(event) {
            var arr = [];
            var type = $(event).data("type");
            var _url = "{{ route('admin.top_up.all_accept.store') }}";
            let _token = $('meta[name="csrf-token"]').attr('content');
            $("input:checkbox[name=select]:checked").each(function() {
                arr.push({
                    id: $(this).val(),
                    cusm_id: $(this).data('cusm_id'),
                });
            });
            var filtarr = arr.filter(function(el) {
                return el != null;
            });
            //  console.log(filtarr);

            if (filtarr.length > 0) {
                Swal.fire({
                    title: 'คุณแน่ใจใช่หรือไม่?',
                    text: "คุณต้องการทำรายการที่เลือกใช่หรือไม่?",
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
                                type: type,
                            },
                            success: function(res) {
                                console.log("Sucess");
                                console.log(res);
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
    </script>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ยืนยันการเติมเงิน</h3>

                        <div class="card-tools">
                            <button class="btn btn-info" status="0" onclick="showInputChouse(event)"
                                id="btn_chouse">เลือก</button>
                            <a href="javascript:void(0)" class="btn btn-outline-success" hidden="true" id="select_all"
                                onclick="select_all()">เลือกทั้งหมด</a>
                            <a href="javascript:void(0)" class="btn btn-outline-info" hidden="true" id="reset_select"
                                onclick="reset_select()">รีเซต</a>
                            <a href="javascript:void(0)" class="btn btn-success" hidden="true" id="accept_select"
                                data-type="0" onclick="select_delete(event.target)">ยืนยันการโอนเงินที่เลือก</a>
                            <a href="javascript:void(0)" class="btn btn-danger" hidden="true" id="cancel_select"
                                data-type="1" onclick="select_delete(event.target)">ยกเลิกการโอนเงินที่เลือก</a>
                            <a href="{{route('admin.top_up.history')}}" class="btn btn-outline-success">ประวัติ</a>
                            {{-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table table-head-fixed text-nowrap table-responsive p-0" id="table_crud">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th id="th_choese" hidden>เลือก</th>
                                    <th scope="col">เจ้าของบัญชี</th>
                                    <th scope="col">รูปสลิป</th>
                                    <th scope="col">เลขที่บัญชี</th>
                                    <th scope="col">ชื่อธนาคาร</th>
                                    <th scope="col">ชื่อบัญชี</th>
                                    <th scope="col">จำนวนเงิน</th>
                                    <th scope="col">โอนไปยัง</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">อัพเดทเมื่อ</th>
                                    <th>อื่นๆ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_ups as $top_up)
                                    @if ($top_up->user->admin_id == Auth::guard('admin')->user()->id)
                                        <tr align="center" id="row_{{ $top_up->id }}">
                                            <th id="td_choese" class="align-middle" hidden>
                                                <div align="center">
                                                    <input type="checkbox" class="form-check" name="select"
                                                        data-cusm_id="{{ $top_up->user->id }}" id="select_input"
                                                        value="{{ $top_up->id }}">
                                                </div>
                                            </th>
                                            <td class="align-middle">
                                                {{ $top_up->user->username }}
                                            </td>
                                            <td class="align-middle">
                                                <a href="#" class="pop">
                                                    <img src="{{ asset($top_up->image) }}" alt="{{ $top_up->image }}"
                                                        width="100" height="100">
                                                </a>
                                            </td>
                                            <td class="align-middle">{{ $top_up->number_account }}</td>
                                            <td class="align-middle">{{ $top_up->name_bank }}</td>
                                            <td class="align-middle">{{ $top_up->name_account }}</td>
                                            <td class="align-middle">{{ $top_up->money }}</td>
                                            <td class="align-middle">
                                                {{ $top_up->bank_organization->name_account }}
                                                <br>
                                                {{ $top_up->bank_organization->number_account }}
                                                <br>
                                                {{ $top_up->bank_organization->name_bank }}

                                            </td>
                                            <td class="align-middle">
                                                @if ($top_up->status == 0)
                                                    รอการยืนยัน
                                                @elseif ($top_up->status == 1)
                                                    สำเร็จ
                                                @else
                                                    มีข้อผิดพลาด
                                                @endif
                                            </td>
                                            <th scope="row" class="align-middle">
                                                {{ Carbon\Carbon::parse($top_up->updated_at)->locale('th')->diffForHumans() }}
                                            </th>
                                            <td class="align-middle" align="center">
                                                <a href="javascript:void(0)" class="btn btn-success" data-type="0"
                                                    data-id="{{ $top_up->id }}" data-cusm_id="{{ $top_up->user->id }}"
                                                    id='btn_accept' onclick="processAccetpe(event.target)">ยืนยัน</a>
                                                <a href="javascript:void(0)" class="btn btn-danger" data-type="1"
                                                    data-cusm_id="{{ $top_up->user->id }}" data-id="{{ $top_up->id }}"
                                                    onclick="processAccetpe(event.target)" id='btn_cancel'>ยกเลิก</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {!! $top_ups->links() !!}
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
    <script>
        $(function() {
            $('.pop').on('click', function() {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');
            });
        });
    </script>

@endsection
