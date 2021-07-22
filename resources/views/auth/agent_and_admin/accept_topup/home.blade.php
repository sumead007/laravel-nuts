@extends('layouts.agent_and_admin.app')

@section('content')

    {{-- <script>
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
    </script> --}}

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('ยืนยันการเติมเงิน') }}</div>

                    <div class="card-body">



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
