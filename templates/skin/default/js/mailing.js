/**
 * Передает данные серверу для добавления новой рассылки
 */
function ajaxMailingSend(subId, textId, activeId) {

    var text;

    if (BLOG_USE_TINYMCE && tinyMCE && (ed=tinyMCE.get(textId))) {
        text = ed.getContent();
    } else {
        text = $(textId).value;
    }

    var aSex = new Hash();

    $('mlForm').getElements('input[name="aSex[]"]:checked').each(function(item, index){
        aSex.set(index,item.value)
    });

    var aLang = new Hash();

    $('mlForm').getElements('input[name="aLangs[]"]:checked').each(function(item, index){
        aLang.set(index,item.value)
    });

    var loader = $('mlForm').getElements('.loader').setStyle('display','inline');

    new Request.JSON({
        url: aRouter['mailing']+'ajaxsave',
        noCache: true,
        data: {
            talk_text: text,
            aSex: aSex.getValues(),
            aLangs: aLang.getValues(),
            subject: $(subId).value,
            active: $(activeId).get('checked'),
            security_ls_key: LIVESTREET_SECURITY_KEY
        },
        onSuccess: function(result){
            if (!result) {
                msgErrorBox.alert('Error', 'Please try again later');
            } else if (result.bStateError == true) {
                msgErrorBox.alert(result.sMsgTitle,result.sMsg);
            } else {
                msgNoticeBox.alert(result.sMsgTitle, result.sMsg);
                $('subject').value='';
                $('talk_text').value='';
                $('mlForm').getElements('input[name="aSex[]"]').each(function(item){
                    item.checked=true
                });
            }
            loader.setStyle('display','none');
        },
        onFailure: function(){
            msgErrorBox.alert('Error','Please try again later');
            loader.setStyle('display','none');
        }
    }).send();

}