Ext.namespace("tw");

Ext.MessageBox.buttonText.yes = text.YES;
Ext.MessageBox.buttonText.no = text.NO;
Ext.MessageBox.buttonText.cancel = text.CANCEL;
/**
 * Users Grid
 * @class usersgrid
 * @extends Ext.grid.GridPanel
 */
tw.usersgrid = Ext.extend(Ext.grid.GridPanel, {
	stateful: true,
	stateEvents: ['sortchange','columnmove','columnresize'],
	stateId: 'users',
	loadMask: true,
	border: true,
	style: 'margin: 10px 0px',
	stripeRows: true,
	autoExpandColumn: 'name',
	width: 'auto',
	height: 'auto',
	autoHeight: true,
	minHeight: 800,
	frame: true,
	pageSize: 30,

	initComponent: function () {
		this.record = Ext.data.Record.create([
			{name: 'userID', type: 'int'},
			'username',
	 		'firstname',
	 		'lastname',
	 		'email',
	 		{name: 'lastLogin', type: 'date', dateFormat: "Y-m-d H:i:s"},
	 		{name: 'signupDate', type: 'date', dateFormat: "Y-m-d H:i:s"},
	 		'userType',
	 		'class'
	 	]);

		var store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
               url: url.GRID_USERS_URL,  //url to data object (server side script).  Defined in global scope by php
               method: 'POST'
            }),
            reader: new Ext.data.JsonReader({
	            	root: 'results',
	            	totalProperty: 'total',
	            	id: 'userID'
	            },
	            this.record
            ),
            sortInfo:{
            	field: 'username',
            	direction: 'asc'
            },
            remoteSort: false
        });

	    var selModel = new Ext.grid.CheckboxSelectionModel();

	    // This is a var cause only admins see it

		Ext.apply(this, {
			store: store,
			view: new Ext.grid.GridView({
				forceFit:true,
				emptyText: text.NO_RESULTS+' <a href="'+ url.ADD_STUDENT_URL +'">' + text.STUDENT_CREATE + '</a>',
				scrollOffset: 0
			}),
			columns: [selModel,{
				dataIndex:'username',
				header:text.USERNAME,
				sortable: true
			},{
				dataIndex:'firstname',
				header:text.FIRST_NAME,
				sortable: true
			},{
				dataIndex:'lastname',
				header:text.LAST_NAME,
				sortable: true
			},{
				dataIndex:'class',
				header:text.CLASS,
				sortable: true
			},{
				dataIndex:'email',
				header:text.EMAIL,
				sortable: true,
				hidden: true
			},{
				dataIndex:'lastLogin',
				header:text.LAST_LOGIN,
				sortable: true,
				renderer: function (value) {
					if (value.format("Y") < 1970) return text.NEVER;
					return value.format((languageID > 1)?'Y-m-d':'M d, Y h:ia ');
				}
			},{
				dataIndex:'signupDate',
				header:text.DATE_ENROLLED,
				sortable: true,
				renderer: function (value) {
					if (value.format("Y") < 1970) return text.NEVER;
					return value.format((languageID > 1)?'Y-m-d':'M d, Y');
				}
			},{
				dataIndex:'userType',
				header:text.ACCOUNT_TYPE,
				sortable: true
			},{
	        	dataIndex: 'view',
	        	menuDisabled: true,
	        	header: '',
	        	align: 'center',
	        	width: 30,
	        	fixed: true,
	        	renderer: function() {
	        		return '<div class="grid-icon-column-full fa fa-search fa-lg" title="'+ text.VIEW_STUDENT +'"></div>';
	        	}
			},{
	        	dataIndex: 'edit',
	        	menuDisabled: true,
	        	header: '',
	        	align: 'center',
	        	width: 30,
	        	fixed: true,
	        	renderer: function(value, meta, record) {
	        		return '<div class="grid-icon-column-full fa fa-pencil fa-lg" title="'+ text.EDIT_STUDENT_TIP +'"></div>';
	        	}
			}],
			selModel: selModel,
			tbar: [{
				xtype: 'label',
				html: '<img style="position: relative; top: 2px;" src="/teacher/images/arrow_turn_left_down.png">',
				style: 'padding: 0 4px'
			},{
				text: '<i class="fa fa-users fa-lg" aria-hidden="true"></i> '+ text.CHANGE_CLESSES,
				iconCls: 'icon-group',
				handler: this.moveUsers,
				scope: this,
				tooltip: text.MOVE_CHECKED_STUDENTS
			},'-',{
				text: '<i class="fa fa-user fa-lg" aria-hidden="true"></i> '+text.RESET_ACCOUNTS,
				iconCls: 'icon-status-offline',
				handler: this.resetUser,
				scope: this,
				tooltip: text.RESETTING_AN_ACCOUNT
			},'-',{
				text: '<i class="fa fa-key fa-lg" aria-hidden="true"></i> '+ text.RESET_PASSWORD,
				iconCls: 'icon-textfield-key',
				handler: this.resetPassword,
				scope: this,
				tooltip: text.RESET_PASSWORD_TIP
			},'-',{
				text: '<i class="fa fa-times fa-lg" aria-hidden="true"></i> '+ text.DELETE_ACCOUNT,
				iconCls: 'icon-cross',
				handler: this.deleteUser,
				scope: this,
				tooltip: text.DELETE_ACCOUNT_TIP
			},'-',{
				text: '<i class="fa fa-chain-broken fa-lg" aria-hidden="true"></i> '+ text.UNLINK_ACCOUNT,
				iconCls: 'icon-unlink',
				handler: this.unlinkUser,
				scope: this,
				tooltip: text.UNLINK_ACCOUNT_TIP
			},
			'->',
			{
				xtype: 'label',
				html: text.SWITCH_CLASS+':'
			},
			{
				xtype: 'combo',
				transform: 'groups',
				id: 'groupFilter',
				lazyRender: true,
				forceSelection: true,
				editable: false,
				triggerAction: 'all',
				width: 170,
				listeners: {
					select: {
						fn: function(field, record) {
							this.store.baseParams.gaGroupID = record.id;
							this.reload();
						},
						scope: this
					}
				}
			}]
		});

		this.editwin = new tw.UserEditWindow( { opener: this } );

		tw.usersgrid.superclass.initComponent.apply(this, arguments);

	    this.on('celldblclick', this.click, this);
	    this.on('cellclick', this.click, this);
	},

	onRender:function() {
		tw.usersgrid.superclass.onRender.apply(this, arguments);

		var groupFilter = Ext.getCmp('groupFilter');
		if (groupFilter) {
			this.store.baseParams.gaGroupID = groupFilter.getValue();
		}
		this.reload();
    },

    reload: function() {
    	this.store.reload({
        	params: {
        		gaGroupID: groupID
        	}
        });
    },

	viewUser: function(uid) {
		location.href = url.DETAILS_STUDENT_URL.replace('_sid',uid);
	},

	editUser: function(uid) {
		this.editwin.show({userID: uid});
	},

	moveUsers: function() {
		var selected = this.getSelectionModel().getSelections();
		if (selected.length == 0) {
			Ext.Msg.alert(text.CHANGE_CLESSES, text.NO_STUDENT_SELECTED);
			return;
		}

		if (totalGroups == 0) {
			Ext.Msg.alert(text.CHANGE_CLESSES, text.YOU_HAVE_NOT_YET + " <a href=\"" + url.CREATE_CLASSES_URL + "\">" + text.CREATE_YOUR_FIRST_CLASS + "</a>");
			return;
		}

		if (!this.movewindow) {
			this.movewindow = new tw.movewindow({opener: this});
		}
		this.movewindow.show();
	},

	moveUsersToGroup: function(groupID) {
		var users = []
		var selected = this.getSelectionModel().getSelections();
		for(var index in selected) {
			users.push(selected[index].id);
		}

		Ext.MessageBox.show({
			title: text.PLEASE_WAIT + "...",
			width: 300,
			wait:true,
			waitConfig: {interval:100},
			msg: text.MOVING_STUDENT,
			progress: true,
			closable: false,
			icon: Ext.MessageBox.INFO
		});
		Ext.Ajax.request({
			url: url.MOVE_STUDENT_URL,
			success: function(response, options) {
				var res = Ext.decode(response.responseText);
				Ext.MessageBox.hide();
				if (res.success) {
					tw.alert(text.MOVE_SUCCESSFUL, text.MOVE_SUCCESSFUL_DESC);
					this.store.reload();
				} else {
					tw.alert(text.THERE_WAS_AN_ERROR, res.message, 'danger');
				}
			},
			params: {
				groupID: groupID,
				users: Ext.encode(users)
			},
			failure: tw.ajaxError,
			scope: this
		});
	},

	deleteUser: function() {
		var selected = this.getSelectionModel().getSelections();
		if (selected.length == 0) {
			Ext.Msg.alert(text.DELETE_STUDENTS, text.NO_STUDENT_SELECTED);
			return;
		}

		var title = text.DELETE_STUDENTS;
		var msg = text.DELETE_CONFIRM;
		var users = [];
		var premiumUsers = false;
		for(var index in selected) {
			if (typeof selected[index] === 'function') continue;
			users.push(selected[index].id);
			premiumUsers = (selected[index].get('userType') == 'Premium') ? true : premiumUsers;
		}

		if (premiumUsers) {
			msg += "<br /><br /><br /><strong>WARNING: At least one of the accounts you are about to delete is a PAID PREMIUM account.  This action is irreversible! Rather than deleting, it is suggested that you Reset these premium accounts and/or change the username/password and give it out to another student.</strong>"
		}
		Ext.MessageBox.confirm(title, msg, function(button) {
			if (button == 'yes') {
				Ext.MessageBox.show({
					title: text.PLEASE_WAIT,
					width: 300,
					wait:true,
					waitConfig: {interval:100},
					msg: text.DELETING_STUDENTS,
					progress: true,
					closable: false,
					icon: Ext.MessageBox.INFO

				});

				var that = this;
				setTimeout(function(){
					Ext.Ajax.request({
						url: url.DELETE_STUDENT_URL,
						success: function(response, options) {
							var res = Ext.decode(response.responseText);
							Ext.MessageBox.hide();
							if (res.success) {
								this.store.reload();
								tw.alert(text.STUDENT_DELETED, text.STUDENT_DELETED_TEXT);
							} else {
								tw.alert(text.THERE_WAS_AN_ERROR, res.message, 'danger');
							}
						},
						params: {
							users: Ext.encode(users)
						},
						failure: tw.ajaxError,
						scope: that
					});
				}, 1000);

			}
		}, this);
	},

	unlinkUser: function() {
		var selected = this.getSelectionModel().getSelections();
		if (selected.length == 0) {
			Ext.Msg.alert(text.UNLINK_STUDENTS, text.NO_STUDENT_SELECTED);
			return;
		}

		var title = text.UNLINK_STUDENTS;
		var msg = text.UNLINK_CONFIRM;
		var users = [];
		for(var index in selected) {
			users.push(selected[index].id);
		}
		Ext.MessageBox.confirm(title, msg, function(button) {
			if (button == 'yes') {
				Ext.MessageBox.show({
					title: text.PLEASE_WAIT,
					width: 300,
					wait:true,
					waitConfig: {interval:100},
					msg: text.UNLINKING_STUDENTS,
					progress: true,
					closable: false,
					icon: Ext.MessageBox.INFO

				});

				var that = this;
				setTimeout(function(){
					Ext.Ajax.request({
						url: url.UNLINK_STUDENT_URL,
						success: function(response, options) {
							var res = Ext.decode(response.responseText);
							Ext.MessageBox.hide();
							if (res.success) {
								this.store.reload();
								tw.alert(text.UNLINKING_SUCCESSFUL, text.UNLINKING_SUCCESSFUL_TEXT);
							} else {
								tw.alert(text.THERE_WAS_AN_ERROR, res.message, 'danger');
							}
						},
						params: {
							users: Ext.encode(users)
						},
						failure: tw.ajaxError,
						scope: that
					});
				}, 1000);
			}
		}, this);
	},

	resetUser: function() {
		var selected = this.getSelectionModel().getSelections();
		if (selected.length == 0) {
			Ext.Msg.alert(text.RESET_STUDENTS, text.NO_STUDENT_SELECTED);
			return;
		}

		var title = text.RESET_STUDENTS;
		var msg = text.RESET_WARNING_TEXT;
		var users = [];
		for(var index in selected) {
			users.push(selected[index].id);
		}
		Ext.MessageBox.confirm(title, msg, function(button) {
			if (button == 'yes') {
				Ext.MessageBox.show({
					title: text.PLEASE_WAIT,
					width: 300,
					wait:true,
					waitConfig: {interval:100},
					msg: text.RESETTING_STUDENTS,
					progress: true,
					closable: false,
					icon: Ext.MessageBox.INFO

				});

				var that = this;
				setTimeout(function(){
					Ext.Ajax.request({
						url: url.RESET_STUDENT_URL,
						success: function(response, options) {
							var res = Ext.decode(response.responseText);
							Ext.MessageBox.hide();
							if (res.success) {
								this.store.reload();
								tw.alert(text.RESET_SUCCESSFUL, text.RESET_SUCCESSFUL_TEXT)
							} else {
								tw.alert(text.THERE_WAS_AN_ERROR, res.message, 'danger');
							}
						},
						params: {
							users: Ext.encode(users)
						},
						failure: tw.ajaxError,
						scope: that
					});
				}, 1000);
			}
		}, this);
	},

	resetPassword: function() {
		var selected = this.getSelectionModel().getSelections();
		if (selected.length == 0) {
			Ext.Msg.alert(text.RESET_PASSWORD, text.NO_STUDENT_SELECTED);
			return;
		}

		var title = text.RESET_PASSWORD;
		var msg = text.NEW_PASSWORD;
		var users = [];
		for(var index in selected) {
			users.push(selected[index].id);
		}
		Ext.MessageBox.prompt(title, msg, function(button, newPass) {
			if (button == 'ok') {
				if (!newPass) {
					tw.alert(text.PASSWORD_RESET_FAILED, text.YOU_MUST_ENTER_A_PASSWORD, 'danger');
					return;
				}
				Ext.MessageBox.show({
					title: text.PLEASE_WAIT,
					width: 300,
					wait:true,
					waitConfig: {interval:100},
					msg: text.RESETTING_PASSWORDS,
					progress: true,
					closable: false,
					icon: Ext.MessageBox.INFO

				});

				var that = this;
				setTimeout(function(){
					Ext.Ajax.request({
						url: url.PASSWORD_STUDENT_URL,
						success: function(response, options) {
							var res = Ext.decode(response.responseText);
							Ext.MessageBox.hide();
							if (res.success) {
								this.store.reload();
								tw.alert(text.PASSWORD_RESET_SUCCESSFUL, text.PASSWORD_RESET_SUCCESSFUL_TEXT)
							} else {
								tw.alert(text.THERE_WAS_AN_ERROR, res.message, 'danger');
							}
						},
						params: {
							users: Ext.encode(users),
							password: newPass
						},
						failure: tw.ajaxError,
						scope: that
					});
				}, 1000);
			}
		}, this);
	},

	search: function() {
		this.reload();
	},

	click: function(grid, rowIndex, columnIndex, e) {
		var index = grid.getColumnModel().getDataIndex(columnIndex);
		if (!index) return;
		var id = this.getSelectionModel().getSelected().id;
		switch(index) {
			case "view":
				this.viewUser(id);
				break;
			case "edit":
				if (!id) return;
				this.editUser(id);
				break;
			default:
				if (e.type == "dblclick") {
					this.viewUser(id);
				}
				break;
		}
		e.stopEvent();
	}

});

tw.movewindow = Ext.extend(Ext.Window, {
	title: text.MOVE_STUDENTS_BETWEEN_CLASSES,
	width: 300,
	modal: true,
	border: false,
	opener: null,

	initComponent: function () {
		Ext.apply(this, {
			items: [
				{
					xtype: 'label',
					html: text.SELECT_A_CLASS,
					style: 'padding: 4px; font-size: 1.2em;'
				},
				{
					xtype: 'combo',
					transform: 'groups2',
					lazyRender: true,
					forceSelection: true,
					editable: false,
					triggerAction: 'all',
					width: 286,
					name: 'groupID',
					hiddenName: 'groupID'
				}
			],
			buttons: [
				{
					text: text.MOVE_STUDENT,
					handler: function() {
						var groupID = this.items.itemAt(1).getValue();
						this.hide();
						this.opener.moveUsersToGroup(groupID);
					},
					scope: this
				},{
					text: text.CANCEL,
					handler: function() {
						this.hide();
					},
					scope: this
				}
			]
		});

		tw.movewindow.superclass.initComponent.apply(this, arguments);
	}
});

var usersgrid;
Ext.onReady(function() {
	usersgrid = new tw.usersgrid({renderTo: 'grid', height: Ext.getDoc().getViewSize().height-165});

	$('#add-user-button').click(function(){
		location.href = url.ADD_STUDENT_URL;
		return false;
	})

	$('#add-existing-button').click(function(){
		if (!tw.linkWin) {
			tw.linkWin = new tw.UserLinkWindow();
		}
		tw.linkWin.show();
		return false;
	});

	$('#export-list-button').click(function(){
		location.href = url.EXPORT_STUDENT_URL.replace('_sid', Ext.getCmp('groupFilter').getValue());
	});

});
