{assign var="bNoSidebar" value=true}
{include file='header.tpl' menu='talk'}
<style type="text/css" media='all'> 
    @import url({$sTemplateWebPathPlugin}/css/style.css); 
</style>
<div class="topic people top-blogs talk-table">
    <h1>{$aLang.ml_list}</h1>
    {if ($aMailing)}
        <table>
            <thead>
                <tr>
                    <td class="subject">{$aLang.ml_subj}</td>
                    <td class="progress-bar">{$aLang.ml_progress}</td>
                    <td class="status">{$aLang.ml_status}</td>
                    <td class="datetime">{$aLang.ml_date}</td>
                    <td class="ml-action">{$aLang.ml_action}</td>
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
                                {$aLang.ml_send_all}
                            {elseif ($oMailing->getMailingActive())}
                                {$aLang.ml_ready}
                            {else}
                                {$aLang.ml_wait}
                            {/if}
                        </td>
                        <td>
                            {$oMailing->getMailingDate()}
                        </td>
                        <td>

                            {if ($oMailing->getMailingSend() != $oMailing->getMailingCount())}
                                <a class="deactivation-mailing" href="{router page='mailing'}activate/{$oMailing->getMailingId()}">
                                    {if ($oMailing->getMailingActive())}
                                {$aLang.ml_deactivation}{else}{$aLang.ml_activation}{/if}</a>&nbsp;
                            {/if}
                            {if ($oMailing->getMailingSend() == 0) }
                            <a class="edit-mailing" href="{router page='mailing'}edit/{$oMailing->getMailingId()}">{$aLang.ml_edit}</a>&nbsp;
                        {/if}
                        <a class="delete-mailing" href="{router page='mailing'}delete/{$oMailing->getMailingId()}">{$aLang.ml_delete}</a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{else}
    {$aLang.ml_no_list}
{/if}
</div>
{include file='footer.tpl'}