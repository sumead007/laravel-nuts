@extends('layouts.user.app')

@section('content')

    <script>
        function createPost() {
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
                    var form = $('#form_first')[0];
                    var data = new FormData(form);
                    let _url = "{{ route('user.top_up.store') }}";
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    // data.append("bank_id", rate_value);
                    $.ajax({
                        enctype: 'multipart/form-data',
                        processData: false, // Important!
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        url: _url,
                        type: "POST",
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            Swal.fire(
                                'สำเร็จ!',
                                'ข้อมูลของท่านถูกบันทึกเรียบร้อยแล้ว',
                                'success',
                            )
                            clearForm();
                            // window.location.reload;
                        },
                        error: function(err) {
                            console.log("ไม่สำเร็จ");
                            // console.log(err);
                            Swal.fire(
                                'ไม่สำเร็จ!',
                                "กรุณาลองใหม่อีกครั้ง",
                                'error'
                            );
                            clearTextAddError();
                            $('#bank_or_idError').text(err.responseJSON.errors.bank_or_id);
                            $('#number_accountError').text(err.responseJSON.errors.number_account);
                            $('#name_accountError').text(err.responseJSON.errors.name_account);
                            $('#name_bankError').text(err.responseJSON.errors.name_bank);
                            $('#imageError').text(err.responseJSON.errors.image);
                            $('#moneyError').text(err.responseJSON.errors.money);
                        }
                    });
                }
            })
        }

        function clearTextAddError() {
            $('#bank_or_idError').text("");
            $('#number_accountError').text("");
            $('#name_accountError').text("");
            $('#name_bankError').text("");
            $('#moneyError').text("");
            $('#imageError').text("");
        }

        function clearForm() {
            clearTextAddError();
            blah.src = "{{ asset('images/unitity/NotFound.jpg') }}";
            $("#form_first")[0].reset();
        }
    </script>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('เติมเงิน') }}</div>

                    <div class="card-body">
                        <p class="text-danger">*กรุณาตรวจสอบให้ดี</p>
                        <div align="right">
                            <a href="{{route('user.top_up.history')}}" class="btn btn-success">ประวัติ</a>
                        </div>
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

                        <form id="form_first">
                            @csrf
                            <h3>โอนไปยัง</h3>
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr align="center">
                                        <th scope="col">เลขที่</th>
                                        <th scope="col">ชื่อบัญชี</th>
                                        <th scope="col">ชื่อธนาคาร</th>
                                        <th scope="col">
                                            <b class="text-danger">*เลือก</b>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bank_organizations as $bank_organization)
                                        <tr align="center">
                                            <th>{{ $bank_organization->number_account }}</th>
                                            <td>{{ $bank_organization->name_account }}</td>
                                            <td>{{ $bank_organization->name_bank }}</td>
                                            <td>
                                                <input type="radio" name="bank_or_id" class="form-check-input"
                                                    value="{{ $bank_organization->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <span id="bank_or_idError" class="alert-message text-danger"></span>

                            <br>
                            <br>
                            <h3>จากบัญชี</h3>
                            <div class="mb-3">
                                <label for="number_account" class="form-label">เลขที่บัญชี</label>
                                <input type="text" class="form-control" id="number_account" name="number_account">
                                <span id="number_accountError" class="alert-message text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label for="name_account" class="form-label">ชื่อบัญชี</label>
                                <input type="text" class="form-control" id="name_account" name="name_account">
                                <span id="name_accountError" class="alert-message text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label for="name_bank" class="form-label">ชื่อธนาคาร</label>
                                <input type="text" class="form-control" id="name_bank" name="name_bank">
                                <span id="name_bankError" class="alert-message text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label for="money" class="form-label">จำนวนเงิน</label>
                                <input type="number" class="form-control" id="money" name="money">
                                <span id="moneyError" class="alert-message text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">อัพโหลดสลิป</label>
                                <input type="file" class="form-control" id="image" accept="image/png, image/gif, image/jpeg"
                                    name="image">
                                <span id="imageError" class="alert-message text-danger"></span>

                            </div>
                            <div class="input-half" align="center">
                                <img id="blah" src="{{ asset('images/unitity/NotFound.jpg') }}" alt="your image"
                                    width="200px" height="200px" />
                            </div>

                            <button type="button" class="btn btn-primary" onclick="createPost()">บันทึก</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        //อัพโหลดโชวรูปและชื่อ logoคือid inputfile [input id='logo']
        image.onchange = evt => {
            const [file] = image.files
            if (file) {
                blah.src = URL.createObjectURL(file)
            }
        }

    </script>

@endsection
