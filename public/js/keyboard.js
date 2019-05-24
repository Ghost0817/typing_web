//window.onload = 

// globale values


function init(options)
{
    var defaultOption = { text: [], lang: "mongolian", onfinish: { } };
    var notyping = "", 
        typing = "",
        currentRow = 0,
        currentIndex = 0, 
        lang = "mongolian", 
        errorNum = [], 
        errorChar = [],
        errored = 0,
        target,
        inputTags = ['INPUT', 'TEXTAREA'],
        maxwidth,
        last_row = 0,
        last_index = 0,
        alllength = 0,
        shiftHolder=0, 
        current, 
        shiftbttn,
        chiesenone, 
        space,
        lefthand,
        time,
        wpm,
        procent,
        started = false,
        t,
        cc = 0,
        isFieled = false,
        righthand;
		
    var keyCodeToChar_en = {8:"Backspace",9:"Tab",13:"Enter",16:"Shift",17:"Ctrl",18:"Alt",19:"Pause/Break",20:"Caps Lock",27:"Esc",32:"Space",33:"Page Up",34:"Page Down",35:"End",36:"Home",37:"Left",38:"Up",39:"Right",40:"Down",45:"Insert",46:"Delete",48:"0",49:"1",50:"2",51:"3",52:"4",53:"5",54:"6",55:"7",56:"8",57:"9",65:"a",66:"b",67:"c",68:"d",69:"e",70:"f",71:"g",72:"h",73:"i",
74:"j",75:"k",76:"l",77:"m",78:"n",79:"o",80:"p",81:"q",82:"r",83:"s",84:"t",85:"u",86:"v",87:"w",88:"x",89:"y",
90:"z",91:"Windows",93:"Right Click",96:"Numpad 0",97:"Numpad 1",98:"Numpad 2",99:"Numpad 3",100:"Numpad 4",101:"Numpad 5",
102:"Numpad 6",103:"Numpad 7",104:"Numpad 8",105:"Numpad 9",106:"Numpad *",107:"Numpad +",109:"Numpad -",110:"Numpad .",111:"Numpad /",112:"F1",113:"F2",114:"F3",115:"F4",116:"F5",117:"F6",118:"F7",119:"F8",120:"F9",121:"F10",122:"F11",123:"F12",144:"Num Lock",145:"Scroll Lock",182:"My Computer",183:"My Calculator",186:";",187:"=",188:",",189:"-",190:".",191:"/",192:"`",219:"[",220:"\\",221:"]",222:"'",1648:")",1649:"!",1650:"@",1651:"#",1652:"$",1653:"%",1654:"^",1655:"&",1656:"*",1657:"(",16189:"_",16187:"+",16219:"{",16221:"}",16190:">",16191:"?",16188:"<",16192:"~",1665:"A",1666:"B",1667:"C",1668:"D",1669:"E",1670:"F",1671:"G",1672:"H",1673:"I",1674:"J",1675:"K",1676:"L",1677:"M",1678:"N",1679:"O",1680:"P",1681:"Q",1682:"R",1683:"S",1684:"T",1685:"U",1686:"V",1687:"W",1688:"X",1689:"Y",1690:"Z",16186:":",16222:"\"",16220:"|"};
		
    var keyCodeToChar_mn = {8:"Backspace",9:"Tab",13:"Enter",16:"Shift",17:"Ctrl",18:"Alt",19:"Pause/Break",20:"Caps Lock",27:"Esc",32:"Space",33:"Page Up",34:"Page Down",35:"End",36:"Home",37:"Left",38:"Up",39:"Right",40:"Down",45:"Insert",46:"Delete",48:"?",49:"№",50:"-",51:"\"",52:"₮",53:":",54:".",55:"_",56:",",57:"%",65:"й",66:"м",67:"ё",68:"б",69:"у",70:"ө",71:"а",72:"х",73:"ш",74:"р",75:"о",76:"л",77:"т",78:"и",79:"ү",80:"з",81:"ф",82:"ж",83:"ы",84:"э",85:"г",86:"с",87:"ц",88:"ч",89:"н",90:"я",91:"Windows",93:"Right Click",96:"Numpad 0",97:"Numpad 1",98:"Numpad 2",99:"Numpad 3",100:"Numpad 4",101:"Numpad 5",102:"Numpad 6",103:"Numpad 7",104:"Numpad 8",105:"Numpad 9",106:"Numpad *",107:"Numpad +",109:"Numpad -",110:"Numpad .",111:"Numpad /",186:"д",		187:"щ",188:"ь",189:"е",190:"в",191:"ю",192:"=",219:"к",220:"\\",221:"ъ",222:"п",1648:"0",1649:"1",1650:"2",1651:"3",1652:"4",			1653:"5",1654:"6",1655:"7",1656:"8",1657:"9",16189:"Е",16187:"Щ",16219:"К",16221:"Ъ",16190:"В",16191:"Ю",16188:"Ь",16192:"+",	1665:"Й",1666:"М",1667:"Ё",1668:"Б",1669:"У",1670:"Ө",1671:"А",1672:"Х",1673:"Ш",1674:"Р",1675:"О",1676:"Л",1677:"Т",1678:"И",1679:"Ү",1680:"З",1681:"Ф",1682:"Ж",1683:"Ы",1684:"Э",1685:"Г",1686:"С",1687:"Ц",1688:"Ч",1689:"Н",1690:"Я",16186:"Д",16222:"П",16220:"|"};
        
    defaultOption = merge(defaultOption, options);
        
	//get text
	typing = defaultOption.text.split("\n");
    last_row = defaultOption.text.split("\n").length;
    last_index = defaultOption.text.split("\n")[last_row-1].length;
	//get lang
	lang = options.lang;
	
	//Rendering target
	target      = document.getElementById(defaultOption.render.renderTo);
	current     = document.getElementById(defaultOption.render.currentbttn);
	shiftbttn   = document.getElementById(defaultOption.render.shiftbttn);
	chiesenone  = document.getElementById(defaultOption.render.chiesenone);
	space       = document.getElementById(defaultOption.render.space);
	lefthand    = document.getElementById(defaultOption.render.lefthand);
	righthand   = document.getElementById(defaultOption.render.righthand);
	time        = document.getElementById(defaultOption.render.timer);
	wpm         = document.getElementById(defaultOption.render.wpm);
	procent     = document.getElementById(defaultOption.render.procent);
	
	maxwidth = 720;
	target.innerHTML = renderText(typing);
	
	function renderText(text)
	{
        var result = "<div class=\"line\">";
        var j = 0;
        var too = text.length;
        alllength = 0;
        for(var i=0; i < too;i++)
        {
            for(var y = 0; y < text[i].length;y++)
            {
                alllength += text[i].length;
                if(i < currentRow)
                {
                    if(in_array(i+","+y,errorNum))
                    {
                        result += "<span class=\"error\">"+ (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) +"</span>";
                    }
                    else
                    {
                        result += "<span class=\"dis\">"+ (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) +"</span>";
                    }
                }
                if(i == currentRow && y < currentIndex)
                {
                    if(in_array(i+","+y,errorNum))
                    {
                        result += "<span class=\"error\">"+ (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) +"</span>";
                    }
                    else
                    {
                        result += "<span class=\"dis\">"+ (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) +"</span>";
                    }
                }
                if(i == currentRow && y >= currentIndex)
                {
                    if(currentIndex == y && currentRow == i)
                    {
                        result += "<span class=\"active\">"+ (text[i][y] == ' ' ? '&nbsp;' : text[i][y]) +"</span>";
                    }
                    else
                    {
                        result += "<span>"+(text[i][y] == ' ' ? '&nbsp;' : text[i][y]) +"</span>";
                    }
                }
                if(i > currentRow)
                {
                    result += "<span>"+(text[i][y] == ' ' ? '&nbsp;' : text[i][y]) +"</span>";
                }
            }
            result += "</div><div class=\"line\">";
        }
		result += "</div>";
		
		if(lang == "mongolian")
		{
			switch(text[currentRow,currentIndex])
			{
				//"Backspace":8,
				//"Tab":9,
				//"Enter":13,
				//"Shift":16,
				//"Ctrl":17,
				//"Alt":18,
				//"Pause/Break":19,
				//"Caps Lock":20,
				//"Esc":27,
				//"Space":32,
				//"Page Up":33,
				//"Page Down":34,
				//"End":35,
				//"Home":36,
				//"Left":37,
				//"Up":38,
				//"Right":39,
				//"Down":40,
				//"Insert":45,
				//"Delete":46,
				
				case ' ':
				space.style.display = "block";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				current.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "50px";
				lefthand.style.left = "115px";
				righthand.style.display = "block";
				righthand.style.top = "50px";
				righthand.style.left = "25px";
				break;
				
				case "=": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "5px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "+": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "5px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				// Start Sylbol
				
				case "?":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "295px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "№": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "34px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "-": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "63px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "\"": 
				current.style.display = "block"
				current.style.top = "5px"
				current.style.left = "92px"
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "₮": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "121px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case ":": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "150px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case ".": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "179px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "_": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "208px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case ",": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "237px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case "%": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "266px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case "е": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "324px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "щ":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "353px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "0":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "295px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "1": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "34px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "2": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "63px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "3": 
				current.style.display = "block"
				current.style.top = "5px"
				current.style.left = "92px"
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "4": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "121px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "5": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "150px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "6": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "179px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "7": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "208px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "8": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "237px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case "9": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "266px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case "Е": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "324px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "Щ":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "353px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				// End Sylbol
				
				//Start Home Row
				
				case "й":
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "61px"; 
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "ы": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "90px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "б": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "119px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "ө": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "148px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "а": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "177px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "х": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "206px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "р": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "235px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "о": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "264px";
				shiftbttn.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case "л": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "293px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case "д": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "322px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "п": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "351px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "Й": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "61px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none"; 
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "Ы": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "90px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "Б": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "119px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "Ө": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "148px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "А": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "177px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "Х": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "206px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "Р": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "235px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "О": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "264px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case "Л": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "293px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case "Д": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "322px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "П": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "351px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				//End Home Row
				
				// Start Top Row
				
				case "ф": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "47px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "ц": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "76px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "у": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "105px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "ж": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "134px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "э": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "163px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "н": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "192px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "г": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "221px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "ш": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "250px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case "ү": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "279px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case "з": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "308px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "к": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "337px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "ъ": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "366px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "\\": 
				chiesenone.style.display = "block";
				chiesenone.style.top = "34px";
				chiesenone.style.left = "395px";
				shiftbttn.style.display = "none";
				current.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "Ф": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "47px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "Ц": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "76px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "У": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "105px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "Ж": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "134px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "Э": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "163px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "Н": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "192px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "Г": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "221px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "Ш": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "250px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case "Ү": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "279px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case "З": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "308px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "К": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "337px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "Ъ": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "366px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "|": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "395px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				// End Top Row
				
				// Start Bottom Row
				
				case "я": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "76px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "ч": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "105px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "ё": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "134px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "с": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "163px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "м": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "192px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "и": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "221px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "т": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "250px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "ь": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "279px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case "в": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "308px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case "ю": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "337px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "Я": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "76px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "Ч": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "105px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "Ё": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "134px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "С": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "163px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "М": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "192px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "И": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "221px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "Т": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "250px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "Ь": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "279px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case "В": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "308px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case "Ю": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "337px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				// End Bottom Row
				
				//"Windows":91,
				//"Right Click":93,
				case "Numpad 0": break;
				case "Numpad 1": break;
				case "Numpad 2": break;
				case "Numpad 3": break;
				case "Numpad 4": break;
				case "Numpad 5": break;
				case "Numpad 6": break;
				case "Numpad 7": break;
				case "Numpad 8": break;
				case "Numpad 9": break;
				case "Numpad *": break;
				case "Numpad +": break;
				case "Numpad -": break;
				case "Numpad .": break;
				case "Numpad /": break;
				
				default: 
				current.style.display    = "none";
				shiftbttn.style.display  = "none";
				chiesenone.style.display = "none";
				space.style.display      = "none";
				righthand.style.display  = "none";
				lefthand.style.display   = "none";
				break;
			}
		}
		if(lang == "english")
		{
			switch(text[currentIndex])
			{
				//"Backspace":8,
				//"Tab":9,
				//"Enter":13,
				//"Shift":16,
				//"Ctrl":17,
				//"Alt":18,
				//"Pause/Break":19,
				//"Caps Lock":20,
				//"Esc":27,
				//"Space":32,
				//"Page Up":33,
				//"Page Down":34,
				//"End":35,
				//"Home":36,
				//"Left":37,
				//"Up":38,
				//"Right":39,
				//"Down":40,
				//"Insert":45,
				//"Delete":46,
				
				case ' ':
				space.style.display = "block";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				current.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "50px";
				lefthand.style.left = "115px";
				righthand.style.display = "block";
				righthand.style.top = "50px";
				righthand.style.left = "25px";
				break;
				
				case "`": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "5px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "~": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "5px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				// Start Sylbol
				
				case "0":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "295px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "1": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "34px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "2": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "63px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "3": 
				current.style.display = "block"
				current.style.top = "5px"
				current.style.left = "92px"
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "4": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "121px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "5": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "150px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "6": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "179px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "7": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "208px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "8": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "237px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case "9": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "266px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case "-": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "324px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "=":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "353px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case ")":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "295px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "!": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "34px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "@": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "63px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "#": 
				current.style.display = "block"
				current.style.top = "5px"
				current.style.left = "92px"
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "$": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "121px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "%": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "150px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "^": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "179px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "&": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "208px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "*": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "237px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case "(": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "266px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case "_": 
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "324px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "+":
				current.style.display = "block";
				current.style.top = "5px";
				current.style.left = "353px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				// End Sylbol
				
				//Start Home Row
				
				case "a":
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "61px"; 
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "s": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "90px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "d": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "119px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "f": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "148px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "g": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "177px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "h": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "206px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "j": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "235px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "k": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "264px";
				shiftbttn.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case "l": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "293px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case ";": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "322px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "'": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "351px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "A": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "61px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px";
				chiesenone.style.display = "none"; 
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "S": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "90px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "D": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "119px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "F": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "148px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "G": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "177px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "H": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "206px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "J": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "235px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "K": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "264px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case "L": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "293px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case ":": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "322px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "\"": 
				current.style.display = "block";
				current.style.top = "63px";
				current.style.left = "351px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				//End Home Row
				
				// Start Top Row
				
				case "q": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "47px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "w": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "76px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "e": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "105px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "r": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "134px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "t": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "163px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "y": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "192px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "u": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "221px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "i": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "250px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case "o": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "279px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case "p": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "308px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "[": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "337px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "]": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "366px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "\\": 
				chiesenone.style.display = "block";
				chiesenone.style.top = "34px";
				chiesenone.style.left = "395px";
				shiftbttn.style.display = "none";
				current.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "Q": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "47px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "W": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "76px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "E": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "105px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "R": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "134px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "T": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "163px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "Y": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "192px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "U": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "221px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "I": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "250px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case "O": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "279px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case "P": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "308px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "{": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "337px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "}": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "366px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				case "|": 
				current.style.display = "block";
				current.style.top = "34px";
				current.style.left = "395px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				// End Top Row
				
				// Start Bottom Row
				
				case "z": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "76px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				righthand.style.display = "none";
				break;
				
				case "x": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "105px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				righthand.style.display = "none";
				break;
				
				case "c": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "134px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				righthand.style.display = "none";
				break;
				
				case "v": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "163px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "b": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "192px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				righthand.style.display = "none";
				break;
				
				case "n": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "221px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case "m": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "250px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				lefthand.style.display = "none";
				break;
				
				case ",": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "279px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				lefthand.style.display = "none";
				break;
				
				case ".": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "308px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				lefthand.style.display = "none";
				break;
				
				case "/": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "337px";
				shiftbttn.style.display = "none";
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				lefthand.style.display = "none";
				break;
				
				case "Z": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "76px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				break;
				
				case "X": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "105px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "7px";
				lefthand.style.left = "56px";
				break;
				
				case "C": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "134px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "2px";
				lefthand.style.left = "72px";
				break;
				
				case "V": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "163px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "B": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "192px";
				shiftbttn.style.display = "block";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "367px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				lefthand.style.display = "block";
				lefthand.style.top = "12px";
				lefthand.style.left = "93px";
				break;
				
				case "N": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "221px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "M": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "250px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "12px";
				righthand.style.left = "46px";
				break;
				
				case "<": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "279px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "2px";
				righthand.style.left = "68px";
				break;
				
				case ">": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "308px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "7px";
				righthand.style.left = "84px";
				break;
				
				case "?": 
				current.style.display = "block";
				current.style.top = "92px";
				current.style.left = "337px";
				shiftbttn.style.display = "block";
				lefthand.style.display = "block";
				lefthand.style.top = "22px";
				lefthand.style.left = "36px";
				shiftbttn.style.top = "92px";
				shiftbttn.style.left = "5px"; 
				chiesenone.style.display = "none";
				space.style.display = "none";
				righthand.style.display = "block";
				righthand.style.top = "22px";
				righthand.style.left = "105px";
				break;
				
				// End Bottom Row
				
				//"Windows":91,
				//"Right Click":93,
				case "Numpad 0": break;
				case "Numpad 1": break;
				case "Numpad 2": break;
				case "Numpad 3": break;
				case "Numpad 4": break;
				case "Numpad 5": break;
				case "Numpad 6": break;
				case "Numpad 7": break;
				case "Numpad 8": break;
				case "Numpad 9": break;
				case "Numpad *": break;
				case "Numpad +": break;
				case "Numpad -": break;
				case "Numpad .": break;
				case "Numpad /": break;
				
				default: 
				current.style.display = "none";
				shiftbttn.style.display = "none";
				break;
			}
		}
		return result;
	}

    window.onfocus = function()
    {
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
            
            if (in_array(e.target.tagName,inputTags)) {
                return true;
            }
            
            if((e.which || e.keyCode) == 13)
            {
                return false;
            }
            if (currentRow+1 == last_row && currentIndex+1 == last_index)
            {
                clearInterval(t);
                window.onkeydown = null;
                defaultOption.onfinish();
            }
            if(lang == "english")
            {
                if(keyCodeToChar_en[e.which || e.keyCode] == "Backspace")
                {
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

                    if ((e.keyCode == 8 || (e.keyCode == 37 && e.altKey) || (e.keyCode == 39 && e.altKey)))
                    {
                        e.cancelBubble = true;
                        e.returnValue = false;
                    }
                }
                else if(keyCodeToChar_en[e.which || e.keyCode] == "Shift")
                {
                    shiftHolder=16;
                }
                else
                {
                    if(keyCodeToChar_en[shiftHolder * Math.pow(10,e.which.toString().length) + e.which || shiftHolder * Math.pow(10,e.keyCode.toString().length) + e.keyCode] == (typing[currentRow][currentIndex]==' ' ? "Space": typing[currentRow][currentIndex]))
                    {
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
                                var ffff = parseInt(cc / 60);
                                var jjjj = cc % 60;
                                time.innerHTML = (ffff.toString().length == 1? "0"+ffff:ffff)+":"+(jjjj.toString().length == 1? "0"+jjjj:jjjj);
                                _rtime = (ffff.toString().length == 1? "0"+ffff:ffff)+":"+(jjjj.toString().length == 1? "0"+jjjj:jjjj);
                                var minute = cc / 60;

                                var crosswpm = ( alllength / 5 );
                                crosswpm = crosswpm / minute;
                                _wpm = crosswpm;
                                var netwpm = crosswpm - ( errored / minute );
                                _netwpm = parseInt(netwpm);
                                wpm.innerHTML = parseInt(netwpm);
                                _procent = parseInt((alllength - errored) * 100 / alllength);
                                procent.innerHTML = parseInt((alllength - errored) * 100 / alllength)+"%";
                                _time = cc;
                            },
                            1000);
                        }
                    }
                    else
                    {
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
                                isFieled = true;
                                target.innerHTML = renderText(typing);
                            }
                            else
                            {

                            }
                        }
                    }
                }
            }
            if(lang == "mongolian")
            {
                if(keyCodeToChar_mn[e.which || e.keyCode] == "Backspace")
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

                        target.innerHTML = renderText(typing);
                    }

                    if ((e.keyCode == 8 || (e.keyCode == 37 && e.altKey) || (e.keyCode == 39 && e.altKey)))
                    {
                        e.cancelBubble = true;
                        e.returnValue = false;
                    }
                }
                else if(keyCodeToChar_mn[e.which || e.keyCode] == "Shift")
                {
                    shiftHolder=16;
                }
                else
                {
                    if(keyCodeToChar_mn[shiftHolder * Math.pow(10,e.which.toString().length) + e.which || shiftHolder * Math.pow(10,e.keyCode.toString().length) + e.keyCode] == (typing[currentIndex]==' ' ? "Space": typing[currentIndex]))
                    {
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
                                var ffff = parseInt(cc / 60);
                                var jjjj = cc % 60;
                                time.innerHTML = (ffff.toString().length == 1? "0"+ffff:ffff)+":"+(jjjj.toString().length == 1? "0"+jjjj:jjjj);
                                _rtime = (ffff.toString().length == 1? "0"+ffff:ffff)+":"+(jjjj.toString().length == 1? "0"+jjjj:jjjj);
                                var minute = cc / 60;

                                var crosswpm = ( alllength / 5 );
                                crosswpm = crosswpm / minute;
                                _wpm = crosswpm;
                                var netwpm = crosswpm - ( errored / minute );
                                _netwpm = parseInt(netwpm);
                                wpm.innerHTML = parseInt(netwpm);
                                _procent = parseInt((alllength - errored) * 100 / alllength);
                                procent.innerHTML = parseInt((alllength - errored) * 100 / alllength)+"%";
                                _time = cc;
                            },
                            1000);
                        }
                    }
                    else
                    {
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
                                isFieled = true;
                                target.innerHTML = renderText(typing);
                            }
                            else
                            {

                            }
                        }
                    }
                }
            }
            _erroredChar = errorChar;
	}
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