/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var win = window,
globalAdapter = win.WebStoreAdapter,
adapter       = globalAdapter || {},
merge         = adapter.merge;

var _defaultOption = {text: null, closebtn: true, closeid: null, texttype: 'label',acceptbtn: false,acceptid: null,width: 600, height: 400 },
myWidth = 0;

function ws_lightbox (option) {
    
    _defaultOption = merge( _defaultOption, option );
    
    if( typeof( window.innerWidth ) == 'number' ) {
        myWidth = window.innerWidth;
    } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        myWidth = document.documentElement.clientWidth;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        myWidth = document.body.clientWidth;
    }
    
    if(_defaultOption.text != null){
        return call_loghtbox();
    } else {
        return false;
    }
}

function call_loghtbox()
{
    var maindiv = document.createElement("div");
    maindiv.style.cssText = "display: block; height: 100%; width: 100%; visibility: visible;position: fixed;top:0;left:0;background-image:url('/bundles/app/css/images/ws_bg.png');backgroun-repeat:repeat;";
    maindiv.setAttribute('id', 'ws_lightbox_bg');
    
    myWidth = (myWidth - _defaultOption.width)/2;

    var mbgdiv = document.createElement("div");
    mbgdiv.setAttribute('id', 'ws_lightbox_border');

    maindiv.appendChild(mbgdiv);

    var bgdiv = document.createElement("div");
    //bgdiv.style.cssText   = "width:"+ _defaultOption.width +"px;height:"+ _defaultOption.height +"px;margin:auto;background-image:url(/bundles/app/css/images/ws_pattern.png);backgroun-repeat:repeat;z-index:9999;background-color:#e6e6e6;margin-top:100px;padding:0px;border:1px solid #303030;position:relative;";
    bgdiv.setAttribute('id', 'ws_lightbox');
    
    bgdiv.innerHTML = _defaultOption.text;

    mbgdiv.appendChild(bgdiv);
    document.body.appendChild(maindiv);
    
    if( _defaultOption.closeid != null && document.getElementById(_defaultOption.closeid) != null )
    {
        $('* #' + _defaultOption.closeid).click(function() {

            var rdiv = document.getElementById('ws_lightbox_bg');
            rdiv.parentNode.removeChild(rdiv);

            rdiv = document.getElementById('ws_lightbox');
            if (rdiv != null) {
                rdiv.parentNode.removeChild(rdiv);
            }
            document.onkeydown = null;
            return false
        });
    }

    if(_defaultOption.acceptbtn){
        $('* #' + _defaultOption.acceptid).click(function() {

            var rdiv = document.getElementById('ws_lightbox_bg');
            rdiv.parentNode.removeChild(rdiv);

            rdiv = document.getElementById('ws_lightbox');
            if (rdiv != null) {
                rdiv.parentNode.removeChild(rdiv);
            }
            document.onkeydown = null;
            return true
        });
    }
}