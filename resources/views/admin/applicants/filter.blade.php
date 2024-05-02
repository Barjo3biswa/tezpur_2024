<div class="filter dont-print">
        <div class="row">
            <div class="col-sm-3">
                <label for="registration_no" class="label-control">Registration No:</label>
                <input type="text" name="registration_no" id="registration_no" class="form-control"
                    placeholder="Registration No" value="{{request()->get("registration_no")}}">
            </div>
            <div class="col-sm-3">
                <label for="name" class="label-control">Applicant Name:</label>
                <input type="text" name="aplicant_name" id="name" class="form-control"
                    placeholder="Applicant Name" value="{{request()->get("aplicant_name")}}">
            </div>
            <div class="col-sm-3">
                <label for="email" class="label-control">Applicant Email:</label>
                <input type="text" name="email" id="email" class="form-control"
                    placeholder="Applicant Email" value="{{request()->get("email")}}">
            </div>
            <div class="col-sm-3">
                <label for="email" class="label-control">Applicant Mobile: <small class="text-danger">(Without ISD code)</small></label>
                <input type="text" name="mobile_no" id="mobile_no" class="form-control"
                    placeholder="Applicant Mobile" value="{{request()->get("mobile_no")}}">
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-3">
                <label for="country" class="label-control">Country of origin</label>
                <select name="country" id="country" class="form-control">
                    @foreach (countries_array() as $id => $name)
                        <option value="{{$id}}" {{request("country", null) == $id ? "selected" : ""}}>{{$name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="session" class="label-control">Session</label>
                <select name="session" id="session" class="form-control">
                    <option value="">All</option>
                    @foreach ($sessions as $key => $session)
                        <option value="{{$session->id}}" {{request("session", null) == $session->id ? "selected" : ""}}>{{$session->name}}</option>
                    @endforeach
                </select>
            </div>
            
        </div>
        <br>
        <div class="row">
            {{-- <div class="col-sm-3">
                <label for="order" class="label-control">Order By:</label>
                <select name="order" id="order" class="form-control">
                    <option value="application_no" {{request()->get("order") === "application_no" ? "selected" : ""}}>Application No</option>
                    <option value="registration_no" {{request()->get("order") === "registration_no" ? "selected" : ""}}>Registration No</option>
                    <option value="applicant_name" {{request()->get("order") === "applicant_name" ? "selected" : ""}}>Applicant Name</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label for="order" class="label-control">ASC/DESC:</label>
                <select name="order" id="order" class="form-control">
                    <option value="ASC" {{request()->get("order") === "ASC" ? "selected" : ""}}>ASC</option>
                    <option value="DESC" {{request()->get("order") === "DESC" ? "selected" : ""}}>DESC</option>
                </select>
            </div> --}}
            <div class="col-sm-3">
                <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
                <button type="reset" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</button>
            </div>
            {{-- <div class="col-sm-3">
                <label for="reset" class="label-control" style="visibility: hidden;">Reset</label><br>
            </div> --}}
        </div>
    </div>