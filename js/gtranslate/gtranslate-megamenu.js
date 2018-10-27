/*
Template: AdForest | Largest Classifieds Portal
Author: ScriptsBundle
Version: 1.0
Designed and Development by: ScriptsBundle
*/
function googleTranslateElementInit2()
{
    new google.translate.TranslateElement({pageLanguage: 'th', autoDisplay: false}, 'google_translate_element2')
}

function GTranslateGetCurrentLang()
{
    var keyValue = document['cookie'].match('(^|;) ?googtrans=([^;]*)(;|$)');
    return keyValue ? keyValue[2].split('/')[2] : null;
}

function GTranslateFireEvent(element,event)
{
    try{
        if(document.createEventObject){
            var evt=document.createEventObject();
            element.fireEvent('on'+event,evt)
        }else{
            var evt=document.createEvent('HTMLEvents');
            evt.initEvent(event,true,true);
            element.dispatchEvent(evt)
        }}catch(e){}
}

function doGTranslate(lang_pair)
{
    if(lang_pair.value)lang_pair=lang_pair.value;
    if(lang_pair=='')return;
    var lang=lang_pair.split('|')[1];
    if(GTranslateGetCurrentLang() == null && lang == lang_pair.split('|')[0])return;
    var teCombo;var sel=document.getElementsByTagName('select');
    for(var i=0;i<sel.length;i++)
        if(/goog-te-combo/.test(sel[i].className)){
            teCombo=sel[i];break;
        }
    if(document.getElementById('google_translate_element2')==null||document.getElementById('google_translate_element2').innerHTML.length==0||teCombo.length==0||teCombo.innerHTML.length==0)
    {
        setTimeout(function(){
            doGTranslate(lang_pair)
        },500)
    } else
    {
        teCombo.value=lang;GTranslateFireEvent(teCombo,'change');
        GTranslateFireEvent(teCombo,'change')
    }
}

function doChangeMain(base_url, lang, name) {
    jQuery('#main-lang').html('<img height="16" width="16" alt="en" src="' + base_url + '/flags/16/' + lang + '.png">'+name+'<i class="fa fa-angle-down fa-indicator"></i>')
}

if(GTranslateGetCurrentLang() != null)
{
    jQuery(document).ready(function() {
        var lang_html = jQuery('div.switcher div.option').find('img[alt="'+GTranslateGetCurrentLang()+'"]').parent().html();
        if(typeof lang_html != 'undefined')
        jQuery('div.switcher div.selected a').html(lang_html.replace('data-gt-lazy-', ''));
    });
}       