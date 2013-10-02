function addNacenka()
{
	$.post(base_url+'admin/nacenka_price/addNacenka', {user:'0', code:$('#atr').val(), id_code:$('#atr2').val(), nacn:$('#nacn').val()}, parceReg, 'json');
}
function addNacenka2()
{
	$.post(base_url+'admin/nacenka_price/addNacenka', {user:'0', code:'0', id_code:$('#atr2').val(), nacn:$('#nacn').val()}, parceReg, 'json');
}

function parceReg(reg)
{
    if (reg.status=='OK') {
		location.href = base_url+'admin/nacenka_price';
    }
    if (reg.status=='ERROR') {
        alert(reg.message);
    }
}

function deleteNacenka_price(el)
{
    var parent = $(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'admin/ajax/deleteNacenka',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                //$(parent).parents('.offers-table').replaceWith(resp.content);
				location.href = base_url+'admin/nacenka_price';
            }
        },'json');
    }
}