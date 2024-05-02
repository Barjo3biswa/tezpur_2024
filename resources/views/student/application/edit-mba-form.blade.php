@extends('student.layouts.auth')
@section('content')
<form method="POST" action="{{url('student/application/update_edit_mba') }}">
    {{ csrf_field() }}
<div class="container">
<div  class="row mba">
    <div  class="col-md-12">
        <label >
             CAT/MAT/ATMA/XAT Details (only for MBA student)
        </label> 
        <div class="panel panel-default">
            <div  class="panel-body">
             <div class="row"><div  class="col-sm-12">
               <table  class="table table-bordered">
                <thead >
                    <tr>
                        <th >Name of the Exam</th> 
                        <th >Registration No.</th> 
                        <th >Date of Exam</th> 
                        <th>Score Obtained</th>
                    </tr>
                </thead> 
                <tbody>
                    @forelse ($data as $value)
                        
                 
                        
                   
                    <tr>
                        <td ><input name="name_of_exam[]" type="text" value="{{$value->name_of_the_exam}}" required="required" class="form-control input-sm" readonly> <!----></td> 
                        <td ><input name="registration_no[]" type="text" value="{{$value->registration_no}}" required="required" class="form-control input-sm"> <!----></td> 
                        <td ><input name="date_of_exam[]" type="date" value="{{$value->date_of_exam}}" required="required" class="form-control input-sm"> <!----></td> 
                        <td ><input name="score_obtained[]" type="number" value="{{$value->score_obtained}}"required="required" class="form-control input-sm text-right"> <!----></td>
                         <input  name="id[]" type="hidden" value="{{$value->id}}">
                         <input id="application_id" name="application_id[]" type="hidden" value="{{$value->application_id}}">
                         <input id="student_id" name="student_id[]" type="hidden" value="{{$value->student_id}}">
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No Record </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div  class="row mba">
    <div  class="col-md-12">
       
        <button id="add" type="button" class="btn btn-xs btn-info" style="float: right;">
            ADD NEW
            <i class="fa fa-plus-circle"></i>
        </button>
    </div>
</div>
<div  class="row mba">
   <!-- <div  class="col-md-12 ">--> 
       <!-- <div class="panel panel-default">-->
            <div  class="panel-body">
                <div class="row">
                    <div  class="col-sm-12 extra">
                    </div>
                </div>
            </div>
        <!--</div>-->
    <!--</div>-->
</div>
<div  class="row mba">
    <div  class="col-md-12">
       
        <button  type="submit" class="btn btn-sm btn-info" style="float: left;">
            UPDATE
            <i data-v-92152462="" class="fa fa-plus-circle"></i>
        </button>
    </div>
</div>

</div>
</div>
</div>
</form>


@endsection
@section('js')
<script src="https://checkout.payabbhi.com/v1/checkout.js"></script>
<script>
    $(document).ready(function() {
        $("#add").click(function() {
            var  array1 = $('input[name="name_of_exam[]"]').map(function(){
                     return this.value;
                }).get();
            array2 = ["CAT", "MAT", "ATMA", "XAT", "GMAT", "CMAT"],
            result = array2.filter(a => !array1.includes(a));
            var i=0;
            $application_id = $('#application_id').val();
            $student_id = $('#student_id').val();
          
            $table = `
            <table  class="table table-bordered">
               <div>
                <thead >
                    <tr>
                        <th >Name of the Exam</th> 
                        <th >Registration No.</th> 
                        <th >Date of Exam</th> 
                        <th>Score Obtained</th>
                        <th>Action</th>
                    </tr>
                </thead> 
                <tbody>                                   
                    <tr>
                        <td >
                            <select name="name_of_exam[]"class="form-control input-sm" name="cars" id="cars">
                            
                            `;
                            for(i=0; i<result.length;++i){
                                $table += `<option class="form-control input-sm" value="`+result[i]+`">`+result[i]+`</option>`;
                             }  
                        $table +=`</select>
                        </td> 
                        <td ><input name="registration_no[]" type="text" value="" required="required" class="form-control input-sm"> <!----></td> 
                        <td ><input name="date_of_exam[]" type="date" value="" required="required" class="form-control input-sm"> <!----></td> 
                        <td ><input name="score_obtained[]"  type="number" value=""required="required" class="form-control input-sm text-right"> <!----></td>
                        <input name="application_id[]" value="${$application_id}" type="hidden"/>     
                        <input name="student_id[]" value="${$student_id}" type="hidden"/>
                        <td><button type='button' class='btn btn-success' onclick='removeThis(this)'>Remove</button></td>
                    </tr>
                </div>
            </table>
            `;

            $(".extra").append($table);

            //$(".labtest").append("<tr><td><button type='submit' class='btn btn-default'>Save</button></td></tr>")
        });
        removeThis = function(obj) {

            $(obj).parents("table").hide(function() {
                $countvalue=$("#count").val();
                $count= --$countvalue;
                console.log($count);
                $("#count").val($count);
                $(this).remove();

            });
        };
        
    });
</script>


@endsection