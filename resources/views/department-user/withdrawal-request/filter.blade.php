<div class="filter dont-print">
    <div class="row">
        <div class="col-sm-6">
            <label for="Programme" class="label-control">Programme:</label>
            <select name="course_id" id="course_id" class="js-example-basic-single form-control" style="width:100% !important"  autocomplete="off">
                <option value="">All</option>
                @foreach($all_courses as $key=>$all_course)
                <option value="{{$all_course->id}}" @if(request()->get("course_id") == $all_course->id) selected @endif >{{$all_course->name}} ({{$all_course->code}})</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6">
            <label for="Programme" class="label-control">Status:</label>
            <select name="status" id="status" class="js-example-basic-single form-control" style="width:100% !important"  autocomplete="off">
                <option value="">All</option>
                @foreach($status as $key=>$status)
                    <option value="{{$status}}" @if(request()->get("status") == $status) selected @endif >{{ucwords(str_replace("_", " ", $status))}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
            <a href="{{route('admin.merit.withdrawal-request')}}" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</a>
        </div>
    </div>
</div>