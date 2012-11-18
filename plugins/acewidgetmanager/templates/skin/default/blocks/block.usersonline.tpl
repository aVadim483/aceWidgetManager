{literal}
<style type="text/css">
    #block_online_list li {
    }

    #block_online_list.compact li {
        display: block;
        float: left;
        padding-left: 1px;
        padding-right: 1px;
    }

    div.block_online_last {
        float: right;
    }

    div.block_online_avatar {
        float: left;
    }

    div.block_online_name {
    }

    div.block_online_name a {
        text-decoration: none;
    }
</style>

{/literal}

<script type="text/javascript">
    if ($ace.blockOnline) {
        $ace.blockOnline.title = '{$oLang->_block_users_online}';
        $ace.blockOnline.subtitle = '{$oLang->_block_users_online_regs}, {$oLang->_block_users_online_guests}';
        $ace.blockOnline.options.users_max = '{$aUsersOnlineParam.users_max}';
        $ace.blockOnline.options.users_period = '{$aUsersOnlineParam.users_period}';
        $ace.blockOnline.options.renew_time = '{$aUsersOnlineParam.renew_time}';
        $ace.blockOnline.options.show_last_time = '{$aUsersOnlineParam.show_last_time}';
        $ace.blockOnline.options.avatar_size = '{$aUsersOnlineParam.show_avatar}';
        $ace.blockOnline.options.show_username = '{$aUsersOnlineParam.show_username}';
        $ace.blockOnline.options.show_login_only = '{$aUsersOnlineParam.show_login_only}';
        $ace.blockOnline.options.show_compact_mode = '{$aUsersOnlineParam.show_compact_mode}';
    }
</script>

<section class="block usersonline">
    <header class="block-header sep">
        <h3>{$oLang->_block_users_online}</h3>
    </header>
    <div class="block-content">
        <div id="block_online_info" style="visibility: hidden;">
            {$oLang->_block_users_online_regs} <span class="online_info_regs"></span>,
            {$oLang->_block_users_online_guests} <span class="online_info_guests"></span>
        </div>
        <ul class="latest-list clearfix" id="block_online_list">
        {foreach from=$aUsersOnline item=aUser}
            <li>
                <div class="block_online_last" style="float:right;">{$aUser.last}</div>
                <div class="block_online_last">
                    {if $aUsersOnlineParam.show_avatar}<a href="{$aUser.link}">{$aUser.avatar}</a>{/if}
                    <a href="{$aUser.link}">{$aUser.login}</a>
                </div>
            </li>
        {/foreach}
        </ul>
        <footer><a href="{router page='people'}online/">{$oLang->_block_users_online_all}</a></footer>
    </div>
</section>