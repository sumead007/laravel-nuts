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
            background-image: url("{{ asset('images/background/7.png') }}");
            margin: auto;
            padding: 10px;
            height: 100%;
            width: 100%;
            background-repeat: no-repeat;
            /* background-size: 100%; */
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
        async function click_select(value) {
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
                            '???????????????????????????!',
                            '??????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
                            'error'
                        );
                    } else {
                        Swal.fire({
                            title: '?????????????????????',
                            html: '?????????????????????????????????1<select id="swal-input1" class="swal2-input">' +
                                '<option value="1">1</option>' +
                                '<option value="2">2</option>' +
                                '<option value="3">3</option>' +
                                '<option value="4">4</option>' +
                                '<option value="5">5</option>' +
                                '<option value="6">6</option>' +
                                '</select><br>' +
                                '?????????????????????????????????2<select id="swal-input2" class="swal2-input">' +
                                '<option value="1">1</option>' +
                                '<option value="2">2</option>' +
                                '<option value="3">3</option>' +
                                '<option value="4">4</option>' +
                                '<option value="5">5</option>' +
                                '<option value="6">6</option>' +
                                '</select><br>' +
                                '?????????????????????????????????3<select id="swal-input3" class="swal2-input">' +
                                '<option value="1">1</option>' +
                                '<option value="2">2</option>' +
                                '<option value="3">3</option>' +
                                '<option value="4">4</option>' +
                                '<option value="5">5</option>' +
                                '<option value="6">6</option>' +
                                '</select><br>',
                            // allowOutsideClick: false,

                            preConfirm: () => {
                                return new Promise(function(resolve) {
                                    // Validate input
                                    if ($('#swal-input1').val() == '' || $(
                                            '#swal-input2').val() == '' || $(
                                            '#swal-input3').val() == '') {
                                        swal.showValidationMessage(
                                            "????????????????????????????????????????????? ?????????????????????????????????????????????????????????????????? 1-6"
                                        ); // Show error when validation fails.
                                        // swal.enableConfirmButton() // Enable the confirm button again.
                                    } else {
                                        var floor = Math.floor;
                                        var swal_input1 = $('#swal-input1').val();
                                        var swal_input2 = $('#swal-input2').val();
                                        var swal_input3 = $('#swal-input3').val();
                                        var input1 = floor(swal_input1);
                                        var input2 = floor(swal_input2);
                                        var input3 = floor(swal_input3);
                                        // console.log(input1, input2, input3);
                                        if ((input1 > 0 && input1 <= 6) && (input2 >
                                                0 && input2 <= 6) && (input3 > 0 &&
                                                input3 <= 6)

                                        ) {
                                            swal
                                                .resetValidationMessage(); // Reset the validation message.
                                            resolve({
                                                input1: input1,
                                                input2: input2,
                                                input3: input3,
                                            });
                                        } else {
                                            swal.showValidationMessage(
                                                "????????????????????????????????????????????????????????????????????? 1-6"
                                            );
                                            return;
                                        }
                                    }
                                })
                            },
                        }).then(function(result) {
                            var input1 = result.value.input1;
                            var input2 = result.value.input2;
                            var input3 = result.value.input3;
                            // If validation fails, the value is undefined. Break out here.
                            if (typeof(result.value) == 'undefined') {
                                return false;
                            }
                            console.log(result);
                            Swal.fire({
                                title: '???????????????????????????????????????????????????????',
                                text: "???????????????????????????????????? " + value +
                                    " ??????????????????????????????? ????????????????????????????????????????????????" + result.value.input1 +
                                    ", " + result.value.input2 + ", " + result.value.input3,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '????????????',
                                cancelButtonText: "??????????????????"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var _url = "{{ route('admin.home.result') }}";
                                    $.ajax({
                                        url: _url,
                                        type: "POST",
                                        data: {
                                            value: value,
                                            input1: input1,
                                            input2: input2,
                                            input3: input3,
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': $(
                                                    'meta[name="csrf-token"]')
                                                .attr(
                                                    'content')
                                        },
                                        success: function(res) {
                                            // console.log("??????????????????");
                                            Swal.fire(
                                                '??????????????????!',
                                                '?????????????????????????????????????????????????????????',
                                                'success'
                                            );
                                        },
                                        error: function(err) {
                                            console.log("???????????????????????????");
                                            Swal.fire(
                                                '???????????????????????????!',
                                                '?????????????????????????????????????????????????????????????????? ???????????????????????????????????????????????????????????????1??????????????????????????????????????????',
                                                'error'
                                            );
                                        }
                                    });
                                }
                            })
                        }).catch(swal.noop)
                    }
                },
                error: function(err) {
                    console.log("???????????????????????????");
                }
            });


        }
    </script>

    <script>
        function turn_on_turn_off(data) {
            Swal.fire({
                title: '???????????????????????????????????????????????????????',
                text: "??????????????????????????????????????????????????????.. ?????????????????????????????????????????????????",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '????????????',
                cancelButtonText: "??????????????????"
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
                            // console.log("??????????????????");
                            Swal.fire(
                                '??????????????????!',
                                '?????????????????????????????????????????????????????????',
                                'success'
                            );
                        },
                        error: function(err) {
                            console.log("???????????????????????????");
                            console.log(err);
                            // console.log(err.message);
                            Swal.fire(
                                '???????????????????????????!',
                                err.responseJSON.message,
                                'error'
                            );
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
                $("#text_status").html('??????????????????????????????');
                $("#text_status").attr('class', 'text-danger');
                $('#btn_close').attr("hidden", "hidden");
                $('#btn_open').removeAttr('hidden');
                // $("#tb_show_bets_now tbody").empty(); ????????????????????????????????????
            } else {
                $("#text_status").html('?????????????????????????????????');
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
                "<td>" +
                "<img src='" + res.data.path1 + "' alt='" + res.data.path1 + "' height='50px' width='50px'>" +
                "<img src='" + res.data.path2 + "' alt='" + res.data.path2 + "' height='50px' width='50px'>" +
                "<img src='" + res.data.path3 + "' alt='" + res.data.path3 + "' height='50px' width='50px'>" +
                "</td>" +
                "<td>" + res.data.result + "</td>" +
                "</tr>");

            $('#tb_show_bets_now tr').each(function() {
                var number = parseInt($(this).find('td:nth-child(2)').html());
                if (number != res.data.result) {
                    $(this).find('td:nth-child(4)').html("<b class='text-success'>???????????????????????????</b>")
                } else {
                    $(this).find('td:nth-child(4)').html("<b class='text-danger'>????????????</b>")
                }
            });
        });

        var channel3 = pusher.subscribe('channel-show-bets-now');
        channel3.bind('event-show-bets-now', function(res) {
            console.log(res);
            ///????????????
            $("#tb_show_bets_now tbody").prepend(
                "<tr align='center' id='row_" + res.data.id + "'>" +
                "<td>" + res.data.username + "</td>" +
                "<td>" + res.data.number + "</td>" +
                "<td>" + res.data.money + "</td>" +
                "<td>" + "????????????" + "</td>" +
                "</tr>");
        });
    </script>

    <div class="container-fluid">
        <div class="row justify-content-center bet-sections">
            <div class="col-md-8">
                <iframe
                    src="https://iframe.dacast.com/live/2caf41ba-8b27-6b01-8dd4-cb7836c8b139/22eb88e2-0a54-e856-abc4-18f3b86728db"
                    frameborder="0" scrolling="no" allow="autoplay" allowfullscreen webkitallowfullscreen mozallowfullscreen
                    oallowfullscreen msallowfullscreen></iframe>
            </div>

            <div class="col-md-4  p-4 statistics">
                <h3 align="center" class="text-light">???????????????????????????????????? (??????????????????)</h3>
                <div class="table-responsive" style="height: 300px;">
                    <table class="table table-light table-hover table-bordered" id="tb_show_bets_now">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>??????????????????????????????</th>
                                <th>??????????????????</th>
                                <th>???????????????????????????</th>
                                <th>???????????????????????????</th>
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
                                            <b>????????????</b>
                                        @elseif ($bet_detail->status == 1)
                                            <b class="text-success">???????????????????????????</b>

                                        @elseif ($bet_detail->status == 2)
                                            <b class="text-danger">????????????</b>

                                        @else
                                            <b class="text-danger">???????????????????????????</b>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-8">
                @if (Auth::guard('admin')->user()->position == 0)

                    <div class="row pl-3 pr-3 mt-4 header-bet">
                        <div class="col-md-12">
                            <p class="text-white">??????</p>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 content-bet vertical-align" style="height:100%;border:1px solid #925415">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-xl-3 col-6">
                                    <img src="{{ asset('images/btn/1.png') }}" alt="{{ asset('images/btn/1.png') }}"
                                        onclick="click_select(1)" role="button" class="imgBox align-middle-center">
                                </div>

                                <div class="col-xl-3 col-6">
                                    <img src="{{ asset('images/btn/2.png') }}" alt="{{ asset('images/btn/2.png') }}"
                                        onclick="click_select(2)" role="button" class="imgBox align-middle-center">
                                </div>

                                <div class="col-xl-3 col-6">
                                    <img src="{{ asset('images/btn/3.png') }}" alt="{{ asset('images/btn/3.png') }}"
                                        onclick="click_select(3)" role="button" class="imgBox align-middle-center">
                                </div>

                                <div class="col-xl-3 col-6">
                                    <img src="{{ asset('images/btn/4.png') }}" alt="{{ asset('images/btn/4.png') }}"
                                        onclick="click_select(4)" role="button" class="imgBox align-middle-center">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success mb-3" style="max-width: 18rem;">
                                <div class="card-header bg-transparent border-success">???????????????:
                                    @if ($config_turn_on_turn_off->status == 0)
                                        <span class="text-success" id="text_status">?????????????????????????????????</span>
                                    @else
                                        <span class="text-danger" id="text_status">??????????????????????????????</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div align="center">
                                        @if ($config_turn_on_turn_off->status == 1)
                                            <button type="button" class="btn btn-success" id="btn_open"
                                                onclick="turn_on_turn_off(0)">?????????????????????????????????</button>
                                        @else
                                            <button type="button" class="btn btn-success" id="btn_open" hidden
                                                onclick="turn_on_turn_off(0)">?????????????????????????????????</button>
                                        @endif

                                        @if ($config_turn_on_turn_off->status == 0)
                                            <button type="button" class="btn btn-danger" id="btn_close"
                                                onclick="turn_on_turn_off(1)">??????????????????????????????</button>
                                        @else
                                            <button type="button" class="btn btn-danger" id="btn_close" hidden
                                                onclick="turn_on_turn_off(1)">??????????????????????????????</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mt-5">
                        {{-- <div class="col-md-12 footer-bet">
                    </div> --}}
                    </div>
                @endif
            </div>
            <div class="col-md-4 mt-4">
                <h3 align="center" class="text-light">?????????????????????</h3>
                <div class="table-responsive" style="height: 200px;">
                    <table class="table table-light table-hover table-bordered" id="tb_history">
                        <thead class="thead-dark">
                            <tr align="center">
                                <th>?????????????????????</th>
                                <th>????????????????????????</th>
                                <th>??????????????????</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no_result = 1;
                            @endphp
                            @foreach ($results as $result)
                                <tr align="center">
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
@endsection
