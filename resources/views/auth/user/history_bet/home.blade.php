@extends('layouts.user.app')

@section('content')
    <script>
        async function createPost() {
            var agent = 0,
                owner = 0;
            var form = $('#form_first')[0];
            var data = new FormData(form);
            // var id = $("#post_id").val();
            let _url = "{{ route('user.get_api.bet_history') }}";
            var lose_total = 0;
            var win_total = 0;
            var sum = 0;
            await $.ajax({
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
                    console.log(res)
                    // $("#btn-clear").attr('disabled', "true");
                    $('#table_crud tbody').empty();
                    $.each(res.data, function(key, value) {
                        var status = "กำลังเดิมพัน";
                        if (value.status == 1) {
                            status = "<b class='text-success'>ถูกรางวัล</b>";
                            win_total += value.money;
                        } else if (value.status == 2) {
                            status = "<b class='text-danger'>เสีย</b>";
                            lose_total += value.money;
                        }
                        // console.log(value.money);
                        $('#table_crud tbody').prepend("<tr align='center' id='row_" +
                            value.id + "'>" +
                            "<td class='align-middle'>" + value.number + "</td>" +
                            "<td class='align-middle'>" + value.money + "</td>" +
                            "<td class='align-middle'>" + status + "</td>" +
                            "<td class='align-middle'>" + value.created_at2 + "</td>" +
                            "</tr>"
                        );
                    });

                    sum = win_total - lose_total;
                    $("#sum").html("<b>" + sum + "</b>");
                    $("#win_total").html(win_total);
                    $("#lose_total").html(lose_total);
                    if (sum < 0) $("#sum").attr("class", "text-danger");
                    Swal.fire(
                        'สำเร็จ!',
                        'รายการของท่านสำเร็จ',
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
    <script>
        function date_dont_back() {
            $('#date_end').attr('min', $('#date_start').val());
        }
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('ประวัติเดิมพัน') }}</div>

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
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif
                        <h3>ประวัติเดิมพัน</h3>
                        <div class="table-responsive">
                            <table class="table table-head-fixed text-nowrap p-0" id="table_crud">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th>แทงเลข</th>
                                        <th>ราคา</th>
                                        <th>ผลได้เสีย</th>
                                        <th>ทำรายการเมื่อ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot class="thead-dark">
                                    <tr align="center">
                                        <th colspan="3">รวมยอดได้</th>
                                        <th>
                                            <b class="text-success" id="win_total">
                                                0
                                            </b>
                                        </th>
                                    </tr>
                                    <tr align="center">
                                        <th colspan="3">รวมยอดเสีย</th>
                                        <th>
                                            <b class="text-danger" id="lose_total">
                                                0
                                            </b>
                                        </th>
                                    </tr>
                                    <tr align="center">
                                        <th colspan="3">รวมกำไร(ขาดทุนสุทธิ)</th>
                                        <th>
                                            <b class="text-success" id="sum">
                                                0
                                            </b>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{-- {!! $histories->links() !!} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
