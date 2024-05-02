<div class="filter dont-print">
        <div class="row">
            <div class="col-sm-4">
                <label for="Programme" class="label-control">Programme1:</label>
                <select name="course_id" id="course_id" class="js-example-basic-single" autocomplete="off">
                    @foreach (programmes_array() as $id => $name)
                    <option value="{{$id}}" {{request()->get("course_id") == $id ? "selected" : ""}}>{{$name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4">
                <label for="merit_master_id" class="label-control">Select List:</label>
                <select name="merit_master_id" id="merit_master_id" class="form-control input-sm" style="width:100% !important"  autocomplete="off">
                    
                </select>
            </div>
            <div class="col-sm-4">
                <label for="application_no" class="label-control">Application No:</label>
                <input type="text" name="application_no" id="application_no" class="form-control input-sm"
                    value="{{request()->get("application_no")}}" autocomplete="off">
            </div>

            <div class="col-sm-4">
                <label for="status" class="label-control">Category</label>
                <select name="admission_category_id" id="admission_category_id" class="form-control input-sm" style="width:100% !important"  autocomplete="off">
                    <option value="">Select</option>
                    @foreach($admission_categories as $key=>$admission_category)
                    <option value="{{$admission_category->id}}" {{request()->get("admission_category_id") == $admission_category->id ? "selected" : ''}}>{{$admission_category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4">
                <label for="status" class="label-control">Merit/Waiting</label>
                <select name="merit" id="merit" class="form-control input-sm" style="width:100% !important"  autocomplete="off">
                    <option value="">All</option>
                    <option value="merit" {{request()->get("merit") == "merit" ? "selected" : ''}}>Merit</option>
                    <option value="waiting" {{request()->get("merit") == "waiting" ? "selected" : ''}}>Waiting</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="status" class="label-control">Status</label>
                <select name="status" id="status" class="form-control input-sm" style="width:100% !important"  autocomplete="off">
                    <option value="">Select</option>
                    @foreach (\App\Models\MeritList::$status as $status_id => $status)
                        
                    <option value="{{$status_id}}" {{request()->get("status") == $status_id ? "selected" : ''}}>{{$status}}</option>
                    @endforeach
                    {{-- <option value="1"  {{request()->get("status") == 1 ? "selected" : ''}}>Approved</option>
                    <option value="2"> {{request()->get("status") == 2 ? "selected" : ''}}Confirmed</option> --}}
                </select>
            </div>

            <div class="col-sm-4">
                <label for="undertaking_status" class="label-control">Undertaking Status</label>
                <select name="undertaking_status" id="undertaking_status" class="form-control input-sm" style="width:100% !important"  autocomplete="off">
                    <option value="">Select</option>
                    <option value="pending" {{request()->get("undertaking_status") == "pending" ? "selected" : ''}}>Pending</option>
                    <option value="approved"  {{request()->get("undertaking_status") == "approved" ? "selected" : ''}}>Approved</option>
                    <option value="rejected"  {{request()->get("undertaking_status") == "rejected" ? "selected" : ''}}>Rejected</option>
                    <option value="not_uploaded"  {{request()->get("undertaking_status") == "not_uploaded" ? "selected" : ''}}>Not Uploaded</option>
                </select>
            </div>


            <div class="col-sm-4">
                <label for="tuee_rank" class="label-control">Order By Rank</label>
                <select name="tuee_rank" id="tuee_rank" class="form-control input-sm" style="width:100% !important"  autocomplete="off">
                    <option value="">Select</option>
                    <option value="asc" {{request()->get("tuee_rank") == "asc" ? "selected" : ''}}>Ascending</option>
                    <option value="desc" {{request()->get("tuee_rank") == "desc" ? "selected" : ''}}>Descending</option>
                </select>
            </div>
           
            
            
        </div>
        <br>
        <div class="row">
            <div class="col-sm-3">
                <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
                <a href='{{route(get_route_guard().".merit.index")}}' class="btn btn-danger"><i class="fa fa-remove"></i> Reset</a>
            </div>
            {{-- <div class="col-sm-3">
                <label for="reset" class="label-control" style="visibility: hidden;">Reset</label><br>
            </div> --}}
        </div>
    </div>