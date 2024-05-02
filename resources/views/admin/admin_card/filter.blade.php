<div class="filter dont-print">
    <div class="row">
        <div class="col-sm-3">
            <label for="exam_center" class="label-control">Exam Center:</label>
            <select name="exam_center" class="form-control">
                <option value="">--select--</option>
                @foreach ($exam_centers as $exm)
                    <option value="{{$exm->id}}" {{ request()->get("exam_center") == $exm->id ? "selected" : "" }}>{{$exm->center_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label for="program" class="label-control">Program:</label>
            <select name="program" id="program" class="form-control ">
                <option value="" selected>All</option>
                @foreach (programmes_array() as $id => $name)
                    <option value="{{ $id }}"
                        {{ request()->get("program") == $id ? "selected" : "" }}>
                        {{ $name }}</option>
                @endforeach 
            </select>
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Admit Card Status:</label>
            <select name="status" class="form-control">
                <option value="">--select--</option>
                <option value="1" {{ request()->get("status") == 1 ? "selected" : "" }}>Published</option>
                <option value="0" {{ request()->get("status") == 0 ? "selected" : "" }}>Not Published</option>
            </select>
        </div>
    </div><br/>
    <div class="row">
        <div class="col-sm-3">
            <label for="student_id" class="label-control">registration No:</label>
            <input type="number" name="student_id" id="student_id" class="form-control"
                placeholder="student_id" value="{{ request()->get("student_id") }}">
        </div>
    </div>
    <br>
    <div class="row">

        <div class="col-sm-3">
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
        </div>
    </div>
</div>