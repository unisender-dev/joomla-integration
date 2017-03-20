(function($){
    $(document).ready(function(){

        //var $apiInp = $('#id-apikey');
        var $apiInp = $('#jform_params_UNISENDER_KEY');
        //var $listSelect = $('#id-unisender_list_name');
        var $listSelect = $('#jform_params_UNISENDER_LIST');
        var $msgApi = $('<small></small>');
        var $msgList = $('<small></small>');
        $apiInp.after($msgApi);
        $listSelect.after($msgList);
        ru.unisender.api_key = $apiInp.val();
        var $listHiddenTitle = $('#id-unisender_list_title');

        ru.unisender.apierror['invalid_api_key'] = function(){
           $msgApi.text(ru.unisender.i18n._('Invalid api key'));
           $msgApi.animate({opacity:0}, 3000,null,function(){$msgApi.css('opacity',1).text('');});
        };

        if(!ru.unisender.api_key)
        {
            $msgApi.text('Enter your unisender api key');
            $msgApi.animate({opacity:0}, 5000);
        }

        var listNameId = parseInt($('#id-unisender_list_name_preset').val());
        var mailingList = new ru.unisender.ListLoader(listNameId,$listSelect,$listHiddenTitle,{"apiproxy_url":$("#id-proxyurl").val()});

        var timer;
        function updateList(){
            clearInterval(timer);
            timer = setInterval(function(){
                clearInterval(timer);
                var newKey = $apiInp.val();
                if(newKey!=ru.unisender.api_key)
                {
                    ru.unisender.api_key = newKey;
                    mailingList.loadList(listNameId);
                }
            }, 300);
        };

        $apiInp.keyup(function(){updateList();})
               .mouseup(function(){updateList();});

    });
})(jQuery);
