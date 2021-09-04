@extends('layouts.user.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('ประวัติเดิมพัน') }}</div>

                    <div class="card-body">
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
                            <table class="table table-head-fixed text-nowrap p-0">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th>แทงเลข</th>
                                        <th>ราคา</th>
                                        <th>ผลได้เสีย</th>
                                        <th>ทำรายการเมื่อ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $bet_win = 0;
                                        $bet_lose = 0;
                                        $bet_total = 0;
                                    @endphp
                                    @foreach ($histories as $history)
                                        <tr align="center">
                                            <td class="align-middle">{{ $history->number }}</td>
                                            <th class="align-middle">{{ $history->money }}</th>
                                            <td class="align-middle">
                                                @if ($history->status == 0)
                                                    <b>รอผล</b>
                                                @elseif ($history->status == 1)
                                                    <b class="text-success">ถูกรางวัล</b>
                                                    @php
                                                        $bet_win += $history->money;
                                                    @endphp
                                                @elseif ($history->status == 2)
                                                    <b class="text-danger">เสีย</b>
                                                    @php
                                                        $bet_lose += $history->money;
                                                    @endphp
                                                @else
                                                    <b class="text-danger">ถูกยกเลิก</b>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                {{ Carbon\Carbon::parse($history->created_at)->locale('th')->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="thead-dark">
                                    <tr>
                                        <th colspan="3">รวมยอดได้</th>
                                        <th>
                                            <b class="text-success">
                                                {{ $bet_win }}
                                            </b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">รวมยอดเสีย</th>
                                        <th>
                                            <b class="text-danger">
                                                {{ $bet_lose }}
                                            </b>
                                        </th>
                                    </tr>
                                    @php
                                        $bet_total = $bet_win - $bet_lose;
                                    @endphp
                                    <tr>
                                        <th colspan="3">รวมกำไร(ขาดทุนสุทธิ)</th>
                                        <th>
                                            <b class="text-@if ($bet_total <= 0)danger @else success @endif ">
                                                {{ $bet_total }}
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
