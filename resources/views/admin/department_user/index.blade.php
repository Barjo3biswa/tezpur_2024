@extends('admin.layout.auth')
@section("content")
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            @include('admin/department_user/filter')
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
            <div class="panel-heading">Department users: <strong>{{$department_users->total()}} records found</strong> <span class="pull-right"><a href="{{route("admin.department-users.create")}}"><button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New</button></a></span></div>
                <div class="panel-body">
                   @include("common.department_user.index")
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
$("button[type='reset']").on("click", function(){
    $(".filter input").attr("value", "").val("");
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
resetPassword = function(string){
    if(!confirm("Change Password ?")){
        return false;
    }
    var ajax_post =  $.post('{{route("admin.applicants.changepass")}}', {"_token" :'{{csrf_token()}}', 'user_id':string});
    ajax_post.done(function(response){
        alert(response.message);
    });
    ajax_post.fail(function(){
        alert("Failed Try again later.");
    });
}
</script>    
@endsection