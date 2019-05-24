Ext.namespace("tw");

tw.lesson = function() {};
tw.lesson.prototype = {
	flashKeyboardMovie: null,
	exercises: {},		// data populated in view script.  contains all exercises and information
	lessonID: 0,
	speedType: 'wpm',
	spaces: " ",
	effects: true,
	pauseImg: '',
	resumeImg: '',
	newslesson: 0,
	numberPad: 0,
	test: 0,	// typing test
	showTestGraph: 0,	// typing test
	name: '',	// lesson name
	exerciseNum: 0,
	exerciseText: '',
	characterNum: 0,
	lastCharError: false,
	characters: 0,
	seconds: 0,
	errors: 0,
	errorKeys: {},
	allKeys: {},
	lessonCharacters: 0,
	lessonSeconds: 0,
	lessonErrors: 0,
	pauseSeconds: 0,
	startSeconds: 0,
	timerStart: 0,
	timer: null,
	paused: false,
	restricted: false,
	playSounds: true,
	sounds: {},
	showProgressWindow: true,
	easyAdvancedDelay: null,
	acceptingInput: false,
	easyAdvancing: false,
	course: '',
	levels: {},
	adTimeout: null,
	isPlatinum: false,
	popupBoxHeight: 500, // 365
	popupBoxWidth: 750, // 720
	isIOS: navigator.userAgent.match(/iPad;|iPhone;|iPod;|iOS;/),
	iOSinput: null,
	win: null,
	hasInited: false,
  asSinglePage: false,
	current: null,
	shiftbttn: null,
	chiesenone: null,
	space: null,
	lefthand: null,
	righthand: null,

	init: function() {

		if (!this.hasInited) {
			Ext.apply(this, lessonData);

			this.sounds['fail'] = '/audio/fail.mp3';
		}

		current     = document.getElementById(this.currentbttn);
		shiftbttn   = document.getElementById(this.shiftbttn);
		chiesenone  = document.getElementById(this.chiesenone);
		space       = document.getElementById(this.space);
		lefthand    = document.getElementById(this.lefthand);
		righthand   = document.getElementById(this.righthand);


		var mainContainer = this.win.Ext.fly('lesson-main-container');
		if (mainContainer && !mainContainer.isVisible()) {
			this.win.Ext.get('lesson-main-container').show();
			if (this.hasInited) {
				this.win.Ext.get('keyboardshell').setHeight(170);
			} else {
				this.win.Ext.get('keyboardshell').setHeight(170, true);
			}

			this.win.Ext.get('lesson-second-container').show();
		}


		if (this.isIOS) {
			// this.showProgressWindow = false;
			this.win.Ext.fly('keyboardshell').hide();
			this.win.Ext.getBody().addClass('iOS');

			if (!this.hasInited) {
				this.iOSinput = Ext.get('iOS-input');
				this.iOSinput.on('keypress', this.easyAdvanceHandler, this);
				this.iOSinput.on('keypress', this.acceptInputHandler, this);
				this.iOSinput.on('keydown', function(e) {if(e.getKey() == Ext.EventObject.TAB || e.getKey() == Ext.EventObject.CAPS_LOCK){e.preventDefault(); this.acceptInputHandler(e);}}, this);
				this.iOSinput.on('blur', function(e){
					if (!this.acceptingInput) {
						return true;
					}
					this.showiOSCapture(true);
				}, this);
				this.iOSinput.on('focus', function(e) {
					var that = this;
					setTimeout(function(){that.hideiOSCapture();}, 0);
				}, this);
			}
		} else {
			if (!this.hasInited) {
				Ext.getDoc().on('keypress', this.easyAdvanceHandler, this);
				Ext.getDoc().on('keypress', this.acceptInputHandler, this);
				Ext.getDoc().on('keydown', function(e) {if(e.getKey() == Ext.EventObject.TAB || e.getKey() == Ext.EventObject.CAPS_LOCK){e.preventDefault(); this.acceptInputHandler(e);}}, this);
			}
		}

		/*if (!this.hasInited) {
			if (Ext.isIE || Ext.isSafari || Ext.isChrome) { Ext.getDoc().on('keydown', this.ieBackspaceCatch, this); }
		}*/
		if (Ext.isIE || Ext.isSafari || Ext.isChrome) { this.win.Ext.getDoc().on('keydown', this.ieBackspaceCatch, this); }

		if (this.exerciseNum === 0) {
			if (Ext.isIE) {
				var delayIntro = new Ext.util.DelayedTask(function() {this.intro();}, this);
				delayIntro.delay(500);
			} else {
				this.intro();
			}
		} else {
			var effects = this.effects;
			this.effects = false;
			this.initExercise();
			this.effects = effects;
			this.beginExercise();
			if (this.isIOS) {
				this.showiOSCapture(true);
			}
		}

		if (this.win.Ext.fly('keyboardshell')) {
			this.win.Ext.fly('keyboardshell').on('click', function(e) {
				if (this.hasInited) {
					this.win.Ext.fly('keyboardshell').toggle(false);
				} else {
					this.win.Ext.fly('keyboardshell').toggle(!Ext.isIE);
				}

			}, this);
		}

		this.win.Ext.fly('start-over').on('click', function(e) {
			e.preventDefault();
			this.initExercise();
			this.win.Ext.fly('start-over').blur();
		}, this);

		this.win.Ext.fly('volume').on('click', function(e) {
			e.preventDefault();
			this.toggleSound();
		}, this);
		this.win.Ext.fly('volume').on('mouseover', function(e){
			e.preventDefault();
			this.win.Ext.fly('volume').dom.className  = 'volume on';
		}, this);
		this.win.Ext.fly('volume').on('mouseout', function(e){
			e.preventDefault();
			if (this.playSounds) {
	      this.win.Ext.fly('volume').dom.className  = 'volume on';
			} else {
  			this.win.Ext.fly('volume').dom.className  = 'volume off';
			}
		}, this);

		this.hasInited = true;
	},

	showiOSCapture: function(showMessage) {
		this.iOSinput.setStyle({
			height: '800px',
			width: Ext.getBody().getWidth()+'px'
		});
		this.iOSinput.blur();

		if (showMessage) {
			//Ext.fly('iOS-keyboard-focus-message').show().center();
		} else {
			//Ext.fly('iOS-keyboard-focus-message').hide();
		}
	},

	hideiOSCapture: function() {
		this.iOSinput.setStyle({
			height: '1px',
			width: '1px'
		});
		Ext.fly('iOS-keyboard-focus-message').hide();
	},

	showText: function(text, isIntro) {
		if (this.isIOS) {
			this.showiOSCapture(false);
			var cb = function(e){
				this.iOSinput.un('click', cb, this);
				if (isIntro) {
					this.beginExercise();
				} else {
					this.nextExercise();
				}
			};
			this.iOSinput.on('click', cb, this);
		}
		Shadowbox.open({
	        player: 'html',
	        content: text,
	        width: this.popupBoxWidth,
	        height: this.popupBoxHeight,
	        animateFade: false
    });
	},

	initExercise: function() {
		lastActivity = new Date();
		if (this.exercises.length <= this.exerciseNum) {
			return;
		}

		if (typeof Shadowbox == "object") { Shadowbox.close(); }

		var index = 0;
		var ele;
		var chars = [];
		var count = 0;
		var totalLines = 0;
		var data = this.exercises[this.exerciseNum];
		var lines = data.exercise.trim().split("\n");
		for(index in lines) {
			if (lines[index] && typeof lines[index] === 'string' && this.spaces == "  " && data.type == "mixed") {
				lines[index] = lines[index].replace(/(\.|\?|\!)\s+/g, "$1"+this.spaces);
				lines[index] = lines[index].replace(/(mrs\.|mr\.|st\.|ms\.|\s\w\.)  /gi, "$1"+" ");
			}
		}
		if (this.newslesson) {
			var urls = data.urls.trim().split("\n");
		}

		this.exerciseText = "";
		this.characterNum = 0;
		this.lastCharError = false;
		this.errors = 0;
		this.seconds = 0;
		this.characters = 0;
		this.errorKeys = {};
		this.allKeys = {};
		this.win.Ext.fly("time").dom.innerHTML = "00:00";
		this.win.Ext.fly("wpm").dom.innerHTML = "0";
		this.win.Ext.fly("pro").dom.innerHTML = "100";
		this.timer = {
			run: this.updateTimer,
			interval: 1000,
			scope: this
		};

		this.paused = false;
		this.pause();
		var timeLimit = this.exercises[this.exerciseNum].timeLimit;
		if (timeLimit > 0) {
			var minutes = Math.floor(timeLimit/60);
			var seconds = timeLimit - (minutes*60);
			this.win.Ext.fly("time").dom.innerHTML = String.leftPad(minutes, 2, "0")+":"+String.leftPad(seconds, 2, "0");
		}
		var speedLimit = this.exercises[this.exerciseNum].speedLimit;
		if (speedLimit > 0) {
			this.win.Ext.fly("wpm").dom.innerHTML = "0 / "+speedLimit;
		}


		ele = this.win.Ext.get('exercise-title');
		ele.dom.innerHTML = "#"+(this.exerciseNum+1)+" &gt; "+ data.title;
		if (this.effects) {
			ele.highlight('ffff9c', {easing: 'easeIn', duration: 1});
		}


		totalLines = (lines.length > 6)?lines.length:6;
		for(var i= 0; i < totalLines; i++) {
			ele = {
				tag: 'div',
				cls: 'lesson-text',
				style: {visibility: (this.effects)?'hidden':'visible'},
				children: []
			};
			if (this.newslesson && urls[i]) {
				ele.style.cursor = 'pointer';
				ele.onclick = "window.open('"+urls[i].replace("'", "\\'")+"')";
			}
			if (lines[i]) {
				lines[i] = lines[i].substr(0, 51).trim();
				if (i != lines.length-1) {
					if (lines[i].length == 51) {
						lines[i] = lines[i].substr(0, 50);
					}
					lines[i] = lines[i].trim()+" ";
				}
				this.exerciseText += lines[i];
				for(var j=0; j<lines[i].length; j++) {
					ele.children[j] = {
						tag: 'span',
						cls: (count === 0)?'l-active':'',
						id: 'char'+count,
						html: (lines[i].charAt(j) == " ")?"&nbsp;":lines[i].charAt(j)
					};
					count++;
				}
			}
			if (i == totalLines-1) {
				ele.cls += " last";
			}
			var elem;
			if (i === 0) {
				elem = this.win.Ext.DomHelper.overwrite('typing-exercise', ele);
			} else {
				elem = this.win.Ext.DomHelper.append('typing-exercise', ele);
			}
			if (this.effects) {
				this.win.Ext.get(elem).pause(0.1*i).fadeIn({
					easing: 'easeIn',
					duration:0.5
				});
			}
		}

		this.setCharPosition(this.exerciseText.charAt(0));
	},

	beginExercise: function() {
		Shadowbox.close();
		this.easyAdvanceRegister(false);

		if (this.exercises[this.exerciseNum].helpText) {	// text section
			var showHelpText = new Ext.util.DelayedTask(function(){this.textSection();}, this);
			if (this.showProgressWindow) {
				showHelpText.delay(500);
			} else {
				showHelpText.delay(0);
			}
			return;
		}

		if (this.effects) {
			this.win.Ext.select(".l-active").frame();
		}

		if (this.flashKeyboardMovie && this.flashKeyboardMovie.flashShowKeys) {
			this.flashKeyboardMovie.flashShowKeys(this.exercises[this.exerciseNum].exercise.charAt(0).charCodeAt(0));
		}
		var delayedFunc = function() {
			if (this.flashKeyboardMovie && this.flashKeyboardMovie.flashShowKeys) {
				this.flashKeyboardMovie.flashShowKeys(this.exercises[this.exerciseNum].exercise.charAt(0).charCodeAt(0));
			}
					//			delayedFunc.defer(5000, this);
		};
		delayedFunc.defer(500, this);

		if (this.lessonCharacters) {
			this.win.Ext.fly("lwpm").dom.innerHTML = this.calculateSpeed(this.lessonCharacters, this.lessonSeconds, this.lessonErrors);
			this.win.Ext.fly("lpro").dom.innerHTML = 100-Math.round((this.lessonErrors/this.lessonCharacters)*100);
		}

		this.acceptInputRegister(true);
//		Ext.getDoc().focus();
	},

	setCharPosition: function(letter) {
		if (this.layout == "Keyboard") {
			for (var i = 0; i < this.keyboardData[this.lang].length; i++) {
					if (this.keyboardData[this.lang][i]["char"] == letter)
					{
							current.style.display     = this.keyboardData[this.lang][i]["currentstyledisplay"];
							current.style.top         = this.keyboardData[this.lang][i]["currentstyletop"];
							current.style.left        = this.keyboardData[this.lang][i]["currentstyleleft"];

							shiftbttn.style.display   = this.keyboardData[this.lang][i]["shiftbttnstyledisplay"];
							shiftbttn.style.top       = this.keyboardData[this.lang][i]["shiftbttnstyletop"];
							shiftbttn.style.left      = this.keyboardData[this.lang][i]["shiftbttnstyleleft"];

							righthand.style.display   = this.keyboardData[this.lang][i]["righthandstyledisplay"];
							righthand.style.top       = this.keyboardData[this.lang][i]["righthandstyletop"];
							righthand.style.left      = this.keyboardData[this.lang][i]["righthandstyleleft"];

							chiesenone.style.display  = this.keyboardData[this.lang][i]["chiesenonestyledisplay"];
							chiesenone.style.top      = this.keyboardData[this.lang][i]["chiesenonestyletop"];
							chiesenone.style.left     = this.keyboardData[this.lang][i]["chiesenonestyleleft"];

							space.style.display       = this.keyboardData[this.lang][i]["spacestyledisplay"];

							lefthand.style.display    = this.keyboardData[this.lang][i]["lefthandstyledisplay"];
							lefthand.style.top        = this.keyboardData[this.lang][i]["lefthandstyletop"];
							lefthand.style.left       = this.keyboardData[this.lang][i]["lefthandstyleleft"];
					}
			}
		}
	},

	acceptInputHandler: function(e) {
		e.preventDefault();

		if (!this.acceptingInput) {
			return;
		}

		if (e.getKey() == Ext.EventObject.CAPS_LOCK) {
			this.paused = false;
			this.pause();
			this.alert(text.CAPS_LOCK_TITLE, text.CAPS_LOCK_PRESSED, function(){this.pause();}, this);
			return;
		}

		lastActivity = new Date();
		var key = e.getKey();
		switch(key) {
			case Ext.EventObject.SHIFT:	// only for silly opera
				return;
			case Ext.EventObject.ENTER:
				if (this.paused) { return; }
				key = Ext.EventObject.SPACE;
				break;
			case Ext.EventObject.BACKSPACE:
				this.backspace();
				return;
			case Ext.EventObject.SPACE:
				if (this.paused) { return; }
				break;
		}

		if (this.paused) {
			this.pause();
		}

		if (e.ctrlKey && e.shiftKey && e.getKey() == Ext.EventObject.RIGHT ) {
			this.pause();
			this.seconds = 5;
			this.characters = 1;
			this.errors = 0;
			this.endExercise();
			return;
		}

		var next = this.exerciseText.charAt(this.characterNum+1).charCodeAt(0);
		var expected = this.exerciseText.charAt(this.characterNum).charCodeAt(0);
		var actual = key;
		if (this.numberPad > 0) {
			if (next == Ext.EventObject.SPACE) {
				next = Ext.EventObject.SPACE;
			}
			if (expected == Ext.EventObject.SPACE) {
				expected = Ext.EventObject.ENTER;
			}
		}
		if (expected != actual && this.lastCharError) {	// this IF is so the keyboard doesnt keep showing progressively darker letters if they just missed a letter and type forward
			expected = actual;
		}

		var letter = String.fromCharCode(key);

		if (String.fromCharCode(expected).trim()) {
			this.allKeys['key-'+String.fromCharCode(expected)] = (this.allKeys['key-'+String.fromCharCode(expected)])?this.allKeys['key-'+String.fromCharCode(expected)]+1:1;
		}

		if (letter == this.exerciseText.charAt(this.characterNum) || (!this.lastCharError && letter != this.exerciseText.charAt(this.characterNum))) {
			if (this.flashKeyboardMovie && this.flashKeyboardMovie.flashShowKeys) {
				this.flashKeyboardMovie.flashShowKeys((next)?next:0 /*, expected, actual*/);
			}
		}

		if (letter == this.exerciseText.charAt(this.characterNum)) {
			this.nextCharacter(true);
		} else {
			var correctLetter = this.exerciseText.charAt(this.characterNum);

			if (this.characterNum === 0 && this.exerciseText.charAt(this.characterNum).toUpperCase() == letter) {
				this.alert(text.CAPS_LOCK_TITLE, text.CAPS_LOCK_WARNING, function(){this.initExercise();}, this);
			}

			// on numberpad lesson, if they are supposed to type 0 through 9 but instead type a keycode between 30 and 40, it's probably cause they have the numlock on
			if ((this.lessonID == 4 || this.lessonID == 315) && (correctLetter >= 0 && correctLetter <= 9) && (key > 30 && key <= 40)) {
				alert(text.NUM_LOCK_WARNING);
			}

			if (correctLetter.trim() && !this.lastCharError) {
				this.errorKeys['key-'+correctLetter] = (this.errorKeys['key-'+correctLetter])?this.errorKeys['key-'+correctLetter]+1:1;
			}
			this.playSound('fail');
			this.nextCharacter(false);
		}
	},

	nextCharacter: function(correct) {
		if (correct === false) {
			if (this.lastCharError === true) {
				return;	// Do not advance if last char was error
			} else {
				this.errors++;
				this.lastCharError = true;
				this.win.Ext.fly('char'+this.characterNum).addClass('l-error');
				this.setCharPosition(this.exerciseText.charAt(this.characterNum + 1)); /* getting keyboard position */
			}
		} else {
			this.setCharPosition(this.exerciseText.charAt(this.characterNum + 1)); /* getting keyboard position */
			this.win.Ext.fly('char'+this.characterNum).addClass('l-typed');
			this.lastCharError = false;
		}
		this.win.Ext.fly('char'+this.characterNum).removeClass('l-active');
		if (this.characterNum+1 >= this.exerciseText.length) {
			this.acceptInputRegister(false);	// No more typing
			this.paused = false;	// force it to pause
			this.pause();
			this.updateTimer();
			this.endExercise();
			return;
		}
		this.characterNum++;
		this.characters++;
		this.win.Ext.fly('char'+this.characterNum).addClass('l-active');
	},

	backspace: function() {
		if (this.characterNum === 0) { return; }

		this.win.Ext.fly('char'+this.characterNum).removeClass('l-active');
		this.win.Ext.fly('char'+this.characterNum).removeClass('l-typed');
		this.characterNum--;
		this.characters--;
		this.win.Ext.fly('char'+this.characterNum).addClass('l-active');
		this.win.Ext.fly('char'+this.characterNum).removeClass('l-typed');
		this.win.Ext.fly('char'+this.characterNum).removeClass('l-error');

		var next = this.exerciseText.charAt(this.characterNum).charCodeAt(0);
		var expected = this.exerciseText.charAt(this.characterNum-1).charCodeAt(0);
		var actual = this.exerciseText.charAt(this.characterNum-1).charCodeAt(0);
		if (this.numberPad > 0) {
			if (next == Ext.EventObject.SPACE) {
				next = Ext.EventObject.ENTER;
			}
			if (expected == Ext.EventObject.SPACE) {
				expected = Ext.EventObject.ENTER;
			}
		}
		if (this.flashKeyboardMovie && this.flashKeyboardMovie.flashShowKeys){
			this.flashKeyboardMovie.flashShowKeys(next /*, expected, actual*/);
		}
	},

	endExercise: function() {
		this.acceptInputRegister(false);

		// Accuracy limit
		var accLimit = this.exercises[this.exerciseNum].accuracyLimit;
		if (accLimit > 0 && Math.round((this.errors/this.characters)*100) > accLimit) {
			this.updateTimer();
			this.alert(text.EXERCISE_FAILURE, String.format(text.FAILURE_ACCURACY, 100-accLimit), function() {
				this.initExercise();
				this.acceptInputRegister(true);
			}, this);
			return;
		}
		// Speed limit
		var speedLimit = this.exercises[this.exerciseNum].speedLimit;
		if (speedLimit > 0 && this.calculateSpeed(this.characters, this.seconds, this.errors) < speedLimit) {
			this.updateTimer();
			this.alert(text.EXERCISE_FAILURE, String.format(text.FAILURE_SPEED, speedLimit, this.speedType), function() {
				this.initExercise();
				this.acceptInputRegister(true);
			}, this);
			return;
		}
		// Time limit
		var timeLimit = this.exercises[this.exerciseNum].timeLimit;
		if (timeLimit > 0 && this.seconds > timeLimit) {
			this.updateTimer();
			this.alert(text.EXERCISE_FAILURE, String.format(text.FAILURE_TIME, timeLimit), function() {
				this.initExercise();
				this.acceptInputRegister(true);
			}, this);
			return;
		}

		if (this.showProgressWindow) {
			this.progressWindow();
		} else {
			this.nextExercise();
		}
	},

	nextExercise: function() {
		this.saveExercise();
		this.exerciseNum++;

		this.lessonSeconds += this.seconds;
		this.lessonCharacters += this.characters;
		this.lessonErrors += this.errors;

		if (this.exercises.length == this.exerciseNum || this.exercises[this.exerciseNum].exercise == "restricted") {	// lesson is over
			this.congrats();
		} else {
			lessonData.innerReady = false;
			//this.win.nextExercise();
			Shadowbox.close();
      this.initExercise();
			this.beginExercise();
		}
	},

	saveExercise: function() {

		if (this.restricted) { return; }

		if (this.test) {	// typing test gets the sum of all when they complete, but otherwise won't save
			if (this.exercises.length == this.exerciseNum+1) {
				this.errorKeys = {};
				this.allKeys = {};
				this.seconds = this.lessonSeconds;
				this.errors = this.lessonErrors;
				this.characters = this.lessonCharacters;
			} else {
				Ext.Ajax.request({
					url: url.SAVE_URL,
					params: {ping:1},
					success: function(data){
						//to do something
					}
				});
				return;
			}
		}

		var params = {};
		params.ek = (this.userID)?Ext.encode(this.errorKeys):{};
		params.ak  = (this.userID)?Ext.encode(this.allKeys):{};
		params.le = this.exercises[this.exerciseNum].lessonExerciseID;
		params.la = this.lang;
		params.p = this.exerciseNum;
		params.l = this.lessonID;
		params.s = this.seconds;
		params.e = this.errors;
		params.t = this.characters;
		params.k = thiskey(params.s+'-'+params.le+'/'+params.p).substr(0,15);
		if (this.exercises.length == this.exerciseNum+1) {
			params.co = 1;
		}
		if (this.userID) {
			params.u = this.userID;
		} else {
			return; //anon people don't send data
		}
		if ((this.course == 'CERTIFICATION')) {
			params.c = 1;
			_gaq.push(['_trackPageview', this.locale +'/student/certification/complete']);
		}

		if (this.lessonID < 1 || this.newslesson) { return; }
		Ext.Ajax.request({
			url: url.SAVE_URL,
			success: function(response, options) {
				var res = Ext.decode(response.responseText);
				if (!res.success) {
					this.alert("Error", res.message);
				}
			},
			failure: function(response, options) {
				this.alert(text.COMMUNICATIONS_ERROR, text.COMMUNICATIONS_ERROR_TEXT);
			},
			params: params,
			scope: this
		});
	},

	updateTimer: function() {
		var minutes;
		var seconds;
		this.seconds = Math.round(new Date().getTime()/1000) - this.startSeconds;

		if (this.exercises.length <= this.exerciseNum) {
			this.pause();
			return;
		}

		var timeLimit = this.exercises[this.exerciseNum].timeLimit;
		if (timeLimit > 0) {	// run backwards if there is a time limit
			minutes = Math.floor((timeLimit - this.seconds)/60);
			seconds = timeLimit - this.seconds - (minutes*60);
			if (timeLimit > this.seconds) {
				this.win.Ext.fly("time").dom.innerHTML = String.leftPad(minutes, 2, "0")+":"+String.leftPad(seconds, 2, "0");
			} else {
				this.win.Ext.fly("time").dom.innerHTML = "time out";
			}
		} else {
			minutes = Math.floor(this.seconds/60);
			seconds = this.seconds-(minutes*60);
			this.win.Ext.fly("time").dom.innerHTML = String.leftPad(minutes, 2, "0")+":"+String.leftPad(seconds, 2, "0");
		}
		if (this.seconds > 5) {
			this.win.Ext.fly("wpm").dom.innerHTML = this.calculateSpeed(this.characters, this.seconds, this.errors);
			var speedLimit = this.exercises[this.exerciseNum].speedLimit;
			if (speedLimit > 0) {
				this.win.Ext.fly("wpm").dom.innerHTML = this.win.Ext.fly("wpm").dom.innerHTML + " / " + speedLimit;
			}
			var acc = 100-Math.round((this.errors/this.characters)*100);
			this.win.Ext.fly("pro").dom.innerHTML = (isNaN(acc) || acc == Number.POSITIVE_INFINITY || acc == Number.NEGATIVE_INFINITY)?100:acc;
		}
	},

	pause: function() {
		this.paused = (this.paused)?false:true;
		if (this.paused) {
			Ext.TaskMgr.stopAll();
			this.pauseSeconds = this.seconds;
		} else {
			Ext.TaskMgr.start(this.timer);
			this.startSeconds = Math.round(new Date().getTime()/1000) - this.pauseSeconds;
		}
	},

	calculateSpeed: function(characters, seconds, errors) {
		var words, minutes, speed;
		if (this.speedType == "kph") {
			speed = Math.round(characters/seconds*3600);
		} else if (this.speedType == "wpm") {
			words = (characters - (errors * 5))/5;		// begin WPM calculation
			minutes = seconds/60;
			speed = Math.max(Math.round(words/minutes),0);
		} else {
			words = (characters - (errors * 5));		// begin WPM calculation
			minutes = seconds/60;
			speed = Math.max(Math.round(words/minutes),0);
		}
		return (speed == Infinity)?100:speed;
	},

	easyAdvanceRegister: function(on) {
		if (on) {
			// Put a delay because shadowbox fails if a box is shown but immediately dismissed before loading.  Also this stops them from accidentally hitting enter or space and missing the box
			this.easyAdvancedDelay = new Ext.util.DelayedTask(function(){this.easyAdvancing = true;}, this);
			this.easyAdvancedDelay.delay(500);
		} else {
			if (this.easyAdvancedDelay) { this.easyAdvancedDelay.cancel(); }
			this.easyAdvancing = false;
		}
	},

	easyAdvanceHandler: function(e) {
		e.preventDefault();
		if (this.isIOS) {
			return;
		}
		if (!this.easyAdvancing) {
			return;
		}
		if (e.getKey() == Ext.EventObject.ENTER || e.getKey() == Ext.EventObject.SPACE || e.getKey() == Ext.EventObject.ESC) {
			if (this.exercises.length >= this.exerciseNum) {
				if (this.characterNum == 0) {
					this.beginExercise();
				} else {
					this.nextExercise();
				}
			} else {
				location.href = '/'+ this.locale +'/student/courses/';
			}
		}
	},

	acceptInputRegister: function(on) {
		this.acceptingInput = on;
	},

	ieBackspaceCatch: function(e) {
		if (!this.acceptingInput) {
			return;
		}
		if (e.getKey() == Ext.EventObject.BACKSPACE) {
			this.acceptInputHandler(e);
		}
	},

	intro: function() {
		this.initExercise();
		this.easyAdvanceRegister(true);
		var template = this.win.Ext.Template.from('text-template');
		var values = {
			text: this.introText,
			title: this.name,
			introContentId: 'lesson-begin-content',
			link: '<div onclick="lesson.beginExercise()" style="font-weight: strong">'+text.BEGIN_LESSON+' &raquo; <a href="/'+ this.locale +'/student/start">&laquo; '+text.BACK_TO_COURSES+'</a></div>'
		};

		this.showText(template.apply(values), true);
	},


	textSection: function() {
		var template = this.win.Ext.Template.from('text-template-help');
		var values = {
			text: this.exercises[this.exerciseNum].helpText,
			title: this.exercises[this.exerciseNum].title,
			link: '<div onclick="lesson.beginExercise()" style="font-weight: strong">'+text.CONTINUE_LESSON+' &raquo; <a href="/'+ this.locale +'/student/start">&laquo; '+text.BACK_TO_COURSES+'</a></div>'
		};

		this.exercises[this.exerciseNum].helpText = '';
		this.showText(template.apply(values), true);
		this.easyAdvanceRegister(true);
	},

	progressWindow: function() {
//		this.pause();

		var template = this.win.Ext.Template.from('progress-template');

		var accuracy = 100-Math.round((this.errors/this.characters)*100);
		var lessonAcc = 100-Math.round(((this.lessonErrors+this.errors)/(this.lessonCharacters+this.characters))*100);
		var problemKeys = [];
		for(var key in this.errorKeys) {
			if (typeof this.errorKeys[key] == "function") { continue; }
			if (key == " ") continue;
			problemKeys.push(key.replace(/key\-/, '').toUpperCase());
			if (problemKeys.length == 4) break;
		}
		var progress = Math.round((this.exerciseNum+1)/this.exercises.length*100);

		var values = {
			netSpeed: this.calculateSpeed(this.characters, this.seconds, this.errors),
			grossSpeed: this.calculateSpeed(this.characters, this.seconds, 0),
			accuracy: (isNaN(accuracy) || accuracy == Number.POSITIVE_INFINITY || accuracy == Number.NEGATIVE_INFINITY)?100:accuracy,
			time: this.win.Ext.fly('time').dom.innerHTML,
			problemKeys: (problemKeys.length > 0)?problemKeys.join(" "):text.NONE,
			lessonNetSpeed: this.calculateSpeed(this.lessonCharacters+this.characters, this.lessonSeconds+this.seconds, this.lessonErrors+this.errors),
			lessonAccuracy: (isNaN(lessonAcc) || lessonAcc == Number.POSITIVE_INFINITY || lessonAcc == Number.NEGATIVE_INFINITY)?100:lessonAcc,
			progressBar: progress*1.5,
			percentComplete: progress,
			speedType: this.speedType  || 'wpm',
			restartLink: '<span onclick="lesson.restartExercise();">'+text.RETAKE_EXERCISE+'</span>',
			link: '<span onclick="lesson.nextExercise();">'+text.SAVE_AND_CONTINUE+' &raquo;</span>'
		};
		this.showText(template.apply(values));
		this.easyAdvanceRegister(true);
	},

	restartExercise: function() {
		this.initExercise();
		this.beginExercise();
	},

	congrats: function() {
		Shadowbox.close();
		this.acceptInputRegister(false);
		this.easyAdvanceRegister(false);

		Ext.getDoc().un('keypress', this.acceptInputHandler, this);
		Ext.getDoc().un('keypress', this.easyAdvanceHandler, this);

		this.pause();
		var template = this.win.Ext.Template.from('text-template-complete', {loadScripts: true});
		if (this.test && this.showTestGraph) {
			this.congratsText = '<IFRAME SRC="'+ this.locale +'/student/stats/'+ this.lang +'/" style="width: 100%;height: 284px;border: none;}"></IFRAME>';
		}

		if (this.course == 'CERTIFICATION') {
			var continueLink = '<div onclick="location.href = \'/'+ this.locale +'/student/certification/\'" style="font-weight: strong">'+text.RETURN_TO_COURSES+' &raquo;</div>';
		} else {
			var continueLink = '<div onclick="location.href = \'/'+ this.locale +'/student/lesson/'+ this.lang +'\'" style="font-weight: strong">'+text.RETURN_TO_COURSES+' &raquo;</div>';
		}

		if (this.test) {
			continueLink =  '<div>&nbsp;</div>' + continueLink;
		} else {
			if (this.userID) {
				continueLink =  '<div onclick="location.href = \'/student/lesson/test/\'" style="font-weight: strong">'+text.TAKE_TYPING_TEST+' &raquo;</div>' + continueLink;
			} else {
				continueLink =  '<div>&nbsp;</div>' + continueLink;
			}
		}

		var message = '';
		if (this.test) {
			message = text.I_JUST_FINISHED + " '" + lessonData.name + "'! " + text.MY_SCORE + ': ' + this.win.Ext.fly('lwpm').dom.innerHTML + ' ' + lessonData.speedType.toUpperCase();
		} else {
			message = text.I_JUST_FINISHED + " '" + lessonData.name + "'! " + text.MY_SCORE + ': ' + this.win.Ext.fly('lpro').dom.innerHTML + ' ' + lessonData.speedType.toUpperCase();
		}

		var values = {
			text: this.congratsText,
			title: this.name,
			link: continueLink
		};

		var that = this;
		setTimeout(function(){
			that.showText(template.apply(values));
		}, 500);


	},

	alert: function(title, text, callback, scope) {
		if (Ext.isIE6) {
			alert(title + "\n" + text);
			if (callback) {
				var fn = callback.createDelegate(scope);
				fn();
			}
		} else {
			Ext.Msg.alert(title, text, callback, scope);
		}
	},

	playSound: function(sound) {
		if (!this.playSounds) return;

		if (navigator.userAgent.match(/MSIE 10/)) {
			return
		}

		if (document.all) {
			document.getElementById('ieSoundEffect').src = lesson.sounds[sound];
		} else {
			if (this.sounds[sound]) {
				new Audio(this.sounds[sound]).play();
			}
		}
	},

	toggleSound: function() {
		if (this.playSounds) {
			this.playSounds = false;
		} else {
			this.playSounds = true;
		}
		// sound enable xiij baigaa xeseg end xiij bn ajax call xiij bn.
		Ext.Ajax.request({
			url: '/student/lesson/sound/',
			success: Ext.emptyFn,
			failure: Ext.emptyFn,
			params: {
				sounds: (this.playSounds)?1:0
			},
			scope: this
		});
	},

	restart: function (url) {
		if (Ext.isIE6) {
			if (confirm(text.RESTART_LESSON+"\n"+text.RESTART_LESSON_TEXT)) {
				location.href = url;
			}
		} else {
			Ext.Msg.confirm(text.RESTART_LESSON, text.RESTART_LESSON_TEXT, function(clicked) {
				if (clicked == "yes") {
					location.href = url;
				}
			}, this);
		}
	}
};

function returnFocusFromFlash() {
	// flash steals focus... Don't know how to get it back, but this is where it would happen
}

lesson = new tw.lesson();

function initTutor() {
  lesson.win = window;
/*
	lesson.win.Ext.fly('lessonLink').on('click', function(e){
		e.preventDefault();
		lesson.restart('/tutor/lesson/'+lessonData.actionName+'/id/'+lessonData.lessonID+'/restart/1');
	});
	lesson.win.Ext.fly('lessonLink').dom.innerHTML = lessonData.name;
  */

		lesson.win.Ext.fly('toggle-full-screen').on('click', function(e){
          if (!document.fullscreenElement &&    // alternative standard method
                  !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
              if (document.documentElement.requestFullscreen) {
                  document.documentElement.requestFullscreen();
              } else if (document.documentElement.msRequestFullscreen) {
                  document.documentElement.msRequestFullscreen();
              } else if (document.documentElement.mozRequestFullScreen) {
                  document.documentElement.mozRequestFullScreen();
              } else if (document.documentElement.webkitRequestFullscreen) {
                  document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
              }
          } else {
              if (document.exitFullscreen) {
                  document.exitFullscreen();
              } else if (document.msExitFullscreen) {
                  document.msExitFullscreen();
              } else if (document.mozCancelFullScreen) {
                  document.mozCancelFullScreen();
              } else if (document.webkitExitFullscreen) {
                  document.webkitExitFullscreen();
              }
          }
		});

	if (lessonData.playSounds) {
		lesson.win.Ext.fly('volume').dom.className = 'volume on';
	} else {
		lesson.win.Ext.fly('volume').dom.className = 'volume off';
	}

	//lesson.win.Ext.select('.speedTypeText').update(lessonData.speedType+' ' || 'wpm ');
	if (lessonData.noKeyboard) {
		lesson.win.Ext.fly('keyboardshell').hide();
	} else {
		lesson.win.Ext.fly('keyboardshell').show();
	}

	/*if (lessonData.restricted || lessonData.course == 'CERTIFICATION' || lessonData.test || lessonData.newslesson || lessonData.course == 'PROBLEM_KEYS' || lessonData.achievedTrophy || !lessonData.hasTrophy) {
		lesson.win.Ext.fly('trophy-complete').remove();
	} else {
		lesson.win.Ext.fly('trophy-image').dom.src = resourceURL + '/tutor/images/trophies/'+lessonData.lessonID+'.png';
	}*/

	lesson.KeyboardMovie = lesson.win.document.getElementById("twkeyboard");

	lesson.init();
}

Ext.onReady(function() {
	initTutor();
});


var hexcase=0;function thiskey(a){return rstr2hex(rstr_key(str2rstr_utf8(a)))}function hex_hmac_key(a,b){return rstr2hex(rstr_hmac_key(str2rstr_utf8(a),str2rstr_utf8(b)))}function key_vm_test(){return thiskey("abc").toLowerCase()=="900150983cd24fb0d6963f7d28e17f72"}function rstr_key(a){return binl2rstr(binl_key(rstr2binl(a),a.length*8))}function rstr_hmac_key(c,f){var e=rstr2binl(c);if(e.length>16){e=binl_key(e,c.length*8)}var a=Array(16),d=Array(16);for(var b=0;b<16;b++){a[b]=e[b]^909522486;d[b]=e[b]^1549556828}var g=binl_key(a.concat(rstr2binl(f)),512+f.length*8);return binl2rstr(binl_key(d.concat(g),512+128))}function rstr2hex(c){try{hexcase}catch(g){hexcase=0}var f=hexcase?"0123456789ABCDEF":"0123456789abcdef";var b="";var a;for(var d=0;d<c.length;d++){a=c.charCodeAt(d);b+=f.charAt((a>>>4)&15)+f.charAt(a&15)}return b}function str2rstr_utf8(c){var b="";var d=-1;var a,e;while(++d<c.length){a=c.charCodeAt(d);e=d+1<c.length?c.charCodeAt(d+1):0;if(55296<=a&&a<=56319&&56320<=e&&e<=57343){a=65536+((a&1023)<<10)+(e&1023);d++}if(a<=127){b+=String.fromCharCode(a)}else{if(a<=2047){b+=String.fromCharCode(192|((a>>>6)&31),128|(a&63))}else{if(a<=65535){b+=String.fromCharCode(224|((a>>>12)&15),128|((a>>>6)&63),128|(a&63))}else{if(a<=2097151){b+=String.fromCharCode(240|((a>>>18)&7),128|((a>>>12)&63),128|((a>>>6)&63),128|(a&63))}}}}}return b}function rstr2binl(b){var a=Array(b.length>>2);for(var c=0;c<a.length;c++){a[c]=0}for(var c=0;c<b.length*8;c+=8){a[c>>5]|=(b.charCodeAt(c/8)&255)<<(c%32)}return a}function binl2rstr(b){var a="";for(var c=0;c<b.length*32;c+=8){a+=String.fromCharCode((b[c>>5]>>>(c%32))&255)}return a}function binl_key(p,k){p[k>>5]|=128<<((k)%32);p[(((k+64)>>>9)<<4)+14]=k;var o=1732584193;var n=-271733879;var m=-1732584194;var l=271733878;for(var g=0;g<p.length;g+=16){var j=o;var h=n;var f=m;var e=l;o=key_ff(o,n,m,l,p[g+0],7,-680876936);l=key_ff(l,o,n,m,p[g+1],12,-389564586);m=key_ff(m,l,o,n,p[g+2],17,606105819);n=key_ff(n,m,l,o,p[g+3],22,-1044525330);o=key_ff(o,n,m,l,p[g+4],7,-176418897);l=key_ff(l,o,n,m,p[g+5],12,1200080426);m=key_ff(m,l,o,n,p[g+6],17,-1473231341);n=key_ff(n,m,l,o,p[g+7],22,-45705983);o=key_ff(o,n,m,l,p[g+8],7,1770035416);l=key_ff(l,o,n,m,p[g+9],12,-1958414417);m=key_ff(m,l,o,n,p[g+10],17,-42063);n=key_ff(n,m,l,o,p[g+11],22,-1990404162);o=key_ff(o,n,m,l,p[g+12],7,1804603682);l=key_ff(l,o,n,m,p[g+13],12,-40341101);m=key_ff(m,l,o,n,p[g+14],17,-1502002290);n=key_ff(n,m,l,o,p[g+15],22,1236535329);o=key_gg(o,n,m,l,p[g+1],5,-165796510);l=key_gg(l,o,n,m,p[g+6],9,-1069501632);m=key_gg(m,l,o,n,p[g+11],14,643717713);n=key_gg(n,m,l,o,p[g+0],20,-373897302);o=key_gg(o,n,m,l,p[g+5],5,-701558691);l=key_gg(l,o,n,m,p[g+10],9,38016083);m=key_gg(m,l,o,n,p[g+15],14,-660478335);n=key_gg(n,m,l,o,p[g+4],20,-405537848);o=key_gg(o,n,m,l,p[g+9],5,568446438);l=key_gg(l,o,n,m,p[g+14],9,-1019803690);m=key_gg(m,l,o,n,p[g+3],14,-187363961);n=key_gg(n,m,l,o,p[g+8],20,1163531501);o=key_gg(o,n,m,l,p[g+13],5,-1444681467);l=key_gg(l,o,n,m,p[g+2],9,-51403784);m=key_gg(m,l,o,n,p[g+7],14,1735328473);n=key_gg(n,m,l,o,p[g+12],20,-1926607734);o=key_hh(o,n,m,l,p[g+5],4,-378558);l=key_hh(l,o,n,m,p[g+8],11,-2022574463);m=key_hh(m,l,o,n,p[g+11],16,1839030562);n=key_hh(n,m,l,o,p[g+14],23,-35309556);o=key_hh(o,n,m,l,p[g+1],4,-1530992060);l=key_hh(l,o,n,m,p[g+4],11,1272893353);m=key_hh(m,l,o,n,p[g+7],16,-155497632);n=key_hh(n,m,l,o,p[g+10],23,-1094730640);o=key_hh(o,n,m,l,p[g+13],4,681279174);l=key_hh(l,o,n,m,p[g+0],11,-358537222);m=key_hh(m,l,o,n,p[g+3],16,-722521979);n=key_hh(n,m,l,o,p[g+6],23,76029189);o=key_hh(o,n,m,l,p[g+9],4,-640364487);l=key_hh(l,o,n,m,p[g+12],11,-421815835);m=key_hh(m,l,o,n,p[g+15],16,530742520);n=key_hh(n,m,l,o,p[g+2],23,-995338651);o=key_ii(o,n,m,l,p[g+0],6,-198630844);l=key_ii(l,o,n,m,p[g+7],10,1126891415);m=key_ii(m,l,o,n,p[g+14],15,-1416354905);n=key_ii(n,m,l,o,p[g+5],21,-57434055);o=key_ii(o,n,m,l,p[g+12],6,1700485571);l=key_ii(l,o,n,m,p[g+3],10,-1894986606);m=key_ii(m,l,o,n,p[g+10],15,-1051523);n=key_ii(n,m,l,o,p[g+1],21,-2054922799);o=key_ii(o,n,m,l,p[g+8],6,1873313359);l=key_ii(l,o,n,m,p[g+15],10,-30611744);m=key_ii(m,l,o,n,p[g+6],15,-1560198380);n=key_ii(n,m,l,o,p[g+13],21,1309151649);o=key_ii(o,n,m,l,p[g+4],6,-145523070);l=key_ii(l,o,n,m,p[g+11],10,-1120210379);m=key_ii(m,l,o,n,p[g+2],15,718787259);n=key_ii(n,m,l,o,p[g+9],21,-343485551);o=safe_add(o,j);n=safe_add(n,h);m=safe_add(m,f);l=safe_add(l,e)}return Array(o,n,m,l)}function key_cmn(h,e,d,c,g,f){return safe_add(bit_rol(safe_add(safe_add(e,h),safe_add(c,f)),g),d)}function key_ff(g,f,k,j,e,i,h){return key_cmn((f&k)|((~f)&j),g,f,e,i,h)}function key_gg(g,f,k,j,e,i,h){return key_cmn((f&j)|(k&(~j)),g,f,e,i,h)}function key_hh(g,f,k,j,e,i,h){return key_cmn(f^k^j,g,f,e,i,h)}function key_ii(g,f,k,j,e,i,h){return key_cmn(k^(f|(~j)),g,f,e,i,h)}function safe_add(a,d){var c=(a&65535)+(d&65535);var b=(a>>16)+(d>>16)+(c>>16);return(b<<16)|(c&65535)}function bit_rol(a,b){return(a<<b)|(a>>>(32-b))};
