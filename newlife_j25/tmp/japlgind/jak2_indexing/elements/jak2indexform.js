var pr_ajax = null;
function ja_reindex(start)
{
	$('loadingspan').setStyle('display','');
	var num_item = $('num_item').value;
	var urlrequest =url+'?jak2_reindex=1&numitem='+num_item+'&start='+start;
	//pr_ajax = new Ajax(urlrequest,{method:"get",onComplete:function Finish(reqTxt){ ja_confirm(reqTxt)}}).request();
	pr_ajax = new Request({
			url: urlrequest,
			method: 'get',
			onComplete: function(reqTxt) {
				ja_confirm(reqTxt);
			}
	});
	pr_ajax.send();
	return;
}
function ja_confirm(reqTxt)
{
	$('loadingspan').setStyle('display','none');
	if(reqTxt &&(reqTxt !=1))
	{
		var rs  =reqTxt.split(',');
		var add_e = new Element('div', {id: 'step_'+rs[0]});
		
		add_e.appendText('Completed for items from '+rs[1] +' to '+rs[0]+' Continues from ' +rs[0]);
		$('update-status').adopt(add_e);
		//$('update-status').appendText('Continues for '+rs[0] +' items from '+rs[1] +'<br/>\n');
		return ja_reindex(rs[0]);
	}
	else 
	{
		var add_e = new Element('div', {id: 'step_end'});
		add_e.appendText('Completed !' );
		$('update-status').adopt(add_e);
	}
	return;
	
}
function form_cancel()
{
	if(pr_ajax != null){
		pr_ajax.cancel();
	}
	$('loadingspan').setStyle('display','none');
}