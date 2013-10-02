<?php $this->load->view('admin/header')?>
<div><h2 class="top_title">Nacenka Price</h2></div>
<script type="text/javascript">
 var type = 'all';
</script>
<br>
<div>
<?php $this->load->view('admin/nacenka_price/add')?>
</div>
<div>
    <?php $this->load->view('admin/nacenka_price/table',array('items'=>$nacenka, 'title'=>'Unvisible list', 'type'=>'nacenka'))?>
    <div class="clear"></div>
</div>
<div id="offer_data"></div>
<div id="contact_data"></div>
<?php $this->load->view('admin/footer')?>
