(function($){
	if (! $) return;
	
	window.DATA_GRAPH_DRAW = {
		_g : undefined, // graph object
		labels : [], // labels show name
		title : '数据统计', // title
		
		setGraphDiv : function(div) {
			// graph div
			this.graphDiv = $(div);
		},
		
		setLabels : function(labels) {
			// show labels as line name
			$.isArray(labels) && (this.labels = labels) || (labels = []);
		},
		
		init : function() {
			// must set graph div
			if ( ! this.graphDiv) {
				alert('Error');
				return;
			}
			
			// parent div position to relative, then add absolute element
			this.graphDiv.parent().css('position', 'relative');
			
			// insert label show div
			this.labelDiv = $("<table>").insertAfter(this.graphDiv)
						.attr('border', "0")
						.attr('align', "center")
						.css("visibility", 'hidden');
			this.labelDiv.append('<tr>');
			for (var i = 0; i < this.labels.length; i ++) {
				var showid = 'show_' + i;
				var html = '<td><hr style="width: 30px;"></td>'
						+ '<td><input type="checkbox" id="' + showid + '" value="' + i + '">'
						+ '<label for="' + showid + '">' + this.labels[i] + '</label></td>';
				$('tr', this.labelDiv).append(html);
			}
			$('tr', this.labelDiv).append('<td style="padding-left: 10px;color: red;"><sub>注：图片选取可放大，双击还原</sub></td>');
			$('input:checkbox', this.labelDiv).click( function() {
				if (window.DATA_GRAPH_DRAW) {
					window.DATA_GRAPH_DRAW._g.setVisibility(parseInt(this.value), this.checked);
				}
			} );
			
			// insert tip div
			this.tipShowDiv = $("<div>").appendTo(this.graphDiv.parent())
						.css('position', 'absolute')
						.css('display', 'none')
						.css('cursor', 'pointer');
						
			// insert data detail div
			this.detailDiv = $("<div>").appendTo(this.graphDiv.parent())
						.css('width', '150px')
						.css('font-size', '1.5em')
						.css('border', '2px solid #89A54E')
						.css('position', 'absolute')
						.css('zIndex', '-10')
						.css('display', 'none');
		},
		
		moveCenter : function() {
			// move tip msg to graphdiv center;
			var tip = this.tipShowDiv;
			tip.css('left', this.graphDiv.width()/2 - tip.width()/2);
			tip.css('top',  this.graphDiv.height()/2 - tip.height()/2)
		},
		
		tipMsg : function(event, args) {
			var tip = this.tipShowDiv;
			
			switch (event) {
				// loading
				case 'load' : 
					tip.empty().html('<span>加载中...</span>').show();
					this.moveCenter();
					break;
				
				// load ok and hide msg;	
				case 'complete' : 
					tip.empty().hide();
					break;
					
				// load error and show msg;
				case 'error' : 
					tip.empty().html('<span>加载失败...</span>').show();
					tip.click( function() { $(this).hide(); } );
					this.moveCenter();
					break;
			}
		},
		
		loadingData : function() {
			// loading tip
			this.tipMsg('load');
		},
		
		showData : function(jsondata, between) {
			var me = this;
			if (jsondata) {
				// loading tip hide;
				this.tipMsg('complete');
				
				// create graph
				this.createGraph(jsondata, jsondata['x-date'], jsondata['y-data'], between);
				// set labels hr color
				this.labelDiv.css('visibility', 'visible').find("input:checkbox").attr('checked', true);
				$('hr', this.labelDiv).each( function(i) { $(this).css('color', me._g.getColors()[i]); } );
			} else {
				// load error
				this.tipMsg('error');
			}
		},
		
		fillValue : function(t, defval, values, pos) {
			// output fill value
			// values[labels.length][lines]
			var line = [t];
			for (var i = 0; i < this.labels.length; i++) {
				values[i] ? line.push(parseInt(values[i][pos])) : line.push(defval);
			}
			return line;
		},
		
		createGraph : function(jsondata, x_date, y_data, between) {
			var me = this;
			// graph options
			var graphOptions = {
					title : me.title,
					drawPoints : true, pointSize : 3, highlightCircleSize : 6,
					showLabelsOnHighlight : false,
					xAxisLabelFormatter : function(d) { return (d.getMonth()+1) + '-' + d.getDate(); },
					highlightCallback : function(e, x, pts) { me.showDataDetail(e, x, pts); },
					unhighlightCallback : function() { me.hideDataDetail(); }
				};
			
			// destroy previou graph
			if (this._g) { this._g.destroy(); }
			this._g = new Dygraph(
					this.graphDiv.get(0),
					(function() {
						var i = 0;
						var x_start = new Date(between[0]).getTime();
						var x_end = new Date(between[1]).getTime();
						var data = [];
						while (x_start <= x_end) {
							if (x_date.length > 0 && i < x_date.length ) {
								var d = new Date(x_date[i]);
								if (d.getTime() == x_start) {
									data.push(me.fillValue(d, 0, y_data, i));
									i++;
								} else {
									data.push(me.fillValue(new Date(x_start), 0, [], i));
								}
							} else {
								data.push(me.fillValue(new Date(x_start), 0, [], i));
							}
							x_start += 24 * 3600 * 1000;
						}
						return data;
					})(),
					graphOptions
			);
		},
		
		showDataDetail : function(e, x, pts) {
			var trline = '';
			// data one line output
			for (var i = 0; i < this.labels.length; i++) {
				trline += '<tr><td>' + this.labels[i] + '</td><td align="right">' + pts[i].yval + '</td></tr>';
			}
			
			// show detail
			this.detailDiv.empty()
				.append("<table width='100%'>" 
						+ "<tr><td colspan='2'>" + new Date(x).strftime('%Y-%m-%d') + "</td></tr>"
						+ trline
						+ "</table>")
				.css('left', $(e)[0].layerX)
				.css('top', $(e)[0].layerY)
				.css('display', '');
		},
	
		hideDataDetail : function() {
			// hide detail
			this.detailDiv.css('display', 'none');
		}
					
	};
})(jQuery);
