Ext.namespace("tw");

tw.reports = Ext.extend(Ext.form.FormPanel, {
	border: false,
	frame: true,
	title: 'Simple Form',
	style: 'margin: 10px 0px',
	buttonAlign: 'left',
	labelWidth: 80,
	autoWidth: true,
	collapsible: false,
	defaults: {
		msgTarget: 'side'
	},

	initComponent: function() {
	 	Ext.apply(this, {
	 		items: [{
		 			layout: 'column',
		 			defaults: {
		 				style: 'padding: 4px'
		 			},
		 			items: [{
			 			html: text.SELECT_DATE_RANGE,
			 			columnWidth: .22
			 		},{
			 			html: text.SELECT_REPORT,
			 			columnWidth: .22
			 		},{
			 			html: text.SELECT_LANG,
			 			columnWidth: .22
			 		},{
			 			html: text.SELECT_CLASS,
			 			columnWidth: .21
			 		}]
	 		},{
	 			layout: 'column',
	 			defaults: {
	 				style: 'padding: 4px'
	 			},
	 			items: [{columnWidth: .22, items:[{
				 			xtype: 'combo',
				 			transform: 'dateRanges',
				 			name: 'dateRange',
				 			hiddenName: 'dateRange',
				 			id: 'dateRangeSelect',
				 			triggerAction: 'all',
				 			forceSelection: true,
							editable: false,
				 			lazyRender: true,
				 			width: 190,
				 			listeners: {
				 				select: {
				 					fn: function(combo, record) {
				 						if (record.id == "custom") {
				 							Ext.fly('range-panel').removeClass('x-hidden');
				 							Ext.fly('range-panel').slideIn('t', {
												remove: false,
											    useDisplay: true,
											    duration: .5
				 							});
				 						} else {
				 							Ext.fly('range-panel').slideOut('t', {
				 								remove: false,
											    useDisplay: true,
											    duration: .5
				 							});
				 						}
				 					},
				 					scope: this
				 				}
				 			}
				 		}]},{columnWidth: .22, items:[{
				 			xtype: 'combo',
				 			transform: 'reports',
				 			name: 'report',
				 			hiddenName: 'report',
				 			triggerAction: 'all',
				 			forceSelection: true,
							editable: false,
				 			lazyRender: true,
				 			width: 190,
				 			listeners: {
				 				select: {
				 					fn: function(combo, record) {
				 						if (record.id == "scoreboard") {
				 							Ext.fly('range-panel').addClass('x-hidden');
				 							Ext.getCmp('dateRangeSelect').hide();
				 						} else {
				 							Ext.fly('range-panel').removeClass('x-hidden');
				 							Ext.getCmp('dateRangeSelect').show();
				 						}
				 					},
				 					scope: this
				 				}
				 			}
				 		}]},{columnWidth: .22, items:[{
				 			xtype: 'combo',
				 			transform: 'language',
				 			name: 'lang',
				 			hiddenName: 'lang',
				 			triggerAction: 'all',
				 			forceSelection: true,
							editable: false,
				 			lazyRender: true,
				 			width: 190
				 		}]},{columnWidth: .22, items:[{
				 			xtype: 'combo',
				 			transform: 'groups',
				 			name: 'gaGroupID',
				 			hiddenName: 'gaGroupID',
				 			triggerAction: 'all',
				 			forceSelection: true,
							editable: false,
				 			lazyRender: true,
				 			width: 190
				 		}]},{items:[{
				 			items:[{
				 				xtype: 'button',
					 			text: '<i class="fa fa-calculator fa-lg" aria-hidden="true"></i> '+ text.RUN_REPORT,
					 			iconCls: 'icon-table',
					 			handler: this.runReport,
					 			scope: this
				 			}]
			 			}]},{items:[{
				 			items:[new Ext.Toolbar.Button({
					 			text: '<i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i> '+ text.EXPORT,
					 			iconCls: 'icon-disk',
					 			handler: function() { this.exportReport('csv'); },
					 			scope: this

				 			})]
			 			}]},{items:[{
				 			items:[{
				 				xtype: 'button',
					 			text: '<i class="fa fa-print fa-lg" aria-hidden="true"></i> '+ text.PRINT,
					 			iconCls: 'icon-printer',
					 			handler: function(){this.exportReport('html')},
					 			scope: this
				 			}]
			 			}]}
	 				]
	 		},{
	 			id: 'range-panel',
	 			autoShow: true,
	 			items: {
	 				layout: 'column',
	 				items: [{
	 					xtype: 'datefield',
	 					id: 'startDate',
	 					format: 'Y-m-d',
	 					allowBlank: false,
	 					minValue: new Date( (new Date()).getTime() - (40 * 24 * 60 * 60 * 1000) ),
	 					maxValue: new Date()
	 				},{html: ' to ', bodyStyle: 'font-size: 1.4em; text-align: center', width: 20},{
	 					xtype: 'datefield',
	 					id: 'endDate',
	 					format: 'Y-m-d',
	 					allowBlank: false,
	 					minValue: new Date( (new Date()).getTime() - (40 * 24 * 60 * 60 * 1000) ),
	 					maxValue: new Date()
	 				}]
	 			}
	 		}]
	 	});
	 	tw.reports.superclass.initComponent.apply(this, arguments);
	},

	runReport: function() {
		var params = this.form.getValues();
		params.date = new Date().format('Y-m-d');

		if (params.report != 'scoreboard' && params.dateRange === 'custom' && !this.getForm().isValid()) {
			tw.alert('Invalid Date Range', 'Please select a date range from the dropdown, or enter a valid custom start and end date.', 'danger');
			return;
		}

		Ext.MessageBox.show({
			title: text.PLEASE_WAIT,
			width: 300,
			wait:true,
			waitConfig: {interval:100},
			msg: text.GENERATING_REPORT,
			progress: true,
			closable: false,
			icon: Ext.MessageBox.INFO

		});
		Ext.Ajax.request({
			url: url.REPORT_URL,
			success: function(response, options) {
				tw.clearAlerts();
				var res = Ext.decode(response.responseText);
				Ext.MessageBox.hide();
				if (!res.success) {
					tw.alert(text.THERE_WAS_AN_ERROR, res.message, 'danger');
					return;
				}

				var grid = Ext.getCmp('reportsgrid');
				grid.getEl().removeClass('x-hidden');
				grid.newReport(res.data);
				if (res.data.limited) {
					tw.alert(text.TOO_MANY_RESULTS, text.TOO_MANY_RESULTS_TEXT.replace('%1', res.data.limited).replace('%2', res.data.limited), 'danger');
				}
				grid.setTitle(res.title);
			},
			failure: tw.ajaxError,
			params: params,
			scope: this
		});
	},

	exportReport: function(format) {
		var data = this.getForm().getValues();
		location.href = url.REPORT_EXPORT_URL.replace('_date',new Date().format('Y-m-d')).replace('_dateRange',data.dateRange).replace('_endDate',data.endDate).replace('_startDate',data.startDate).replace('_GroupID',data.gaGroupID).replace('_lang',data.lang).replace('_report',data.report);
	}
});

tw.reportgrid = Ext.extend(Ext.grid.GridPanel, {
	border: true,
	style: 'margin: 4px 0px',
	stripeRows: true,
	autoExpandColumn: 'name',
	width: 978,
	height: 620,
	frame: true,
	title: text.REPORT_DATA,
	id: 'reportsgrid',
	data: [],

	initComponent: function () {
	 	var reader = new Ext.data.JsonReader(
			{id:'id'},
			Ext.data.Record.create(['id'])
		);

		Ext.apply(this, {
			store: new Ext.data.GroupingStore({
				data: [],
				reader: reader
			}),
			columns: [{dataIndex: 'id', header: 'id'}],
			view: new Ext.grid.GroupingView({
				forceFit:true,
				emptyText: text.NO_RESULTS,
				groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? text.ROWS : text.ROW]})',
				hideGroupedColumn: true
			}),
			selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
		});

		tw.reportgrid.superclass.initComponent.apply(this, arguments);
	},

	newReport: function(data) {
		this.reconfigure(this.getStoreObj(data), new Ext.grid.ColumnModel(this.getColumnsObj(data.columns)));
	},

	getStoreObj: function(data) {
		var record = Ext.data.Record.create(data.fields);

	 	var reader = new Ext.data.JsonReader(
			{id:'id'},
			record
		);

		var store = new Ext.data.GroupingStore({
			data: data.data,
			reader: reader,
			sortInfo:{field: 'lastname', direction: "ASC"}
		});

		return store;
	},

	getColumnsObj: function(data) {
		var columns = [];
		var col;
		for(var i=0; i<data.length; i++) {
			col = data[i];
			if (col.speed) {
				col.renderer = function(value) {
					return value+' '+speedType;
				}
			}
			if (col.acc) {
				col.renderer = function(value) {
					return value+'%';
				}
			}
			if (col.time) {
				col.renderer = function(value) {
					return Math.ceil(value/60) + ' minute'+((Math.ceil(value/60) != 1)?'s':'');
				}
			}
			columns[i] = col;
		}

		return columns;
	}
});

Ext.onReady(function(){
	new tw.reports({renderTo: 'reports-container'});
	new tw.reportgrid({renderTo: 'reports-grid', cls: 'x-hidden'});
});
