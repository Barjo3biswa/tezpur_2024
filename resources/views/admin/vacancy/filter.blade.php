<div class="filter dont-print">
        <div class="row">
            <div class="col-sm-6">
                <label for="Programme" class="label-control">Programme:</label>
                <select name="course_id" id="course_id" class="js-example-basic-single" style="width:100% !important"  autocomplete="off">
                    <option value="">Select</option>
                    @foreach($all_courses as $key=>$all_course)
                    <option value="{{$all_course->id}}" @if(request()->get("course_id") == $all_course->id) selected @endif >{{$all_course->name}} ({{$all_course->code}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
                <a href="{{route('admin.merit.index')}}" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</a>
            </div>

            
        </div>
        
    </div>