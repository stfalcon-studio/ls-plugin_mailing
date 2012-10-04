{assign var="bNoSidebar" value=true}
{include file='header.tpl' menu='talk'}

<style type="text/css" media='all'> 
    @import url({$sTemplateWebPathPluginMailing}/css/style.css); 
</style>

<div class="topic people top-blogs talk-table">
<h1>{$aLang.ml_list}</h1>

{if ($aMailing)}
    <table class="table blog-list-table table-people">
            <thead>
                <tr>
                    <td class="subject">{$aLang.plugin.mailing.ml_subj}</td>
                    <td class="progress-bar">{$aLang.plugin.mailing.ml_progress}</td>
                    <td class="status">{$aLang.plugin.mailing.ml_status}</td>
                    <td class="datetime">{$aLang.plugin.mailing.ml_date}</td>
                    <td class="ml-action">{$aLang.plugin.mailing.ml_action}</td>
                </tr>
            </thead>
            <tbody>
                {foreach from=$aMailing item=oMailing}
                    <tr>
                        <td>
                            {$oMailing->getMailingTitle()|escape:'html'}
                        </td>
                        <td>
                            {$oMailing->getMailingSend()} / {$oMailing->getMailingCount()}
                        </td>
                        <td>
                            {if ($oMailing->getMailingSend() == $oMailing->getMailingCount())}
                                {$aLang.plugin.mailing.ml_send_all}
                            {elseif ($oMailing->getMailingActive())}
                                {$aLang.plugin.mailing.ml_ready}
                            {else}
                                {$aLang.plugin.mailing.ml_wait}
                            {/if}
                            {if $oMailing->getMailingDirect()}{$aLang.plugin.mailing.ml_send_direct}{/if}
                            {if $oMailing->getMailingTalk()}{$aLang.plugin.mailing.ml_send_talk}{/if}
                        </td>
                        <td>
                            {$oMailing->getMailingDate()}
                        </td>
                        <td>

                            {if ($oMailing->getMailingSend() != $oMailing->getMailingCount())}
                                <a class="deactivation-mailing" href="{router page='mailing'}activate/{$oMailing->getMailingId()}">
                                    {if ($oMailing->getMailingActive())}
                                {$aLang.plugin.mailing.ml_deactivation}{else}{$aLang.plugin.mailing.ml_activation}{/if}</a>&nbsp;
                            {/if}
                            {if ($oMailing->getMailingSend() == 0) }
                            <a class="edit-mailing" href="{router page='mailing'}edit/{$oMailing->getMailingId()}">{$aLang.plugin.mailing.ml_edit}</a>&nbsp;
                        {/if}
                        <a class="delete-mailing" href="{router page='mailing'}delete/{$oMailing->getMailingId()}">{$aLang.plugin.mailing.ml_delete}</a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{else}
    {$aLang.plugin.mailing.ml_no_list}
{/if}
</div>

{include file='footer.tpl'}