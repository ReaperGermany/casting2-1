CREATE VIEW requests AS
  	SELECT
        o.fk_phone,
        o.price request_price,
        o.pk_id request_id,
        o.fk_staff request_staff_id,
        o.date request_date,
        o.qty request_qty,
        o.offer_price request_offer_price,
        av.value request_currency,
        cr.rate*o.price request_base_price,
        c.name request_company
    FROM offers o
    LEFT JOIN currency_rates cr
        ON  cr.fk_currency = o.fk_currency
    LEFT JOIN attribute_values av
        ON av.pk_id = o.fk_currency
    LEFT JOIN staff s
        ON s.pk_id = o.fk_staff
    LEFT JOIN company c
        ON c.pk_id = s.fk_company
    WHERE o.type="request" AND o.status="active";
        