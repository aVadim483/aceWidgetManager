var $ace = $ace || { };

$ace.blockOnline = {
    title:'',
    subtitle:'',
    head:'',
    info:'',
    options:{},
    list:[],

    request:function () {
        var $that = $ace.blockOnline;
        var params = {
            security_ls_key:LIVESTREET_SECURITY_KEY,
            users_max:$that.options['users_max'],
            users_period:$that.options['users_period'],
            renew_time:$that.options['renew_time'],
            avatar_size:$that.options['avatar_size']
        };

        ls.ajax('usersonline', params, function (result) {
            if (result && !result.bStateError) {
                $that.onLoad(result);
            }
        });

    },

    onLoad:function (result) {
        var $that = $ace.blockOnline;
        if (!result) {
            //msgErrorBox.alert('Error','Please try again later');
        }
        if (result.bStateError) {
            //msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        } else {
            $that.head.text($that.title + ' (' + (result.nUsersTotal) + ')');
            $that.info.css('visibility', '')
                .find('.online_info_regs').text(result.nUsersCount);
            $that.info.find('.online_info_guests').text(result.nUsersTotal - result.nUsersCount);

            $that.list.empty();
            $.each(result.aUsersOnline, function(index, item) {
                var div_name = '', div_avatar = '', div_last = '';

                if ($that.options['show_compact_mode']) $that.list.addClass('compact');
                else $that.list.removeClass('compact');
                /*
                if ($that.options['show_last_time']) {
                    var div_last = '<div class="block_online_last">' + item.last + '</div>';
                }
                if ($that.options['show_username']) {
                    div_name =
                        '<div class="block_online_name">'
                            + '<a href="' + item.link + '">' + ($that.options['show_login_only'] ? item.login : item.name) + '</a>'
                            + '</div>';
                }
                if ($that.options['avatar_size'] > 0) {
                    var title = item.name + ' [' + item.last + ']';
                    div_avatar =
                        '<div class="block_online_avatar">'
                            + '<a href="' + item.link + '"><img src="' + item.avatar + '" alt="' + item.name + '" title="' + title + '"/></a>'
                            + '</div>';
                }
                //$that.list.append($('<li></li>').html(div_last + div_avatar + div_name));
                */
                var li = $('<li></li>');
                if ($that.options['show_last_time'] && !$that.options['show_compact_mode']) {
                   li.append($('<div class="block_online_last">' + item.last + '</div>'));
                }
                if ($that.options['show_username']) {
                    li.append($(
                        '<div class="block_online_name">'
                            + '<a href="' + item.link + '">' + ($that.options['show_login_only'] ? item.login : item.name) + '</a>'
                            + '</div>'));
                }
                if ($that.options['avatar_size'] > 0) {
                    var title = item.name + ' [' + item.last + ']';
                    li.append($(
                        '<div class="block_online_avatar">'
                            + '<a href="' + item.link + '"><img src="' + item.avatar + '" alt="' + item.name + '" title="' + title + '"/></a>'
                            + '</div>'));
                }
                $that.list.append(li);
            });
            if ($that.options['renew_time'] > 0) setTimeout($that.request, 1000 * $that.options['renew_time']);
        }
    }
};


$(function () {
    $ace.blockOnline.head = $('.block.usersonline header h3').first();
    $ace.blockOnline.info = $('#block_online_info').first();
    $ace.blockOnline.list = $('#block_online_list').first();
    $ace.blockOnline.request();
});
