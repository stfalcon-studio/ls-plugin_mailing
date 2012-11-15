{assign var="bNoSidebar" value=true}
{include file='header.tpl' menu='talk'}
<style type="text/css" media='all'> 
    @import url({$sTemplateWebPathPlugin}/css/style.css); 
</style>
<div class="topic">
    <h1>{$aLang.ml_title}</h1>
    <form action="" id="mlForm" method="post">
        <div class="fieldset">
            <dl class="mlInfo"> 
                <dt>{$aLang.ml_subj} </dt>
                <dd>{$oMailing->getMailingTitle()}</dd>
                <dt>{$aLang.ml_status}: </dt>
                <dd>
                    {if ($oMailing->getMailingSend() == $oMailing->getMailingCount())}
                        {$aLang.ml_send_all}
                    {elseif ($oMailing->getMailingActive())}
                        {$aLang.ml_ready}
                    {else}
                        {$aLang.ml_wait}
                    {/if}
                    {if $oMailing->getMailingDirect()}{$aLang.ml_send_direct}{/if}
                    {if $oMailing->getMailingTalk()}{$aLang.ml_send_talk}{/if}
                </dd>
            </dl>
        </div>
        <div class="fieldset" style="text-align:center">{$aLang.ml_delete_confirm}</div>
        <div style="text-align:center">
            <input type="submit" name="delete_mailing" style="width:80px" value="{$aLang.ml_yes}">
            <input type="submit" name="cancel" style="width:80px" value="{$aLang.ml_cancel}">
        </div>
    </form>
</div>
{include file='footer.tpl'}
