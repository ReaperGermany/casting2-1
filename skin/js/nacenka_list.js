var blocker = false;
var response = [];

function load_attr(code)
{
		if (code=='company_name') {
		$.post(base_url+'admin/attributes/ajaxAttributeList3', {code:code}, parceResp, 'json');
		}
		else	
		$.post(base_url+'admin/attributes/ajaxAttributeList4', {code:code}, parceResp, 'json');
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

function addNacenka()
{
	$.post(base_url+'admin/nacenka/addNacenka', {user:$('#user').val(), nacn:$('#nacn').val()}, parceReg, 'json');
}

function parceReg(reg)
{
    if (reg.status=='OK') {
		location.href = base_url+'admin/nacenka';
    }
    if (reg.status=='ERROR') {
        alert(reg.message);
    }
}