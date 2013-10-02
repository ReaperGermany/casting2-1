<?php $this->load->view('admin/header')?>
<div><h2 class="top_title">Currency Rates</h2></div>
<form action="<?php echo site_url("admin/currencies/save"); ?>" method="post">
    <table>
        <thead>
            <tr>
                <th>Currency</th>
                <th>Base Currency</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rates as $rate):?>
                <tr>
                    <td><?php echo $rate->getData('currency')?></td>
                    <td>
                        <input name="currency[<?php echo $rate->getData('currency_id')?>][rate]" value="<?php echo $rate->getData('rate')?>" type="text" class="text"/>
                        <input type="hidden" name="currency[<?php echo $rate->getData('currency_id')?>][id]" value="<?php echo $rate->getId()?>"/>
                    </td>
                </tr>
            <?php endforeach;?>
        <tbody>
        <tfoot>
            <tr>
                <td colspan="2"><input type="submit" class="btn" value="Submit"/></td>
            </tr>
        </tfoot>
    </table>
</form>

<?php $this->load->view('admin/footer')?>
