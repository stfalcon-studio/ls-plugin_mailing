<li{if ($sEvent!='list') && ($sAction == 'mailing')} class="active"{/if}>
    <div><a href="{router page='mailing'}">{$aLang.plugin.mailing.ml_title}</a></div>
</li>
<li{if $sEvent=='list'} class="active"{/if}>
    <div><a href="{router page='mailing'}list">{$aLang.plugin.mailing.ml_list}</a></div>
</li>