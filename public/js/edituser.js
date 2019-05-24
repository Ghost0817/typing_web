tw.UserEditForm = Ext.extend(Ext.FormPanel, {
	border: true,
	frame: false,
	bodyStyle:'padding: 2px',

	initComponent: function() {
	 	Ext.apply(this, {
	 		 labelWidth: 120
			,labelAlign: 'right'
			,width: 400
			,defaults: {
				anchor: '94%'
			},
            items: [{
				title: text.ACCOUNT_INFORMATION,
				baseCls:'form-group'
            },{
	 			xtype: 'textfield',
	 			fieldLabel: text.USERNAME,
	 			name: 'username',
	 			allowBlank: false,
	 			msgTarget: 'side',
	 			invalidText: text.USERNAME_FIELD_ERROR,
	 			regex: /^[А-Яа-яA-Za-z0-9\.\_\@\-\$]{4,30}$/
	 		},{
	 			xtype: 'textfield',
	 			fieldLabel: text.PASSWORD,
	 			name: 'password',
	 			allowBlank: true,
	 			msgTarget: 'side'
	 		},{
	 			xtype: 'label',
	 			html: text.NOTE_PASSWORD_TIP,
	 			style: 'margin-left: 130px'
	 		},{
				title: text.OPTIONAL_FIELDS,
				baseCls:'form-group'
            },{
	 			xtype: 'textfield',
	 			fieldLabel: text.FIRST_NAME,
	 			name: 'firstname',
	 			allowBlank: true,
	 			msgTarget: 'side'
	 		},{
	 			xtype: 'textfield',
	 			fieldLabel: text.LAST_NAME,
	 			name: 'lastname',
	 			allowBlank: true,
	 			msgTarget: 'side'
	 		},{
	 			xtype: 'textfield',
	 			fieldLabel: text.EMAIL,
	 			name: 'email',
	 			allowBlank: true,
	 			msgTarget: 'side',
	 			vtype: 'email'
	 		},{
				title: text.PREFERENCES,
				baseCls:'form-group'
            },{
	 			xtype: 'combo',
	 			fieldLabel: text.SPEED_CALCULATION,
	 			transform: 'speedTypes',
	 			name: 'speed',
	 			hiddenName: 'speed',
	 			triggerAction: 'all',
	 			forceSelection: true,
				editable: false,
	 			lazyRender: true
	 		},{
	 			xtype: 'combo',
	 			fieldLabel: text.SENTENCE_SPACES,
	 			transform: 'spaces',
	 			name: 'spaces',
	 			hiddenName: 'spaces',
	 			triggerAction: 'all',
	 			forceSelection: true,
				editable: false,
	 			lazyRender: true
	 		},{
	 			xtype: 'combo',
	 			fieldLabel: text.TYPING_SOUNDS,
	 			transform: 'sounds',
	 			name: 'sounds',
	 			hiddenName: 'sounds',
	 			triggerAction: 'all',
	 			forceSelection: true,
				editable: false,
	 			lazyRender: true
	 		}]
	 	});

	 	tw.UserEditForm.superclass.initComponent.apply(this, arguments);
}
});

tw.UserEditWindow = Ext.extend(Ext.Window, {
	modal: true,
	userID: 0,
	title: text.EDIT_STUDENT,
	reopen: false,
	closeAction: 'hide',
	width: 414,
	height: 'auto',

	initComponent: function() {
		this.form = new tw.UserEditForm();

	 	Ext.apply(this, {
	 		 items: [this.form],
	 		 buttons: [
	 		 	{
	 		 		text: text.SAVE,
	 		 		handler: function() {
	 		 			if (!this.form.getForm().isValid()) {
					 		Ext.Msg.alert(text.VALIDATION_ERROR, text.PLEASE_CORRECT);
					 		return;
					 	}

			 			Ext.MessageBox.show({
							title: text.PLEASE_WAIT,
							width: 300,
							wait:true,
							waitConfig: {interval:100},
							msg: text.SAVING_STUDENT,
							progress: true,
							closable: false,
							icon: Ext.MessageBox.INFO
						});

					 	var that = this;
					 	setTimeout(function(){
						 	that.form.getForm().submit({
						 		url: url.SAVE_STUDENT_URL,
						 		method: 'post',
						 		params: {
						 			userID: that.userID
						 		},
						 		success: function(form, action) {
						 			Ext.MessageBox.hide();
						 			tw.alert(text.STUDENT_ACCOUNT_SUCCESSFULLY_UPDATED);
						 			this.hide();
						 			this.opener.reload();
						 		},
						 		failure: function(form, action) {
						 			Ext.MessageBox.hide();
						 			if (action.result && action.result.message) {
						 				Ext.Msg.alert(text.THERE_WAS_AN_ERROR, action.result.message, 'danger');
						 			} else {
						 				Ext.Msgalert(text.THERE_WAS_AN_ERROR, text.UNKNOWN_ERROR, 'danger');
						 			}
						 		},
						 		scope: that
						 	});
					 	}, 1000);
	 		 		},
	 		 		scope: this
	 		 	},
	 		 	{
	 		 		text: text.CANCEL,
	 		 		handler: function() {
	 		 			this.hide();
	 		 		},
	 		 		scope: this
	 		 	}]
	 	});
	 	tw.UserEditWindow.superclass.initComponent.apply(this, arguments);
	},

	show: function(config) {
		this.form.getForm().reset();

		Ext.MessageBox.show({
			title: text.PLEASE_WAIT,
			width: 300,
			wait:true,
			waitConfig: {interval:100},
			msg: text.LOADING_STUDENT,
			progress: true,
			closable: false,
			icon: Ext.MessageBox.INFO
		});

		var args = arguments;
		this.userID = config.userID;
		this.form.getForm().load({
			url: url.LOAD_STUDENT_URL,
			method: 'post',
			waitTitle: text.PLEASE_WAIT,
			waitMsg: text.LOADING_STUDENT,
			params: {
				userID: config.userID
			},
			success: function() {
				Ext.Msg.hide();
				if (this.reopen) {
					tw.UserEditWindow.superclass.show.apply(this, args);
				}
				this.reopen = true;
			},
			failure: function() {
				if (this.reopen) {
					tw.UserEditWindow.superclass.show.apply(this, args);
				}
				Ext.Msg.hide();
				tw.alert(text.THERE_WAS_AN_ERROR, text.UNKNOWN_ERROR, 'danger');
				this.hide();
				this.reopen = true;
			},
			scope: this
		});

		if (!this.reopen) {
			tw.UserEditWindow.superclass.show.apply(this, args);
		}
	}
});
