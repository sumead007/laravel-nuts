@extends('layouts.user.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('ประวัติการเติมเงิน') }}</div>

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
                        <h3>ประวัติการเติมเงิน</h3>
                        <table class="table table-head-fixed text-nowrap table-responsive p-0">
                            <thead>
                                <tr align="center">
                                    <th scope="col">รูปสลิป</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">เลขที่บัญชี</th>
                                    <th scope="col">ชื่อธนาคาร</th>
                                    <th scope="col">ชื่อบัญชี</th>
                                    <th scope="col">จำนวนเงิน</th>
                                    <th scope="col">โอนไปยัง</th>
                                    <th scope="col">อัพเดทเมื่อ</th>
                                    <th scope="col">หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_ups as $top_up)
                                    <tr align="center">
                                        <th class="align-middle">
                                            <a href="#" class="pop">
                                                <img src="{{ asset($top_up->image) }}" alt="{{ $top_up->image }}"
                                                    width="100" height="100">
                                            </a>
                                        </th>
                                        <td class="align-middle">
                                            @if ($top_up->status == 0)
                                                รอการยืนยัน
                                            @elseif ($top_up->status == 1)
                                                สำเร็จ
                                            @else
                                                มีข้อผิดพลาด
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $top_up->number_account }}</td>
                                        <th class="align-middle">{{ $top_up->name_bank }}</th>
                                        <td class="align-middle">{{ $top_up->name_account }}</td>
                                        <td class="align-middle">{{ $top_up->money }}</td>
                                        <th class="align-middle">

                                            {{ $top_up->bank_organization->name_account }}
                                            <br>
                                            {{ $top_up->bank_organization->number_account }}
                                            <br>
                                            {{ $top_up->bank_organization->name_bank }}
                                        </th>
                                        <td class="align-middle">
                                            {{ Carbon\Carbon::parse($top_up->updated_at)->locale('th')->diffForHumans() }}
                                        </td>
                                        <td class="align-middle">
                                         {{-- {{$top_up}} --}}
                                            @if ($top_up->confirm_topup == null)
                                                -
                                            @else
                                                {{ $top_up->confirm_topup->note }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {!! $top_ups->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal image preview --}}
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('.pop').on('click', function() {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');
            });
        });
    </script>

@endsection
