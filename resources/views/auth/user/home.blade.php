@extends('layouts.user.app')

@section('content')

    <style>
        tbody {
            height: 10pc;
            /* Just for the demo          */
            overflow-y: auto;
            /* Trigger vertical scroll    */
            /* overflow-x: hidden; */
            /* Hide the horizontal scroll */

        }

        body {
            margin: 0;
        }

        .statistics {
            background-image: url('{{ asset('images/background/1.jpg') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-size: 100%;
        }



        .header-bet {
            background-image: linear-gradient(#925415, #3A1D1D);
            height: 40px;
            margin: auto;
            padding: 10px;
        }

        .content-bet {
            background-color: #A27140;
            margin: auto;
            padding: 10px;
            /* height: 100%; */
        }

        .footer-bet {
            background-image: linear-gradient(#925415, #3A1D1D);
            height: 40px;
        }

        .vertical-align {
            display: flex;
            align-items: center;
        }

    </style>

    <style>
        .imgBox:hover {
            -moz-box-shadow: 0 0 10px #ccc;
            -webkit-box-shadow: 0 0 10px #ccc;
            box-shadow: 0 0 10px #ccc;
        }

    </style>

    <style>
        .align-middle-center {
            margin: auto;
            width: 25%;
            padding: 10px;
        }

        iframe {
            width: 100%;
            height: 500px;
        }

        .imgBox {
            width: 100%;
            height: auto;
        }

        @media only screen and (max-width: 600px) {

            iframe {
                width: 100%;
                height: 100vw;
            }

            .bet-sections> :nth-child(1) {
                order: 1;
            }

            .bet-sections> :nth-child(2) {
                order: 3;
            }

            .bet-sections> :nth-child(3) {
                order: 2;
            }

            .bet-sections> :nth-child(4) {
                order: 4;
            }
        }

    </style>

    <script>
        function click_select(value) {
            $("#show_number_selected").html(value);
            $("#number").val(value);
        }
    </script>

    <script>
        function form_submit() {

            Swal.fire({
                title: 'คุณแน่ใจใช่หรือไม่?',
                text: "คุณแน่ใจใช่หรือไม่.. ที่จะทำรายการนี้?",
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
                    var _url = "{{ route('user.bet') }}";
                    // console.log(data);
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
                            console.log(res);

                            // console.log($("#number").val());
                            Swal.fire(
                                'สำเร็จ!',
                                'รายการของท่านสำเร็จ',
                                'success'
                            );
                            clear_input();
                            clear_msg_error();
                            var string_status;
                            if (res.data.status == 0) {
                                string_status = "รอผล";
                            } else if (res.data.status == 1) {
                                string_status = "ถูกรางวัล";
                            } else if (res.data.status == 2) {
                                string_status = "เสีย";
                            } else {
                                string_status = "ถูกยกเลิก";
                            }
                            $('#tb_history_bet tbody').prepend("<tr align='center' data-bet_id='" + res
                                .data
                                .bet_id + "'" +
                                ">" +
                                "<td>" + "" + "</td>" +
                                "<td>" + res.data.number + "</td>" +
                                "<td>" + res.data.money + "</td>" +
                                "<td> <b>" + string_status + "</b></td>" +
                                "<td>" + res.data.created_at2 + "</td>" +
                                "</tr>"
                            );
                            $("#show_money").val(res.money);
                        },
                        error: function(err) {

                            if (err.status == 403) {
                                Swal.fire(
                                    'ไม่สำเร็จ!',
                                    'จำนวนเงินของท่านไม่เพียงพอ',
                                    'error'
                                );
                            } else if (err.status == 422) {
                                console.log("ไม่สำเร็จ");
                                Swal.fire(
                                    'ไม่สำเร็จ!',
                                    'รายการของท่านไม่สำเร็จ',
                                    'error'
                                );

                                clear_msg_error();
                                show_msg_error(err);
                            } else {
                                console.log("ไม่สำเร็จ");
                                Swal.fire(
                                    'ไม่สำเร็จ!',
                                    'รายการของท่านไม่สำเร็จ กรุณาลองรีเฟชหน้าเว็บใหม่อีกครั้ง',
                                    'error'
                                );
                            }
                        }
                    });
                }
            })
        }

        function show_msg_error(err) {
            $('#numberError').text(err.responseJSON.errors.number);
            $('#moneyError').text(err.responseJSON.errors.money);
        }

        function clear_input() {
            $("#number").val('');
            $("#show_number_selected").html('ยังไม่เลือก');
            $("#money").val('');

        }

        function clear_msg_error() {
            $('#numberError').text("");
            $('#moneyError').text("");
        }
    </script>

    <div class="container-fluid">
        <div class="row justify-content-center bet-sections">
            <div class="col-md-8">
                <iframe
                    src="https://iframe.dacast.com/live/2caf41ba-8b27-6b01-8dd4-cb7836c8b139/22eb88e2-0a54-e856-abc4-18f3b86728db"
                    frameborder="0" scrolling="no" allow="autoplay" allowfullscreen webkitallowfullscreen mozallowfullscreen
                    oallowfullscreen msallowfullscreen>
                </iframe>
            </div>

            <div class="col-md-4 p-4 statistics">
                <h3 align="center" class="text-light">สถิติย้อนหลัง</h3>
                <div class="table-responsive" style="height: 300px;">
                    <table class="table table-light table-hover table-bordered" id="tb_history">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>เปิดที่</th>
                                <th>หน้าเต๋า</th>
                                <th>ผลถั่ว</th>
                                {{-- <th>เมื่อ</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no_result = 1;
                            @endphp
                            @foreach ($results as $result)
                                <tr align="center" id="row_result_{{ $result->bet_id }}">
                                    <td>{{ $no_result++ }}</td>
                                    <td>
                                        <img src="{{ asset($result->path1) }}" alt="{{ asset($result->path1) }}"
                                            height="50px" width="50px">
                                        <img src="{{ asset($result->path2) }}" alt="{{ asset($result->path2) }}"
                                            height="50px" width="50px">
                                        <img src="{{ asset($result->path3) }}" alt="{{ asset($result->path3) }}"
                                            height="50px" width="50px">
                                    </td>
                                    <td>{{ $result->result }}</td>
                                    {{-- <td> {{ Carbon\Carbon::parse($result->created_at)->locale('th')->diffForHumans() }}
                                        </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row pl-3 pr-3 mt-4 header-bet">
                    <div class="col-md-12">
                        <p class="text-white">เดิมพัน</p>
                    </div>
                </div>
                <div class="row pl-3 pr-3 content-bet vertical-align" style="height:80%;border:1px solid #925415">
                    @if ($config_turn_on_turn_off->status == 0)
                        <div class="col-md-8" id="content-left">
                            <div class="d-flex justify-content-between" id="img-content-bet">
                                <div class="row">
                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/1.png') }}"
                                            alt="{{ asset('images/btn/1.png') }}" onclick="click_select(1)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>

                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/2.png') }}"
                                            alt="{{ asset('images/btn/2.png') }}" onclick="click_select(2)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>

                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/3.png') }}"
                                            alt="{{ asset('images/btn/3.png') }}" onclick="click_select(3)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>

                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/4.png') }}"
                                            alt="{{ asset('images/btn/4.png') }}" onclick="click_select(4)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-8" id="content-left" hidden>
                            <div class="d-flex justify-content-between" id="img-content-bet">
                                <div class="row">
                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/1.png') }}"
                                            alt="{{ asset('images/btn/1.png') }}" onclick="click_select(1)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>

                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/2.png') }}"
                                            alt="{{ asset('images/btn/2.png') }}" onclick="click_select(2)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>

                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/3.png') }}"
                                            alt="{{ asset('images/btn/3.png') }}" onclick="click_select(3)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>

                                    <div class="col-xl-3 col-6">
                                        <img src="{{ asset('images/btn/4.png') }}"
                                            alt="{{ asset('images/btn/4.png') }}" onclick="click_select(4)" role="button"
                                            class="imgBox align-middle-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($config_turn_on_turn_off->status == 1)
                        <h1 align="center" id="text-turn_off" class="align-middle-center text-danger">ปิดเดิมพัน
                            กรุณารอสักครู่.....</h1>
                    @else
                        <h1 align="center" id="text-turn_off" class="align-middle-center text-danger" hidden>ปิดเดิมพัน
                            กรุณารอสักครู่.....</h1>
                    @endif

                    @if ($config_turn_on_turn_off->status == 0)
                        <div class="col-md-4" id="content-right">
                            <div class="card">
                                <div class="card-body">
                                    <form id="form_first">
                                        <div class="form-group">
                                            <label for="money">ใส่จำนวนเงิน</label>
                                            <input type="number" class="form-control" id="money"
                                                placeholder="ใส่จำนวนเงิน" name="money">
                                        </div>
                                        <span id="moneyError" class="alert-message text-danger"></span>

                                        <div class="form-group">
                                            <label>ตัวเลขที่คุณเลือกคือ: <b id="show_number_selected"
                                                    class="text-danger">ยังไม่เลือก</b></label>
                                            <input type="hidden" class="form-control" id="number"
                                                placeholder="ยังไม่ได้เลือกตัวเลขเดิมพัน" name="number" readonly="true"
                                                value="">
                                        </div>
                                        <span id="numberError" class="alert-message text-danger"></span>

                                    </form>
                                    <div align="right">
                                        <button class="btn btn-primary" onclick="form_submit()">ยืนยัน</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-4" id="content-right" hidden>
                            <div class="card">
                                <div class="card-body">
                                    <form id="form_first">
                                        <div class="form-group">
                                            <label for="money">ใส่จำนวนเงิน</label>
                                            <input type="number" class="form-control" id="money"
                                                placeholder="ใส่จำนวนเงิน" name="money" min="1">
                                        </div>
                                        <span id="moneyError" class="alert-message text-danger"></span>

                                        <div class="form-group">
                                            <label>ตัวเลขที่คุณเลือกคือ: <b id="show_number_selected"
                                                    class="text-danger">ยังไม่เลือก</b></label>
                                            <input type="hidden" class="form-control" id="number"
                                                placeholder="ยังไม่ได้เลือกตัวเลขเดิมพัน" name="number" readonly="true"
                                                value="">
                                        </div>
                                        <span id="numberError" class="alert-message text-danger"></span>

                                    </form>
                                    <div align="right">
                                        <button class="btn btn-primary" onclick="form_submit()">ยืนยัน</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="row pl-3 pr-3">
                    <div class="col-md-12 footer-bet">
                    </div>
                </div>
                <br>
                <br>
                <br>
            </div>

            <div class="col-md-4 mt-4">
                <h3 align="center" class="text-light">ประวัติ</h3>
                <div class="table-responsive" style="height: 200px;">
                    <table class="table table-light table-hover table-bordered" id="tb_history_bet">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>เปิดที่</th>
                                <th>แทงเลข</th>
                                <th>ราคา</th>
                                <th>ผลได้เสีย</th>
                                <th>ทำรายการเมื่อ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $historie)
                                <tr align="center" data-bet_id="{{ $historie->bet_id }}">
                                    <td></td>
                                    <td>{{ $historie->number }}</td>
                                    <td>{{ $historie->money }}</td>
                                    <td>
                                        @if ($historie->status == 0)
                                            <b>รอผล</b>
                                        @elseif ($historie->status == 1)
                                            <b class="text-success">ถูกรางวัล</b>

                                        @elseif ($historie->status == 2)
                                            <b class="text-danger">เสีย</b>
                                        @else
                                            <b class="text-danger">ถูกยกเลิก</b>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($historie->created_at)->locale('th')->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            {{-- <div class="col-md-8">
                <div class="row pl-3 pr-3 mt-4 header-bet">
                    <div class="col-md-12">
                        <p class="text-white">เดิมพัน</p>
                    </div>
                </div>
                <div class="row pl-3 pr-3 content-bet vertical-align" style="height:80%;border:1px solid #925415">
                    @if ($config_turn_on_turn_off->status == 0)
                        <div class="col-md-8" id="content-left">
                            <div class="d-flex justify-content-between" id="img-content-bet">
                                <img src="{{ asset('images/btn/1.png') }}" alt="{{ asset('images/btn/1.png') }}"
                                    onclick="click_select(1)" role="button" class="imgBox align-middle-center">
                                <img src="{{ asset('images/btn/2.png') }}" alt="{{ asset('images/btn/2.png') }}"
                                    onclick="click_select(2)" role="button" class="imgBox align-middle-center">
                                <img src="{{ asset('images/btn/3.png') }}" alt="{{ asset('images/btn/3.png') }}"
                                    onclick="click_select(3)" role="button" class="imgBox align-middle-center">
                                <img src="{{ asset('images/btn/4.png') }}" alt="{{ asset('images/btn/4.png') }}"
                                    onclick="click_select(4)" role="button" class="imgBox align-middle-center">
                            </div>
                        </div>
                    @else
                        <div class="col-md-8" id="content-left" hidden>
                            <div class="d-flex justify-content-between" id="img-content-bet">
                                <img src="{{ asset('images/btn/1.png') }}" alt="{{ asset('images/btn/1.png') }}"
                                    onclick="click_select(1)" role="button" class="imgBox align-middle-center">
                                <img src="{{ asset('images/btn/2.png') }}" alt="{{ asset('images/btn/2.png') }}"
                                    onclick="click_select(2)" role="button" class="imgBox align-middle-center">
                                <img src="{{ asset('images/btn/3.png') }}" alt="{{ asset('images/btn/3.png') }}"
                                    onclick="click_select(3)" role="button" class="imgBox align-middle-center">
                                <img src="{{ asset('images/btn/4.png') }}" alt="{{ asset('images/btn/4.png') }}"
                                    onclick="click_select(4)" role="button" class="imgBox align-middle-center">
                            </div>
                        </div>
                    @endif

                    @if ($config_turn_on_turn_off->status == 1)
                        <h1 align="center" id="text-turn_off" class="align-middle-center text-danger">ปิดเดิมพัน
                            กรุณารอสักครู่.....</h1>
                    @else
                        <h1 align="center" id="text-turn_off" class="align-middle-center text-danger" hidden>ปิดเดิมพัน
                            กรุณารอสักครู่.....</h1>
                    @endif

                    @if ($config_turn_on_turn_off->status == 0)
                        <div class="col-md-4" id="content-right">
                            <div class="card">
                                <div class="card-body">
                                    <form id="form_first">
                                        <div class="form-group">
                                            <label for="money">ใส่จำนวนเงิน</label>
                                            <input type="number" class="form-control" id="money"
                                                placeholder="ใส่จำนวนเงิน" name="money">
                                        </div>
                                        <span id="moneyError" class="alert-message text-danger"></span>

                                        <div class="form-group">
                                            <label>ตัวเลขที่คุณเลือกคือ: <b id="show_number_selected"
                                                    class="text-danger">ยังไม่เลือก</b></label>
                                            <input type="hidden" class="form-control" id="number"
                                                placeholder="ยังไม่ได้เลือกตัวเลขเดิมพัน" name="number" readonly="true"
                                                value="">
                                        </div>
                                        <span id="numberError" class="alert-message text-danger"></span>

                                    </form>
                                    <div align="right">
                                        <button class="btn btn-primary" onclick="form_submit()">ยืนยัน</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-4" id="content-right" hidden>
                            <div class="card">
                                <div class="card-body">
                                    <form id="form_first">
                                        <div class="form-group">
                                            <label for="money">ใส่จำนวนเงิน</label>
                                            <input type="number" class="form-control" id="money"
                                                placeholder="ใส่จำนวนเงิน" name="money" min="1">
                                        </div>
                                        <span id="moneyError" class="alert-message text-danger"></span>

                                        <div class="form-group">
                                            <label>ตัวเลขที่คุณเลือกคือ: <b id="show_number_selected"
                                                    class="text-danger">ยังไม่เลือก</b></label>
                                            <input type="hidden" class="form-control" id="number"
                                                placeholder="ยังไม่ได้เลือกตัวเลขเดิมพัน" name="number" readonly="true"
                                                value="">
                                        </div>
                                        <span id="numberError" class="alert-message text-danger"></span>

                                    </form>
                                    <div align="right">
                                        <button class="btn btn-primary" onclick="form_submit()">ยืนยัน</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="row pl-3 pr-3">
                    <div class="col-md-12 footer-bet">
                    </div>
                </div>
                <br>
                <br>
                <br>
            </div>
            <div class="col-md-4 mt-4">
                <h3 align="center" class="text-light">ประวัติ</h3>
                <div class="table-responsive" style="height: 200px;">
                    <table class="table table-light table-hover table-bordered" id="tb_history_bet">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>เปิดที่</th>
                                <th>แทงเลข</th>
                                <th>ราคา</th>
                                <th>ผลได้เสีย</th>
                                <th>ทำรายการเมื่อ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $historie)
                                <tr align="center" data-bet_id="{{ $historie->bet_id }}">
                                    <td></td>
                                    <td>{{ $historie->number }}</td>
                                    <td>{{ $historie->money }}</td>
                                    <td>
                                        @if ($historie->status == 0)
                                            <b>รอผล</b>
                                        @elseif ($historie->status == 1)
                                            <b class="text-success">ถูกรางวัล</b>

                                        @elseif ($historie->status == 2)
                                            <b class="text-danger">เสีย</b>
                                        @else
                                            <b class="text-danger">ถูกยกเลิก</b>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($historie->created_at)->locale('th')->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>
    </div>


    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('212943a24c303cf7a3a3', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('turn_on-turn_off');
        channel.bind('event-turn_on-turn_off', function(data) {
            // alert(JSON.stringify(data));
            var status = data.status.status;
            if (status == "1") {
                $("#text-turn_off").removeAttr("hidden");
                $('#content-left, #content-right').attr("hidden", "hidden");

                // $("#content-left").attr("hidden").remove();
                // $("#content-right").attr("hidden").remove();
            } else {
                $('#text-turn_off').attr("hidden", "hidden");
                $('#content-left, #content-right').removeAttr("hidden");
            }
        });

        var channel2 = pusher.subscribe('channel-result');
        channel2.bind('event-result', function(res) {
            console.log(res);
            var no_last_tr = parseInt($('#tb_history tr:last td:nth-child(1)').html()) + 1;
            no_last_tr = no_last_tr || 1;
            var total = 0;
            $("#tb_history tbody").append(
                "<tr align='center' id='row_result_" + res.data.bet_id + "'>" +
                "<td>" + no_last_tr + "</td>" +
                "<td>" +
                "<img src='" + res.data.path1 + "' alt='" + res.data.path1 + "' height='50px' width='50px'>" +
                "<img src='" + res.data.path2 + "' alt='" + res.data.path2 + "' height='50px' width='50px'>" +
                "<img src='" + res.data.path3 + "' alt='" + res.data.path3 + "' height='50px' width='50px'>" +
                "</td>" +
                "<td>" + res.data.result + "</td>" +
                "</tr>");

            $('#tb_history_bet tr').each(function() {
                var bet_id = $(this).data('bet_id');
                if (bet_id == res.data.bet_id) {
                    if (parseInt($(this).find('td:nth-child(2)').html()) == res.data.result) {
                        $(this).find('td:nth-child(4)').html("<b class='text-success'>ถูกรางวัล</b>");
                        var money_col = parseInt($(this).find('td:nth-child(3)').html());
                        total += (money_col*2);
                    } else {
                        $(this).find('td:nth-child(4)').html("<b class='text-danger'>เสีย</b>");
                    }
                }
            });
            var old_money = parseInt($("#show_money").val());
            $("#show_money").val(old_money + total);

            //เปิดหัว
            $('#tb_history tbody tr').each(function() {
                var history_bet_id = $(this).attr('id');
                // console.log(history_bet_id);
                var outerThis = this;
                $('#tb_history_bet tr').each(function() {
                    var bet_id = "row_result_" + $(this).data('bet_id');
                    // console.log(history_bet_id, bet_id);
                    if (bet_id == history_bet_id && $(this).find('td:nth-child(1)').html() ==
                        "") {
                        $(this).find('td:nth-child(1)').html($(outerThis).find('td:nth-child(1)')
                            .html());
                    }
                    // else {
                    //     $(this).find('td:nth-child(1)').html("รอสักครู่");
                    // }
                });
            });

        });
    </script>
    <script>
        function head_open() {
            $('#tb_history tbody tr').each(function() {
                var history_bet_id = $(this).attr('id');
                // console.log(history_bet_id);
                var outerThis = this;
                $('#tb_history_bet tr').each(function() {
                    var bet_id = "row_result_" + $(this).data('bet_id');
                    // console.log(history_bet_id, bet_id);
                    if (bet_id == history_bet_id) {
                        $(this).find('td:nth-child(1)').html($(outerThis).find('td:nth-child(1)').html());
                    }
                    // else {
                    //     $(this).find('td:nth-child(1)').html("รอสักครู่");
                    // }
                });
            });
        }

        window.onload = function() {
            head_open();
        };
        // $('#tb_history_bet tr').each(function() {
        //     var bet_id = $(this).data('bet_id');
        //     if (bet_id == res.data.bet_id) {
        //         if (parseInt($(this).find('td:nth-child(1)').html()) == res.data.result) {
        //             $(this).find('td:nth-child(3)').html("<b class='text-success'>ถูกรางวัล</b>")
        //         } else {
        //             $(this).find('td:nth-child(3)').html("<b class='text-danger'>เสีย</b>")

        //         }
        //     }
        // });
    </script>
    <script>
        // Select your input element.
        var number = document.getElementById('money');

        // Listen for input event on numInput.
        number.onkeydown = function(e) {
            if (!((e.keyCode > 95 && e.keyCode < 106) ||
                    (e.keyCode > 47 && e.keyCode < 58) ||
                    e.keyCode == 8)) {
                return false;
            }
        }
    </script>

@endsection
