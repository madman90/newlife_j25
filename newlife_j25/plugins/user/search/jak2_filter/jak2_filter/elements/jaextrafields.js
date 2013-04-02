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

function changeExtraValue(action, element) {
	var elements 
		= $("extrafield_data_id").elements 
		= $("extrafield_data_id").elements ? $("extrafield_data_id").elements : {};
	switch (action) {
	case 'add':
		elements[element.id] = element
		break;
	case 'remove':
		if (elements[element.id]) {
			delete (elements[element.id]);
		}
		break;
	}
	return;
}

function updateExtrafields(id, val) {
	var value = '';
	if ($("paramsfield_type_" + id).value == 'jarange') {
		value = $("custom_value_" + id).value;
		var values = value.split(/\r\n|\r|\n/);
		value = values.join(';');
	}
	var element = {
		id : id,
		type : $("paramsfield_type_" + id).value,
		order : $("paramsfield_order_" + id).value,
		ranges : value
	};
	$("jacustom_type_" + id).style.display = "none";

	if (val) {
		if ($('jform_params_show_cats0').checked && $('jform_params_show_extra_fields_groups0').checked) {
			var groups = $$('tbody[id^=group_] ');
			if (groups && groups.length > 1) {
				var k = 0;
				groups.each(function(group) {
					var childs = group.getElements('input[id^=extrafield_]');
					var checked = 0;
					childs.each(function(item) {
						if (item.checked)
							checked = 1
					});
					if (checked == 1)
						k++;
				});
				if (k > 1) {
					alert('Please show drop-down categories selection for multi groups');
					$('extrafield_' + id).checked = false;
					return false;
				}
			}
		}
		$("paramsfield_type_" + id).disabled = false;
		if ($("paramsfield_type_" + id).value == 'jarange') {
			$("jacustom_type_" + id).style.display = "";
			if ($$('.jpane-slider')[0] != null) {
				var parent = $$('.jpane-slider')[0];
				parent.setStyle('height', parent.offsetHeight + $("jacustom_type_" + id).offsetHeight);
			}
		}
		$("paramsfield_order_" + id).disabled = false;
		changeExtraValue("add", element);
	} else {
		$("paramsfield_type_" + id).disabled = true;
		$("paramsfield_order_" + id).disabled = true;
		changeExtraValue("remove", element);
	}
}

function checkAll(ischecked) {
	var fields = $$('input[id^=extrafield]');
	if (!fields)
		return;
	fields.each(function(input) {
		if (input.type == 'checkbox') {
			input.checked = ischecked;
			updateExtrafields(input.value, ischecked);
		}
	});
}

function removeSelectedExtrafields() {
	var groups = $$('tbody[id^=group_] ');
	var cats0 = $('jform_params_show_cats0').checked;
	var efgs0 = $('jform_params_show_extra_fields_groups0').checked;

	if (cats0 && efgs0 && groups && groups.length > 1) {
		var dgroup = groups[0];
		groups.each(function(group) {
			if (group.id != dgroup.id) {
				var childs = group.getElements('input[id^=extrafield_] ');
				var checked = 0;
				childs.each(function(item) {
					if (item.checked) {
						item.checked = false;
						updateExtrafields(item.value, item.checked);
					}
				});
			}
		});
	}
}

window.addEvent('load', function() {
	if (($("extrafield_data_id").value != 'undefined') && ($("extrafield_data_id").value != '')) {
		$("extrafield_data_id").elements = JSON.decode($("extrafield_data_id").value);
	}

	$('jform_params_show_cats0').onclick = function(e) {
		removeSelectedExtrafields();
	}

	$('jform_params_show_extra_fields_groups0').onclick = function(e) {
		removeSelectedExtrafields();
	}

	document.adminForm.onsubmit =  function(action) {
		if (action == undefined) {
			action = this.task.value;
			action = action.split('.');
			if (action.length == 2) {
				action = action[1];
			}
		}
		switch (action) {
		case 'save':
		case 'apply':
			$("extrafield_data_id").value = JSON.encode($("extrafield_data_id").elements);
			break;
		default:
			break;
		}
		//this.submit(action);
		return true;
	};
});