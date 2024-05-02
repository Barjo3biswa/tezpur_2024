<script>
  
    $(document).ready(function() {
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
        $(document).on("change", "input[name^='is_required']", function(){
            if($(this).is(":checked")){
                $(this).parents("tr").addClass('required-class');
            }else{
                $(this).parents("tr").removeClass('required-class');
            }
            globaleChanged();
        });
        $("#global_free").change(function(){
            if($(this).is(":checked")){
                $("#fee_head_table").find("tr").find("#is_free").prop({
                    "checked":true
                }).trigger("change");
            }else{
                $("#fee_head_table").find("tr").find("#is_free").prop({
                    "checked":false
                }).trigger("change");
            }
        });
        $(document).on("change", "input[name^='is_free']", function(){
            if($(this).is(":checked")){
                $(this).parents("tr").addClass("free-class");
            }else{
                $(this).parents("tr").removeClass("free-class");
            }
            globaleChangedFree();
        });
        $(document).on("input", ".amounts", function(){
            calculateTotal();
        });
        $(document).on("focus", ".amounts", function(){
            $(this).select();
        });
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
    globaleChangedFree = function(){
        var all_required         = $("input[name^='is_free']").length;
        var all_required_checked = $("input[name^='is_free']:checked").length;
        if(all_required == all_required_checked){
            $("#global_free").prop({
                "checked": true
            });            
        }else{
            $("#global_free").prop({
                "checked": false
            });
        }
    }
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
</script>