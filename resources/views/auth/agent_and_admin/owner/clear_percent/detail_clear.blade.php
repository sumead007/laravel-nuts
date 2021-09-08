@extends('layouts.agent_and_admin.app')

@section('content')
    <script>
        function createPost(data_arr, data_money, data_agentID) {
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
                    // var form = $('#form_first')[0];
                    // var data = new FormData(form);
                    // var id = $("#post_id").val();
                    let _url = "{{ route('admin.clear_percent_detail.store') }}";
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        timeout: 600000,
                        url: _url,
                        type: "POST",
                        data: {
                            data: data_arr,
                            money: data_money,
                            agent_id: data_agentID
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            // console.log(res)
                            $("#table_crud #tf_total th:nth-child(3)").html(
                                "<b class='text-success'>เคลีย์บิลเรียบร้อย</b>");
                            $("#btn-clear").attr('disabled', "true");
                            Swal.fire(
                                'สำเร็จ!',
                                'ข้อมูลของท่านถูกบันทึกเรียบร้อยแล้ว',
                                'success'
                            )
                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                            Swal.fire(
                                'ไม่สำเร็จ!',
                                'กรุณาลองใหม่อีกครั้ง',
                                'error'
                            )
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
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">เคลียร์ยอด คุณ: {{ $agent->username }}</h3>
                            </div>
                            <div class="col-md-6" align="right">
                                <a href="{{ url()->previous() }}" class="btn btn-success">ย้อนกลับ</a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table_crud">
                                <thead>
                                    <tr align="center" class="bg-info">
                                        <th class="align-middle" rowspan="2">ชื่อ</th>
                                        <th class="align-middle" rowspan="2">ชื่อผู้ใช้</th>
                                        <th class="align-middle" rowspan="2">เบอร์โทร</th>
                                        <th colspan="4">ยอดผู้เล่น</th>
                                    </tr>
                                    <tr align="center">
                                        <th class="bg-danger">ยอดผู้เล่น(ได้)</th>
                                        <th class="bg-success">ยอดผู้เล่น(เสีย)</th>
                                        <th class="bg-warning">ค่าน้ำ</th>
                                        <th class="bg-secondary">รวมยอดผู้เล่น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $all_total = 0;
                                        $bets_id = [];
                                        $percent_money = 0;
                                        $percent_money_owner = 0;
                                    @endphp
                                    @foreach ($users as $user)
                                        @php
                                            $money_deducted_first = 0;
                                        @endphp
                                        <tr align="center" id="row_{{ $user->id }}">
                                            <td class="align-middle">
                                                {{ $user->name }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $user->username }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $user->telephone }}
                                            </td>
                                            <td class="align-middle bet_win">
                                                @php
                                                    $bet_win = 0;
                                                @endphp
                                                @foreach ($user->bet_detail as $bet_detail)
                                                    @if ($bet_detail->status == 1 && $bet_detail->clear_percent == null)
                                                        {{-- {{ $bet_detail->money }} --}}
                                                        @php
                                                            $bet_win += $bet_detail->money;
                                                            $bets_id[] = $bet_detail->id;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <b>
                                                    {{ $bet_win }}
                                                </b>

                                            </td>
                                            <td class="align-middle bet_lose">
                                                @php
                                                    $bet_lose = 0;
                                                @endphp
                                                @foreach ($user->bet_detail as $bet_detail)
                                                    @if ($bet_detail->status == 2 && $bet_detail->clear_percent == null)
                                                        {{-- {{ $bet_detail->money }} --}}
                                                        @php
                                                            $bet_lose += $bet_detail->money;
                                                            $bets_id[] = $bet_detail->id;
                                                            $money_deducted_first += $bet_detail->money_deducted_first;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <b>
                                                    {{ $bet_lose }}
                                                </b>
                                            </td>
                                            <td class="align-middle money_deducted_first">
                                                {{ $money_deducted_first }}
                                            </td>
                                            <td class="align-middle bet_total">
                                                @php
                                                    $bet_total = $bet_win - $bet_lose;
                                                    $all_total += $bet_total;
                                                @endphp
                                                <b>
                                                    {{ $bet_total }}
                                                </b>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-warning">
                                    <tr align="center">
                                        <th colspan="3">รวมทั้งหมด</th>
                                        <th id="total_bet_win">0</th>
                                        <th id="total_bet_lose">0</th>
                                        <th id="total_bet_money_deducted_first">0</th>
                                        <th id="total_bet_total">0</th>
                                    </tr>
                                    <tr align="center" id="tf_total">
                                        <th colspan="3">รวมแบ่งเปอร์เซนเอเย่น</th>
                                        <th colspan="3" class="text-danger">เปอร์เซนที่ได้ :
                                            {{ $agent->share_percentage }}%</th>
                                        <th>
                                            @if ($all_total > 0)
                                                <b class="text-danger"> ยอดผู้เล่นได้ เยอะกว่า</b>
                                            @else
                                                @php
                                                    $percent_money = ($agent->share_percentage / 100) * $all_total;
                                                    $percent_money_owner = $all_total - $percent_money;
                                                @endphp
                                                <b class="text-success">{{ $percent_money }}</b>
                                            @endif
                                        </th>
                                    </tr>
                                    <tr align="center">
                                        <th colspan="3">รวมแบ่งเปอร์เซนเจ้าของ</th>
                                        <th colspan="3" class="text-danger">เปอร์เซนที่ได้ :
                                            {{ 100 - $agent->share_percentage }}%</th>
                                        <th>
                                            @if ($percent_money_owner >= 0)
                                                <b class="text-danger">{{ $percent_money_owner }}</b>
                                            @else
                                                <b class="text-success">{{ $percent_money_owner }}</b>
                                            @endif
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if ($all_total >= 0)
                            <div align="right">
                                <button class="btn btn-primary" disabled id="btn-clear">เคลีย์บิลนี้</button>
                            </div>
                        @else
                            <div align="right">
                                <button class="btn btn-primary" id="btn-clear"
                                    onclick="createPost(@json($bets_id), @json($percent_money),  @json($agent->id))">เคลีย์บิลนี้</button>
                            </div>
                        @endif
                        {{-- <div class="d-flex justify-content-center">
                            {!! $users->links() !!}
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        var bet_win = 0;
        // iterate through each td based on class and add the values
        $(".bet_win").each(function() {
            var value = $(this).text();
            // add only if the value is number
            if (!isNaN(value) && value.length != 0) {
                bet_win += parseFloat(value);
            }
        });
        $("#total_bet_win").html("<b>" + bet_win + "</b>");

        var bet_lose = 0;
        // iterate through each td based on class and add the values
        $(".bet_lose").each(function() {
            var value = $(this).text();
            // add only if the value is number
            if (!isNaN(value) && value.length != 0) {
                bet_lose += parseFloat(value);
            }
        });
        $("#total_bet_lose").html("<b>" + bet_lose + "</b>");

        var bet_total = 0;
        // iterate through each td based on class and add the values
        $(".bet_total").each(function() {
            var value = $(this).text();
            // add only if the value is number
            if (!isNaN(value) && value.length != 0) {
                bet_total += parseFloat(value);
            }
        });
        $("#total_bet_total").html("<b>" + bet_total + "</b>");


        var bet_money_deducted_first = 0;
        // iterate through each td based on class and add the values
        $(".money_deducted_first").each(function() {
            var value = $(this).text();
            // add only if the value is number
            if (!isNaN(value) && value.length != 0) {
                bet_money_deducted_first += parseFloat(value);
            }
        });
        $("#total_bet_money_deducted_first").html("<b>" + bet_money_deducted_first + "</b>");
    </script>

@endsection
