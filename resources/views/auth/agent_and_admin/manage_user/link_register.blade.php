@extends('layouts.agent_and_admin.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('ลิงค์สมัครสมาชิก') }}</div>

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
                        <h3>ลิงค์สมัครสมาชิก</h3>

                        <input class="form-control" type="text"
                            value="{{ url('/register/' . Auth::guard('admin')->user()->id) }}" id="url_link" readonly>
                        <button class="btn btn-success mt-3" onclick="myFunction()">คัดลอกไปยังคลิปบอร์ด</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function myFunction() {
            var copyText = document.getElementById("url_link");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: 'คัดลอกไปยังคลิปบอร์ด เรียบร้อย'
            })
        }
    </script>

@endsection
