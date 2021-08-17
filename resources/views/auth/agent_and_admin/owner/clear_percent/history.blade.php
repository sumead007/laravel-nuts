@extends('layouts.agent_and_admin.app')

@section('content')
    <script>
        function createPost() {

            var form = $('#form_first')[0];
            var data = new FormData(form);
            // var id = $("#post_id").val();
            let _url = "{{ route('admin.get_api.clear_percents') }}";
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                timeout: 600000,
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
                    // console.log(res)
                    // $("#btn-clear").attr('disabled', "true");
                    $('#table_crud tbody').empty();
                    $.each(res.data, function(key, value) {
                        // console.log(value);
                        $('#table_crud tbody').prepend("<tr align='center' id='row_" +
                            value.id + "'" +
                            "role='button'  data-href='/admin/clear_percent_detail/history/view/" +
                            value.id + "'" +
                            ">" +
                            "<th id='td_choese" +
                            "' class='align-middle' hidden='true'>" +
                            "<div align='center'>" +
                            "<input type='checkbox' class='form-check' name='select'" +
                            "id='select_input' value='" + value.id + "'>" +
                            "</div>" +
                            "</th>" +

                            "<td class='align-middle'>" + value.id + "</td>" +
                            "<td class='align-middle'>" + value.username +
                            "</td>" +
                            "<td class='align-middle'>" + value.telephone +
                            "</td>" +
                            "<td class='align-middle'>" + value.share_percentage +
                            "</td>" +
                            "<td class='align-middle'>" + value.created_at2 +
                            "</td>" +
                            "</tr>"
                        );
                    });
                    Swal.fire(
                        'สำเร็จ!',
                        'ข้อมูลของท่านถูกบันทึกเรียบร้อยแล้ว',
                        'success'
                    );
                    clear_ms_error();
                },
                error: function(err) {
                    Swal.fire(
                        'ไม่สำเร็จ!',
                        'กรุณาลองใหม่อีกครั้ง',
                        'error'
                    );
                    console.log("ไม่สำเร็จ");
                    clear_ms_error();
                    $('#date_startError').text(err.responseJSON.errors.date_start);
                    $('#date_endError').text(err.responseJSON.errors.date_end);
                }
            });
        }


        function clear_ms_error() {
            $('#date_endError').text("");
            $('#date_startError').text("");
        }
    </script>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">ประวัติเคลียร์ยอด</h3>
                            </div>
                            <div class="col-md-6" align="right">
                                <a href="{{ url()->previous() }}" class="btn btn-success">ย้อนกลับ</a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <form id="form_first">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_start">เริ่มวันที่</label>
                                        <input type="date" name="date_start" id="date_start" class="form-control"
                                            onchange="$('#date_end').attr('disabled', false); $('#date_end').val('');">
                                        <span id="date_startError" class="alert-message text-danger"></span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_end">ถึงวันที่</label>
                                        <input type="date" name="date_end" id="date_end" class="form-control"
                                            onclick="date_dont_back()" disabled>
                                        <span id="date_endError" class="alert-message text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div align="right" class="mb-2">
                            <button class="btn btn-primary" onclick="createPost()">ค้นหา</button>
                        </div>

                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table_crud">
                                    <thead>
                                        <tr align="center">
                                            <th scope="col">ชื่อ</th>
                                            <th scope="col">ชื่อผู้ใช้</th>
                                            <th scope="col">เบอร์โทร</th>
                                            <th scope="col">เปอร์เซนหุ้น</th>
                                            <th scope="col">ทำรายการเมื่อ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="d-flex justify-content-center">
                            {!! $users->links() !!}
                        </div> --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
            function date_dont_back() {
                $('#date_end').attr('min', $('#date_start').val());
            }
        </script>
        <script>
            jQuery(document).ready(function($) {
                $(".table").on("click", "tr[role=\"button\"]", function(e) {
                    window.location = $(this).data("href");
                });
            });
        </script>

    @endsection
