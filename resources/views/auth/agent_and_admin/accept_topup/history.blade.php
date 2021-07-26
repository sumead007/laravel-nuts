@extends('layouts.agent_and_admin.app')

@section('content')



    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ยืนยันการเติมเงิน</h3>

                        <div class="card-tools">
                            <button class="btn btn-info" status="0" onclick="showInputChouse(event)"
                                id="btn_chouse">เลือก</button>
                            <a href="javascript:void(0)" class="btn btn-outline-success" hidden="true" id="select_all"
                                onclick="select_all()">เลือกทั้งหมด</a>
                            <a href="javascript:void(0)" class="btn btn-outline-info" hidden="true" id="reset_select"
                                onclick="reset_select()">รีเซต</a>
                            <a href="javascript:void(0)" class="btn btn-success" hidden="true" id="accept_select"
                                data-type="0" onclick="select_delete(event.target)">ยืนยันการโอนเงินที่เลือก</a>
                            <a href="javascript:void(0)" class="btn btn-danger" hidden="true" id="cancel_select"
                                data-type="1" onclick="select_delete(event.target)">ยกเลิกการโอนเงินที่เลือก</a>
                            <a href="#" class="btn btn-outline-success">ประวัติ</a>
                            {{-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table table-head-fixed text-nowrap table-responsive p-0" id="table_crud">
                            <thead>
                                <tr align="center">
                                    <th id="th_choese" hidden>เลือก</th>
                                    <th scope="col">เจ้าของบัญชี</th>
                                    <th scope="col">รูปสลิป</th>
                                    <th scope="col">เลขที่บัญชี</th>
                                    <th scope="col">ชื่อธนาคาร</th>
                                    <th scope="col">ชื่อบัญชี</th>
                                    <th scope="col">จำนวนเงิน</th>
                                    <th scope="col">โอนไปยัง</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">อัพเดทเมื่อ</th>
                                    <th>อื่นๆ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_ups as $top_up)
                                    <tr align="center" id="row_{{ $top_up->id }}">
                                        <th id="td_choese" class="align-middle" hidden>
                                            <div align="center">
                                                <input type="checkbox" class="form-check" name="select"
                                                    data-cusm_id="{{ $top_up->id }}" id="select_input"
                                                    value="{{ $top_up->id }}">
                                            </div>
                                        </th>
                                        <td class="align-middle">
                                            {{ $top_up->user->username }}
                                        </td>
                                        <td class="align-middle">
                                            <a href="#" class="pop">
                                                <img src="{{ asset($top_up->image) }}" alt="{{ $top_up->image }}"
                                                    width="100" height="100">
                                            </a>
                                        </td>
                                        <td class="align-middle">{{ $top_up->number_account }}</td>
                                        <td class="align-middle">{{ $top_up->name_bank }}</td>
                                        <td class="align-middle">{{ $top_up->name_account }}</td>
                                        <td class="align-middle">{{ $top_up->money }}</td>
                                        <td class="align-middle">
                                            {{ $top_up->bank_organization->name_account }}
                                            <br>
                                            {{ $top_up->bank_organization->number_account }}
                                            <br>
                                            {{ $top_up->bank_organization->name_bank }}

                                        </td>
                                        <td class="align-middle">
                                            @if ($top_up->status == 0)
                                                รอการยืนยัน
                                            @elseif ($top_up->status == 1)
                                                สำเร็จ
                                            @else
                                                มีข้อผิดพลาด
                                            @endif
                                        </td>
                                        <th scope="row" class="align-middle">
                                            {{ Carbon\Carbon::parse($top_up->updated_at)->locale('th')->diffForHumans() }}
                                        </th>
                                        <td class="align-middle" align="center">
                                            <a href="javascript:void(0)" class="btn btn-success" data-type="0"
                                                data-id="{{ $top_up->id }}" data-cusm_id="{{ $top_up->user->id }}"
                                                id='btn_accept' onclick="processAccetpe(event.target)">ยืนยัน</a>
                                            <a href="javascript:void(0)" class="btn btn-danger" data-type="1"
                                                data-cusm_id="{{ $top_up->user->id }}" data-id="{{ $top_up->id }}"
                                                onclick="processAccetpe(event.target)" id='btn_cancel'>ยกเลิก</a>
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
