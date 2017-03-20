var ru = {};
ru.unisender = {};

ru.unisender.apierror = function(code){
    if(typeof(ru.unisender.apierror[code])=='function')
    {
        ru.unisender.apierror[code].call(null);
    }
};

ru.unisender.i18n={};
ru.unisender.i18n['Invalid api key'] = 'Invalid api key';
ru.unisender.i18n['Enter your unisender api key'] = 'Enter your unisender api key';
ru.unisender.i18n['Some fields were not filled properly'] = 'Some fields were not filled properly';
ru.unisender.i18n['Title is empty'] = 'Title is empty';
ru.unisender.i18n['Name is empty'] = 'Name is empty';
ru.unisender.i18n['Field with this name already exists'] = 'Field with this name already exists';
ru.unisender.i18n['Subscribe'] = 'Subscribe';
ru.unisender.i18n['Unisender API not available now. Try again later.'] = 'Unisender API not available now. Try again later.';
ru.unisender.i18n['API response'] = 'API response';

ru.unisender.i18n._ = function(key){
    if(typeof(ru.unisender.i18n[key])=='string')
    {
        return ru.unisender.i18n[key];
    }
    else
    {
        return key;
    };
};