<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SL</th>
                <th>Document Name</th>
                <th>Uploaded at</th>
                <th>Remark</th>
                <th>Re-uploading Deadline</th>
                <th>Status</th>
                
                @if(auth("admin")->check())
                    <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if ($meritList->undertakings)
                @foreach ($meritList->undertakings as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{str_replace("_", " ",$item->doc_name)}}</td>
                        <td>{{ $item->created_at->format("Y-m-d h:i a") }}</td>
                        <td>{{ $item->remark_by_admin }}</td>
                        <td>
                            @if($item->closing_date_time)
                                {{date("Y-m-d h:i a", strtotime($item->closing_date_time))}}
                            @else
                                NA
                            @endif
                        </td>
                        <td>
                            @if (in_array($item->status, [\App\Models\MeritListUndertaking::$accepted, \App\Models\MeritListUndertaking::$other_accepted]))
                                <span class="label label-success">{{ $item->statusString() }}</span>
                            @elseif (in_array($item->status, [\App\Models\MeritListUndertaking::$rejected, \App\Models\MeritListUndertaking::$other_rejected]))
                                <span class="label label-danger">{{ $item->statusString() }}</span>
                            @else 
                                <span class="label label-info">{{ $item->statusString() }}</span>
                            @endif 
                        </td>
                        @if(auth("admin")->check())
                        <td>
                            <a href="{{ route("common.download.image", [$meritList->application->student_id, $meritList->application->id, $item->undertaking_link]) }}"
                                target="_blank">
                                <button type="button" class="btn btn-sm btn-primary"><i
                                    class="glyphicon glyphicon-eye-open"></i> View</button>
                            </a>
                            @if ($item->status === \App\Models\MeritListUndertaking::$pending || $item->status === \App\Models\MeritListUndertaking::$other_pending)
                                <button type="button" class="btn btn-sm btn-success" onclick="approveUndertaking(this)"
                                    data-url="{{ route("admin.application.approve-undertaking", $item->id) }}">Approve
                                    {{str_replace("_", " ",$item->doc_name)}}</button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="showReject(this)" data-url="{{ route("admin.application.approve-undertaking", [$item->id, "rejected" => "yes"]) }}">Reject
                                    {{str_replace("_", " ",$item->doc_name)}} & allow re-upload</button>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach 
            @endif 
        </tbody>
    </table>
</div>
<div class="container-fluid" id="rejectDiv" style="display: none;">
    <hr>
    <h3>Reject Undertaking</h3>
    <div class="row">
        <div class="col-sm-8">
            <form id="rejectForm"
                action=""
                method="GET" onsubmit="submitRejectForm(this, event)">

                <div class="row">
                    <div class="col-sm-6">
                        <label for="last_date">Last Date of undertaking Submission</label>
                        <div class="form-group">
                            <input type="date" class="form-control" required name="closing_date" id="cldate">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="last_date"> Time (hh:mm)</label>
                        <div class="form-group">
                            <select name="hour" id="" class="" required style="min-width: 50px;" required>
                                <option value="" selected disabled>hh</option>
                                @foreach (range(0, 23) as $number)
                                    <option
                                        value="{{ str_pad($number, 2, "00", STR_PAD_LEFT) }}">
                                        {{ str_pad($number, 2, "00", STR_PAD_LEFT) }}
                                    </option>
                                @endforeach 
                            </select>
                            <select name="minute" id="" class="" style="min-width: 50px;" required>
                                <option value="" selected disabled>mm</option>
                                @foreach (range(0, 59) as $number)
                                    <option
                                        value="{{ str_pad($number, 2, "00", STR_PAD_LEFT) }}">
                                        {{ str_pad($number, 2, "00", STR_PAD_LEFT) }}
                                    </option>
                                @endforeach 
                            </select>
                            {{-- <select name="type" id="" class="" style="min-width: 50px;" required>
                                <option value="am">am</option>
                                <option value="pm">pm</option>
                            </select> --}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control" cols="30" rows="3" placeholder="Remark"
                        required></textarea>
                </div>
                <div class="form-group text-right">
                    <button class="btn btn-sm btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .Zebra_DatePicker_Icon_Wrapper {
        width: 100%
    }
</style>
<script>
    @if(auth("admin")->check())
    // $('#cldate').Zebra_DatePicker({
    //     direction: true,
    //     format: 'Y-m-d H:i'
    // });
    approveUndertaking = function (obj) {
        var $this = $(obj);
        if (confirm("Are you sure ?")) {
            $(".loading").fadeIn();
            var xhr2 = $.get($this.data("url"));

            xhr2.done(function (resp) {
                alert(resp);
                closeUndertakingModal();
            });

            xhr2.fail(function (resp) {
                alert(resp);
            });
            xhr2.always(function () {
                $(".loading").fadeOut();
            });
        }
    }
    showReject = function (obj) {
        var $form_div = $("#rejectDiv");
        // $form_div.find("form").prop("action", $form_div.data("url"))
        $("#rejectForm").prop("action", $(obj).data("url"));
        $form_div.show();
    }

    function submitRejectForm(obj, event) {
        event.preventDefault();
        var $this = $(obj);
        if (confirm("Are you sure ?")) {
            $(".loading").fadeIn();
            var xhr2 = $.get($this.attr("action"), $this.find("input,textarea,select").serialize());

            xhr2.done(function (resp) {
                alert(resp);
                location.reload();
            });

            xhr2.fail(function (resp) {
                alert(resp);
            });
            xhr2.always(function () {
                $(".loading").fadeOut();
            })
        }
    }
    @endif
</script>