<div class="filter dont-print">
        <div class="row">
            <div class="col-sm-4">
                <label for="Programme" class="label-control">Programme:</label>
                <select name="course_id" id="course_id" class="js-example-basic-single select2" style="width:100% !important"  autocomplete="off">
                    <option value="">ALL</option>
                    @foreach($courses as $key=>$course)
                        <option value="{{$course->id}}" @if(request()->get("course_id") == $course->id) selected @endif >{{$course->name}} ({{$course->code}})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-4">
                <label for="type" class="label-control">Question type</label>
                <select name="type" id="type" class="js-example-basic-single select2" style="width:100% !important"  autocomplete="off">
                    <option value="">ALL</option>
                    @foreach (\App\Models\EligibilityQuestion::$QUESTION_TYPES as $type)
                        
                        <option value="{{ $type}}" {{request()->get("type") == $type ? "selected" : ''}}>{{$type}}</option>
                    @endforeach
                </select>
            </div>
            
            
        </div>
        <br>
        <div class="row">
            <div class="col-sm-3">
                <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
                <a href="{{route("admin.questionnaires.index")}}" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</a>
            </div>
            {{-- <div class="col-sm-3">
                <label for="reset" class="label-control" style="visibility: hidden;">Reset</label><br>
            </div> --}}
        </div>
    </div>