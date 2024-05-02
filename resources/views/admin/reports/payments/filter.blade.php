<div class="row">
    <div class="col-md-4 form-group">
        <label>Date from</label>
        <input type="text" name="date_from" id="date_from" class="form-control"
            value="{{ request("date_from") }}">
    </div>
    <div class="col-md-4 form-group">
        <label>Date To</label>
        <input type="text" name="date_to" id="date_to" class="form-control"
            value="{{ request("date_to") }}">
    </div>
    @if(!isset($showPage) || $showPage)
    <div class="col-md-4 form-group">
        <label>Per Page</label>
        <input type="number" name="per_page" id="per_page" class="form-control text-right"
            value="{{ request("per_page", 100) }}">
    </div>
    @endif
    <div class="col-md-4 form-group" style="margin-top:25px;">
        <button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-filter"></i> Filter</button>
        <a href="{{route("admin.reports.application-payments")}}" ><button type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-reset"></i> Reset</button></a>
    </div>
</div>