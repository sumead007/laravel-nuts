@extends('layouts.agent_and_admin.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">เคลียร์ยอด</h3>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table_crud">
                                <thead>
                                    <tr align="center">
                                        <th scope="col">ชื่อ</th>
                                        <th scope="col">ชื่อผู้ใช้</th>
                                        <th scope="col">เบอร์โทร</th>
                                        <th scope="col">เปอร์เซนหุ้น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $agent)
                                        <tr align="center" id="row_{{ $agent->id }}" role="button" data-href="{{route('admin.clear_percent_detail.view', $agent->id)}}">
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
                                        </tr>

                                    @endforeach
                                </tbody>
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
            $(".table").on("click", "tr[role=\"button\"]", function (e) {
          window.location = $(this).data("href");
     });
        });
    </script>

@endsection
