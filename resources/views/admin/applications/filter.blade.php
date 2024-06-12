
@php
$castes = \App\Models\Caste::/* pluck("name","id")->toArray(); */get();
$exam_center = \App\Models\ExamCenter::get();
// dd($exam_center);
@endphp
<div class="filter dont-print">
    <div class="row">
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application No:</label>
            <input type="text" name="application_id" id="application_id" class="form-control input-sm"
                placeholder="Application No" value="{{request()->get("application_id")}}">
        </div>
        <div class="col-sm-3">
            <label for="registration_no" class="label-control">Registration No:</label>
            <input type="text" name="registration_no" id="registration_no" class="form-control input-sm"
                placeholder="Registration No" value="{{request()->get("registration_no")}}">
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application Status:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="status" id="status" class="form-control input-sm">
                <option value="all">All</option>
                {{-- <option value="payment_pending">Payment Pending</option> --}}
                <option value="application_submitted" {{request()->get("status") == "application_submitted" ? "selected" : ""}}>Incomplete</option>
                <option value="payment_done" {{request()->get("status", "payment_done") == "payment_done" ? "selected" : ""}}>Payment Done</option>
                <option value="accepted" {{request()->get("status") == "accepted" ? "selected" : ""}}>Accepted</option>
                <option value="rejected" {{request()->get("status") == "rejected" ? "selected" : ""}}>Rejected</option>
                <option value="on_hold" {{request()->get("status") == "on_hold" ? "selected" : ""}}>On Hold</option>
                <option value="qualified" {{request()->get("status") == "qualified" ? "selected" : ""}}>Qualified</option>
                <option value="admitted_student" {{request()->get("status") == "admitted_student" ? "selected" : ""}}>Admitted Student</option>
                <option value="payment_pending" {{request()->get("status") == "payment_pending" ? "selected" : ""}}>Payment Pending</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Gender:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="gender" id="gender" class="form-control input-sm">
                <option value="" selected>All</option>
                <option value="Male" {{request()->get("gender") == "Male" ? "selected" : ""}}>Male</option>
                <option value="Female" {{request()->get("gender") == "Female" ? "selected" : ""}}>Female</option>
            </select>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <label for="category" class="label-control">Category:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="caste" id="caste" class="form-control input-sm">
                <option value="" selected>All</option>
                @foreach ($castes as $index => $caste)
                    <option value="{{$caste->id}}" {{request()->get("caste") == $caste->id ? "selected" : ""}}>{{$caste->name}}</option>
                @endforeach
            </select>
        </div>
        @if(auth("admin")->check())
        <div class="col-sm-3">
            <label for="department" class="label-control">Department:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="department" id="department" class="form-control input-sm select2">
                <option value="" selected>All</option>
                @foreach (departments_array() as $id => $department_name)
                    <option value="{{$id}}" {{request()->get("department") == $id ? "selected" : ""}}>{{$department_name}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-sm-3">
            <label for="program" class="label-control">Program:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="program" id="program" class="form-control input-sm select2">
                <option value="" selected>All</option>
                @foreach (programmes_array() as $id => $name)
                    <option value="{{$id}}" {{request()->get("program") == $id ? "selected" : ""}}>{{$name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-3">
            <label for="program" class="label-control">CUET/ TUEE:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="EXAM_THROUGH" id="EXAM_THROUGH" class="form-control input-sm select2">
                <option value="">All</option>
                <option value="CUET" {{request()->get("EXAM_THROUGH") == "CUET" ? "selected" : ""}}>CUET</option>
                <option value="TUEE" {{request()->get("EXAM_THROUGH") == "TUEE" ? "selected" : ""}}>TUEE</option>
                <option value="UCEED" {{request()->get("EXAM_THROUGH") == "UCEED" ? "selected" : ""}}>UCEED</option>
                <option value="CEED" {{request()->get("EXAM_THROUGH") == "CEED" ? "selected" : ""}}>CEED</option>
                {{-- <option value="GATE" {{request()->get("EXAM_THROUGH") == "is_cuet_pg" ? "selected" : ""}}>GATE</option> --}}
                <option value="Visvesvaraya" {{request()->get("EXAM_THROUGH") == "Visvesvaraya" ? "selected" : ""}}>Visvesvaraya</option>
            </select>
        </div>

    </div>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <label for="program" class="label-control">UG/PG/PHD:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="ug_pg" id="ug_pg" class="form-control input-sm select2">
                <option value="">All</option>
                <option value="is_cuet_ug" {{request()->get("ug_pg") == "is_cuet_ug" ? "selected" : ""}}>UG</option>
                <option value="is_cuet_pg" {{request()->get("ug_pg") == "is_cuet_pg" ? "selected" : ""}}>PG</option>
                <option value="is_phd" {{request()->get("ug_pg") == "is_phd" ? "selected" : ""}}>PHD</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label for="aplicant_name" class="label-control">Applicant Name:</label>
            <input type="text" name="aplicant_name" id="aplicant_name" class="form-control input-sm"
            placeholder="Applicant Name" value="{{request()->get("aplicant_name")}}">
        </div>
        
        <div class="col-sm-3">
            <label for="payment_date" class="label-control">Payment Date From:</label>
            <input type="text" name="payment_date_from" id="payment_date_from" class="form-control input-sm zebra"
                placeholder="Application No" value="{{request()->get("payment_date_from")}}">
        </div>
        <div class="col-sm-3">
            <label for="payment_date" class="label-control">Payment Date To:</label>
            <input type="text" name="payment_date_to" id="payment_date_to" class="form-control input-sm zebra"
                placeholder="Application No" value="{{request()->get("payment_date_to")}}">
        </div>
        
        <div class="col-sm-3">
            <label for="country" class="label-control">Country of origin</label>
            <select name="country" id="country" class="form-control input-sm">
                @foreach (countries_array() as $id => $name)
                    <option value="{{$id}}" {{request("country", null) == $id ? "selected" : ""}}>{{$name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Documents Uploaded:</label>
            <select name="doc_uploaded" id="doc_uploaded" class="form-control input-sm">
                <option value="" selected>All</option>
                <option value="uploaded" {{request()->get("doc_uploaded") === "uploaded" ? "selected" : ""}}>Uploaded</option>
                <option value="not_uploaded" {{request()->get("doc_uploaded") === "not_uploaded" ? "selected" : ""}}>Not Uploaded</option>
            </select>
        </div>
        {{-- <div class="col-sm-3">
            <label for="order" class="label-control">Order By:</label>
            <select name="order" id="order" class="form-control input-sm">
                <option value="application_no" {{request()->get("order") === "application_no" ? "selected" : ""}}>Application No</option>
                <option value="registration_no" {{request()->get("order") === "registration_no" ? "selected" : ""}}>Registration No</option>
                <option value="applicant_name" {{request()->get("order") === "applicant_name" ? "selected" : ""}}>Applicant Name</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label for="order" class="label-control">ASC/DESC:</label>
            <select name="order" id="order" class="form-control input-sm">
                <option value="ASC" {{request()->get("order") === "ASC" ? "selected" : ""}}>ASC</option>
                <option value="DESC" {{request()->get("order") === "DESC" ? "selected" : ""}}>DESC</option>
            </select>
        </div> --}}

        <div class="col-sm-3">
            <label for="session" class="label-control">Session:</label>
            <select name="session" id="session" class="form-control input-sm">
                <option value="" selected>All</option>
                @foreach ($sessions as $id => $name)
                    <option value="{{$id}}" {{request()->get("session") == $id ? "selected" : ""}}>{{$name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-3">
            <label for="session" class="label-control">Exam Center:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="center_id" id="center_id" class="form-control input-sm">
                <option value="" selected>--Select--</option>
                @foreach ($exam_center as $center)
                    <option value="{{$center->id}}" {{request()->get("center_id") == $center->id ? "selected" : ""}}>{{$center->center_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-3">
            <label for="program" class="label-control">Select Category:</label>
            <select name="visvesvaraya" id="visvesvaraya" class="form-control input-sm select2">
                <option value="" selected>All</option>
                <option value="Visvesvaraya"{{request()->get("visvesvaraya") == "Visvesvaraya" ? "selected" : ""}}>Visvesvaraya</option>
            </select>
        </div>

        <div class="col-sm-3">
            <label for="program" class="label-control">Qualified National Lavel Test:</label>
            <select name="Qualified" id="Qualified" class="form-control input-sm select2">
                <option value="" selected>All</option>
                <option value="Qualified"{{request()->get("Qualified") == "Qualified" ? "selected" : ""}}>Qualified</option>
            </select>
        </div>

        <div class="col-sm-3">
            <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Filter</button>
            {{-- <a href="{{route(get_route_guard().".application.index", ["status" => "payment_done"])}}"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Reset</button></a> --}}
        </div>
        {{-- <div class="col-sm-3">
            <label for="reset" class="label-control" style="visibility: hidden;">Reset</label><br>
        </div> --}}
    </div>
</div>