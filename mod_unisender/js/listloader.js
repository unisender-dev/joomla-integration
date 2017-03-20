var ru;
if(!ru || !ru.unisender)
{
    throw new Error('Namespace ru.unisender is not found');
}

(function($){
    $(document).ready(function(){
        ru.unisender.wp_plugin_url = $('#id-wp_plugin_url').val();
    });

    ru.unisender.ListLoader = function (listId,$list,$title,opts)
    {
        var _this = this;
        var initListId = (listId)?listId:0;
        var $progress = $('<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" class="ajaxloader" alt="Loading..."/>');

        var OPTIONS = {"apiproxy_url":ru.unisender.wp_plugin_url+'/unisender/apiproxy.php'};

        if(opts)
        {
            OPTIONS = $.extend(OPTIONS,opts);
        };

        if($list && $list.length>0)
        {
            $list.after($progress);
            $progress.css({'visibility':'hidden','vertical-align':'middle'});
        }
        else
        {
            throw new Error('list node not found');
        };

        _this.disable = function(){
            $list.attr('readonly','readonly');
        };

        _this.loadList = function(val){
            $list.html('');
            $progress.css('visibility','visible');
            $.ajax({
                'url':OPTIONS["apiproxy_url"],
                'type':'post',
                'data':'function=getLists&data='+encodeURIComponent('api_key='+ru.unisender.api_key),
                'dataType':'json',
                success:function(resp){
                    if(resp)
                    {
                        if(!resp.error)
                        {
                            var optsHTML = '';
                            for(var i=0,len=resp.result.length;i<len;i++)
                            {
                                optsHTML+='<option value="' + resp.result[i].id + '"'+((initListId==parseInt(resp.result[i].id))?' selected="selected"':'')+'>' + resp.result[i].title + '</option>';
                            }
                            $list.html(optsHTML);
                            $title.val($list.find('option:selected').text());
                        }
                        else
                        {
                            var msg=ru.unisender.i18n._("API response")+": {error:"+resp.error;
                            if(resp.code)
                            {
                                msg+=", code: "+resp.code+"}";
                            }
                            if (ru.unisender.api_key != '')
                                alert(msg);
                        }
                    }
                    else
                    {
                        alert(ru.unisender.i18n._("Unisender API not available now. Try again later."));
                    }
                },
                error:function(){
                },
                complete:function(){$progress.css('visibility','hidden');}
            });
        };

        $list.change(function(){
            $title.val($list.find('option:selected').text());
        });

        //init
        _this.loadList(initListId);

    };

})(jQuery);
