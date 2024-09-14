$(document).ready(function (params) {
    if($("#countries").val()){
        $("#countries").trigger("change")
    }

    $("body").on("change",'#states',function () {
      //  $(this).val($(this).attr("data-val")).trigger("change")
    })
})