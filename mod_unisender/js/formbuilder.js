var ru;
if(!ru || !ru.unisender)
{
    throw new Error("Namespace ru.unisender not found");
}

(function($){

    var $dialog;
    var $dlgInpTitle;
    var $dlgInpName;
    var $dlgNote;

    var $fieldsHid;
    var $addBtn;

    var $fieldslist;
    var $hiddenCont;

    var formAction;

    var $formHtml;

    function appendField(name,title,checked)
    {
       var mand = 0;
       if(checked){
           mand = 1;
       };

       var li = '<li class="item dynamic" rel="'+name+'">\n\
                <span class="rmbtm"><button rel="'+name+'">х</button></span>\n\
                <span class="mandchk"><input type="checkbox" rel="'+name+'"'+((mand)?' checked="checked"':'')+'/><sup>*</sup></span>\n\
                <span class="titlename"><span class="title">'+title+'</span><br/><span class="name">('+name+')</span></span>\n\
            </li>';
        var len = Math.ceil($hiddenCont.find('input[type="hidden"]').length/3);
        var hidden='<input type="hidden" name="jform[params][field_'+(len)+'_name]" value="'+name+'" rel="'+name+'"/>';
        hidden+='<input type="hidden" name="jform[params][field_'+(len)+'_title]" value="'+title+'" rel="'+name+'"/>';
        hidden+='<input type="hidden" name="jform[params][field_'+(len)+'_mand]" value="'+mand+'" rel="'+name+'" class="mand"/>';

        $hiddenCont.append(hidden);
        $fieldslist.append(li);
        $fieldslist.find('button[rel="'+name+'"]').click(function(e){
            e.preventDefault();
            removeField($(this).attr('rel'));
        });
        $fieldslist.find('input[type="checkbox"][rel="'+name+'"]').click(function(){
            var $this = $(this);
            var chk = $this.attr("checked");
            $hiddenCont.find('input[type="hidden"][rel="'+$this.attr('rel')+'"][class="mand"]').val((chk?1:0));
            $formHtml.val(buildFormHtml(formAction, getListItems()));
        });

        $formHtml.val(buildFormHtml(formAction, getListItems()));
    };

    function removeField(name)
    {
        if(name && name.length)
        {
            $fieldslist.children('.item[rel="'+name+'"]').remove();
            $hiddenCont.children('input[rel="'+name+'"]').remove();
            rearrange();
        }
    };

    function buildFormHtml(action,fields)
    {
        var out = '<form action="'+action+'" method="post" class="us-subscribeform">';
        out+='<dl class="us-subscribe-fields">';
        for(var i=0,len=fields.length;i<len;i++)
        {
            out+='<dt><label for="'+fields[i]['name']+'">'+fields[i]['title']+(fields[i]['mand']?'<sup>*</sup>':'')+':</label></dt><dd><input type="text" name="'+fields[i]['name']+'"></dd>';
        }
        out+='</dl>';
        out+='<div class="us-submit"><input type="submit" value="'+ru.unisender.i18n._('Subscribe')+'" name="unisender_subscribe"/></div>';
        out+="</form>";
        return out;
    };

    function getListItems()
    {
        var items = [];
        $fieldslist.find('.item').each(function(){
            var $this = $(this);
            var name = $this.attr('rel');
            var title = $this.find('.title').text();
            var mand = ($this.find('input[type="checkbox"][rel="'+name+'"]:checked').length==1)?1:0;
            items.push({'name':name,'title':title,'mand':mand});
        });

        return items;
    }

    function rearrange()
    {
        var hiddHtml = '';
        var num = 0;
        var fields = getListItems();
        for(var i=0,len=fields.length;i<len;i++)
        {
            hiddHtml+='<input type="hidden" name="jform[params][field_'+(i)+'_name]" value="'+fields[i].name+'" rel="'+fields[i].name+'"/>';
            hiddHtml+='<input type="hidden" name="jform[params][field_'+(i)+'_title]" value="'+fields[i].title+'" rel="'+fields[i].name+'"/>';
            hiddHtml+='<input type="hidden" name="jform[params][field_'+(i)+'_mand]" value="'+fields[i].mand+'" rel="'+fields[i].name+'" class="mand"/>';
        }

        $hiddenCont.html(hiddHtml);
        var fHtml = buildFormHtml(formAction, fields);
        $formHtml.val(fHtml);
    };

    $(document).ready(function(){
        // remove form params
        for(var i=0; i<10; i++)
        {
            $('#jform_params_field_'+i+'_name').remove();
            $('#jform_params_field_'+i+'_title').remove();
            $('#jform_params_field_'+i+'_mand').remove();
        }


        $formHtml = $('#jform_params_UNISENDER_FORM');
        formAction = $('#id-formaction').val();

        $fieldslist = $('#id-forfields');

        $dialog = $('#id-addfielddlg');
        $dlgInpTitle = $('#id-newfieldtitle');
        $dlgInpName = $('#id-newfieldname');
        $dlgNote = $('#id-dlgnote');

        $addBtn = $('#id-addnewfield');
        $hiddenCont = $('#id-fieldsinfocont');

        var dlgButtons = {};
        dlgButtons[ru.unisender.i18n._('Ok')] = function() {
                    var errors = [];
                    $dlgInpTitle.removeClass('invalid');
                    $dlgInpName.removeClass('invalid');
                    $dlgNote.html('').hide();
                    var title = $.trim($dlgInpTitle.val());
                    var name = $.trim($dlgInpName.val());

                    if(title.length==0)
                    {
                        $dlgInpTitle.addClass('invalid');
                        errors.push(ru.unisender.i18n._('Title is empty'));
                    };
                    if(name.length==0)
                    {
                        $dlgInpName.addClass('invalid');
                        errors.push(ru.unisender.i18n._('Name is empty'));
                    }else
                    {
                        if(name=='email' || $hiddenCont.find('input[rel="'+name+'"]').length>0)
                        {
                            errors.push(ru.unisender.i18n._('Field with this name already exists'));
                            $dlgInpName.addClass('invalid');
                        }
                    };
                    if(errors.length == 0)
                    {
                        appendField(name, title, false);
                        $dialog.dialog("close");
                        $dlgInpName.val('');
                        $dlgInpTitle.val('');
                    }
                    else
                    {
                        $dlgNote.html(errors.join('<br/>')).show();
                    };
                };
        dlgButtons[ru.unisender.i18n._('Cancel')] = function() {
                    $dlgInpTitle.removeClass('invalid');
                    $dlgInpName.removeClass('invalid');
                    $dlgNote.text('').hide();
                    $dialog.dialog("close");
                };
        $dialog.dialog({autoOpen:false,
            buttons:dlgButtons, modal:true, width:350, height:200});

        $('#id-forfields').sortable({stop:rearrange});

        $addBtn.click(function(e){
            e.preventDefault();
            //$dialog.dialog("option", "position", 'center');
            //$dialog.dialog("option", "position",  [350,100]);
            $dialog.dialog('open');
            $dialog.parent().css('top', $(window).height()/2-$dialog.height()/2);
            $dialog.parent().css('left', $(window).width()/2-$dialog.width()/2);
            });

        $fieldslist.find('.rmbtm').find('button').click(function(e){
            e.preventDefault();
            removeField($(this).attr('rel'));
        });
        $fieldslist.find('input[type="checkbox"]').click(function(){
            var $this = $(this);
            var chk = $this.attr("checked");
            $hiddenCont.find('input[type="hidden"][rel="'+$this.attr('rel')+'"][class="mand"]').val((chk?1:0));
            $formHtml.val(buildFormHtml(formAction, getListItems()));
        });

        $formHtml.val(buildFormHtml(formAction,getListItems()));

    });

})(jQuery);
