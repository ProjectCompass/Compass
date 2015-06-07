$(document).foundation();
$(function(){
    $(".deletereg").click(function(){
        if (confirm("<?php echo lang('core_confirm_delete'); ?>")) return true; else return false;
    });
});