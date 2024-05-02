@extends('admin.layout.auth')
@section('title', 'Withdrawal Requests')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/css/bootstrap/zebra_datepicker.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <style>
        .form-control {
            min-width: 133px;
            border: 1px solid #949292 !important;
        }

        span.Zebra_DatePicker_Icon_Wrapper {
            position: unset !important;
        }

        label.date_time {
            font-weight: normal;
            font-weight: bold;
            line-height: 3.3rem;
        }

        .box.box-danger {
            border-top-color: #319DD3;
        }

        .box.box-light {
            border-top-color: #b4e6ff;
        }

        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }

        .box-body {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
        }

        @media print {
            .noPrint{
                display:none;
            }
        }

       

    </style>

@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            @include('admin.withdrawal-request.filter')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" >
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Request No</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Programme</th>
                                        <th>RollNo</th>
                                        <th>ReceiptNo</th>
                                        <th>Reason from list</th>
                                        <th>Reason by candidate</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($withdrawal_requests as $key => $w_request)
                                        <tr>
                                            <td>{{ $w_request->id }}</td>
                                            <td>{{ $w_request->application->full_name ?? "NA" }}</td>
                                            <td>{{ $w_request->meritList->admissionCategory->name ?? "NA" }}</td>
                                            <td>{{ $w_request->meritList->course->name ?? "NA" }}</td>
                                            <td>{{ $w_request->meritList->admissionReceipt->roll_number  ?? "NA"}}</td>
                                            <td>{{ $w_request->meritList->admissionReceipt->receipt_no ?? "NA" }}</td>
                                            <td>{{ $w_request->reason_from_list ?? "NA" }}</td>
                                            <td>{{ $w_request->reason ?? "NA" }}</td>
                                            <td>{{ $w_request->created_at->format("Y-m-d H:i a") }}</td>
                                            <td><span class="label label-primary">{{ $w_request->status_plain_text ?? "NA" }}</span></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="...">
                                                    {{-- @if($w_request->status == "request_sent")
                                                        @include('admin.withdrawal-request.approve-modal', ["request" => $w_request])
                                                        @include('admin.withdrawal-request.reject-modal', ["request" => $w_request])
                                                    @else
                                                        
                                                    @endif --}}
                                                    @include("admin.withdrawal-request.withdrawal-request-modal", ["withdrawal_request" => $w_request])
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-danger text-capitalize text-center" colspan="11">No request
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $withdrawal_requests->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.15/zebra_datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $("button[type='reset']").on("click", function() {
            $(".filter input").attr("value", "").val("");
            $(".filter").find("select").each(function(index, element) {
                $(element).find("option").each(function() {
                    if (this.defaultSelected) {
                        this.defaultSelected = false;
                        // this.selected = false;
                        $(element).val("").val("all");
                        return;
                    }
                });
            });
        });
        resetPassword = function(string) {
            if (!confirm("Change Password ?")) {
                return false;
            }
            var ajax_post = $.post('{{ route('admin.applicants.changepass') }}', {
                "_token": '{{ csrf_token() }}',
                'user_id': string
            });
            ajax_post.done(function(response) {
                alert(response.message);
            });
            ajax_post.fail(function() {
                alert("Failed Try again later.");
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }


        function closeOneModal(modalId) {

        // get modal
        const modal = document.getElementById(modalId);

        // change state like in hidden modal
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        modal.setAttribute('style', 'display: none');

        // get modal backdrop
        const modalBackdrops = document.getElementsByClassName('modal-backdrop');

        // remove opened modal backdrop
        document.body.removeChild(modalBackdrops[0]);
        document.body.style.overflowY = 'scroll';
        }

        
    </script>
@endsection
