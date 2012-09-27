{assign var="noSidebar" value=true}
{include file='header.tpl' menu='talk'}
<script type="text/javascript" src="{$sTemplateWebPathPlugin}js/mailing.js"></script>

{if $oConfig->GetValue('view.tinymce')}
    <script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce-jq/tiny_mce.js"></script>

    <script type="text/javascript">
        {literal}
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
		pagebreak_separator :"<cut>",
		media_strict : false,
		language : TINYMCE_LANG,
		inline_styles:false,
		formats : {
			underline : {inline : 'u', exact : true},
			 strikethrough : {inline : 's', exact : true}
		}
	});
        {/literal}
    </script>
{else}
    {include file='window_load_img.tpl' sToLoad='topic_text'}
    <script type="text/javascript">
	jQuery(document).ready(function($){
		ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li"});
		// Подключаем редактор
		$('#talk_text').markItUp(getMarkitupSettings());
	});
    </script>
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
            <input class="input-wide" name="subject" id="subject" type="text"  size="55"
                   value="{$oMailing->getMailingTitle()}"/>
        </div>
        <div class="fieldset">
            <div class="note"></div>
            <label for="talk_text">{$aLang.talk_create_text}:</label>
            <textarea name="talk_text" id="talk_text" rows="20" class="input-wide">{$oMailing->getMailingText()}</textarea>
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
            <input id="talk" name="talk" type="checkbox" value="1" {if ($oMailing->getMailingTalk())}checked="checked"{/if}/> — {$aLang.ml_send_talk}
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