<?php $this->load->view('admin/header')?>
<div><h2 class="top_title">Nacenka List</h2></div>
<br>
<div>
<?php $this->load->view('admin/nacenka/add')?>
</div>
<div>
    <?php $this->load->view('admin/nacenka/table',array('items'=>$nacenka, 'title'=>'Unvisible list', 'type'=>'nacenka'))?>
    <div class="clear"></div>
</div>
<div id="offer_data"></div>
<div id="contact_data"></div>
<?php $this->load->view('admin/footer')?>
