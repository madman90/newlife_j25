/**
 * ------------------------------------------------------------------------
 * JA K2 Search Plugin for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

var hide_k2_filter = false;

function load_jak2_filter(html, inline_label) {
	var areas = $$('input[name^=areas] ');
	var other_group = [];
	var ja_group = [];

	var jak2_input = null;

	var eja_group = new Element('div', {
		id : 'ja_group'
	});
	var eother_group = new Element('div', {
		id : 'other_options'
	});
	var plg_ja_filterform = new Element('div', {
		id : 'plg_ja_filterform'
	});
	areas.each(function(input) {
		if (input.type == 'checkbox') {
			if (input.value == 'jak2_filter') {
				jak2_input = input;

				// Fix un-capatible in IE9 of mootools has version < 1.25
				var area_jak2 = new Element('input');
				area_jak2.setProperty('type', 'radio');
				area_jak2.setProperty('checked', jak2_input.checked);
				area_jak2.setProperty('id', 'area_jak2');
				area_jak2.setProperty('name', 'filter_group');
				area_jak2.setProperty('value', 'jagroup');
				area_jak2.addEvent('click', function() {
					change_group(this.value);
				});
				ja_group.push(area_jak2);

				var textField = new Element('input');
				textField.setProperty('type', 'hidden');
				textField.setProperty('id', 'jaarea');
				textField.setProperty('name', 'areas[]');
				textField.setProperty('value', 'jak2_filter');
				ja_group.push(textField);

				var label = input.getNext('label');
				label.htmlFor = 'area_jak2';
				ja_group.push(label);

				var other_radio = new Element('input');
				other_radio.setProperty('type', 'radio');
				other_radio.setProperty('checked', !jak2_input.checked);
				other_radio.setProperty('id', 'other_group');
				other_radio.setProperty('name', 'filter_group');
				other_radio.setProperty('value', 'other_group');
				other_radio.addEvent('click', function() {
					change_group(this.value);
				});
				ja_group.push(other_radio);

				var label = new Element('label', {
					'for' : 'other_group'
				});
				label.innerHTML = 'Other group';
				ja_group.push(label);

				eja_group.inject(input, 'before');
				eother_group.inject(input, 'before');
				plg_ja_filterform.inject(input, 'before');
				//input.remove();
				input.parentNode.removeChild(input);
			} else {
				other_group.push(input);
				other_group.push(input.getNext('label'));
			}
			//input.remove();
		}
	});
	if (jak2_input != null) {
		eja_group.adopt(ja_group);

		eother_group.adopt(other_group);

		plg_ja_filterform.innerHTML = html;
		$('area_jak2').checked = jak2_input.checked;
		$('other_group').checked = !jak2_input.checked;
		show_jafilterform($('area_jak2').checked, html, inline_label)
	}
	// If default is hide k2 filter form
	if (hide_k2_filter) {
		$('plg_ja_filterform').setStyle('display', 'none');
	}
}

function change_group(group) {
	if (group == 'other_group') {
		$('jaarea').value = '';
		$('other_options').setStyle('display', '');
		$('plg_ja_filterform').setStyle('display', 'none');
	} else {
		$('jaarea').value = 'jak2_filter';
		$('other_options').setStyle('display', 'none');
		if (!hide_k2_filter) {
		    $('plg_ja_filterform').setStyle('display', 'block');
		}
	}
}

function show_jafilterform(ischeck, html, inline_label) {
	if (ischeck) {
		var plg_ja_filterform = $('plg_ja_filterform');
		if (plg_ja_filterform) {
			$('plg_ja_filterform').setStyle('display', '');
		} else {
			var plg_ja_filterform = new Element('div', {
				id : 'plg_ja_filterform'
			});
			plg_ja_filterform.inject($('ja_group'), 'before');
			plg_ja_filterform.innerHTML = html;
		}
		if ($('other_options')) {
			$('other_options').setStyle('display', 'none');
		}
		label_sliding(inline_label, 'plg_ja_filterform');

	} else {
		var plg_ja_filterform = $('plg_ja_filterform');
		if (plg_ja_filterform) {
			$('plg_ja_filterform').setStyle('display', 'none');
		}
		if ($('other_options')) {
			$('other_options').setStyle('display', '');
		}
	}
	if ($('search-searchword').value == 'customsearch') {
		$('search-searchword').value = '';
	}
}

function build_jafilter(div_parent_id, group_tag) {
	if (!$(div_parent_id)) {
		return false;
	}
	var ja_filter = {};
	var extra_fields_groups = $(div_parent_id).getElementById(
			group_tag + 'efgroups');
	if (extra_fields_groups) {
		ja_filter['extra_fields_groups'] = extra_fields_groups.value;
	}
	var catitem = $(div_parent_id).getElementById(group_tag + 'catid');
	var group = '';
	if (catitem) {
		ja_filter['catid'] = catitem.value;
		var tmpl = catitem.value.split('_');
		if ($(group_tag + tmpl[0])) {
			group = ' #' + group_tag + tmpl[0];
		}
	} else {
		if (extra_fields_groups) {
			if ($(group_tag + extra_fields_groups.value)) {
				group = ' #' + group_tag + extra_fields_groups.value;
			}
		}
	}
	var created_by = $(div_parent_id).getElementById(group_tag + 'created_by');
	if (created_by) {
		ja_filter['created_by'] = created_by.value;
	}
	inputs = $$('#' + div_parent_id + group + ' input');
	if (inputs.length > 0) {
		inputs.each(function(input) {
			if (input.type == 'text') {
				v_label = ($$('label[for=' + input.id + ']')[0].innerHTML) + '...';
				if ((input.value != '') && (input.value != v_label)) {
					if (ja_filter[input.name] == undefined) {
						ja_filter[input.name] = input.value;
					} else
						ja_filter[input.name] += '-' + input.value;
				}
			} else if (input.type == 'radio') {
				if (input.checked) {
					ja_filter[input.name] = input.value;
				}
			} else if (input.type == 'checkbox') {
				if (input.checked) {
					if (ja_filter[input.name] == undefined) {
						ja_filter[input.name] = input.value;
					} else
						ja_filter[input.name] += ',' + input.value;
				}
			}
		});
	}
	selects = $$('#' + div_parent_id + group + '  select');
	selects.each(function(selectitem) {
		if (selectitem.id == group_tag + 'catid') {
			ja_filter['catid'] = selectitem.value;
		} else if (selectitem.value != '') {
			ja_filter[selectitem.name] = selectitem.value;
		}
	});

	if ($('area_jak2') && ($('area_jak2').checked)) {
		areas = $$('input[name^=areas] ');
		areas.each(function(input) {
			if (input.type == 'checkbox') {
				if (input.checked)
					input.checked = false;
			}
		});

		if ($('search-searchword').value != '') {
			$('ja_searchword').value = $('search-searchword').value;
		}
	} else {
		if ($('jaarea')) {
			$('jaarea').value = '';
		}
	}
	var cookie = Cookie.read("jak2_filter");
	if (cookie) {
		Cookie.dispose("jak2_filter");
	}
	Cookie.write("jak2_filter", JSON.encode(ja_filter), {
		duration : 1,
		path : "/"
	});
    // Avoid cache when only select k2 filter
	var k = '';
	for (e in ja_filter) {
	    k += ja_filter[e];
	}
	var jak2code = null;
	if ($$('#' + div_parent_id + ' .jak2code').length > 0) {
	    jak2code = $$('#' + div_parent_id + ' .jak2code')[0];
	} else {
    	jak2code = new Element('input', {
    	    'type': 'hidden',
    	    'class': 'jak2code',
    	    'name': 'areas[]',
    	});
    	if ($(div_parent_id)) jak2code.inject($(div_parent_id));
	}
	jak2code.value = k;
}

function label_sliding(inline_label, div_parent_id) {
	if (inline_label == '1')
		return true;
	inputs = $$('#' + div_parent_id + ' input[type=text]');
	if (inputs.length > 0) {
		inputs.each(function(input) {
			label = $$('label[for=' + input.id + ']')[0];
			v_input = label.innerHTML + '...';
			label.setStyle('display', 'none');
			if (input.value == '') {
				input.value = v_input;
			}
			input.addEvents( {
				focus : function() {
					if (input.value == $$('label[for=' + this.id + ']')[0].innerHTML + '...') {
						input.focus();
						input.value = '';
					}
				},
				keypress : function() {
					if (this.value == $$('label[for=' + this.id + ']')[0].innerHTML + '...') {
						this.value = '';
						this.removeClass("focus").addClass(
								"typing");
					}
				},
				blur : function() {
					this.removeClass("focus").removeClass(
							"typing");
					if (this.value == '') {
						this.value = $$('label[for=' + this.id + ']')[0].innerHTML + '...';
					}
				}
			});
		});
	}
	selects = $$('#' + div_parent_id + '  select');
	selects.each(function(selectitem) {
	    var tmp = $$('#' + div_parent_id + ' label[for=' + selectitem.id + ']')[0];
		if (tmp) {
			tmp.setStyle('display', 'none');
		}
	});
}

function load_categories(value, groupname) {
	catid = $(groupname + 'catid');
	if (catid) {
		categories = catid.getElements('option');
		categories.each(function(category) {
			if (value > 0)
				category.setStyle('display', 'none');
			else
				category.setStyle('display', '');
		});
		categories = catid.getElements('option[value^=' + value + ']');
		categories.each(function(category) {
			category.setStyle('display', '');
		});
		if (categories.length > 0) {
			categories[0].selected = 1;
			load_extrafields(catid.value, groupname);
		}
	} else {
		load_extrafields(value + '_0', groupname);
	}
}

function load_extrafields(value, groupname) {
	var tmpl = value.split('_');
	if (tmpl.length == 2) {
		var groups = $$('div[id^=' + groupname + '] ');
		if (groups) {
			groups.setStyle('display', 'none');
		}
		if ($(groupname + tmpl[0])) {
			$(groupname + tmpl[0]).setStyle('display', '');
		}
	}
}

function expand_catoptions(groupname) {
	var ua = navigator.userAgent.toLowerCase();
	var UA = ua.match(/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/)
			|| [ null, 'unknown', 0 ];
	var browser = (UA[1] == 'version') ? UA[3] : UA[1];
	if (browser == 'ie' && $(groupname + 'catid')) {
		$(groupname + 'catid').addEvents( {
			focus : function() {
				this.setStyle("origWidth", this.getStyle("width"));
				this.setStyle("width", "auto");
			},
			blur : function() {
				this.setStyle("width", this.getStyle("origWidth"));
			}
		});
	}
}