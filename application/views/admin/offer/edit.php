    <form action="<?php echo site_url("admin/offer/savePost"); ?>" method="post" id="offer_form">
        <fieldset>
        <input type="hidden" name="type" value="<?php echo $offer->getData('type')?>" class="hidden"/>
        <input type="hidden" name="row[0][offer_id]" value="<?php echo $offer->getId()?>" class="hidden"/>
        <input type="hidden" name="row[0][profit_state]" value="<?php echo $offer->getData('profit_state')?>" class="hidden"/>
        <table>
            <tbody>
                <tr style="display:none;">
                    <td>Company</td>
                    <td>
                        <input id="company_id" type="hidden" name="company_id" value="<?php echo $offer->getData("company_id")?>" class="hidden" />
                        <input id="company" type="text" name="company" class="attribute" value="<?php echo $offer->getData("company_name")?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Company</td>
                    <td>
                        <input id="staff_id" type="text" name="staff_id" class="required hidden" value="<?php echo $offer->getData("fk_staff")?>"/>
                        <input id="staff" type="text" name="staff" class="attribute" value="<?php echo $offer->getData("email")?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Manufacturer</td>
                    <td>
                        <input id="manufacturer_id" type="text" name="row[0][attribute][manufacturer][id]" class="required hidden" value="<?php echo $offer->getData("fk_manufacturer")?>"/>
                        <input id="manufacturer" type="text" name="row[0][attribute][manufacturer][value]" class="attribute" value="<?php echo $offer->getData("manufacturer")?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Model</td>
                    <td>
                        <input id="model_id" type="hidden" name="row[0][attribute][model][id]" class="required" value="<?php echo $offer->getData("fk_model")?>"/>
                        <input id="model" type="text" name="row[0][attribute][model][value]" class="attribute" value="<?php echo $offer->getData("model")?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td>
                        <input id="color_id" type="hidden" name="row[0][attribute][color][id]" value="<?php echo $offer->getData("fk_color")?>"/>
                        <input id="color" type="text" name="row[0][attribute][color][value]" class="attribute" value="<?php echo $offer->getData("color")?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Spec</td>
                    <td>
                        <input id="spec_id" type="hidden" name="row[0][attribute][spec][id]" value="<?php echo $offer->getData("fk_spec")?>"/>
                        <input id="spec" type="text" name="row[0][attribute][spec][value]" class="attribute" value="<?php echo $offer->getData("spec")?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Qty</td>
                    <td>
                        <input id="qty" type="text" name="row[0][qty]" value="<?php echo $offer->getData("qty")?>"/>
                    </td>
                </tr>
					<tr>
                    <td>Qty fctor</td>
                    <td>
                        <input id="qty_factor" type="text" name="row[0][qty_factor]" value="<?php echo $offer->getData("qty_factor")?>"/>
                    </td>
                </tr>
                <tr>
				<tr>
                    <!--td>Brand</td>
                    <td>
                        <input id="brand" type="text" name="row[0][brand]" value="<?php echo $offer->getData("brand")?>"/>
                    </td>
                </tr>
                <tr>
				<tr>
                    <td>Location</td>
                    <td>
                        <input id="location" type="text" name="row[0][location]" value="<?php echo $offer->getData("location")?>"/>
                    </td>
                </tr-->
                <tr>
                    <td>Offer Price</td>
                    <td>
                        <input id="price" type="text" name="row[0][price]" class="required" value="<?php echo $offer->getData("price")?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Currency</td>
                    <td>
                        <input id="currency_id" type="hidden" name="row[0][attribute][currency][id]" class="required" value="<?php echo $offer->getData("fk_currency")?>"/>
                        <input id="currency" type="text" name="row[0][attribute][currency][value]" class="attribute" value="<?php echo $offer->getData("currency_code")?>"/>
                    </td>
                </tr>
                <!--tr>
                    <td>Offer Price</td>
                    <td>
                        <input id="offer_price" type="text" name="row[0][offer_price]" value="<?php echo $offer->getData("offer_price")?>"/>
                    </td>
                </tr-->
                <tr>
                    <td>Notes</td>
                    <td>
                        <textarea id="notes" name="row[0][notes]"><?php echo $offer->getData("notes")?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        </fieldset>
    </form>

