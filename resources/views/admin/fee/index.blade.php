@extends('admin.layout.auth')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section("content")
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: 

                      <span class="pull-right"><a href="{{route('admin.fee.create')}}"><button
                        class="btn btn-sm btn-primary"> Add New</button></a></span>
                    </div>
                    <div class="panel-body">
                        <form action="" method="get" class="filter">
                          <div class="row">
                            <div class="col-md-6 col-lg-6">
                              <div class="form-group">
                                <label class="form-label">Programme</label>
                                <select name="course" id="course" class="input-sm js-example-basic-single" style="width:100%">
                                  <option value="">Select Programme</option>
                                  @foreach($courses as $course)
                                  <option value="{{$course->id}}" {{Request::get('course')==$course->id?'selected':''}}>{{$course->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-lg-3">
                              <div class="form-group">
                                <label class="form-label">Fee Head</label>
                                <select name="fee_head" class="form-control  input-sm">
                                  <option value="">Select Fee Head</option>
                                  @foreach($fee_heads as $fee_head)
                                  <option value="{{$fee_head->id}}" {{Request::get('fee_head')==$fee_head->id?'selected':''}}>{{$fee_head->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3 col-lg-3">
                              <div class="form-group">
                                <label class="form-label">Category</label>
                                <select name="admission_category_id" class="form-control  input-sm">
                                  <option value="">Select </option>
                                  @foreach($admission_categories as $key=>$val)
                                   <option value="{{$val->id}}" {{(Request::get('admission_category_id')==$val->id)?'selected':''}}>{{$val->name}}</option>
                                  @endforeach
                          
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-md-4 col-lg-3">
                              <div class="form-group">
                                <label class="form-label">Financial Year</label>
                                <select name="financial_year" class="form-control  input-sm" required>
                                  <option value="">Select Financial Year</option>
                                  @foreach($financial_years as $value)
                                  <option value="{{$value->financial_year}}" {{(Request::get('financial_year')==$value->financial_year)?'selected':''}}>{{$value->financial_year}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                              <div class="col-md-3 col-lg-3">
                                  <div class="form-group">
                                      <label class="form-label">Type</label>
                                      <select name="type" class="form-control  input-sm">
                                          <option value="">Select</option>
                                          <option value="admission" {{(Request::get('type')=='admission')?'selected':''}}>Admission</option>
                                          <option value="examination" {{(Request::get('type')=='examination')?'selected':''}}>Examination</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-3 col-lg-3">
                                  <div class="form-group">
                                      <label class="form-label">Hostel</label>
                                      <select name="hostel_required" class="form-control  input-sm">
                                          <option value="">Select</option>
                                          <option value="1" {{(Request::get('hostel_required')== 1 )?'selected':''}}>Required</option>
                                          <option value="0" {{(Request::get('hostel_required')== 0)?'selected':''}}>Not Required</option>
                                      </select>
                                  </div>
                              </div>
                          </div>  
                          <br>
                          <div class="row">
                              <div class="col-sm-3">
                                  <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
                                  <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
                                  <button type="reset" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</button>
                              </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading"></strong></div>
                <div class="panel-body">
                  @include('admin.fee.list')
                  {{$fees->appends(request()->all())->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
$("button[type='reset']").on("click", function(){
            $(".filter input-sm").attr("value", "").val("");
            $(".js-example-basic-single").val('');
            $('.js-example-basic-single').trigger('change');
            $(".filter").find("select").each(function(index, element){
            
                $(element).find("option").each(function(){
                    if (this.defaultSelected) {
                        this.defaultSelected = false;
                        // this.selected = false;
                        $(element).val("").val("all");
                        return;
                    }
                });
            });
        });
</script>
@endsection