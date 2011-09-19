$(document).ready(function(){
    /**
     * Preview mailing
     */
    jQuery('input[name="submit_preview"]').bind('click', function(){
        var tp = jQuery('#text_preview');

        tp.parent().show();
        ls.tools.textPreview('talk_text',false);
        jQuery('html, body').animate({
            scrollTop: tp.offset().top
        }, 100);

        return false;
    });
});