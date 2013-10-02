var blocker = false;
var response = [];

function load_attr(code)
{
	if (code=='company_name') {
	$.post(base_url+'admin/attributes/ajaxAttributeList3', {code:code}, parceResp, 'json');
	}
	else	
	$.post(base_url+'admin/attributes/ajaxAttributeList2', {code:code}, parceResp, 'json');
}

function parceResp(resp)
{
    if (resp.status=='OK') {
       // response = resp;
        $('div.atr2').replaceWith(resp.content);
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}

function addUnvisible()
{
	$.post(base_url+'admin/visible/addUnvisible', {user:$('#user').val(), code:$('#atr').val(), id_code:$('#atr2').val()}, parceReg, 'json');
}

function parceReg(reg)
{
    if (reg.status=='OK') {
		location.href = base_url+'admin/visible';
    }
    if (reg.status=='ERROR') {
        alert(reg.message);
    }
}