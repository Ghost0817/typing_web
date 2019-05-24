Ext.namespace("tw");

Ext.MessageBox.buttonText.yes = text.YES;
Ext.MessageBox.buttonText.no = text.NO;
Ext.MessageBox.buttonText.cancel = text.CANCEL;

tw.studentinfo = Ext.extend(Ext.form.FormPanel, {
    border: false,
    frame: true,
    style: 'margin: 10px 0px',
    buttonAlign: 'left',
    labelWidth: 80,
    autoWidth: true,
    collapsible: false,
    defaults: {
        msgTarget: 'side'
    },
    initComponent: function() {

        var store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: '/teacher/users/load',  //url to data object (server side script).  Defined in global scope by php
                method: 'POST',
                waitTitle: text.PLEASE_WAIT,
                waitMsg: text.LOADING_STUDENT,
                params: {
                    userID: value.USERID
                },
                scope: this
            }),
        });

        console.log(store);

        Ext.apply(this, {
            title: text.STUDENTINFORMATION,
            items: [{
                layout: 'column',
                defaults: {
                    style: 'padding: 4px'
                },
                items: [{
                    html: text.USERNAME,
                    columnWidth: .4
                },{
                    items: [{
                        html: value.USERNAME,
                        style:{float:'left'}
                    },{
                        html: ' [',
                        style:{float:'left'}
                    },{
                        itemId: 'editLink',
                        xtype: 'box',
                        autoEl: {
                            tag: 'a',
                            href: '#',
                            html: text.EDITSTUDENT
                        },
                        listeners: {
                            scope: this,
                            render: function(c){
                                c.getEl().on('click', this.editUser, this, {stopEvent: true});
                            }
                        },
                        style:{float:'left'}
                    },{
                        html: ']',
                        style:{float:'left'}
                    }],
                    columnWidth: .6
                },{
                    html: text.NAME,
                    columnWidth: .4
                },{
                    html: value.NAME,
                    columnWidth: .6
                },{
                    html: text.CLASS,
                    columnWidth: .4
                },{
                    html: '<a href="' + url.CLASS_URL + '">'+ value.CLASS+ '</a>',
                    columnWidth: .6
                },{
                    html: text.EMAIL,
                    columnWidth: .4
                },{
                    html: value.EMAIL,
                    columnWidth: .6
                }]
            }]
        })

        this.editwin = new tw.UserEditWindow( { opener: this } );

        tw.studentinfo.superclass.initComponent.apply(this, arguments);
    },


    reload: function() {
        this.store.reload({
            params: {
                gaGroupID: value.USERID
            }
        });
    },

    editUser: function() {
        this.editwin.show({userID: value.USERID});
    },
});

tw.accountdetails = Ext.extend(Ext.form.FormPanel, {
    border: false,
    frame: true,
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
            title: text.ACCOUNTDETAILS,
            items: [{
                layout: 'column',
                defaults: {
                    style: 'padding: 4px'
                },
                items: [{
                    html: text.LASTLOGIN,
                    columnWidth: .4
                },{
                    html: value.LASTLOGIN,
                    columnWidth: .6
                },{
                    html: text.ACOOUNTTYPE,
                    columnWidth: .4
                },{
                    html: 'Standard [<a href="' + url.UPDATE_URL + '">Upgrade Premium</a>]',
                    columnWidth: .6
                },{
                    html: text.SPEEDCALCULATION,
                    columnWidth: .4
                },{
                    html: value.SPEEDCALCULATION,
                    columnWidth: .6
                },{
                    html: text.SENTENCESPACES,
                    columnWidth: .4
                },{
                    html: value.SENTENCESPACES,
                    columnWidth: .6
                }]
            }]
        })
        tw.accountdetails.superclass.initComponent.apply(this, arguments);
    },

});

tw.lessonprogress = Ext.extend(Ext.grid.GridPanel, {
    loadMask: true,
    border: true,
    style: 'margin: 10px 0px',
    stripeRows: true,
    width: 'auto',
    height: 'auto',
    autoHeight: true,
    minHeight: 800,
    frame: true,
    pageSize: 30,
    title: text.LESSONPROGRESS,
    collapsible: true,
    initComponent: function() {
        this.record = Ext.data.Record.create([
            {name: 'lessonID', type: 'int'},
            {name: 'gaUserID', type: 'int'},
            'course',
            'name',
            'originalName',
            {name: 'active', type: 'boolean'},
            {name: 'custom', type: 'boolean'},
            {name: 'modified', type: 'boolean'},
            {name: 'displayOrder', type: 'int'},
            'progress',
            'spenttime',
            'grossSpeed',
            'accuracy',
            'netSpeed',
            {name: 'lastTyped', type: 'date', dateFormat: "Y-m-d H:i:s"}
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
                ,emptyText: ''
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
                ,header:text.LESSON
                ,sortable: false
                ,renderer: function(value, meta, record) {
                    return value;
                }
            },{
                dataIndex:'progress',
                header:text.PROGRESS,
                xtype: 'progress',
                progressText: '{0} %',
                width: 90,
                fixed: true,
                sortable: false,
                renderer: function(value, meta, record) {
                    var mytest = value||'&#160;';
                    return '<div class="x-progress-wrap">'+
                        '<div class="x-progress-inner">'+
                        '<div class="x-progress-bar" style="width: ' + mytest + '">'+
                        '<div class="x-progress-text">'+
                        '<div>' + mytest + '</div>'+
                        '</div>'+
                        '</div>'+
                        '<div class="x-progress-text x-progress-text-back">'+
                        '<div>' + mytest + '</div>'+
                        '</div>'+
                        '</div>'+
                        '</div>';
                }
            },{
                dataIndex:'spenttime',
                header:text.SPENTTIME,
                width: 120,
                fixed: true,
                sortable: false
            },{
                dataIndex:'grossSpeed',
                header:text.CROSSSPEED,
                width: 80,
                fixed: true,
                sortable: false
            },{
                dataIndex:'accuracy',
                header:text.ACCURACY,
                width: 80,
                fixed: true,
                sortable: false
            },{
                dataIndex:'netSpeed',
                header:text.NETSPEED,
                width: 80,
                fixed: true,
                sortable: false
            }, {
                dataIndex: 'lastTyped',
                header: text.LASTTYPED,
                width: 100,
                fixed: true,
                sortable: true,
                renderer: function (value) {
                    if (value.format("Y") < 1970) return text.NEVER;
                    return value.format((languageID > 1) ? 'Y-m-d' : 'M d, Y');
                }
            }]
            ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
            tbar: [{
                text: '<i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i> '+text.PROBLEMKEYSGRAPH,
                iconCls: 'icon-cross',
                handler: this.deleteUser,
                scope: this,
            },
                {
                    text: '<i class="fa fa-save fa-lg" aria-hidden="true"></i> '+text.EXPORT,
                    iconCls: 'icon-cross',
                    handler: this.deleteUser,
                    scope: this,
                },'->',
                {
                    text: '<i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i> '+text.VIEWSUMMARYGRAPH,
                    iconCls: 'icon-cross',
                    handler: this.deleteUser,
                    scope: this,
                },
                {
                    text: '<i class="fa fa-save fa-lg" aria-hidden="true"></i> '+text.EXPORT,
                    iconCls: 'icon-cross',
                    handler: this.deleteUser,
                    scope: this,
                }]
        });

        tw.lessonprogress.superclass.initComponent.apply(this, arguments);
    },

});

tw.testprogress = Ext.extend(Ext.grid.GridPanel, {
    loadMask: true,
    border: true,
    style: 'margin: 10px 0px',
    stripeRows: true,
    width: 'auto',
    height: 'auto',
    autoHeight: true,
    minHeight: 800,
    frame: true,
    pageSize: 30,
    title: text.TYPINGTESTPROGRESS,
    collapsible: true,
    initComponent: function() {
        this.record = Ext.data.Record.create([
            'id',
            {name: 'testTime', type: 'date', dateFormat: "Y-m-d H:i:s"},
            'grossSpeed',
            'accuracy',
            'netSpeed',
            'spentTime',
            'improve',
            'overallimprove',
        ]);

        var reader = new Ext.data.JsonReader(
            {id:'id'},
            this.record
        );

        var store = new Ext.data.Store({
            data: TestsData,
            reader: reader
        });

        Ext.apply(this, {
            store: store,
            view: new Ext.grid.GridView({
                forceFit:true,
                emptyText: '',
                scrollOffset: 0
            }),
            columns: [{
                dataIndex:'testTime',
                header:text.TESTTIME,
                sortable: false,
                renderer: function (value) {
                    if (value.format("Y") < 1970) return text.NEVER;
                    return value.format('d/m/Y H:i:s');
                }
            },{
                dataIndex:'grossSpeed',
                header:text.CROSSSPEED,
                sortable: false
            },{
                dataIndex:'accuracy',
                header:text.ACCURACY,
                sortable: false
            },{
                dataIndex:'netSpeed',
                header:text.NETSPEED,
                sortable: false
            },{
                dataIndex:'spentTime',
                header:text.SPENTTIME,
                sortable: false
            },{
                dataIndex:'improve',
                header:text.IMPROVEMENT,
                sortable: false
            },{
                dataIndex:'overallimprove',
                header:text.OVERALLIMPROVEMENT,
                sortable: false
            }],
            tbar: ['->',
                {
                    text: '<i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i> '+text.VIEWGRAPH,
                    iconCls: 'icon-cross',
                    handler: this.deleteUser,
                    scope: this,
                },
                {
                    text: '<i class="fa fa-save fa-lg" aria-hidden="true"></i> '+text.EXPORT,
                    iconCls: 'icon-cross',
                    handler: this.deleteUser,
                    scope: this,
                }]
        })
        tw.testprogress.superclass.initComponent.apply(this, arguments);
    },

});

tw.studentactivity = Ext.extend(Ext.grid.GridPanel, {
    border: false,
    frame: true,
    style: 'margin: 10px 0px',
    buttonAlign: 'left',
    labelWidth: 80,
    autoWidth: true,
    collapsible: true,
    title: text.RECENTSTUDENTACTIVITY,
    initComponent: function() {
        this.record = Ext.data.Record.create([
            {name: 'lessonID', type: 'int'},
            {name: 'gaUserID', type: 'int'},
            'course',
            'name',
            'originalName',
            {name: 'active', type: 'boolean'},
            {name: 'custom', type: 'boolean'},
            {name: 'modified', type: 'boolean'},
            {name: 'displayOrder', type: 'int'},
            'progress',
            'spenttime',
            'grossSpeed',
            'accuracy',
            'netSpeed',
            {name: 'lastTyped', type: 'date', dateFormat: "Y-m-d H:i:s"}
        ]);
        Ext.apply(this, {
            store: store,
            view: new Ext.grid.GridView({
                forceFit:true,
                emptyText: '',
                scrollOffset: 0
            }),
            tbar: ['->',
                {
                    text: '<i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i> '+text.VIEWGRAPH,
                    iconCls: 'icon-cross',
                    handler: this.deleteUser,
                    scope: this,
                },
                {
                    text: '<i class="fa fa-save fa-lg" aria-hidden="true"></i> '+text.EXPORT,
                    iconCls: 'icon-cross',
                    handler: this.deleteUser,
                    scope: this,
                }]
        })
        tw.studentactivity.superclass.initComponent.apply(this, arguments);
    },

});

Ext.onReady(function(){
    new tw.studentinfo({renderTo: 'student-info'});
    new tw.accountdetails({renderTo: 'account-details'});

    new tw.lessonprogress({renderTo: 'lesson-progress'});
    new tw.testprogress({renderTo: 'typing-test-progress'});
    //new tw.studentactivity({renderTo: 'recent-student-act'});
});