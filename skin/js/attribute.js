var blocker = false;
var response = [];

function load_attr(el,code)
{
    $('ul#left_nav li.active').removeClass('active');
    $(el).addClass('active');
    $.post(base_url+'admin/attributes/ajaxAttributeList', {code:code}, parceResp, 'json');
}

function delete_image(id)
{
        $.post(base_url+'admin/attributes/ajaxDeleteImage', {
            id:id,
        }, parceResp,'json');
}

function save_attr()
{
    if ($("#attr_value").val()) {
        $.post(base_url+'admin/attributes/ajaxAttributeSave', {
            id:$("#attr_id").val(),
            value: $("#attr_value").val(),
			image: $("#attr_image").val(),
        }, parceResp,'json');
    }
}

function parceResp(resp)
{
    if (resp.status=='OK') {
        $("#attribute_edit_container").dialog( "close" );
        $("#model_edit_container").dialog( "close" );
        response = resp;
        $('div.attribute_container').replaceWith(resp.content);
        $("#attr_code").val(resp.code);
        prepareTable();
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}

function add_attr()
{
    $("#attr_id").val('');
    $("#attr_value").val('');
	$("#attr_image").val('');
    $("#attribute_edit_container").dialog( "open" );
}

function add_model()
{
    $("#model_id").val('');
    $("#model_value").val('');
    $("#model_manufacturers").html(prepareOptions(response.manufacturers));
    $("#model_colors").html(prepareOptions(response.colors));
    $("#model_specs").html(prepareOptions(response.specs));
    $("#model_edit_container").dialog( "open" );
}

function prepareTable()
{
    /* Обработка клика по строке в таблице с атрибутами*/
    $('#attributes_table tbody tr').dblclick(function(){
            if (blocker){
                blocker = false;
                return;
            }
            var id = $(this).attr('id');
            $("#attr_id").val($('#'+id+'_id').html());
            $("#attr_value").val($('#'+id+'_value').html());
			$("#attr_image").val($('#'+id+'_image').val());
            $("#attribute_edit_container").dialog( "open" );
    });

    /* Обработка клика по строке в таблице с моделями*/
    if ($('#models_table').length)
    {

    }
    $('#models_table tbody tr').click(function(){
            if (blocker){
                blocker = false;
                return;
            }
            var id = $(this).attr('id');
            $("#model_id").val($('#'+id+'_id').val());
            $("#model_value").val($('#'+id+'_value').val());
            $("#model_manufacturers").html(prepareOptions(response.manufacturers, id+'_manufacturers'));
            $("#model_colors").html(prepareOptions(response.colors, id+'_colors'));
            $("#model_specs").html(prepareOptions(response.specs, id+'_specs'));
            $("#model_edit_container").dialog( "open" );
    });
}

function prepareOptions(data, id)
{
    var retval = "";
    var selected_list = [];
    if ($('#'+id).val())
        selected_list = $('#'+id).val().split(',');
    $(data).each(function(el){
        if ($.inArray(data[el]['id'], selected_list) != -1) {
            selected = "selected";
        }
        else selected = "";
        retval += "<option value='"+data[el]['id']+"' "+selected+">"+data[el]['label']+"</option>"
        return;
    });

    return retval;
}

function delete_attr()
{
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'admin/attributes/ajaxAttributeDelete',{ids:data, code:$("#attr_code").val()},parceResp,'json');
    }
}

$("#attribute_edit_container").dialog({
        autoOpen: false,
        height: 200,
        width: 250,
        modal: true,
        buttons: {
            "Save": function() {
                $.post(base_url+'admin/attributes/ajaxAttributeSave', {
                    id:$("#attr_id").val(),
                    value: $("#attr_value").val(),
                    code: $("#attr_code").val(),
					image: $("#attr_image").val()
                }, parceResp,'json');
                //$( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });

$("#model_edit_container").dialog({
        autoOpen: false,
        height: 500,
        width: 320,
        modal: true,
        buttons: {
            "Save": function() {
                $.post(base_url+'admin/attributes/ajaxAttributeSave', {
                    id:$("#model_id").val(),
                    value: $("#model_value").val(),
                    manufacturers: $("#model_manufacturers").val(),
                    colors: $("#model_colors").val(),
                    specs: $("#model_specs").val(),
                    code: $("#attr_code").val()
                }, parceResp,'json');
                //$( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });

function sortModelsBy(field, dir)
{
    $.post(base_url+'admin/attributes/setAttributeOrder/order/'+field+'/dir/'+dir, {code:'model'}, parceResp, 'json');
}