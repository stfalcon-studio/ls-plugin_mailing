{assign var="bNoSidebar" value=true}
{include file='header.tpl' menu='talk'}
<script type="text/javascript" src="{$sTemplateWebPathPlugin}js/mailing.js"></script>
{if $oConfig->GetValue('view.tinymce')}
    <script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce/tiny_mce.js"></script>

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
                inline_styles:false,
                formats : {
                    underline : {inline : 'u', exact : true},
                    strikethrough : {inline : 's', exact : true}
                },
            forced_root_block : '', // Needed for 3.x
            force_p_newlines : false,
            plugins : "lseditor,safari,inlinepopups,media,pagebreak",
            convert_urls : false,
            extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
            language : TINYMCE_LANG
        });
        {/literal}
    </script>

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
        <input type="hidden" name="mailing_id" value="{$oMailing->getMailingId()}" />
        <div class="fieldset">
            <label for="subject">{$aLang.talk_create_title}:</label>
            <input class="w100p" name="subject" id="subject" type="text"  size="55"
                   value="{$oMailing->getMailingTitle()}"/>
        </div>
        <div class="fieldset">
            <div class="note"></div>
            <label for="talk_text">{$aLang.talk_create_text}:</label>
            {if !$oConfig->GetValue('view.tinymce')}
                <div class="panel_form">
                    <select onchange="lsPanel.putTagAround('talk_text',this.value); this.selectedIndex=0; return false;">
                        <option value="">{$aLang.panel_title}</option>
                        <option value="h4">{$aLang.panel_title_h4}</option>
                        <option value="h5">{$aLang.panel_title_h5}</option>
                        <option value="h6">{$aLang.panel_title_h6}</option>
                    </select>
                    <select onchange="lsPanel.putList('talk_text',this); return false;">
                        <option value="">{$aLang.panel_list}</option>
                        <option value="ul">{$aLang.panel_list_ul}</option>
                        <option value="ol">{$aLang.panel_list_ol}</option>
                    </select>
                    <a href="#" onclick="lsPanel.putTagAround('talk_text','b'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/bold.png" title="{$aLang.panel_b}"></a>
                    <a href="#" onclick="lsPanel.putTagAround('talk_text','i'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/italic.png" title="{$aLang.panel_i}"></a>
                    <a href="#" onclick="lsPanel.putTagAround('talk_text','u'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/underline.png" title="{$aLang.panel_u}"></a>
                    <a href="#" onclick="lsPanel.putTagAround('talk_text','s'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.png" title="{$aLang.panel_s}"></a>
                    &nbsp;
                    <a href="#" onclick="lsPanel.putTagUrl('talk_text','{$aLang.panel_url_promt}'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/link.png"  title="{$aLang.panel_url}"></a>
                    <a href="#" onclick="lsPanel.putQuote('talk_text'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/quote.png" title="{$aLang.panel_quote}"></a>
                    <a href="#" onclick="lsPanel.putTagAround('talk_text','code'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/code.png" title="{$aLang.panel_code}"></a>
                    <a href="#" onclick="lsPanel.putTagAround('talk_text','video'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/video.png" title="{$aLang.panel_video}"></a>

                    <a href="#" onclick="showImgUploadForm(); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/img.png" title="{$aLang.panel_image}"></a>
                </div>
            {/if}
            <textarea name="talk_text" id="talk_text" cols="40" rows="12">{$oMailing->getMailingText()}</textarea>
        </div>
        {if $bEditMode}
            <div class="fieldset">
                <span class="input-note">{$aLang.ml_edit_warning}</span>
            </div>
        {/if}
        <div class="fieldset">
            {$aLang.ml_sex}:
            <br />
            <input name="aSex[]" type="checkbox" value="man" {if in_array('man', $oMailing->getMailingSex())}checked="checked"{/if} /> — {$aLang.user_stats_sex_man}
            <br />
            <input name="aSex[]" type="checkbox" value="woman" {if in_array('woman', $oMailing->getMailingSex())}checked="checked"{/if}/> — {$aLang.user_stats_sex_woman}
            <br />
            <input name="aSex[]" type="checkbox" value="other" {if in_array('other', $oMailing->getMailingSex())}checked="checked"{/if}/> — {$aLang.user_stats_sex_other}
            <br />
        </div>
        {if $sTemplateWebPathPluginL10n}
            <div class="fieldset">
                {$aLang.ml_lang}:
                <br />
                {foreach from=$aLangs key=sLangKey item=sLangText}
                    <input name="aLangs[]" type="checkbox" value="{$sLangKey}" {if in_array($sLangKey, $oMailing->getMailingLang())}checked="checked"{/if} /> —
                    <img src="{$sTemplateWebPathPluginL10n}images/flags/{$sLangKey}.png" alt="{$sLangKey}"/>
                    <br />
                {/foreach}
            </div>
        {/if}
        <div class="fieldset">
            <input id="talk" name="talk" type="checkbox" value="1" {if ($oMailing->getMailingTalk()))}checked="checked"{/if}/> — {$aLang.ml_send_talk}
        </div>
        {if !$bEditMode}
        <div class="fieldset">
            <input name="active" id="active" type="checkbox" checked="checked"/> — {$aLang.ml_active}
        </div>
            {/if}
        <p class="buttons">
            <input type="submit" name="submit_button_save" value="{$aLang.ml_save}"/>
            <input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" />
            <input type="reset"  value="{$aLang.ml_cancel}" onclick="location.href='{$sLinkList}'; return false;" />
        </p>
    </form>
</div>

{include file='footer.tpl'}