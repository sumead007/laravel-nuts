@extends('layouts.agent_and_admin.app')

@section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ประวัติยืนยันการเติมเงิน</h3>
                    </div>

                    <div class="card-body">

                        <table class="table table-head-fixed text-nowrap table-responsive p-0" id="table_crud">
                            <thead>
                                <tr align="center">
                                    <th id="th_choese" hidden>เลือก</th>
                                    <th scope="col">เจ้าของบัญชี</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">รูปสลิป</th>
                                    <th scope="col">เลขที่บัญชี</th>
                                    <th scope="col">ชื่อธนาคาร</th>
                                    <th scope="col">ชื่อบัญชี</th>
                                    <th scope="col">จำนวนเงิน</th>
                                    <th scope="col">โอนไปยัง</th>
                                    <th scope="col">อัพเดทเมื่อ</th>
                                    <th>หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($confirm_topups as $confirm_topup)
                                    <tr align="center" id="row_{{ $confirm_topup->id }}">
                                        <th id="td_choese" class="align-middle" hidden>
                                            <div align="center">
                                                <input type="checkbox" class="form-check" name="select"
                                                    data-cusm_id="{{ $confirm_topup->id }}" id="select_input"
                                                    value="{{ $confirm_topup->id }}">
                                            </div>
                                        </th>
                                        <td class="align-middle">
                                            {{ $confirm_topup->top_up->user->username }}
                                        </td>
                                        <td class="align-middle">
                                            @if ($confirm_topup->top_up->status == 0)
                                                รอการยืนยัน
                                            @elseif ($confirm_topup->top_up->status == 1)
                                                สำเร็จ
                                            @else
                                                มีข้อผิดพลาด
                                            @endif
                                        </td>
                                        <td class="align-middle"><a href="#" class="pop">
                                                <img src="{{ asset($confirm_topup->top_up->image) }}"
                                                    alt="{{ $confirm_topup->top_up->image }}" width="100" height="100">
                                            </a></td>
                                        <td class="align-middle">{{ $confirm_topup->top_up->number_account }}</td>
                                        <td class="align-middle">{{ $confirm_topup->top_up->name_bank }}</td>
                                        <td class="align-middle">{{ $confirm_topup->top_up->name_account }}</td>
                                        <td class="align-middle">
                                            {{ $confirm_topup->top_up->money }}
                                        </td>
                                        <td class="align-middle">

                                            {{ $confirm_topup->top_up->bank_organization->name_account }}
                                            <br>
                                            {{ $confirm_topup->top_up->bank_organization->number_account }}
                                            <br>
                                            {{ $confirm_topup->top_up->bank_organization->name_bank }}

                                        </td>
                                        <th scope="row" class="align-middle">
                                            {{ Carbon\Carbon::parse($confirm_topup->updated_at)->locale('th')->diffForHumans() }}
                                        </th>
                                        <td scope="row" class="align-middle">
                                            {{ $confirm_topup->note }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {!! $confirm_topups->links() !!}
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
