<fieldset>
    <table>
        <thead>
            <tr>
                <th>Offer Price</th>
				<th>Date</th>
				<th>Company</th>
                <th>Currency</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($history as $row):?>
                <tr>
                    <td><?php echo format_price($row->getData("price"))?></td>
					<td><?php echo format_date($row->getData("created_at"))?></td>
					<td><?php echo company_code($row->getData("fk_staff"))?></td>
                    <td><?php echo currency_code($row->getData("currency_id"))?></td>
                </tr>
            <?php endforeach?>
        </tbody>
    </table>
</fieldset>

