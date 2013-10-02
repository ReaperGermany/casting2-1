<?php $this->load->view('header')?>
<?php $this->load->view('topnav')?>

<h2>Subscribe Updates</h2>
<?php // echo $dim;?>
<div>
	<?php $this->load->view('subscribe/table',array('items'=>$manufacturer, 'title'=>'Offers list', 'type'=>'offer_scribe'))?>
    <div class="clear"></div>
</div>

<?php $this->load->view('footer')?>