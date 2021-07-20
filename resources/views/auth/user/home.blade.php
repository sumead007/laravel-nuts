@extends('layouts.app')

@section('content')

    <style>
        .statistics {
            background-image: url('{{ asset('images/background/1.jpg') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-size: 100%;
        }

    </style>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <img src="{{ asset('images/example_video/1.jpg') }}" alt="{{ asset('images/example_video/1.jpg') }}"
                    width="100%" height="80%">
            </div>
            <div class="col-md-4 statistics p-4">
                <h3 align="center" class="text-light">สถิติย้อนหลัง</h3>
                <table class="table table-light table-hover" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
