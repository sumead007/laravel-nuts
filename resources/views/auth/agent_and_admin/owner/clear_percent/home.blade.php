@extends('layouts.agent_and_admin.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">เคลียร์ยอด</h3>
                            </div>
                            <div class="col-md-6" align="right">
                                <a href="{{ route('admin.clear_percent.history.view') }}"
                                    class="btn btn-success">ประวัติ</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table_crud">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th scope="col">ชื่อ</th>
                                        <th scope="col">ชื่อผู้ใช้</th>
                                        <th scope="col">เบอร์โทร</th>
                                        <th scope="col">เปอร์เซนหุ้น</th>
                                        <th scope="col">เอเย่น</th>
                                        <th scope="col">เจ้าของ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $agent)
                                        <tr align="center" id="row_{{ $agent->id }}" role="button"
                                            data-href="{{ route('admin.clear_percent_detail.view', $agent->id) }}">
                                            <td class="align-middle">
                                                {{ $agent->name }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $agent->username }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $agent->telephone }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $agent->share_percentage }}%
                                            </td>
                                            <td class="align-middle agent">
                                                @php
                                                    $bet_win = 0;
                                                    $bet_lose = 0;
                                                    $betwl_total = 0;
                                                    $agent_total = 0;
                                                    $owner_total = 0;
                                                @endphp
                                                @foreach ($agent->user as $user1)
                                                    @foreach ($user1->bet_detail as $bet_detail1)
                                                        @if ($bet_detail1->status == 2 && $bet_detail1->clear_percent == null)
                                                            @php
                                                                $bet_win += $bet_detail1->money;
                                                            @endphp
                                                        @endif
                                                        @if ($bet_detail1->status == 1 && $bet_detail1->clear_percent == null)
                                                            @php
                                                                $bet_lose += $bet_detail1->money;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                                @php
                                                    $betwl_total = $bet_win - $bet_lose;
                                                    $agent_total = $betwl_total * ($agent->share_percentage / 100);
                                                    $owner_total = $betwl_total - $agent_total;
                                                @endphp
                                                {{ $agent_total }}
                                            </td>
                                            <td class="align-middle owner">
                                                @if ($owner_total < 0)
                                                    <b class="text-danger"> {{ $owner_total }}</b>
                                                @else
                                                    <b class="text-success"> {{ $owner_total }}</b>
                                                @endif
                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>
                                <tfoot class="bg-secondary" id="tf_total">
                                    <tr align="center">
                                        <th colspan="4">รวมทั้งหมด</th>
                                        <th id="total_agent"></th>
                                        <th id="total_owner"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {!! $agents->links() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var agent = 0,
                owner = 0;

            // iterate through each td based on class and add the values
            $(".owner").each(function() {
                var value = $(this).text();
                // console.log($(".agent"));
                // add only if the value is number
                if (!isNaN(value) && value.length != 0) {
                    owner += parseFloat(value);
                }
                // console.log(owner);
            });
            $("#total_owner").html("<b>" + owner + "</b>");

            // iterate through each td based on class and add the values
            $(".agent").each(function() {
                var value = $(this).text();
                // console.log($(".agent"));
                // add only if the value is number
                if (!isNaN(value) && value.length != 0) {
                    agent += parseFloat(value);
                }
            });
            $("#total_agent").html("<b>" + agent + "</b>");


            $(".table").on("click", "tr[role=\"button\"]", function(e) {
                window.location = $(this).data("href");
            });
        });
    </script>

@endsection
