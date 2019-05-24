Ext.namespace("tw");

/**
 * Lessons Grid
 * @class tw.lessonsgrid
 * @extends Ext.grid.GridPanel
 */
tw.lessonsgrid = Ext.extend(Ext.grid.GridPanel, {
	 loadMask: true
	,border: true
	,style: 'margin: 10px 0px'
	,stripeRows: true
	,autoExpandColumn: 'name'
	,width: 'auto'
	,height: 'auto'
	,autoHeight: true
	,frame: true
	,enableHdMenu: false

	,initComponent: function () {
		this.record = Ext.data.Record.create([
			 {name: 'lessonID', type: 'int'}
			,{name: 'gaUserID', type: 'int'}
	 		,'course'
	 		,'name'
	 		,'originalName'
	 		,{name: 'active', type: 'boolean'}
	 		,{name: 'custom', type: 'boolean'}
	 		,{name: 'modified', type: 'boolean'}
	 		,{name: 'displayOrder', type: 'int'}
	 	]);

		var reader = new Ext.data.JsonReader(
			{id:'lessonID'},
			this.record
		);
		var store = new Ext.data.GroupingStore({
			data: lessonsData,
			reader: reader,
			sortInfo:{field: 'displayOrder', direction: "ASC"},
			groupField: 'course',
			enableGroupingMenu: false
		 });

		Ext.apply(this, {
			 store: store
			,view: new Ext.grid.GroupingView({
				 forceFit:true
				,emptyText: text.NO_RESULTS
				,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Lessons" : "Lesson"]})'
				,hideGroupedColumn: true
				,scrollOffset: 0
			})
			,columns: [{
				 dataIndex:'course'
				,header:text.COURSE
				,sortable: false
				,renderer: function(value, record) {
					if (value)
						return value.substr(2).ucWords();
//					else
//						console.dir(record.data);
				}
			},{
				 dataIndex:'name'
				,header:text.LESSON_NAME
				,sortable: false
				,renderer: function(value, meta, record) {
					return value;
				}
			},{
	        	 dataIndex: 'view'
	        	,menuDisabled: true
	        	,header: ''
	        	,align: 'center'
	        	,width: 30
	        	,fixed: true
	        	,renderer: function() {
	        		return '<div class="grid-icon-column-full fa fa-search fa-lg" title="'+text.VIEW_LESSON+'"></div>';
	        	}
			}]
			,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
		});

		tw.lessonsgrid.superclass.initComponent.apply(this, arguments);

	    this.on('celldblclick', this.click, this);
	    this.on('cellclick', this.click, this);
	},

	viewLesson: function(id) {
		var win = new tw.lessonswin({
			modal: true,
			autoLoad: url.LESSON_VIEW_URL.replace('_id',id).replace('_languageID',languageID),
			title: this.getSelectionModel().getSelected().data.name
		});
		win.show();
	},

	click: function(grid, rowIndex, columnIndex, e) {
		var index = grid.getColumnModel().getDataIndex(columnIndex);
		var id = this.getSelectionModel().getSelected().id;
		switch(index) {
			case "view":
				this.viewLesson(id);
				break;
			default:
				if (e.type == "dblclick") {
					this.viewLesson(id);
				}
				break;
		}
		e.stopEvent();
	}

});



tw.lessonswin = Ext.extend(Ext.Window, {
	width: 800,
	height: 500,
	minHeight: 500,
	autoHeight: false,
	autoScroll: true,

	initComponent: function() {

		Ext.apply(this, {
			buttons: [{
		 		text: 'Close',
		 		handler: function() {
		 			this.close();
		 		},
		 		scope: this
		 	}]
		});

		tw.lessonswin.superclass.initComponent.apply(this, arguments);
	}
});




Ext.onReady(function() {
	var lessonsgrid = new tw.lessonsgrid({renderTo: 'grid', height: Ext.getDoc().getViewSize().height-165});
});
