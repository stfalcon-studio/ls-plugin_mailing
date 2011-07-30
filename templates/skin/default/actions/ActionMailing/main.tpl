{assign var="bNoSidebar" value=true}
{include file='header.tpl' menu='talk'}

{literal}
<script type="text/javascript">
    function ajaxMailingSend(subId, textId, activeId) {
        var text;
        if (BLOG_USE_TINYMCE && tinyMCE && (ed=tinyMCE.get(textId))) {
            text = ed.getContent();
        } else {
            text = $(textId).value;
        }
        subj = $(subId).value;
        active = $(activeId).get('checked');
        hash = new Hash();
        $('mlForm').getElements('input[name="aSex[]"]:checked').each(function(item, index){hash.set(index,item.value)}); 
        
        hashlang = new Hash();
        $('mlForm').getElements('input[name="aLangs[]"]:checked').each(function(item, index){hashlang.set(index,item.value)}); 
	
        JsHttpRequest.query(
            'POST '+DIR_WEB_ROOT+'/plugins/mailing/include/ajax/mailing_send.php',
            { talk_text: text, aSex: hash, aLangs: hashlang, subject: subj, active: active, security_ls_key: LIVESTREET_SECURITY_KEY, },
            function(result, errors) {
                if (!result) {
                    msgErrorBox.alert('Error', 'Please try again later');
                }
                if (result.bStateError==1) {
                    msgErrorBox.alert(result.sErrorTitle, result.sError);
                } else {
                    msgNoticeBox.alert(result.sTitle, result.sText);
					$('subject').value='';
                                        $('talk_text').value='';
					$('mlForm').getElements('input[name="aSex[]"]').each(function(item, index){ item.checked=true }); 
                }
            },
            true
        );
    }
</script>
{/literal}

{if $oConfig->GetValue('view.tinymce')}
    <script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce_3.2.7/tiny_mce.js"></script>

    {literal}
    <script type="text/javascript">
        tinyMCE.init({
            mode : "textareas",
            theme : "advanced",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_buttons1 : "lshselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,lsvideo,lsimage,pagebreak,code",
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : "",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
            theme_advanced_resize_horizontal : 0,
            theme_advanced_resizing_use_cookie : 0,
            theme_advanced_path : false,
            object_resizing : true,
            force_br_newlines : true,
            forced_root_block : '', // Needed for 3.x
            force_p_newlines : false,
            plugins : "lseditor,safari,inlinepopups,media,pagebreak",
            convert_urls : false,
            extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
            language : TINYMCE_LANG
        });
    </script>
    {/literal}

{else}
	{include file='window_load_img.tpl' sToLoad='talk_text'}
{/if}

<div class="topic" style="display: none;">
    <div class="content" id="text_preview"></div>
</div>

<div class="topic">
    <h1>{$aLang.ml_title}</h1>
    <form action="" method="post" id="mlForm">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <p>
            <label for="subject">{$aLang.talk_create_title}:</label>
            <input class="w100p" name="subject" id="subject" type="text"  size="55" value="{$_aRequest.subject}"/>
        </p>
        <p>
            <div class="note"></div>
            <label for="talk_text">{$aLang.talk_create_text}:</label>
            {if !$oConfig->GetValue('view.tinymce')}
            <div class="panel_form">
                <select onchange="lsPanel.putTagAround('talk_text',this.value); this.selectedIndex=0; return false;" style="width: 110px; height: 25px;">
                    <option value="">{$aLang.panel_title}</option>
                    <option value="h4">{$aLang.panel_title_h4}</option>
                    <option value="h5">{$aLang.panel_title_h5}</option>
                    <option value="h6">{$aLang.panel_title_h6}</option>
                </select>
                <select onchange="lsPanel.putList('talk_text',this); return false;" style="width: 85px; height: 25px;">
                    <option value="">{$aLang.panel_list}</option>
                    <option value="ul">{$aLang.panel_list_ul}</option>
                    <option value="ol">{$aLang.panel_list_ol}</option>
                </select>
                <a href="#" onclick="lsPanel.putTagAround('talk_text','b'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/bold_ru.gif" width="20" height="20" title="{$aLang.panel_b}"></a>
                <a href="#" onclick="lsPanel.putTagAround('talk_text','i'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/italic_ru.gif" width="20" height="20" title="{$aLang.panel_i}"></a>
                <a href="#" onclick="lsPanel.putTagAround('talk_text','u'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/underline_ru.gif" width="20" height="20" title="{$aLang.panel_u}"></a>
                <a href="#" onclick="lsPanel.putTagAround('talk_text','s'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.gif" width="20" height="20" title="{$aLang.panel_s}"></a>
                &nbsp;
                <a href="#" onclick="lsPanel.putTagUrl('talk_text','{$aLang.panel_url_promt}'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/link.gif" width="20" height="20"  title="{$aLang.panel_url}"></a>
                <a href="#" onclick="lsPanel.putQuote('talk_text'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/quote.gif" width="20" height="20" title="{$aLang.panel_quote}"></a>
                <a href="#" onclick="lsPanel.putTagAround('talk_text','code'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/code.gif" width="30" height="20" title="{$aLang.panel_code}"></a>
                <a href="#" onclick="lsPanel.putTagAround('talk_text','video'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/video.gif" width="20" height="20" title="{$aLang.panel_video}"></a>

                <a href="#" onclick="showImgUploadForm(); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/img.gif" width="20" height="20" title="{$aLang.panel_image}"></a>
            </div>
            {/if}
            <textarea name="talk_text" id="talk_text" rows="12">{$_aRequest.talk_text}</textarea>
        </p>
        
        <p>
            {$aLang.ml_sent_filter}:
            <br />
            <input name="aSex[]" type="checkbox" value="man" checked /> — {$aLang.user_stats_sex_man}
            <br />
            <input name="aSex[]" type="checkbox" value="woman" checked /> — {$aLang.user_stats_sex_woman}
            <br />
            <input name="aSex[]" type="checkbox" value="other" checked /> — {$aLang.user_stats_sex_other}
            <br />
        </p>
        {if $sTemplateWebPathPluginL10n}
        <p>
            {$aLang.ml_lang}:
            <br />
            {foreach from=$aLangs key=sLangKey item=sLangText}
                <input name="aLangs[]" type="checkbox" value="{$sLangKey}" checked /> — 
                <img src="{$sTemplateWebPathPluginL10n}images/flags/{$sLangKey}.png"></img>
                <br />
            {/foreach}
        </p>
        {/if}
        <p>
            <input name="active" id="active" type="checkbox" checked /> — {$aLang.ml_active}
            <br />
        </p>
        <p class="buttons">
            <input type="submit" name="send" value="{$aLang.talk_create_submit}" onclick="ajaxMailingSend('subject','talk_text', 'active'); return false;"/>
            <input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="$('text_preview').getParent('div').setStyle('display','block'); ajaxTextPreview('talk_text',false); return false;" />
        </p>
    </form>
</div>

{include file='footer.tpl'}