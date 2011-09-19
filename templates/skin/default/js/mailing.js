window.addEvent('domready', function() {

    var scroll =  new Fx.Scroll(window,  {
        duration: 100,
        transition: Fx.Transitions.Quad.easeInOut
    });

    /**
    * Preview mailing
    */
    $$('input[name="submit_preview"]').addEvent('click', function(){
        var tp = $('text_preview');
        tp.getParent('div').setStyle('display','block');
        ajaxTextPreview('talk_text',false);
        scroll.toElement(tp);
        return false;
    });
});