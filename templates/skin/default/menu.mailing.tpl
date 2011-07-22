<li {if $sEvent=='main'}class="active"{/if}>
    <div><a href="{router page='mailing'}">{$aLang.ml_title}</a></div>
</li>
<li {if $sEvent=='list'}class="active"{/if}>
    <div><a href="{router page='mailing'}list">{$aLang.ml_list}</a></div>
</li>