Ext.namespace("tw");

tw.statistics = function() {};
tw.statistics.prototype = {

	openGraph: function(e) {
		lastActivity = new Date();

		var lessonID;
		var name;

		if (Ext.fly(e.target).hasClass("clr_status")) {
			return;
		}

		var target = e.getTarget('.graph-stat', null, true);
		var id = target.id;
		var lang = "";
		if(id.startsWith("english")){
			id = target.id.substr(7);
			lang = target.id.substr(0, 7);
		}
		if(id.startsWith("mongolian")){
			id = target.id.substr(9);
			lang = target.id.substr(0, 9);
		}

		if (target.hasClass('certification')) {
			lessonID = id;
			id = 'CERTIFICATION';
			name = e.getTarget('.graph-stat', null, true).child('.lesson-name').dom.innerHTML;
		}
		if (id == "CERTIFICATE") {
			if (showCertificate) {
				location.href = '/student/statistics/certificate/';
				return;
			} else {
				this.alert(text.CERTIFICATE, text.NOT_ENOUGH_TESTS);
				return;
			}
		}

		if (id*1 == id) {
			lessonID = id;
			id = 'DETAILED';
		}

		var graphTemplate = Ext.Template.from('graph-template');
		if (!name) {
			name = text[id];
			if (!name) {
				name = e.getTarget('.graph-stat', null, true).dom.title;
			}
		}

		if (!lessonID) {
			lessonID = '';
		}

		var values = {
			id: id,
			lid: lessonID,
			lang: lang,
			'graph': name
		};
		Shadowbox.open({
	        player: 'html',
	        content: graphTemplate.apply(values),
	        width: 740,
	        height: 380,
	        animateFade: false
    });
    var tryCount = 0;
    var showGraph = new Ext.util.DelayedTask(function(id) {
    	var ele = Ext.fly('graph-'+id);
    	if (!ele && tryCount < 5) {
    		tryCount++;
    		showGraph.delay(100);
    		return;
    	}
			var chartType = this.getChartType(id);
    	// params are (swf, domid, width, height, debug, regWithJS)
      FusionCharts.ready(function () {
          var revenueChart = new FusionCharts({
              type: chartType,
							loadMessage: text.LOAD_MESSAGE,
              renderAt: 'graph-'+id,
              width: '695',
              height: '285',
              dataFormat: 'jsonurl',
              dataSource: url.TUTOR_STATISTICS_GRAPH.replace('_lang_id', lang).replace('_type_id', id)
          });

					revenueChart.configure({
			        'ChartNoDataText': text.GRAPH_NO_DATA,
			        "LoadDataErrorText": text.GRAPH_NO_DATA_ERROR
			    });

					revenueChart.render();
      });
		//var chart = new FusionCharts("/tutor/flash/"+this.getChartType(id)+".swf?ChartNoDataText="+text.GRAPH_NO_DATA, "graph-flash", "695", "285", "0", "1");
		//chart.setDataURL("/tutor/statistics/graph/id/"+id+"/lid/"+lessonID);
		//chart.render('graph-'+id);

    }, this, [id]);
    showGraph.delay(100);
	},

	getChartType: function(id) {
		switch(id) {
			case "SUMMARY":
				return "StackedColumn3DLineDY";
			case "PROBLEM_KEYS":
				return "Column3D";
			case "TYPING_TEST":
			case "CERTIFICATION":
				return "StackedColumn3DLineDY";
			case "DETAILED":
				return "StackedColumn3DLineDY";
			case "BY_COUNTRY":
			case "BY_AGE":
			case "BY_GENDER":
				return "Column3D";
			default:
				return "Column3D";
		}
	},

	showContent: function(e) {
		lastActivity = new Date();

		var target = e.getTarget();
		var id = target.id.split("-")[1];
		var content = Ext.get('content-'+id);
		if (content.getStyle("display") == "none") {
			content.slideIn('t', {
			    easing: 'easeOut',
			    duration: 0.5,
			    remove: false,
			    useDisplay: true
			});
			target = Ext.fly(target);
			if (target) {
				if (target.hasClass("stats-header")) {
					target.addClass("h-arrow-open");
				} else {
					target.addClass("arrow-open");
				}
			}
		} else {
			content.slideOut('t', {
			    easing: 'easeOut',
			    duration: 0.5,
			    remove: false,
			    useDisplay: true
			});
			target = Ext.fly(target);
			if (target) {
				if (target.hasClass("stats-header")) {
					target.removeClass("h-arrow-open");
				} else {
					target.removeClass("arrow-open");
				}
			}
		}
	},

	clearStats: function(e) {
		lastActivity = new Date();

		var target = e.getTarget();
		var id = target.id.split("-")[1];
		var lang = target.id.split("-")[0];

		var fn = function(button){
			if (button == "yes") {
				Ext.MessageBox.show({
					title: text.PLEASE_WAIT,
					width: 300,
					wait:true,
					waitConfig: {interval:100},
					msg: text.CLEARING_STATISTICS,
					progress: true,
					closable: false,
					icon: Ext.MessageBox.INFO

				});

				var form = document.forms[0],
					that = this;
				setTimeout(function(){
					Ext.Ajax.request({
						url: url.TUTOR_STATISTICS_DELETE,
						success: function(response, options) {
							var res = Ext.decode(response.responseText);
							Ext.MessageBox.hide();
							if (!res.success) {
								this.alert("ERROR", res.message);
							}
						},
						failure: that.ajaxError,
						params: {
							'id': id,
							'lang': lang
						},
						scope: that
					});
				}, 1000);

			}
		};
		if (Ext.isIE6) {
			if(confirm(text.CLEAR_STATS+"?" +"\n"+ text.ARE_YOU_SURE)) {
				fn("yes");
			}
		} else {
			Ext.Msg.confirm(text.CLEAR_STATS+"?", text.ARE_YOU_SURE, fn, this);
		}

		e.preventDefault();
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
	}
};


stat = new tw.statistics();
Ext.onReady(function(){
	Ext.select('.content-title').on('click', stat.showContent, stat);

	Ext.select('.graph-stat').on('click', stat.openGraph, stat);

	Ext.select('.clr_status').on('click', stat.clearStats, stat);

	Ext.select('div.text-row').addClassOnOver('text-row-over');
});
