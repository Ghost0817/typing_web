Ext.namespace("tw");

if(navigator.userAgent.match(/Chrome/)){
    var chromeDatePickerCSS = ".x-date-picker {border-color: #1b376c;background-color:#fff;position: relative;width: 185px;}";
    Ext.util.CSS.createStyleSheet(chromeDatePickerCSS,'chromeDatePickerStyle');
}

tw.ajaxError = function(response, other) {
	Ext.Msg.alert("ERROR", "There was an error communicating with the server");
	if (typeof console == "object") console.dir(response);
	if (typeof console == "object") console.dir(other);
}

tw.clearAlerts = function() {
	var area = $('#alert-area').html('');
}

tw.alert = function(title, text, type) {
	// type can be 'success', 'info', 'danger'
	type = type || 'success';
	title = title || '';
	text = text || '';

	var area = $('#alert-area'),
		alert = $('<div class="msg-'+type+'"><strong>'+title+'</strong> '+text+' <a href="#" class="close">&times;</a></div>').hide();
	if (!area.length) {
		return;
	}

	area.html(alert);
	alert.fadeIn();
};

function querystring(key) {
   var re=new RegExp('(?:\\?|&)'+key+'=(.*?)(?=&|$)','gi');
   var r=[], m;
   while ((m=re.exec(document.location.search)) != null) r.push(m[1]);
   return r;
}


Ext.onReady(function() {
	var area = $('#alert-area');
	if (!area.length) {
		var header = $('.pageHeader').eq(0);
		if (header.length) {
			area = $('<div id="alert-area" />');
			header.after(area);

			$('#alert-area').on('click', '.close', function(e){
				$(e.target).parent('.alert').fadeOut();
			});
		}
	}


	Ext.state.Manager.setProvider(new Ext.state.CookieProvider({path: '/teacher/', expires: new Date(new Date().getTime()+(1000*60*60*24*30))}));
	Ext.QuickTips.init();
});

String.prototype.ucFirst = function() {
    var f = this.charAt(0).toUpperCase();
    return f + this.substr(1);
}

String.prototype.ucWords = function(){
     return this.toLowerCase().replace(/\w+/g,function(s){
          return s.charAt(0).toUpperCase() + s.substr(1);
     })
}


/** Bunch of Checkbox bug fixes **/
Ext.override(Ext.form.Checkbox, {
	onRender: function(ct, position){
		Ext.form.Checkbox.superclass.onRender.call(this, ct, position);
		if(this.inputValue !== undefined){
			this.el.dom.value = this.inputValue;
		}
		//this.el.addClass('x-hidden');
		this.innerWrap = this.el.wrap({
			//tabIndex: this.tabIndex,
			cls: this.baseCls+'-wrap-inner'
		});
		this.wrap = this.innerWrap.wrap({cls: this.baseCls+'-wrap'});
		this.imageEl = this.innerWrap.createChild({
			tag: 'img',
			src: Ext.BLANK_IMAGE_URL,
			cls: this.baseCls
		});
		if(this.boxLabel){
			this.labelEl = this.innerWrap.createChild({
				tag: 'label',
				htmlFor: this.el.id,
				cls: 'x-form-cb-label',
				html: this.boxLabel
			});
		}
		//this.imageEl = this.innerWrap.createChild({
			//tag: 'img',
			//src: Ext.BLANK_IMAGE_URL,
			//cls: this.baseCls
		//}, this.el);
		if(this.checked){
			this.setValue(true);
		}else{
			this.checked = this.el.dom.checked;
		}
		this.originalValue = this.checked;
	},
	afterRender: function(){
		Ext.form.Checkbox.superclass.afterRender.call(this);
		//this.wrap[this.checked ? 'addClass' : 'removeClass'](this.checkedCls);
		this.imageEl[this.checked ? 'addClass' : 'removeClass'](this.checkedCls);
	},
	initCheckEvents: function(){
		//this.innerWrap.removeAllListeners();
		this.innerWrap.addClassOnOver(this.overCls);
		this.innerWrap.addClassOnClick(this.mouseDownCls);
		this.innerWrap.on('click', this.onClick, this);
		//this.innerWrap.on('keyup', this.onKeyUp, this);
	},
	onFocus: function(e) {
		Ext.form.Checkbox.superclass.onFocus.call(this, e);
		//this.el.addClass(this.focusCls);
		this.innerWrap.addClass(this.focusCls);
	},
	onBlur: function(e) {
		Ext.form.Checkbox.superclass.onBlur.call(this, e);
		//this.el.removeClass(this.focusCls);
		this.innerWrap.removeClass(this.focusCls);
	},
	onClick: function(e){
		if (e.getTarget().htmlFor != this.el.dom.id) {
			if (e.getTarget() !== this.el.dom) {
				this.el.focus();
			}
			if (!this.disabled && !this.readOnly) {
				this.toggleValue();
			}
		}
		//e.stopEvent();
	},
	onEnable: Ext.form.Checkbox.superclass.onEnable,
	onDisable: Ext.form.Checkbox.superclass.onDisable,
	onKeyUp: undefined,
	setValue: function(v) {
		var checked = this.checked;
		this.checked = (v === true || v === 'true' || v == '1' || String(v).toLowerCase() == 'on');
		if(this.rendered){
			this.el.dom.checked = this.checked;
			this.el.dom.defaultChecked = this.checked;
			//this.wrap[this.checked ? 'addClass' : 'removeClass'](this.checkedCls);
			this.imageEl[this.checked ? 'addClass' : 'removeClass'](this.checkedCls);
		}
		if(checked != this.checked){
			this.fireEvent("check", this, this.checked);
			if(this.handler){
				this.handler.call(this.scope || this, this, this.checked);
			}
		}
	},
	getResizeEl: function() {
		//if(!this.resizeEl){
			//this.resizeEl = Ext.isSafari ? this.wrap : (this.wrap.up('.x-form-element', 5) || this.wrap);
		//}
		//return this.resizeEl;
		return this.wrap;
	}
});
Ext.override(Ext.form.Radio, {
	checkedCls: 'x-form-radio-checked'
});

Ext.override(Ext.Component, {
    saveState : function(){
        if(Ext.state.Manager && this.stateful !== false){
            var state = this.getState();
            if(this.fireEvent('beforestatesave', this, state) !== false){
                Ext.state.Manager.set(this.stateId || this.id, state);
                this.fireEvent('statesave', this, state);
            }
        }
    },
    stateful : false
});

Date.prototype.toISO8601 = function() {
	var d = this;
    var pad = function pad(n){
        return n < 10 ? '0'+n : n
    }
    return d.getUTCFullYear()+'-'
    + pad(d.getUTCMonth()+1)+'-'
    + pad(d.getUTCDate())+'T'
    + pad(d.getUTCHours())+':'
    + pad(d.getUTCMinutes())+':'
    + pad(d.getUTCSeconds())+'Z'
}
