<?php $this->load->view('admin/header')?>
<div>
<ul class="right_top">
<li><a href="<?php echo site_url("admin/nacenka_price"); ?>">Nacenka price</a></li>
</ul>
<h2 class="top_title">Product Controls</h2></div>

<br>
<div>
<?php $this->load->view('admin/visible/add')?>
</div>
<div>
    <?php $this->load->view('admin/visible/table',array('items'=>$visibl, 'title'=>'Unvisible list', 'type'=>'visible'))?>
    <div class="clear"></div>
</div>
<div id="offer_data"></div>
<div id="contact_data"></div>
<script type="text/javascript" src="<?php echo base_url() ?>skin/js/visible.js"></script>
<?php $this->load->view('admin/footer')?>
