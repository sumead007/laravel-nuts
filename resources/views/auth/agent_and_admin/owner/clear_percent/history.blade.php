@extends('layouts.agent_and_admin.app')

@section('content')
    <script>
        async function createPost() {
            var agent = 0,
                owner = 0;
            var form = $('#form_first')[0];
            var data = new FormData(form);
            // var id = $("#post_id").val();
            let _url = "{{ route('admin.get_api.clear_percents') }}";
            let _token = $('meta[name="csrf-token"]').attr('content');
            await $.ajax({
                timeout: 600000,
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
                    console.log(res)
                    // $("#btn-clear").attr('disabled', "true");
                    $('#table_crud tbody').empty();
                    $.each(res.data, function(key, value) {

                        // console.log(value);
                        $('#table_crud tbody').prepend("<tr align='center' id='row_" +
                            value.id + "'" +
                            "role='button'  data-href='/admin/clear_percent_detail/history/view/" +
                            value.id + "'" +
                            ">" +
                            "<th id='td_choese" +
                            "' class='align-middle' hidden='true'>" +
                            "<div align='center'>" +
                            "<input type='checkbox' class='form-check' name='select'" +
                            "id='select_input' value='" + value.id + "'>" +
                            "</div>" +
                            "</th>" +

                            "<td class='align-middle'>" + value.name + "</td>" +
                            "<td class='align-middle'>" + value.username +
                            "</td>" +
                            "<td class='align-middle'>" + value.telephone +
                            "</td>" +
                            "<td class='align-middle'>" + value.share_percentage +
                            "</td>" +
                            "<td class='align-middle agent'>" + value.agent +
                            "</td>" +
                            "<td class='align-middle owner'>" + value.owner +
                            "</td>" +
                            "<td class='align-middle'>" + value.created_at2 +
                            "</td>" +
                            "</tr>"
                        );
                    });

                    $(".agent").each(function() {
                        var value = $(this).text();
                        // console.log($(".agent"));
                        // add only if the value is number
                        if (!isNaN(value) && value.length != 0) {
                            agent += parseFloat(value);
                        }
                    });
                    $("#total_agent").html("<b>" + agent + "</b>");

                    $(".owner").each(function() {
                        var value = $(this).text();
                        // console.log($(".agent"));
                        // add only if the value is number
                        if (!isNaN(value) && value.length != 0) {
                            owner += parseFloat(value);
                        }
                        // console.log(owner);
                    });
                    $("#total_owner").html("<b>" + owner + "</b>");

                    Swal.fire(
                        '??????????????????!',
                        '?????????????????????????????????????????????????????????',
                        'success'
                    );
                    clear_ms_error();
                },
                error: function(err) {
                    Swal.fire(
                        '???????????????????????????!',
                        '????????????????????????????????????????????????????????????',
                        'error'
                    );
                    console.log("???????????????????????????");
                    clear_ms_error();
                    $('#date_startError').text(err.responseJSON.errors.date_start);
                    $('#date_endError').text(err.responseJSON.errors.date_end);
                }
            });
        }


        function clear_ms_error() {
            $('#date_endError').text("");
            $('#date_startError').text("");
        }
    </script>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">???????????????????????????????????????????????????</h3>
                                <div id="digital-clock"></div>
                            </div>
                            <div class="col-md-6" align="right">
                                <a href="{{ url()->previous() }}" class="btn btn-success">????????????????????????</a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <form id="form_first">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_start">?????????????????????????????????</label>
                                        <input type="date" name="date_start" id="date_start" class="form-control"
                                            onchange="$('#date_end').attr('disabled', false); $('#date_end').val('');">
                                        <span id="date_startError" class="alert-message text-danger"></span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_end">???????????????????????????</label>
                                        <input type="date" name="date_end" id="date_end" class="form-control"
                                            onclick="date_dont_back()" disabled>
                                        <span id="date_endError" class="alert-message text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div align="right" class="mb-2">
                            <button class="btn btn-primary" onclick="createPost()">???????????????</button>
                        </div>

                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table_crud">
                                    <thead class="thead-dark">
                                        <tr align="center">
                                            <th scope="col">????????????</th>
                                            <th scope="col">??????????????????????????????</th>
                                            <th scope="col">????????????????????????</th>
                                            <th scope="col">????????????????????????????????????</th>
                                            <th scope="col">??????????????????</th>
                                            <th scope="col">?????????????????????</th>
                                            <th scope="col">???????????????????????????????????????</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-secondary" id="tf_total">
                                        <tr align="center">
                                            <th colspan="4">??????????????????????????????</th>
                                            <th id="total_agent">0</th>
                                            <th id="total_owner">0</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            {{-- <div class="d-flex justify-content-center">
                            {!! $users->links() !!}
                        </div> --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
            function date_dont_back() {
                $('#date_end').attr('min', $('#date_start').val());
            }
        </script>
        <script>
            jQuery(document).ready(function($) {
                $(".table").on("click", "tr[role=\"button\"]", function(e) {
                    window.location = $(this).data("href");
                });
            });
        </script>

        <script>
            function getDateTime() {
                var now = new Date();
                var year = now.getFullYear();
                var month = now.getMonth() + 1;
                var day = now.getDate();
                var hour = now.getHours();
                var minute = now.getMinutes();
                var second = now.getSeconds();
                if (month.toString().length == 1) {
                    month = '0' + month;
                }
                if (day.toString().length == 1) {
                    day = '0' + day;
                }
                if (hour.toString().length == 1) {
                    hour = '0' + hour;
                }
                if (minute.toString().length == 1) {
                    minute = '0' + minute;
                }
                if (second.toString().length == 1) {
                    second = '0' + second;
                }
                var dateTime = month + '/' + day + '/' + year + ' ' + hour + ':' + minute + ':' + second;
                return dateTime;
            }

            // example usage: realtime clock
            setInterval(function() {
                currentTime = getDateTime();
                document.getElementById("digital-clock").innerHTML = currentTime;
            }, 1000);
        </script>
    @endsection
