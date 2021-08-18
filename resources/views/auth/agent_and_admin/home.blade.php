@extends('layouts.agent_and_admin.app')

@section('content')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

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
            $.ajax({
                url: "{{ route('admin.get.turn_on_turn_off') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    // console.log(res);
                    if (res.status == 0) {
                        Swal.fire(
                            'ไม่สำเร็จ!',
                            'กรุณาปิดให้เล่นก่อนถึงจะสามารถออกผลได้',
                            'error'
                        );
                    } else {
                        Swal.fire({
                            title: 'คุณแน่ใจใช่หรือไม่?',
                            text: "ยืนยันตัวเลข " + value + " ใช่หรือไม่?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ตกลง',
                            cancelButtonText: "ยกเลิก"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var _url = "{{ route('admin.home.result') }}";
                                $.ajax({
                                    url: _url,
                                    type: "POST",
                                    data: {
                                        value: value
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    },
                                    success: function(res) {
                                        // console.log("สำเร็จ");
                                        Swal.fire(
                                            'สำเร็จ!',
                                            'รายการของท่านสำเร็จ',
                                            'success'
                                        );
                                    },
                                    error: function(err) {
                                        console.log("ไม่สำเร็จ");
                                        Swal.fire(
                                            'ไม่สำเร็จ!',
                                            'รายการของท่านไม่สำเร็จ ต้องมีลูกค้าอย่างน้อย1เล่นเข้ามาก่อน',
                                            'error'
                                        );
                                    }
                                });
                            }
                        })
                    }
                },
                error: function(err) {
                    console.log("ไม่สำเร็จ");
                }
            });


        }
    </script>

    <script>
        function turn_on_turn_off(data) {
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
                    var _url = "{{ route('admin.home.turn_on_turn_off') }}";
                    $.ajax({
                        url: _url,
                        type: "POST",
                        data: {
                            value: data
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            // console.log("สำเร็จ");
                            Swal.fire(
                                'สำเร็จ!',
                                'รายการของท่านสำเร็จ',
                                'success'
                            );
                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                        }
                    });
                }
            })

        }
    </script>
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
                $("#text_status").html('ปิดให้เล่น');
                $("#text_status").attr('class', 'text-danger');
                $('#btn_close').attr("hidden", "hidden");
                $('#btn_open').removeAttr('hidden');
                // $("#tb_show_bets_now tbody").empty(); ลบแถวทั้งหมด
            } else {
                $("#text_status").html('เปิดให้เล่น');
                $("#text_status").attr('class', 'text-success');
                $('#btn_open').attr("hidden", "hidden");
                $('#btn_close').removeAttr('hidden');
                $("#tb_show_bets_now tbody").empty();
            }
        });

        var channel2 = pusher.subscribe('channel-result');
        channel2.bind('event-result', function(res) {
            var no_last_tr = parseInt($('#tb_history tr:last td:nth-child(1)').html()) + 1;
            no_last_tr = no_last_tr || 1;
            // console.log(res);
            $("#tb_history tbody").append(
                "<tr align='center'>" +
                "<td>" + no_last_tr + "</td>" +
                "<td>" + "ยังไม่มี" + "</td>" +
                "<td>" + res.data.result + "</td>" +
                "</tr>");

            $('#tb_show_bets_now tr').each(function() {
                var number = parseInt($(this).find('td:nth-child(2)').html());
                if (number == res.data.result) {
                    $(this).find('td:nth-child(4)').html("<b class='text-success'>ถูกรางวัล</b>")
                } else {
                    $(this).find('td:nth-child(4)').html("<b class='text-danger'>เสีย</b>")
                }
            });
        });

        var channel3 = pusher.subscribe('channel-show-bets-now');
        channel3.bind('event-show-bets-now', function(res) {
            console.log(res);
            ///ทำผล
            $("#tb_show_bets_now tbody").prepend(
                "<tr align='center' id='row_" + res.data.id + "'>" +
                "<td>" + res.data.username + "</td>" +
                "<td>" + res.data.number + "</td>" +
                "<td>" + res.data.money + "</td>" +
                "<td>" + "รอผล" + "</td>" +
                "</tr>");
        });
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
                <h3 align="center" class="text-light">รายชื่อคนแทง (รอบนี้)</h3>
                <div class="table-responsive" style="height: 300px;">
                    <table class="table table-light table-hover table-bordered" id="tb_show_bets_now">
                        <thead>
                            <tr align="center">
                                <th>ชื่อผู้ใช้</th>
                                <th>ทายเลข</th>
                                <th>จำนวนเงิน</th>
                                <th>ผลได้เสีย</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bet_details as $bet_detail)
                                <tr align="center">
                                    <td>{{ $bet_detail->username }}</td>
                                    <td>{{ $bet_detail->number }}</td>
                                    <td>{{ $bet_detail->money }}</td>
                                    <td>
                                        @if ($bet_detail->status == 0)
                                            <b>รอผล</b>
                                        @elseif ($bet_detail->status == 1)
                                            <b class="text-success">ถูกรางวัล</b>

                                        @elseif ($bet_detail->status == 2)
                                            <b class="text-danger">เสีย</b>

                                        @else
                                            <b class="text-danger">ถูกยกเลิก</b>
                                        @endif
                                    </td>
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
                        <p class="text-white">ผล</p>
                    </div>
                </div>
                <div class="row pl-3 pr-3 content-bet">
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between">
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
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">สถานะ:
                                @if ($config_turn_on_turn_off->status == 0)
                                    <span class="text-success" id="text_status">เปิดให้เล่น</span>
                                @else
                                    <span class="text-danger" id="text_status">ปิดให้เล่น</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div align="center">
                                    @if ($config_turn_on_turn_off->status == 1)
                                        <button type="button" class="btn btn-success" id="btn_open"
                                            onclick="turn_on_turn_off(0)">เปิดให้เล่น</button>
                                    @else
                                        <button type="button" class="btn btn-success" id="btn_open" hidden
                                            onclick="turn_on_turn_off(0)">เปิดให้เล่น</button>
                                    @endif

                                    @if ($config_turn_on_turn_off->status == 0)
                                        <button type="button" class="btn btn-danger" id="btn_close"
                                            onclick="turn_on_turn_off(1)">ปิดให้เล่น</button>
                                    @else
                                        <button type="button" class="btn btn-danger" id="btn_close" hidden
                                            onclick="turn_on_turn_off(1)">ปิดให้เล่น</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pl-3 pr-3">
                    <div class="col-md-12 footer-bet">
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <h3 align="center" class="text-light">ประวัติ</h3>
                <div class="table-responsive" style="height: 150px;">
                    <table class="table table-light table-hover table-bordered" id="tb_history">
                        <thead>
                            <tr align="center">
                                <th>เปิดที่</th>
                                <th>หน้าเต๋า</th>
                                <th>ผลถั่ว</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{ $no_result = 1 }}
                            @foreach ($results as $result)
                                <tr align="center">
                                    <td>{{ $no_result++ }}</td>
                                    <td>{{ $result->pic }}</td>
                                    <td>{{ $result->result }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
