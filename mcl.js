jQuery(document).ready(function($){
    $('.mcl-color-field').wpColorPicker();
    $("#mcl_settings").click(function() {
        $("#settings").slideDown();
        $("#mcl_settings").remove();
    });
    $("span.check-settings").click(function() {
        if($("#settings").is(":hidden")) {
            $("#settings").slideDown('fast');
            $("div#mcl_settings").remove();
        }
        $("tr.appearance input").addClass("red-background").focus();
        $('html, body').animate({
            scrollTop: $("tr.appearance").offset().top
        }, 500);
        $("span.check-settings").remove();
    });
    $(".mcl-switch-back").click(function() {
        if($("#settings").is(":hidden")) {
            $("#settings").slideDown('fast');
            $("div#mcl_settings").remove();
        }
        $("tr.classic ").addClass("red-background").focus();
        $('html, body').animate({
            scrollTop: $("tr.classic").offset().top
        }, 500);
    });
});