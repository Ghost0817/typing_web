/**
 * Created by esaintor on 10/3/14.
 */
//(function(){

    function init(options)
    {
        var defaultOption = { text: [], lang: "mongolian", onfinish: { } };
        var typing = "",
            currentRow = 0,
            currentIndex = 0,
            lang = "mongolian",
            errorNum = [],
            errorChar = [],
            errored = 0,
            target,
            inputTags = ['INPUT', 'TEXTAREA'],
            examtime = 0,
            maxwidth,
            last_row = 0,
            last_index = 0,
            alllength = 0,
            current,
            shiftbttn,
            chiesenone,
            space,
            lefthand,
            time,
            wpm,
            PermissiblePercent,
            procent,
            started = false,
            t,
            cc = 0,
            isFieled = false,
            rfinish = false,
            righthand;

        defaultOption = merge(defaultOption, options);

        //get text
        typing = defaultOption.text.split("\n");
        last_row = defaultOption.text.split("\n").length;
        last_index = defaultOption.text.split("\n")[last_row-1].length;
        //get lang
        lang = options.lang;
        //Rendering target
        target      = document.getElementById(options.render.renderTo);
        current     = document.getElementById(options.render.currentbttn);
        shiftbttn   = document.getElementById(options.render.shiftbttn);
        chiesenone  = document.getElementById(options.render.chiesenone);
        space       = document.getElementById(options.render.space);
        lefthand    = document.getElementById(options.render.lefthand);
        righthand   = document.getElementById(options.render.righthand);
        time        = document.getElementById(options.render.timer);
        wpm         = document.getElementById(options.render.wpm);
        procent     = document.getElementById(options.render.procent);
        PermissiblePercent = options.PermissiblePercent;
        layout = options.layout;
        examtime = options.examtime;

        _isFinished = false;

        maxwidth = 720;

        if (examtime == 0) {
            time.innerHTML = _rtime;
        } else {
            var ffff = parseInt((examtime - cc) / 60);
            var jjjj = (examtime - cc) % 60;
            time.innerHTML = (ffff.toString().length == 1 ? "0" + ffff : ffff) + ":" + (jjjj.toString().length == 1 ? "0" + jjjj : jjjj);
            _rtime = (ffff.toString().length == 1 ? "0" + ffff : ffff) + ":" + (jjjj.toString().length == 1 ? "0" + jjjj : jjjj);
        }

        target.innerHTML = renderText(typing);

        function renderText(text) {

            if (_isFinished == true) {return; }

            var result = "<div class=\"line\">";
            var j = 0;
            var too = text.length;
            alllength = 0;
            for (var i = 0; i < too; i++) {
                for (var y = 0; y < text[i].length; y++) {
                    if (i < currentRow) {
                        if (in_array(i + "," + y, errorNum)) {
                            result += "<span class=\"error\">" + (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) + "</span>";
                        }
                        else {
                            result += "<span class=\"dis\">" + (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) + "</span>";
                        }
                    }
                    if (i == currentRow && y < currentIndex) {
                        if (in_array(i + "," + y, errorNum)) {
                            result += "<span class=\"error\">" + (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) + "</span>";
                        }
                        else {
                            result += "<span class=\"dis\">" + (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) + "</span>";
                        }
                    }
                    if (i == currentRow && y >= currentIndex) {
                        if (currentIndex == y && currentRow == i) {
                            result += "<span class=\"active\">" + (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) + "</span>";
                        }
                        else {
                            result += "<span>" + (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) + "</span>";
                        }
                    }
                    if (i > currentRow) {
                        result += "<span>" + (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) + "</span>";
                    }
                }
                alllength += text[i].length;
                result += "</div><div class=\"line\">";
            }
            result += "</div>";
            if (layout == "Keyboard") {
                for (var i = 0; i < _mykb.length; i++) {
                    if (_mykb[i]["char"] == text[currentRow][currentIndex])
                    {
                        current.style.display = _mykb[i]["currentstyledisplay"];
                        current.style.top = _mykb[i]["currentstyletop"];
                        current.style.left = _mykb[i]["currentstyleleft"];
                        shiftbttn.style.display = _mykb[i]["shiftbttnstyledisplay"];
                        righthand.style.display = _mykb[i]["righthandstyledisplay"];
                        righthand.style.top = _mykb[i]["righthandstyletop"];
                        righthand.style.left = _mykb[i]["righthandstyleleft"];
                        shiftbttn.style.top = _mykb[i]["shiftbttnstyletop"];
                        shiftbttn.style.left = _mykb[i]["shiftbttnstyleleft"];
                        chiesenone.style.display = _mykb[i]["chiesenonestyledisplay"];
                        space.style.display = _mykb[i]["spacestyledisplay"];
                        lefthand.style.display = _mykb[i]["lefthandstyledisplay"];
                        lefthand.style.top = _mykb[i]["lefthandstyletop"];
                        lefthand.style.left = _mykb[i]["lefthandstyleleft"];
                    }
                }
            }

            if (layout == "Keypad") {
                switch(text[currentRow][currentIndex]){
                    case "0":
                        current.style.display = "block";
                        current.style.top = "132px";
                        current.style.left = "4px";
                        current.style.height = "26px";
                        current.style.width = "58px";
                        righthand.style.display = "block";
                        righthand.style.top = "50px";
                        righthand.style.left = "25px";
                        break;

                    case "1":
                        current.style.display = "block";
                        current.style.top = "100px";
                        current.style.left = "4px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "12px";
                        righthand.style.left = "46px";
                        break;

                    case "2":
                        current.style.display = "block";
                        current.style.top = "100px";
                        current.style.left = "36px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "2px";
                        righthand.style.left = "68px";
                        break;

                    case "3":
                        current.style.display = "block";
                        current.style.top = "100px";
                        current.style.left = "68px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "7px";
                        righthand.style.left = "84px";
                        break;

                    case "4":
                        current.style.display = "block";
                        current.style.top = "68px";
                        current.style.left = "4px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "12px";
                        righthand.style.left = "46px";
                        break;

                    case "5":
                        current.style.display = "block";
                        current.style.top = "68px";
                        current.style.left = "36px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "2px";
                        righthand.style.left = "68px";
                        break;

                    case "6":
                        current.style.display = "block";
                        current.style.top = "68px";
                        current.style.left = "68px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "7px";
                        righthand.style.left = "84px";
                        break;

                    case "7":
                        current.style.display = "block";
                        current.style.top = "36px";
                        current.style.left = "4px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "12px";
                        righthand.style.left = "46px";
                        break;

                    case "8":
                        current.style.display = "block";
                        current.style.top = "36px";
                        current.style.left = "36px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "2px";
                        righthand.style.left = "68px";
                        break;

                    case "9":
                        current.style.display = "block";
                        current.style.top = "36px";
                        current.style.left = "68px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "7px";
                        righthand.style.left = "84px";
                        break;

                    case "-":
                        current.style.display = "block";
                        current.style.top = "5px";
                        current.style.left = "100px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "22px";
                        righthand.style.left = "105px";
                        break;

                    case "+":
                        current.style.display = "block";
                        current.style.top = "36px";
                        current.style.left = "100px";
                        current.style.height = "58px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "22px";
                        righthand.style.left = "105px";
                        break;

                    case "/":
                        current.style.display = "block";
                        current.style.top = "4px";
                        current.style.left = "36px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "2px";
                        righthand.style.left = "68px";
                        break;

                    case "*":
                        current.style.display = "block";
                        current.style.top = "4px";
                        current.style.left = "68px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "7px";
                        righthand.style.left = "84px";
                        break;

                    case ".":
                        current.style.display = "block";
                        current.style.top = "132px";
                        current.style.left = "68px";
                        current.style.height = "26px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "7px";
                        righthand.style.left = "84px";
                        break;

                    case " ":
                        current.style.display = "block";
                        current.style.top = "100px";
                        current.style.left = "100px";
                        current.style.height = "58px";
                        current.style.width = "26px";
                        righthand.style.display = "block";
                        righthand.style.top = "22px";
                        righthand.style.left = "105px";
                        break;
                }
            }
            return result;
        }

        window.onfocus = function() {
            if($('* #current').css('display') == "block")
            {
                $('* #current').addClass("moving");
                setTimeout(function(){$('#current').removeClass("moving");},1000);
            }
            if($('* #shiftbttn').css('display') == "block")
            {
                $('* #shiftbttn').addClass("moving");
                setTimeout(function(){$('#shiftbttn').removeClass("moving");},1000);
            }
            if($('* #slash').css('display') == "block")
            {
                $('* #slash').addClass("moving");
                setTimeout(function(){$('#slash').removeClass("moving");},1000);
            }
            if($('* #spacebttn').css('display') == "block")
            {
                $('* #spacebttn').addClass("moving");
                setTimeout(function(){$('#spacebttn').removeClass("moving");},1000);
            }
        }

        window.onkeydown = function (e) {
            if (e.keyCode == 8){

                if(currentRow >= 0 )
                {
                    if ( currentIndex > 0 )
                    {
                        currentIndex = currentIndex - 1;

                        for(var i=0; i < errorNum.length; i++) {
                            if(errorNum[i] == currentRow+","+currentIndex) {
                                errorNum.splice(i, 1);
                                break;
                            }
                        }
                    }
                    else
                    {
                        if(currentRow > 0)
                        {
                            currentRow -= 1;
                            currentIndex = typing[currentRow].length - 1;
                        }
                    }
                    target.innerHTML = renderText(typing);
                }

                if (navigator.userAgent.toLowerCase().indexOf("msie") == -1) {
                    e.stopPropagation();
                } else {
                    e.cancelBubble = true;
                    e.returnValue = false;
                }
                return false;
            }
        }

        eventUtility.addEvent(document, "keypress",
            function(evt) 
            {

                if (rfinish == true) { return; }  // хэрвээ дууссан байх юм бол ямарч үйлдэл хийхгүй.
                var code = eventUtility.getCharCode(evt);
                if(code == 13){ code = 32;}
                if (currentRow+1 == last_row && currentIndex+1 == last_index)
                {
                    clearInterval(t);
                    defaultOption.onfinish();
                    _isFinished = true;
                    rfinish = true;
                    if( _procent < PermissiblePercent ){
                        _isFinished = false;
                    }
                }

                if(code == "8")
                {

                }
                else
                {
                    if(String.fromCharCode(code) == typing[currentRow][currentIndex])
                    {
                        _correcthit += 1;
                        if(typing[currentRow].length == currentIndex+1)
                        {
                            currentIndex = 0;
                            currentRow +=1;
                        }
                        else {
                            currentIndex += 1;
                        }
                        isFieled = false;
                        target.innerHTML = renderText(typing);
                        if(started == false)
                        {
                            started = true;
                            t = setInterval(function(){
                                    cc+=1;

                                    if (examtime == 0) {
                                        var ffff = parseInt(cc / 60);
                                        var jjjj = cc % 60;
                                        time.innerHTML = (ffff.toString().length == 1 ? "0" + ffff : ffff) + ":" + (jjjj.toString().length == 1 ? "0" + jjjj : jjjj);
                                        _rtime = (ffff.toString().length == 1 ? "0" + ffff : ffff) + ":" + (jjjj.toString().length == 1 ? "0" + jjjj : jjjj);
                                    } else {
                                        var ffff = parseInt((examtime - cc) / 60);
                                        var jjjj = (examtime - cc) % 60;
                                        time.innerHTML = (ffff.toString().length == 1 ? "0" + ffff : ffff) + ":" + (jjjj.toString().length == 1 ? "0" + jjjj : jjjj);
                                        _rtime = (ffff.toString().length == 1 ? "0" + ffff : ffff) + ":" + (jjjj.toString().length == 1 ? "0" + jjjj : jjjj);
                                    }

                                    var minute = cc / 60;

                                    var crosswpm = ( alllength / 5 );
                                    crosswpm = crosswpm / minute;
                                    _wpm = crosswpm;
                                    var netwpm = crosswpm - ( errored / minute );
                                    _netwpm = parseInt(netwpm);
                                    wpm.innerHTML = parseInt(netwpm);
                                    _procent = parseInt((netwpm / crosswpm) * 100);
                                    procent.innerHTML = parseInt((_correcthit - errored) * 100 / _correcthit)+"%";
                                    _time = cc;

                                    if (examtime != 0 && examtime == cc){
                                        _isFinished = false;
                                        clearInterval(t);
                                        eventUtility.removeEvent(document,"keypress", this);
                                        defaultOption.onfinish();
                                    }
                                },
                                1000);
                        }
                    }
                    else
                    {
                        _mp3.play();

                        if(started == true)
                        {
                            if(isFieled == false)
                            {
                                errorNum.push(currentRow+","+currentIndex);
                                if(typing[currentRow][currentIndex] != ' '){
                                    var is_entered_list = false;
                                    var o = 0;
                                    for(o = 0; o < errorChar.length; o++)
                                    {
                                        if(errorChar[o] == typing[currentRow][currentIndex]) { is_entered_list = true; }
                                    }
                                    if(is_entered_list == false) {
                                        errorChar.push(typing[currentRow][currentIndex]);
                                    }
                                }
                                if(typing[currentRow].length == currentIndex+1)
                                {
                                    currentIndex = 0;
                                    currentRow +=1;
                                }
                                else {
                                    currentIndex += 1;
                                }
                                errored += 1;
                                _mistakehit += 1;
                                isFieled = true;
                                target.innerHTML = renderText(typing);
                            }
                            else
                            {

                            }
                        }
                    }
                }
                _erroredChar = errorChar;
            }
        );
    }


    function in_array (needle, haystack, argStrict) {
        // http://kevin.vanzonneveld.net
        // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   improved by: vlado houba
        // +   input by: Billy
        // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
        // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
        // *     returns 1: true
        // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
        // *     returns 2: false
        // *     example 3: in_array(1, ['1', '2', '3']);
        // *     returns 3: true
        // *     example 3: in_array(1, ['1', '2', '3'], false);
        // *     returns 3: true
        // *     example 4: in_array(1, ['1', '2', '3'], true);
        // *     returns 4: false
        var key = '',
            strict = !! argStrict;

        if (strict) {
            for (key in haystack) {
                if (haystack[key] === needle) {
                    return true;
                }
            }
        } else {
            for (key in haystack) {
                if (haystack[key] == needle) {
                    return true;
                }
            }
        }

        return false;
    }

//}());