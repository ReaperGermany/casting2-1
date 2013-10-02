<?php $this->load->view('admin/header')?>
<div class="top_form">
<button id="new-offer" class="btn" onclick="document.location='<?php echo site_url("admin/offers/add/offer"); ?>'">New offer</button>
<button id="new-request" class="btn" onclick="document.location='<?php echo site_url("admin/offers/add/request"); ?>'">New request</button>

<!--form method="POST" action="<?php echo site_url('admin/matches')?>">
	<input type="submit" class="btn" name="Match" value="send"/>
	<input class="text" type="text" name="match_value" id="match_value"/>
    <label for="match_value">Match value</label>
        
</form-->
<div class="clear"></div>
</div>
<div><h2>Offers List</h2></div>
<div>
    <?php $this->load->view('admin/offer/table',array('items'=>$offers, 'title'=>'Offers list', 'type'=>'offer'))?>
    <?php //$this->load->view('admin/offer/table',array('items'=>$requests, 'title'=>'Requests list', 'type'=>'request'))?>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    var attrs = <?php echo json_encode($attrs)?>
</script>
<div id="offer_data"></div>
<div id="merge_offer"></div>
<div id="contact_data"></div>
<script type="text/javascript" src="<?php echo base_url() ?>skin/js/offers.js"></script>
<?php $this->load->view('admin/footer')?>
