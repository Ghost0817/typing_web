Ext.namespace("tw");

Ext.MessageBox.buttonText.yes = text.YES;
Ext.MessageBox.buttonText.no = text.NO;
Ext.MessageBox.buttonText.cancel = text.CANCEL;

/**
 * Groups Grid
 * @class tw.groupsgrid
 * @extends Ext.grid.GridPanel
 */
tw.groupsgrid = Ext.extend(Ext.grid.GridPanel, {
	 stateful: true
	,stateEvents: ['sortchange','columnmove','columnresize']
	,stateId: 'classes'
	,loadMask: true
	,border: true
	,style: 'margin: 10px 0px'
	,stripeRows: true
	,autoExpandColumn: 'name'
	,width: 'auto'
	,height: 620
	,frame: true
	// ,title: 'Classes'

	,initComponent: function () {
		this.record = Ext.data.Record.create([
			 'gaGroupID'
	 		,'name'
	 		,{name: 'users', type: 'int'}
	 	]);

		var reader = new Ext.data.JsonReader(
			{id:'gaGroupID'},
			this.record
		);
		var store = new Ext.data.Store({
			data: groupsData,
			reader: reader
		 });

		Ext.apply(this, {
			 store: store
			,view: new Ext.grid.GridView({
				 forceFit:true
				,emptyText: text.NO_RESULTS
			})
			,columns: [{
				 dataIndex:'name'
				,header:text.CLASS_NAME
				,sortable: true
				,renderer: Ext.util.Format.htmlEncode
			},{
				 dataIndex:'users'
				,header:text.STUDENT_CLASS
				,sortable: true
				,renderer: function(value, meta, record) {
					return value+" "+text.STUDENTS;
				}
			},{
	        	 dataIndex: 'view'
	        	,menuDisabled: true
	        	,header: ''
	        	,align: 'center'
	        	,width: 30
	        	,fixed: true
	        	,renderer: function() {
	        		return '<div class="grid-icon-column-full fa fa-search fa-lg" title="'+text.VIEW_CLASS+'"></div>';
	        	}
			},{
	        	 dataIndex: 'edit'
	        	,menuDisabled: true
	        	,header: ''
	        	,align: 'center'
	        	,width: 30
	        	,fixed: true
	        	,renderer: function(value, meta, record) {
	        		if (record.id > 0)
	        			return '<div class="grid-icon-column-full fa fa-pencil fa-lg" title="'+text.EDIT_CLASS+'"></div>';
	        	}
			},{
	        	 dataIndex: 'delete'
	        	,menuDisabled: true
	        	,header: ''
	        	,align: 'center'
	        	,width: 30
	        	,fixed: true
	        	,renderer: function(value, meta, record) {
	        		if (record.id > 0)
	        			return '<div class="grid-icon-column-full fa fa-times fa-lg" title="'+text.DELETE_THIS_CLASS+'"></div>';
	        	}
			}]
			,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
		});

		tw.groupsgrid.superclass.initComponent.apply(this, arguments);
    this.on('celldblclick', this.click, this);
    this.on('cellclick', this.click, this);
	},

	viewGroup: function(id) {
		location.href = url.CLASS_URL.replace('_cid', id);
	},

	editGroup: function(gid) {
		if (gid === 'ungrouped') {
			return;
		}
		var win = new tw.groupswin({
			gid: gid,
			title: text.EDIT_CLASS_NAME,
			grid: this,
			values: this.getSelectionModel().getSelected().data
		});
		win.show();
	},

	deleteGroup: function(gid) {
		var group = this.getSelectionModel().getSelected().data.name;
		if (gid === 'ungrouped') {
			return;
		}
		Ext.MessageBox.confirm(text.DELETE_CLASS+": "+group+"?", text.DELETE_SMG, function(button) {
			if (button == 'yes') {
				Ext.MessageBox.show({
					title: text.PLEASE_WAIT,
					width: 300,
					wait:true,
					waitConfig: {interval:100},
					msg: text.DELETING_CLASS,
					progress: true,
					closable: false,
					icon: Ext.MessageBox.INFO

				});

				var that = this;
				setTimeout(function(){
					Ext.Ajax.request({
						url: url.CLASS_DELETE_URL.replace( '_cid', gid),
						success: function(response, options) {
							var res = Ext.decode(response.responseText);
							if (res.success) {
								location.reload();
							} else {
								Ext.MessageBox.hide();
								Ext.Msg.alert("There was an error", res.message);
							}
						},
						failure: tw.ajaxError,
						scope: that
					});
				}, 1000);
			}
		}, this);
	},

	newGroup: function() {
		var win = new tw.groupswin({
			title: text.NEW_CLASS,
			grid: this
		});
		win.show();
	},

	click: function(grid, rowIndex, columnIndex, e) {
		var index = grid.getColumnModel().getDataIndex(columnIndex);
		if (!index) return;
		var id = this.getSelectionModel().getSelected().id;
		switch(index) {
			case "view":
				this.viewGroup(id);
				break;
			case "edit":
				if (!id) return;
				this.editGroup(id);
				break;
			case "delete":
				if (!id) return;
				this.deleteGroup(id);
				break;
			default:
				if (e.type == "dblclick") {
					this.viewGroup(id);
				}
				break;
		}
		e.stopEvent();
	},

	contextMenu: function(grid, rowIndex, e) {
		this.getSelectionModel().selectRow(rowIndex);
	    if (!this.menu) {
	        this.menu = new Ext.menu.Menu({
			     items: [{
			     	 iconCls: 'icon-magnifier'
					,text: 'Manage Class\'s Students'
					,handler: function() {
						this.viewGroup(this.getSelectionModel().getSelected().id);
					}
					,scope: this
			     },{
					 iconCls: 'icon-pencil'
					,text: text.EDIT_CLASS
					,handler: function() {
						this.editGroup(this.getSelectionModel().getSelected().id);
					}
					,scope: this
				},{
					 iconCls: 'icon-delete'
					,text: text.DELETE_CLASS
					,handler: function() {
						this.deleteGroup(this.getSelectionModel().getSelected().id);
					}
					,scope: this
				}]
	        });
		}
		e.stopEvent();
	    this.menu.showAt(e.getXY());
	}
});

/**
 * Groups Form
 * @class tw.groupsform
 * @extends Ext.FormPanel
 */
tw.groupsform = Ext.extend(Ext.FormPanel, {
	border: true,
	frame: false,
	bodyStyle:'padding: 2px',
	id: 'groupsform',

	initComponent: function() {

		var items = [{
             xtype:'textfield'
            ,anchor:'94%'
            ,fieldLabel: text.CLASS_NAME
            ,name: 'name'
            ,id: 'name'
            ,allowBlank: false
            ,msgTarget: 'side'
            ,value: this.values.name
            ,listeners: {
            	specialkey: function (field, event) {
					if (event.getKey() == event.ENTER) {
						this.ownerCt.ownerCt.save();
					}
				}
            }
        }];

	 	Ext.apply(this, {
	 		 labelWidth: 120
			,labelAlign: 'right'
			,width: 400
            ,items: items
	 	});

	 	tw.groupsform.superclass.initComponent.apply(this, arguments);
	 	setTimeout(function(){Ext.getCmp('name').focus(false, 200);}, 0);
	 }

	 ,validateForm: function() {
	 	var errors = false;
	 	if (!this.getForm().isValid()) {
	 		errors = true;
	 	}

	 	if (errors) {
	 		Ext.Msg.alert('Validation Error', 'Please correct invalid fields and values.');
	 	} else {
	 		return true;
	 	}
	 }
});

tw.groupswin = Ext.extend(Ext.Window, {
	width: 410,
	height: 'auto',
	modal: true,
	values: {},
	gid: 0,

	initComponent: function() {
		this.form = new tw.groupsform({values: this.values});

	 	Ext.apply(this, {
	 		 items: [this.form],
	 		 buttons: [
	 		 	{
	 		 		text: text.SAVE,
	 		 		id: 'saveButton',
	 		 		handler: function() {
	 		 			this.save();
	 		 		},
	 		 		scope: this
	 		 	},
	 		 	{
	 		 		text: text.CANCEL,
	 		 		handler: function() {
	 		 			this.close();
	 		 		},
	 		 		scope: this
	 		 	}]
	 	});
	 	tw.groupswin.superclass.initComponent.apply(this, arguments);
	},

	save: function() {
		if (!this.form.validateForm()) {
			return;
		}
		var data = this.form.getForm().getValues();
		if (this.form.usersGrid) {
			var users = this.form.usersGrid.getSelectionModel().getSelections();
			var gaUserIDs = [];
			for(var i=0; i<users.length; i++) {
				gaUserIDs.push(users[i].id);
			}
		}
		data.gaUserIDs = Ext.encode(gaUserIDs);
		Ext.MessageBox.show({
			title: text.PLEASE_WAIT,
			width: 300,
			wait:true,
			waitConfig: {interval:100},
			msg: text.SAVING,
			progress: true,
			closable: false,
			icon: Ext.MessageBox.INFO

		});

		var that = this;
		setTimeout(function(){
			Ext.Ajax.request({
				url: url.CLASS_SAVE_URL.replace('_cid', that.gid),
				success: function(response, options) {
					var res = Ext.decode(response.responseText);
					if (res.success) {
						location.reload();
					} else {
						this.close();
						Ext.MessageBox.hide();
						Ext.Msg.alert("There was an error", res.message);
					}
				},
				failure: tw.ajaxError,
				params: data,
				scope: that
			});
		}, 1000);
	}
});


Ext.onReady(function() {
	var groupsgrid = new tw.groupsgrid({renderTo: 'grid', height: Ext.getDoc().getViewSize().height-235}), newClass, editedClass;

	$('#add-class-button').click(function(){
		groupsgrid.newGroup();
		return false;
	});
});
