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

                            // console.log($("#number").val());
                            Swal.fire(
                                'สำเร็จ!',
                                'รายการของท่านสำเร็จ',
                                'success'
                            );
                            clear_input();
                            clear_msg_error();
                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                            Swal.fire(
                                'ไม่สำเร็จ!',
                                'รายการของท่านไม่สำเร็จ',
                                'error'
                            );

                            clear_msg_error();
                            show_msg_error(err);
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <iframe
                    src="https://iframe.dacast.com/live/2caf41ba-8b27-6b01-8dd4-cb7836c8b139/22eb88e2-0a54-e856-abc4-18f3b86728db"
                    width="100%" height="100%" frameborder="0" scrolling="no" allow="autoplay" allowfullscreen
                    webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
            </div>
            <div class="col-md-4  p-4 statistics">
                <h3 align="center" class="text-light">สถิติย้อนหลัง</h3>
                <div class="table-responsive" style="height: 300px;">
                    <table class="table table-light table-hover table-bordered" id="tb_history">
                        <thead>
                            <tr align="center">
                                <th>เปิดที่</th>
                                <th>หน้าเต๋า</th>
                                <th>ผลถั่ว</th>
                                {{-- <th>เมื่อ</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr align="center">
                                    <td>ยังไม่มี</td>
                                    <td>{{ $result->pic }}</td>
                                    <td>{{ $result->result }}</td>
                                    {{-- <td> {{ Carbon\Carbon::parse($result->created_at)->locale('th')->diffForHumans() }}
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row pl-3 pr-3 mt-4 header-bet">
                    <div class="col-md-12">
                        <p class="text-white">เดิมพัน</p>
                    </div>
                </div>
                <div class="row pl-3 pr-3 content-bet">
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
                                            <input type="number" class="form-control" id="money" placeholder="ใส่จำนวนเงิน"
                                                name="money">
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
                                            <input type="number" class="form-control" id="money" placeholder="ใส่จำนวนเงิน"
                                                name="money">
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
            </div>
            <div class="col-md-4 mt-4">
                <h3 align="center" class="text-light">ประวัติ</h3>
                <div class="table-responsive" style="height: 150px;">
                    <table class="table table-light table-hover table-bordered">
                        <thead>
                            <tr align="center">
                                <th>แทงเลข</th>
                                <th>ราคา</th>
                                <th>ผลได้เสีย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <td>5</td>
                                <td>100</td>
                                <td>เสีย</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
            $("#tb_history tbody").prepend(
                "<tr align='center'>" +
                "<td>ยังไม่ใส่</td>" +
                "<td>" + "ยังไม่ใส่" + "</td>" +
                "<td>" + res.data.result + "</td>" +
                "</tr>");
        });
    </script>

@endsection
