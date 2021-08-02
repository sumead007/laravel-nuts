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
        }
    </script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <iframe
                    src="https://iframe.dacast.com/live/52fd5bb2-30ea-bca3-ef00-ce6268358965/6e098149-2dc5-98e5-a749-f2e778197b14"
                    width="100%" height="100%" frameborder="0" scrolling="no" allow="autoplay" allowfullscreen
                    webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
            </div>
            <div class="col-md-4  p-4 statistics">
                <h3 align="center" class="text-light">สถิติย้อนหลัง</h3>
                <div class="table-responsive" style="height: 300px;">
                    <table class="table table-light table-hover table-bordered">
                        <thead>
                            <tr align="center">
                                <th>เปิดที่</th>
                                <th>หน้าเต๋า</th>
                                <th>ผลถั่ว</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <td>5</td>
                                <td>Mark</td>
                                <td>3</td>
                            </tr>
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
                            <div class="card-body">
                                <form action="">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">ใส่จำนวนเงิน</label>
                                        <input type="email" class="form-control" id="exampleFormControlInput1"
                                            placeholder="ใส่จำนวนเงิน">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">ตัวเลขที่คุณเลือกคือ: <b
                                                id="show_number_selected" class="text-danger">ยังไม่เลือก</b></label>
                                        <input type="hidden" class="form-control" id="exampleFormControlInput1"
                                            placeholder="ยังไม่ได้เลือกตัวเลขเดิมพัน" readonly="true" value="">
                                    </div>
                                </form>
                                <div align="right">
                                    <a href="#" class="btn btn-primary">ยืนยัน</a>
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
@endsection
