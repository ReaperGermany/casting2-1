var blocker = false;
$(function(){
    var requires = {};
    $('.filter').keypress(function(e){
	var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        if (key == 13) {
            filterOffers(this);
            e.stopImmediatePropagation();
        }
        return key;
    });
    $('.autocomplete').each(function(){
        el = this;
        $(this).autocomplete({
            minLength: 0,
            source:  attrs[$(el).attr('name')]
        });
    });

    prepareRows();

    $( "#offer_data" ).dialog({
			autoOpen: false,
			modal: true,
			width: 380,
			buttons: {
				"Save": function() {
                                        if (!validateOffer()) return;
                                        $.post(base_url+'admin/ajax/saveOffer', prepare_data(), function(resp){
                                            if (resp.status=='OK') {
                                                location.href = base_url+'admin/offers';
                                            }
                                        },'json');
                                        $( this ).dialog( "close" );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
			}
	});
	
	$( "#merge_offer" ).dialog({
			autoOpen: false,
			modal: true,
			width: 380,
			buttons: {
				"Save": function() {
                                        if (!validateOffer()) return;
                                        $.post(base_url+'admin/ajax/mergeOffer', prepare_ids(), function(resp){
                                            if (resp.status=='OK') {
                                                location.href = base_url+'admin/offers';
                                            }
                                        },'json');
                                        $( this ).dialog( "close" );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
			}
	});

    $( "#contact_data" ).dialog({
			autoOpen: false,
			width: 400,
			modal: true
    });
});

function prepareRows()
{
    $('.item_id').each(function(){
        $(this).click(function(){ blocker = true})
        var id = $(this).val();
/*
        $(this).parents('tr').click(function(){
            if (!blocker) {
                $.post(base_url+'admin/ajax/getEditOfferForm',{id:id},showOfferEditForm,'json');
            }
            blocker = false;
        }); */
        $('.company_name',$(this).parents('tr'))
            .click(function(){
                blocker = true;
            })
            .dblclick(function(){
                $.post(base_url+'admin/ajax/getContactData',{id:id},showContactData,'json');
        });

        $('.offer_price',$(this).parents('tr'))
            .click(function(){
                blocker = true;
            })
            .dblclick(function(){
                $.post(base_url+'admin/ajax/getPriceHistory',{id:id},showPriceHistory,'json');
            }
        );
    });
}

function validateOffer()
{
    var has_errors = false;
    $('.error').removeClass('error');
    $('.required').each(function(){
        if (! $(this).val()) {
            parent = $(this).parents('td');
            $('input',parent).addClass('error');
            $('textarea',parent).addClass('error');
            has_errors = true;
        }
    });
    return !has_errors;
}

function showOfferEditForm(resp)
{
    $('#offer_data').html(resp.content);
    requires = resp.attrs;

    $('.attribute').each(function(){
        var id = $(this).attr('id');
        if (!requires[id]) return;
        $(this).autocomplete({
           minLength: 0,
           source:  requires[id],
           search: function(){
               $('#'+id+'_id').hval('');
           },
           select: function(event, ui){
               $('#'+id+'_id').hval(ui.item.id);
               event.stopImmediatePropagation();
           }
        });

        var field = this;
        $('#'+requires[id]['require']+"_id").hchange(function(){
            if ($('#'+requires[id]['require']+"_id").val()=='') {
                $(field).val('');
                $('#'+id+'_id').hval('');
            }
            else {
                $(field).focus();
            }
        });
    });

    $('.attribute').focus(function(){
                var id = $(this).attr('id');
                var source = [];
                if (!requires[id]) return;
                if (!requires[id]['require']) source = requires[id]['data'];
                else {
                    data_id = $('#'+requires[id]['require']+'_id').val();
                    if (data_id && requires[id]['data'][data_id]) source = requires[id]['data'][data_id];
                }

                if(source.length == 1) {
                    $(this).val(source[0]['label']);
                    $('#'+id+'_id').hval(source[0]['id']);
                }

                $(this).autocomplete({source:  source});
            });

    $('#offer_form').submit(function(){
        $('#offer_data').dialog('option','buttons').Save();
        $('#offer_data').dialog('close');
        return false;
    });

    $('input','#offer_form').keypress(
    function(e)
    {
        var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
        if (key == 13) {
            $('#offer_form').submit();
        }
        return key;
    });

    $('#offer_data').dialog("open");
}

function showContactData(resp)
{
    $('#contact_data').html(resp.content);
    $('#contact_data').dialog( "open" );
     $( ".ui-widget-overlay" ).click(function(){
        $( "#contact_data" ).dialog( "close" );
    });
}

function showPriceHistory(resp)
{
    $('#contact_data').html(resp.content);
    $('#contact_data').dialog( "open" );
    $( ".ui-widget-overlay" ).click(function(){
        $( "#contact_data" ).dialog( "close" );
    });
}

function applyResponse(resp) {
    if (resp.status=="OK"){
        $(this).parents('.offers-table').replaceWith(resp.content);
        prepareRows();
    }
}

function getSelectedItems(container) {
    var data = [];
    $('input.item_id', container).each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });
    return data;
}

function makeActive(el, state)
{
    var parent = $('#head_table').parents('table');//$(el).parents('table');
    var data = getSelectedItems(parent);

    if (data.length) {
        $.post(base_url+'admin/ajax/profitOffers',{
                ids:data,
                state: state,
                type: $('.item_type',parent).val()
            }, $.proxy(applyResponse, parent),'json');
    }
}

function changeStockStatus(el, state)
{
    var parent = $('#head_table').parents('table');//$(el).parents('table');
    var data = getSelectedItems(parent);

    if (data.length) {
        $.post(base_url+'admin/ajax/saveStockStatus',{
            ids:data,
            status: state,
            type: $('.item_type',parent).val()
        }, $.proxy(applyResponse, parent),'json');
    }
}

function toArhive(el, status)
{
    var parent = $('#head_table').parents('table');//$(el).parents('table');
    var data = getSelectedItems(parent);

    if (data.length) {
        $.post(base_url+'admin/ajax/toArhive',{
                ids:data,
                type: $('.item_type',parent).val(),
		        status: status
            }, $.proxy(applyResponse, parent),'json');
    }
}

function mergeOffers(el)
{
    var parent = $('#head_table').parents('table');//$(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });
	
	content = "<ul>";
	$.each(data, function(i, val) 
		{content = content + "<li><input id='"+val+"' class='merge_ids' type='radio' name='ids' value="+val+" /><label for='"+val+"'>"+$('#model-'+val).html()+"</label></li>";
		
		}		
		);
	content = content + "</ul>";
	$("#merge_offer").html(content);
    $("#merge_offer").dialog( "open" );
    $( ".ui-widget-overlay" ).click(function(){
        $( "#merge_offer" ).dialog( "close" );
    });
}

function prepare_ids()
{
    var post_data = {};
    var data = [];
    $('input.merge_ids').each(function(){
		data.push($(this).val());
		if ($(this).attr('checked')){
            post_data['new_id'] = $(this).val();
        }
    });
	post_data['ids'] = data;
    return post_data;
}

function validateOffer()
{
	var flag = false;
	$('input.merge_ids').each(function(){
		if ($(this).attr('checked')){
           flag = true;
        }
    });
	
	return flag;
}