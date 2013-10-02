CREATE VIEW `matches` AS
  	SELECT 
		p.pk_id,
        av.value manufacturer,
        avm.value model,
        avc.value color,
        avs.value spec,
        of.pk_id offer_id,
		of.fk_staff offer_staff_id,
		of.date offer_date,
        re.*,
		avcur.value offer_currency,
		cr.rate*of.price offer_base_price,
		of.price offer_price,
        of.offer_price offer_offer_price,
        of.qty offer_qty,
        c.name offer_company
    FROM offers of
    INNER JOIN requests re ON re.fk_phone = of.fk_phone
    LEFT JOIN phones p ON p.pk_id = of.fk_phone
    LEFT JOIN attribute_values av ON p.fk_manufacturer = av.pk_id
    LEFT JOIN attribute_values avm ON p.fk_model = avm.pk_id
    LEFT JOIN attribute_values avc ON p.fk_color = avc.pk_id
    LEFT JOIN attribute_values avs ON p.fk_spec = avs.pk_id
    LEFT JOIN currency_rates cr ON  cr.fk_currency = of.fk_currency
    LEFT JOIN attribute_values avcur ON avcur.pk_id = of.fk_currency
    LEFT JOIN staff s ON s.pk_id = of.fk_staff
    LEFT JOIN company c ON c.pk_id = s.fk_company
    WHERE of.type="offer" AND of.status="active";