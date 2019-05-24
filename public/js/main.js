var UT = {}, datatimer;

UT.UsersColl = Backbone.Collection.extend({

	initialize: function() {
		this.refresh();
	},

	refresh: function() {
		var that = this,
			params;
			
		clearInterval(datatimer);

		params = {
			portalID: PORTAL_ID
		};

		$.post(STUDENT_FETCH_URL, params, function(data){
			that.reset(that.mergeData(data.data));
			datatimer = setTimeout(_.bind(that.refresh, that), 10000);
		});

		///teacher/main/data/

	},

	mergeData: function(data) {
		var rows = [],
			groupID = $('#groupID').val(),
			includeOffline = $('#includeOffline').is(':checked'),
			offline = false,
			row;

		if (groupID == 'ungrouped') {
			groupID = null;
		}
		_.each(USERS_DATA, function(user, userID) {
			if (groupID != 'all' && groupID != USERS_DATA[userID].gaGroupID) {
				return;
			}
			row = data[userID];

			offline = !row || !row.page || !row.lastActivity || row.lastActivity < (Math.round(new Date().getTime() / 1000) - 600);
			if (includeOffline) {
				if (!row) row = {};
				if (!row.extra) row.extra = {};
				if (offline) row.extra.offline = true;
			} else if (offline) {
				return;
			}

			row.id = userID;
			row.page = this.formatPageDescription(row.page, row.extra);
			row.prettyStamp = $.timeago(new Date(row.lastActivity*1000));
			row = _.extend(row, USERS_DATA[userID]);
			rows.push(row);
		}, this);
		return rows;
	},

	formatPageDescription: function(page, data) {
		var desc;

		if (data && data.offline) {
			page = 'OFFLINE';
		}

		desc = PAGE_DESCRIPTIONS[page];
		if (data) {
			if (data.value && page === 'PLAYGAME') {
				desc = desc.replace('%s', GAMES[data.value]);
			}
			if (data.value && page === 'LESSON') {
				desc = desc.replace('%s', COURSES[LESSONS[data.value].course].ucFirst() + ': ' + LESSONS[data.value].name);
			}
			if (data.lessonID && page === 'TYPEDPAGE') {
				desc = desc.replace('%s', COURSES[LESSONS[data.lessonID].course].ucFirst() + ': ' + LESSONS[data.lessonID].name);
				if (data.typed) {
					var words = (data.typed - (data.errors * 5))/5;		// begin WPM calculation
					var minutes = data.seconds/60;
					data.speed = Math.max(Math.round(words/minutes),0);
				}
			}
		}

		return desc || 'Unknown Page';
	},

	comparator: function(row) {
		return row.get('username');
	}
});



/**
 * Views
 */

UT.UsersTableView = Backbone.View.extend({
	el: '#usertrackTable',
	template: null,
	/*events: {
		'change #groupID' : 'filterClass',
		'click #includeOffline' : 'filterClass'
	},*/

	initialize: function() {
		this.template = $('#userViewTmpl').template();
		//this.model.bind('reset', this.render, this);
		this.model.on('reset', this.render, this);
	},

	render: function() {
		var views = [];
		this.$('tbody').html($.tmpl(this.template, {data: this.model.toJSON()}));
	},

	filterClass: function() {
		this.model.refresh();
	}
});

UT.BlogColl = Backbone.Collection.extend({

	initialize: function() {
		this.refresh();
	},

	refresh: function() {
		var that = this,
			t;

		$.getJSON('https://blog.bicheech.com/?feed=json&callback=?', function(data){

			data = data.slice(0,3);
			_.each(data, function(row){
				t = row.date.split(/[- :]/);
				row.date = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
			});
			that.reset(data);
		});
	}

});


UT.BlogView = Backbone.View.extend({
	el: '#blogFeedBox',
	className: 'entry',
	template: null,

	initialize: function() {
		if (!this.template) {
			this.template = _.template($('#blogViewTmpl').html());
			//this.template = $('#blogViewTmpl').template();
		}
		this.model.on('reset', this.render, this);
	},

	render: function() {
		this.$el.html($.temp(this.template, {data: this.model.toJSON()}));
	}
});

$(function(){
	new UT.UsersTableView({
		model: new UT.UsersColl()
	});

	new UT.BlogView({
		model: new UT.BlogColl()
	});
});
