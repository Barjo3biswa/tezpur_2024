<div class="filter dont-print">
    <div class="row">
        <div class="col-sm-3">
            <label for="mobile_no" class="label-control">Mobile No:</label>
            <input type="number" name="mobile" id="registration_no" class="form-control"
                placeholder="Mobile No" value="{{request()->get("mobile")}}">
        </div>
        <div class="col-sm-3">
            <label for="name" class="label-control">Name:</label>
            <input type="text" name="name" id="name" class="form-control"
                placeholder="Name" value="{{request()->get("name")}}">
        </div>
        <div class="col-sm-3">
            <label for="email" class="label-control">Email:</label>
            <input type="email" name="email" id="email" class="form-control"
                placeholder="Email" value="{{request()->get("email")}}">
        </div>
        <div class="col-sm-3">
            <label for="department" class="label-control">Department <small class="text-danger"> multiple select </small><kbd>Ctrl + Click</kbd></label>
            <select name="department[]" id="department" class="form-control" multiple>
                @foreach (departments_array() as $id => $name)
                    <option value="{{$id}}" {{in_array($id, request("department", [])) ? "selected" : ""}}>{{$name}}</option>
                @endforeach
            </select>
        </div>        
    </div>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
            <a href="{{request()->url()}}"><button type="button" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</button></a>
        </div>
    </div>
</div>