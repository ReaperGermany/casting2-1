$(function(){

    var requires = {};
    $('.autocomplete').each(function(){
        parent_row = $("tr.filters");
        el = this;
        source = {};
        id = $(el).attr("name");
    /*    if (attrs[id]['require']) {
            parent = $('#'+attrs[id]['require']+"_id",parent_row);
            console.log("Parent val: "+$(parent).val());
            if ($(parent).val()) {
                source = attrs[id]["data"][$(parent).val()];
            }
            else {
                source = attrs[id]["data"]['all'];
            }
        }
        else {
            source = attrs[id]["data"];
        }*/
        $(this).autocomplete({
            minLength: 0,
            source:  source,
            search: function(){
                $('#'+$(this).attr('id')+'_id',parent_row).hval('');
            },
            select: function(event, ui){
                $('#'+$(this).attr('id')+'_id',parent_row).hval(ui.item.id);
                event.stopImmediatePropagation();
            }
        });
    });

    $('.autocomplete').focus(function(){
        parent_row = $("tr.filters");
        el = this;
        source = {};
        id = $(el).attr("name");
        if (attrs[id]['require']) {
            parent = $('#'+attrs[id]['require']+"_id",parent_row);
            if ($(parent).val()) {
                source = attrs[id]["data"][$(parent).val()];
            }
            else {
                source = attrs[id]["data"]['all'];
            }
        }
        else {
            source = attrs[id]["data"];
        }
        $(this).autocomplete({ source:  source });
    });

    prepareRows();

    $( "#offer_data" ).dialog({
			autoOpen: false,
                        width: 330,
			modal: true
	});

    $( "#contact_data" ).dialog({
			autoOpen: false,
			width: 380,
			modal: true
	});
});

function prepareRows()
{
    $('.offer').dblclick(function(){
        var parent = $(this).parents('tr');
        var id = $('#offer',parent).val();
        getRowData(id);
    });

    $('.request').dblclick(function(){
        var parent = $(this).parents('tr');
        var id = $('#request',parent).val();
        getRowData(id);
    });

    $('.company').dblclick(function(){
        var id = $('input',this).val();
        getContactData(id);
    });
}

function getRowData(id)
{
    $.post(base_url+'admin/ajax/getEditOfferForm',{id:id},showOfferEditForm,'json');
}

function getContactData(id)
{
    
    $.post(base_url+'admin/ajax/getContactData',{id:id},showContactData,'json');
}

function showContactData(resp)
{
    showContent(resp.content,"contact_data");
}

function showOfferEditForm(resp)
{
    showContent(resp.content,"offer_data");
}

function showContent(content, container_id)
{
    $('#'+container_id).html(content);
    $('#'+container_id).dialog("open");

    $( ".ui-widget-overlay" ).click(function(){
        $( '#'+container_id ).dialog( "close" );
    });

    $('#'+container_id).submit(function(){
        $('#'+container_id).dialog("close");
        return false;
    });
}

function applyFilter()
{
    var container = $('.filters');
    var post_data = {};
    $('input', container).each(function(){
        post_data[$(this).attr('id')] = $(this).val();
    });

    $.post(base_url+'admin/matches/filter',post_data,function(){location.href=base_url+'admin/matches'});
}

function resetFilter()
{
    $.post(base_url+'admin/matches/reset',{},function(){location.href=base_url+'admin/matches'});
}

function sortMatchBy(field, dir)
{
    location.href = base_url+'admin/matches/setOrder/order/'+field+'/dir/'+dir;
}