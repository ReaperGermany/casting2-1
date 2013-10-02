(function($) {

    offers_list = function(element,options){this.init(element,options)};
    offers_list.prototype = {
        element: null,
        options: {},
        rows_count: 0,

        // инициализация списка заявок, создание объектов для каждой строки таблицы
        init: function(element,options){
            this.element = element;
            options = $.extend({parent:this},options);
            this.options = options;
            rows_count = this.rows_count;
            $('tbody tr',element).each(function(){
                new offer_row(this,options);
                rows_count++;
            });
            $("#clear-table",this.element).bind('click',this.clear.bind(this));
            this.rows_count = rows_count;
        },

        clear: function(event) {
            $('input', this.element).each(function(){
                if ($(this).attr('type')=='button') return;
                $(this).val('');
            })

            $('textarea', this.element).each(function(){
                $(this).val('');
            })
            event.stopImmediatePropagation();
            return false;
        },

        // копирование данных из указанного объекта в новый. Объект, в который переносятся данные, будет создан
        copy: function(from) {
            to = this.addRow();
            data = from.getData();
            data.price = "";
            data.qty = "";
            to.setData(data);
        },

        // добавление строки
        addRow: function() {
            if (this.rows_count++%2) row_class = 'odd';
            else row_class = 'even';
            id = 'row_'+this.rows_count;
            $('tbody:last',this.options.container).append("<tr id='"+id+"' class='"+row_class+"'>\
                    <td>\
                        <input id='manufacturer_id' type='hidden' name='row["+this.rows_count+"][attribute][manufacturer][id]' class='required' />\
                        <input id='manufacturer' type='text' name='row["+this.rows_count+"][attribute][manufacturer][value]' class='attribute' />\
                    </td>\
                    <td>\
                        <input id='model_id' type='hidden' name='row["+this.rows_count+"][attribute][model][id]' class='required'/>\
                        <input id='model' type='text' name='row["+this.rows_count+"][attribute][model][value]' class='attribute' />\
                    </td>\
                    <td>\
                        <input id='color_id' type='hidden' name='row["+this.rows_count+"][attribute][color][id]' />\
                        <input id='color' type='text' name='row["+this.rows_count+"][attribute][color][value]' class='attribute'/>\
                    </td>\
                    <td>\
                        <input id='spec_id' type='hidden' name='row["+this.rows_count+"][attribute][spec][id]' />\
                        <input id='spec' type='text' name='row["+this.rows_count+"][attribute][spec][value]' class='attribute'/>\
                    </td>\
                    <td>\
                        <input id='qty' type='text' name='row["+this.rows_count+"][qty]' />\
                    </td>\
                    <td>\
                        <input id='price' type='text' name='row["+this.rows_count+"][price]' class='required'/>\
                    </td>\
                    <td>\
                        <input id='currency_id' type='hidden' name='row["+this.rows_count+"][attribute][currency][id]' class='required'/>\
                        <input id='currency' type='text' name='row["+this.rows_count+"][attribute][currency][value]' class='attribute'/>\
                    </td>\
                    <td>\
                        <input id='offer_price' type='text' name='row["+this.rows_count+"][offer_price]' />\
                    </td>\
                    <td>\
                        <textarea id='notes' name='row["+this.rows_count+"][notes]'></textarea>\
                    </td>\
                    <td>\
                        <button id='clear' class='button'>Clear</button>\
                        <button id='copy' class='button'>Copy</button>\
                        <!--a id='copy' href='#' class='button'>Copy</a-->\
                    </td>\
                </tr>");
            return new offer_row($('#'+id, this.element),this.options);
        }
    }

// класс, описывающий отдельную строку в таблице
    offer_row = function(element,options){this.init(element,options)};
    offer_row.prototype = {
        element: null, //объект строки
        options: {},
        init: function(element,options){
            this.element = element;
            this.options = options;
            $("#clear",this.element).bind('click',this.clear.bind(this));
            $("#copy",this.element).bind('click',this.copy.bind(this));
            $('.attribute',this.element).bind('keypress', this.onKeyPress.bind(this))

            $('.attribute',element).each(function(){
                var id = $(this).attr('id');
                if (!attrs[id]) return;
                $(this).autocomplete({
                   autoFocus: true,
                   minLength: 0,
                   source:  attrs[id],
                   search: function(event,ui){
                       $('#'+id+'_id',element).hval('');
                   },
                   select: function(event, ui){
                       $('#'+id+'_id',element).hval(ui.item.id);
                       event.stopImmediatePropagation();
                   }
                });
                
                if (attrs[id]['default_val'] != undefined) {
            	    $('#'+id+'_id',element).hval(attrs[id]['data'][attrs[id]['default_val']]['id']);
            	    $('#'+id,element).val(attrs[id]['data'][attrs[id]['default_val']]['value']);
                }

                var field = this;
                $('#'+attrs[id]['require']+"_id",element).hchange(function(){
                    $(field).val('');
                    $('#'+id+'_id',element).hval('');
                });
            });

            $('.attribute',element).focus(function(){
                var id = $(this).attr('id');
                var source = [];
                if (!attrs[id]) return;
                if (!attrs[id]['require']) source = attrs[id]['data'];
                else {
                    data_id = $('#'+attrs[id]['require']+'_id',element).val();
                    /*if (data_id == "") {
                        $(this).val("");
                        $('#'+attrs[id]['require'],element).focus();
                        return;
                    }*/
                    if (data_id && attrs[id]['data'][data_id]) source = attrs[id]['data'][data_id];
                }

                if(source.length == 1) {
                    $(this).val(source[0]['label']);
                    $('#'+id+'_id',element).hval(source[0]['id']);
                }

                $(this).autocomplete({source:  source});
            });


            $('.attribute',element).blur(function(){
                id = $(this).attr('id');
                // Выходим, если атрибут не является автодополняемым
                if (!attrs[id]) return;

                el_value = $(this).val().toLowerCase();
                el_id = $('#'+id+'_id',element).val();
                // Выходим, если ничего не было введено либо значение было выбрано из списка
                if (/*el_value != "" && el_id != "" ||*/ el_value == "") return;

                // Получение списка имеющихся значений для данного атрибута
                source = {};
                require_code = "";
                data_id = "";
                if (!attrs[id]['require']) source = attrs[id]['data'];
                else {
                    require_code = attrs[id]['require'];
                    data_id = $('#'+attrs[id]['require']+'_id',element).val();
                    // Если данный атрибут зависит от другого, а родительский атрибут не установлен,
                    // переходим к полю ввода родительского атрибута
                    if (data_id == "") {
                        $(this).val("");
                        $('#'+attrs[id]['require'],element).focus();
                        return;
                    }
                    if (data_id && attrs[id]['data'][data_id]) source = attrs[id]['data'][data_id];
                }

                // Проверяем наличие введенного значения в списке существующих.
                // Если значени обнаружено, выбирается именно оно.
                for (i=0; i< source.length ; i++ ){
                    if (source[i].label.toLowerCase() == el_value) {
                        $(this).val(source[i].label);
                        $('#'+id+'_id',element).hval(source[i].id);
                        return;
                    }
                }
            });
        },

        onKeyPress: function(e)
                {
                    var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
                    if (key == 13) {
                        this.autoAddItem(e.target);
                        e.stopImmediatePropagation();
                    }
                    return key;
                },

        autoAddItem: function(item){
                id = $(item).attr('id');
                // Выходим, если атрибут не является автодополняемым
                if (!attrs[id]) return;

                el_value = $(item).val().toLowerCase();
                el_id = $('#'+id+'_id',this.element).val();
                // Выходим, если ничего не было введено либо значение было выбрано из списка
                if (el_value != "" && el_id != "" || el_value == "") return;

                // Получение списка имеющихся значений для данного атрибута
                source = {};
                var require_code = "";
                var data_id = "";
                if (!attrs[id]['require']) source = attrs[id]['data'];
                else {
                    require_code = attrs[id]['require'];
                    data_id = $('#'+attrs[id]['require']+'_id',this.element).val();
                    // Если данный атрибут зависит от другого, а родительский атрибут не установлен,
                    // переходим к полю ввода родительского атрибута
                    if (data_id == "") {
                        $(item).val("");
                        $('#'+attrs[id]['require'],this.element).focus();
                        return;
                    }
                    if (data_id && attrs[id]['data'][data_id]) source = attrs[id]['data'][data_id];
                }

                // Проверяем наличие введенного значения в списке существующих.
                // Если значени обнаружено, выбирается именно оно.
                for (i=0; i< source.length ; i++ ){
                    if (source[i].label.toLowerCase() == el_value) {
                        $(item).val(source[i].label);
                        $('#'+id+'_id',this.element).hval(source[i].id);
                        return;
                    }
                }

                data = prompt("Do you want to add new item?\nCheck entered value", $(item).val());
                if (data) {
                    $.post(base_url+"admin/attributes/ajaxAttributeAdd",
                    {
                        code:id,
                        value:data,
                        require_code:require_code,
                        require_id: data_id
                    },
                    function(resp){
                        if (resp.status=="OK") {
                            $('#'+id,this.element).val(resp.value);
                            $('#'+id+'_id',this.element).hval(resp.id);
                            if (data_id!=""){
                                attrs[id]['data'][data_id].push({id:resp.id, label:resp.value, value:resp.value});
                            }
                            else {
                                attrs[id]['data'].push({id:resp.id, label:resp.value, value:resp.value});
                            }
                        }
                    },'json');
                }
                else {
                    // Отказались добавлять новое значение, значит вводим значение снова
                    $(item).val("");
                    $(item).focus();
                    return;
                }




                //if (!attrs[id]['require']) source = attrs[id]['data'];
                //else {
                //    data_id = $('#'+attrs[id]['require']+'_id',element).val();
                //    if (data_id && attrs[id]['data'][data_id]) source = attrs[id]['data'][data_id];
                //}

                /*if(source.length == 1) {
                    $(this).val(source[0]['label']);
                    $('#'+id+'_id',element).hval(source[0]['id']);
                }*/

                //$(this).autocomplete({source:  source});
            },

        clear: function(event){
            $('input', this.element).each(function(){
                $(this).val('');
            })
            
            $('textarea', this.element).each(function(){
                $(this).val('');
            })
            event.stopImmediatePropagation();
            return false;
        },
        copy: function(event){
            this.options.parent.copy(this);
            event.stopImmediatePropagation();
            return false;
        },
        getData: function(){
            var data = {};
            $('input', this.element).each(function(){
                data[$(this).attr('id')] = $(this).val();
            });
            $('textarea', this.element).each(function(){
                data[$(this).attr('id')] = $(this).val();
            });
            return data;
        },
        setData: function(data) {
            $('input', this.element[0]).each(function(){
                if (data[$(this).attr('id')]){
                    $(this).val(data[$(this).attr('id')]);
                }
            });
            $('textarea', this.element).each(function(){
                if (data[$(this).attr('id')]){
                    $(this).val(data[$(this).attr('id')]);
                }
            });
            return this;
        }
    };

    $.fn.offers = function(options) {
        var options = $.extend({container:this},options);

        this.each(function(){
            new offers_list(this,options);
        })

        return this
    };
})(jQuery);


(function($){
    $.fn.hval = function(data) {
        $(this).val(data);
        $(this).hchange();
    };

    $.fn.hchange = function(fn){
        name = 'hchange';
	return fn ? this.bind(name, fn) : this.trigger(name);
    };
})(jQuery);


jQuery(function(){
    $('form').each(function(){
        $(this).submit(function(){
            $(this).parent('div').dialog('option','buttons').Save();
            $(this).parent('div').dialog('close');
            return false;
        });
    })
});

function addrequest(id, t_qty)
{
	var qty = parseInt($('#qty_'+id).val());
	if (isNaN(qty)) {alert('Enter qty'); return false;}
	//if (qty > t_qty) {alert('Etered qty above');return false;}
	else
	{
    $.post(base_url+'account/addRequest',{id:id, qty:qty},function(){location.href=base_url+'account'});
	}
}

function sortBy(type, attr, dir)
{
    var post_data = {};
	post_data['type'] = type;
	post_data['attr'] = attr;
	post_data['dir'] = dir;
	$.post(base_url+'admin/offers/setOrder',post_data,function(){location.href=base_url+'admin/offers'});
	//location.href = base_url+'admin/offers/setOrder/type/'+type+'/order/'+attr+'/dir/'+dir;
}

function sortByNac(type, attr, dir)
{
    var post_data = {};
	post_data['type'] = type;
	post_data['attr'] = attr;
	post_data['dir'] = dir;
	$.post(base_url+'admin/nacenka/setOrder',post_data,function(){location.href=base_url+'admin/nacenka'});
}

function sortBy_vis(type, attr, dir)
{
    //location.href = base_url+'admin/visible/setOrder/type/'+type+'/order/'+attr+'/dir/'+dir;
	var post_data = {};
	post_data['type'] = type;
	post_data['attr'] = attr;
	post_data['dir'] = dir;
	$.post(base_url+'admin/visible/setOrder',post_data,function(){location.href=base_url+'admin/visible'});
}

function sortBySubscribe(type, attr, dir)
{
    //location.href = base_url+'admin/visible/setOrder/type/'+type+'/order/'+attr+'/dir/'+dir;
	var post_data = {};
	post_data['type'] = type;
	post_data['attr'] = attr;
	post_data['dir'] = dir;
	$.post(base_url+'admin/cron2/setOrder',post_data,function(){location.href=base_url+'admin/cron2'});
}

function sortBy_front(type, attr, dir)
{
    //location.href = base_url+'admin/visible/setOrder/type/'+type+'/order/'+attr+'/dir/'+dir;
	var post_data = {};
	post_data['type'] = type;
	post_data['attr'] = attr;
	post_data['dir'] = dir;
	$.post(base_url+'account/setOrder',post_data,function(){location.href=base_url+'account'});
}

function sortBy_front2(type, attr, dir)
{
    //location.href = base_url+'admin/visible/setOrder/type/'+type+'/order/'+attr+'/dir/'+dir;
	var post_data = {};
	post_data['type'] = type;
	post_data['attr'] = attr;
	post_data['dir'] = dir;
	$.post(base_url+'subscribe/setOrder',post_data,function(){location.href=base_url+'subscribe'});
}

function filterOffers(el)
{
    var container = $('#head_table').parents('thead');//$(el).parents('thead');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
	post_data['status'] = $(el).val();
    $.post(base_url+'admin/offers/filter',post_data,function(){location.href=base_url+'admin/offers'});
}

function filterOffersFront(el)
{
    var container = $('#head_table').parents('thead');//$(el).parents('thead');
    var post_data = {};
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
	post_data['subscribe'] = $(el).val();
    $.post(base_url+'account/filter',post_data,function(){location.href=base_url+'account'});
}

function filterOffersFront2(el)
{
    var container = $(el).parents('thead');
    var post_data = {};
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });

    $.post(base_url+'subscribe/filter',post_data,function(){location.href=base_url+'subscribe'});
}

function filterOffersFront2(el)
{
    var container = $(el).parents('thead');
    var post_data = {};
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });

    $.post(base_url+'subscribe/filter',post_data,function(){location.href=base_url+'subscribe'});
}

function filterOffers_vis(el)
{
    var container = $(el).parents('thead');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });

    $.post(base_url+'admin/visible/filter',post_data,function(){location.href=base_url+'admin/visible'});
}

function filterOffersNac(el)
{
    var container = $(el).parents('thead');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });

    $.post(base_url+'admin/nacenka/filter',post_data,function(){location.href=base_url+'admin/nacenka'});
}

function resetFilter(el)
{
    var container = $('#head_table').parents('thead');//$(el).parents('th');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $.post(base_url+'admin/offers/reset',post_data, function(){location.href=base_url+'admin/offers'});
}

function resetFilterFront(el)
{
    var container = $('#head_table').parents('thead');//$(el).parents('th');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $.post(base_url+'account/reset',post_data, function(){location.href=base_url+'account'});
}

function resetFilterFront2(el)
{
    var container = $(el).parents('th');
    var post_data = {};
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $.post(base_url+'subscribe/reset',post_data, function(){location.href=base_url+'subscribe'});
}

function resetFilter_vis(el)
{
    var container = $(el).parents('th');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $.post(base_url+'admin/visible/reset',post_data, function(){location.href=base_url+'admin/visible'});
}

function resetFilterNac(el)
{
    var container = $(el).parents('th');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $.post(base_url+'admin/nacenka/reset',post_data, function(){location.href=base_url+'admin/nacenka'});
}

function prepare_data()
{
    var post_data = {};
    
    $('#offer_data input').each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $('#offer_data textarea').each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });

    return post_data;
}

function validate()
{
    var has_errors = false;
	var nul = 0;
	var reqred = false;
	var msg = '';
    $('.error').removeClass('error');
	if (!$('#comp_email').val())
		{
			has_errors = true;
			reqred = true;
			msg += 'Offeror, ';
			$('#comp_email').addClass('error');
		}
/*	if (!$('#staff_id').val())
		{
			has_errors = true;
			reqred = true;
			msg += 'Staff, ';
			$('#staff_id').addClass('error');
		} */
    $('#add_offers_table tr').each(function(){
        var has_data = false;
        $('input',this).each(function(){
            if($(this).val() && $(this).attr('id')!='currency' && $(this).attr('id')!='currency_id') 
			{
			has_data = true; 
			if ($(this).attr('id')!='apply_offer' && $(this).attr('id')!='clear-table') {nul += 1;}}
        });
        $('textarea', this).each(function(){
            if($(this).val()) has_data = true;
        });

        $('.required',this).each(function(){
            if (has_data && ! $(this).val() && $(this).attr('rel')!='Price') {
                parent = $(this).parents('td');
                $('input',parent).addClass('error');
                $('textarea',parent).addClass('error');
                has_errors = true;
				reqred = true;
				if (!msg.match($(this).attr('rel'))) 
				{msg += $(this).attr('rel')+', ';}
            }
			if (has_data && ! $(this).val() && $(this).attr('rel')=='Price') {
                parent = $(this).parents('td');
                $('input',parent).addClass('error');
                $('textarea',parent).addClass('error');
                has_errors = true;
				alert('Fields Price should be entered.');
				return !has_errors;
				if (!msg.match($(this).attr('rel'))) 
				{msg += $(this).attr('rel')+', ';}
            }
        });
    });
	if (reqred) {alert('Fields '+msg+'should be selected from the directory, which is available by pressing down.');return !has_errors;}
	if (nul==0) {alert('Fill in the fields for at least one of the goods.');return !has_errors;}
    return !has_errors;
}

function clear_staff_data()
{
    $('#staff_id').val('');
    $('#appeal').val('');
    $('#email').val('');
    $('#skype').val('');
    $('#msn').val('');
    $('#jahoo').val('');
    $('#phone').val('');
    $('#cell').val('');
}

function create_staf_autocomplete(source)
{
    // Автодополнение для поля сотрудников
    $('#staff').autocomplete({
        minLength: 0,
        source: source,
        search: function(){
            clear_staff_data();
        },
        // Заполнение формы редактирования данными выбранного сотрудника
        select: function(event, staff){
            $('#staff_id').val(staff.item.id);
            $('#appeal').val(staff.item.appeal),
            $('#email').val(staff.item.email),
            $('#skype').val(staff.item.skype),
            $('#msn').val(staff.item.msn),
            $('#jahoo').val(staff.item.jahoo),
            $('#phone').val(staff.item.phone),
            $('#cell').val(staff.item.cell),
			$('#bbm').val(staff.item.bbm),
            $('#aim').val(staff.item.aim),
            $('#icq').val(staff.item.icq)
        }
    });
    
    if (source.length == 1) {
        $('#staff_id').val(source[0].id);
        $('#staff').val(source[0].appeal);
        $('#appeal').val(source[0].appeal);
        $('#email').val(source[0].email);
        $('#skype').val(source[0].skype);
        $('#msn').val(source[0].msn);
        $('#jahoo').val(source[0].jahoo);
        $('#phone').val(source[0].phone);
        $('#cell').val(source[0].cell);
        $("#manufacturer:first").focus();
    }
    else {
        clear_staff_data();
        $("#staff:first").focus();
    }
    $('#staff_list').css('display','block');
}

function clear_offer_form()
{
    $('#add_offers_table input').each(function() {
        if ($(this).attr('class') == 'hidden' || $(this).attr('class') == 'button') return;
        id = $(this).attr('id');
        element = $(this).parents('td');
        if (attrs[id] && attrs[id]['default_val'] != undefined) {
    	    $('#'+id+'_id',element).hval(attrs[id]['data'][attrs[id]['default_val']]['id']);
            $('#'+id,element).val(attrs[id]['data'][attrs[id]['default_val']]['value']);
        }
        else $(this).val('');
    });
    $('#add_offers_table textarea').each(function() {
        $(this).val('');
    });
}

function toggleCheckbox(el)
{
    var parent = $(el).parents("table");
    $("input[type='checkbox']",parent).each(function(){
        $(this).attr('checked',$(el).attr('checked'))
    });
}

function deleteOffers(el)
{
    var parent = $('#head_table').parents('table');//$(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'admin/ajax/deleteOffers',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                $(parent).parents('.offers-table').replaceWith(resp.content);
            }
        },'json');
    }
}

function deleteVisible(el)
{
    var parent = $(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'admin/ajax/deleteVisible',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                //$(parent).parents('.offers-table').replaceWith(resp.content);
				location.href = base_url+'admin/visible';
            }
        },'json');
    }
}

function deleteNacenka(el)
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
				location.href = base_url+'admin/nacenka';
            }
        },'json');
    }
}

function exportOffers(el)
{
    var re = /[^0-9\-\.]/gi;
	var nacenka = prompt ("Edit margin(%):", defaultText="0");
	if (nacenka) {
    if (re.test(nacenka)) {alert('Input error, margin should look like the following: -15.25');}
	else
	{	
	var parent = $('#head_table').parents('table');//$(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    location.href = base_url+'admin/ajax/exportOffers/type/'+$('.item_type',parent).val()+"/nac/"+nacenka+"/ids/"+data.join("_");
	}
	}
//    $.post(base_url+'admin/ajax/exportOffers',{
//            ids:data,
//            type: $('.item_type',parent).val()
//        },function(resp){
//            if (resp.status=="OK"){
//                //$(parent).parents('.offers-table').replaceWith(resp.content);
//            }
//        },'json'
//    );
}

function subscribe(el)
{
    var parent = $('#head_table').parents('table');//$(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'account/subscribe',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                $(parent).parents('.offers-table').replaceWith(resp.content);
            }
        },'json');
    }
}

function subscribe2(el)
{
    var parent = $(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'subscribe/subs',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                $(parent).parents('.offers-table').replaceWith(resp.content);
            }
        },'json');
    }
}

function unsubscribe(el)
{
    var parent = $('#head_table').parents('thead');//$(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'account/unsubscribe',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                $(parent).parents('.offers-table').replaceWith(resp.content);
            }
        },'json');
    }
}

function unsubscribe2(el)
{
    var parent = $(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'subscribe/unsubs',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                $(parent).parents('.offers-table').replaceWith(resp.content);
            }
        },'json');
    }
}

function cron_subscribe(el)
{
    var parent = $(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'admin/cron2/subscribe',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                $(parent).parents('.offers-table').replaceWith(resp.content);
            }
        },'json');
    }
}

function cron_unsubscribe(el)
{
    var parent = $(el).parents('table');
    var data = [];
    $('input.item_id').each(function(){
        if ($(this).attr('checked')){
            data.push($(this).val());
        }
    });

    if (data.length) {
        $.post(base_url+'admin/cron2/unsubscribe',{
                ids:data,
                type: $('.item_type',parent).val()
            },function(resp){
            if (resp.status=="OK"){
                $(parent).parents('.offers-table').replaceWith(resp.content);
            }
        },'json');
    }
}

function sending(stat)
{
    $.post(base_url+'admin/cron2/status',{stat:stat},
	function(resp){
            if (resp.status=="OK"){
                alert('sucssesful');
            }
        },'json');
    
}

function sending_front(stat, login)
{
	if (stat) {stat = 1;}
	else {stat = 0;}
	
	$.post(base_url+'admin/cron2/statusFront',{stat:stat, login:login},
	function(resp){
            if (resp.status=="OK"){
                alert('sucssesful');
            }
        },'json');
    
}

function filterUser(el)
{
    var container = $(el).parents('thead');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });

    $.post(base_url+'admin/cron2/filter',post_data,function(){location.href=base_url+'admin/cron2'});
}

function resetFilterUser(el)
{
    var container = $(el).parents('th');
    var post_data = {};
//    $('input', container).each(function(){
//        post_data[$(this).attr('name')] = $(this).val();
//    });
    $('.filter', container).each(function(){
        post_data[$(this).attr('name')] = $(this).val();
    });
    $.post(base_url+'admin/cron2/reset',post_data, function(){location.href=base_url+'admin/cron2'});
}