@extends('admin.layout.auth')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
.required-class {
    background: #f7eef1;
}

.form-control:disabled, .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Fee Head</strong>
                    <span class="pull-right"><a href="{{route('admin.fee.index')}}"><button
                                class="btn btn-sm btn-primary"> Fee List</button></a></span>
                </div>
                <div class="panel-body">
                  <form name="application" id="application" method="post" action="{{route('admin.fee.store')}}" autocomplete="off">
                        {{ csrf_field() }}
                        @include('admin.fee.form')
                        <div class="card-footer text-center">
                          <div class="d-flex">
                            <button type="submit" class="btn btn-primary">Create</button>
                          </div>
                        </div>
                  </form>

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
    $("#global_required").change(function(){
            if($(this).is(":checked")){
                $("#fee_head_table").find("tr").find("#required_fee_head").prop({
                    "checked":true
                }).trigger("change");
            }else{
                $("#fee_head_table").find("tr").find("#required_fee_head").prop({
                    "checked":false
                }).trigger("change");
            }
        });
        
});

$(document).on("change", "input[name^='is_required']", function(){
            if($(this).is(":checked")){
                $(this).parents("tr").addClass('required-class');
            }else{
                $(this).parents("tr").removeClass('required-class');
            }
            globaleChanged();
        });
globaleChanged = function(){
  var all_required         = $("input[name^='is_required']").length;
  var all_required_checked = $("input[name^='is_required']:checked").length;
  if(all_required == all_required_checked){
      $("#global_required").prop({
          "checked": true
      });            
  }else{
      $("#global_required").prop({
          "checked": false
      });
  }
}

otherCategoryChanged = function(){
  var all_required         = $("input[name^='other_admission_category_id']").length;
  var all_required_checked = $("input[name^='other_admission_category_id']:checked").length;
  if(all_required == all_required_checked){
      $("#other").prop({
          "checked": true
      });            
  }else{
      $("#other").prop({
          "checked": false
      });
  }
}

$(document).on("change", "#other", function(){

            if($(this).is(":checked")){
                $("#fee_head_table").find(".other_category").prop({
                    "checked":true
                }).trigger("change");
            }else{
                $("#fee_head_table").find(".other_category").prop({
                    "checked":false
                }).trigger("change");
            }
  });
$(document).on("input", ".amounts", function(){
            calculateTotal();
});
	calculateTotal = function(){
        var sum = 0;
        $(".amounts").each(function(index, element){
            var amount = parseFloat($(element).val());
            if(isNaN(amount)){
                amount = 0;
            }
            sum += amount;
        });
        sum = sum.toFixed(2);
        $("#total").text(sum);
    }

$('#admission_category_id').change(function(){
  if($(this).val() == "other"){
    $.ajax({
      url:'{{route("admin.fee.other_category")}}',
      type:'get',
      dataType:'json',
      success:function(resp){
          console.log(resp);
        if(resp.success == true){
          var other_categories = '<label class="custom-control custom-checkbox custom-control-inline" data-toggle="tooltip" data-title="Check if  applicable." data-container="body">';
          other_categories += '<input type="checkbox" class="custom-control-input" id="other">';
          other_categories += '<span class="custom-control-label">Check All</span></label> &nbsp; &nbsp;';
          $.each(resp.data,function(k,v){
            other_categories += '<label class="custom-control custom-checkbox custom-control-inline" data-toggle="tooltip" data-title="Check if free applicable." data-container="body">';
            other_categories += '<input type="checkbox" class="custom-control-input other_category" name="other_admission_category_id[]" value="'+v.id+'">';
            other_categories += '<span class="custom-control-label">'+v.name+'</span></label> &nbsp; &nbsp;';
          })

          $('.hidden_admission_category').html(other_categories);
          
        }
        else
          alert("something went wrong");
      },
      error:function(resp){
        alert("something went wrong");
        console.log(resp);
      }
    })
  }else{
    $('.hidden_admission_category').html('');
    $('#other_category_all').html('');
  }
})
</script>
@endsection