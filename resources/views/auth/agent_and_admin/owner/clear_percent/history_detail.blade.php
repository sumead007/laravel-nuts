@extends('layouts.agent_and_admin.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">เคลียร์ยอด คุณ:
                                    @foreach ($clear_percent->clear_percent_details as $clear_percent_detail)
                                    {{$clear_percent_detail->bet_details}}
                                    @endforeach
                                </h3>
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
                                        <th colspan="3">ยอดผู้เล่น</th>
                                    </tr>
                                    <tr align="center">
                                        <th class="bg-danger">ยอดผู้เล่น(ได้)</th>
                                        <th class="bg-success">ยอดผู้เล่น(เสีย)</th>
                                        <th class="bg-secondary">รวมยอดผู้เล่น</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @php
                                        $all_total = 0;
                                        $bets_id = [];
                                        $percent_money = 0;
                                    @endphp
                                    @foreach ($users as $user)
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
                                                    @if ($bet_detail->status == 1)
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
                                                    @if ($bet_detail->status == 2)
                                                        @php
                                                            $bet_lose += $bet_detail->money;
                                                            $bets_id[] = $bet_detail->id;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <b>
                                                    {{ $bet_lose }}
                                                </b>
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
                                        <th id="total_bet_win"></th>
                                        <th id="total_bet_lose"></th>
                                        <th id="total_bet_total"></th>
                                    </tr>
                                    <tr align="center" id="tf_total">
                                        <th colspan="3">รวมแบ่งเปอร์เซน</th>
                                        <th colspan="2" class="text-danger">เปอร์เซนที่ได้ :
                                            {{ $agent->share_percentage }}%</th>
                                        <th>
                                            @if ($all_total > 0)
                                                <b class="text-danger"> ยอดผู้เล่นได้ เยอะกว่า</b>
                                            @else
                                                @php
                                                    $percent_money = ($agent->share_percentage / 100) * $all_total;
                                                @endphp
                                                <b class="text-success">{{ $percent_money }}</b>
                                            @endif
                                        </th>
                                    </tr>
                                </tfoot> --}}
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
    </script>

@endsection
