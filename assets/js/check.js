$(function(){
 
    // add multiple select / deselect functionality
    $(".check-all").click(function () {
          $('.case').attr('checked', this.checked);
    });
 
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".case").click(function(){
 
        if($(".case").length == $(".case:checked").length) {
            $(".check-all").attr("checked", "checked");
        } else {
            $(".check-all").removeAttr("checked");
        }
 
    });
});