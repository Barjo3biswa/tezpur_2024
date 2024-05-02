
<div class="modal fade" id="ajax_file_upload">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>

<script>
    $(".ajax_load_view").on("click", function(e){
        $(".loading").fadeIn();
        e.preventDefault();
        $.get($(this).prop("href"))
        .done(function(resp){
            $("#ajax_file_upload").find(".modal-body").html(resp);
            $("#ajax_file_upload").modal();
        })
        .fail(function(){
            alert("data loading failed.");
        })
        .always(function(){
            $(".loading").fadeOut();
        });
    });
</script>