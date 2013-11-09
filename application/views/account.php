<?php $this->load->view('header')?>
<div class="top">
    <div class="content">
        <h1 id="login"><a href="<?php echo base_url()?>"></a></h1>
		<?php $this->load->view('topnav')?>
		<div class="phone">
		<h4>+1.347.90DIMEX (34639)
		</br> 
		Monday - Friday 9AM - 6PM EST
		</h4>
		</div>
		<div class="skype">
			<a href="skype:dimexintl?call">
			<img src="http://cdn.dev.skype.com/uri/callbutton_24px.png"
					 alt="Skype Me™!" style="border: none;margin: 24px; vertical-align: -41px">
			</a>
		</div>
        
        <div class="clear"></div>
    </div>
</div>
<div class="wellcome">
    <div class="content">
    	<h2>Welcome <?php echo $login;?>!</h2>
        <div class="search">
            <h4>Search by model</h4>
            <input autofocus type="text" id="model_value" onkeyup="this.value = this.value.replace (/[^0-9\a-z\A-Z\-\_\ ]/gi, '')" onkeypress="if(key(event)==13){search_model();}" value="<?php if(isset($filters['offer_front']['model'])) {echo $filters['offer_front']['model'];} ?>"  /> 
            <button id="entryButton" onclick="search_model()"><span>Search</span></button>	
        </div>
        <div class="clear"></div>    
    </div>
</div>
<div class="col">
	<div class="left-col">
    	<h2>Manufacturer</h2>		
		<?php $this->load->view('account/list',array('items'=>$list))?>
        <div class="clear"></div>
		
	</div>
	<div class="right-col">		
		<div>
			<div>
				<label>Subscribe Updates</label>
				<input type="checkbox" id='send' onchange='sending_front(this.checked, <?php echo $login?>)' 
				<?php if ($subs) {echo 'checked=checked';} ?>/>
			</div>
			<div> 
			<?php echo $user->getData('name');?>
			</div>
			<?php $this->load->view('account/table',array('items'=>$offers, 'title'=>'Offers list', 'type'=>'offer_front'))?>
		    <div class="clear"></div>
		</div>
	</div>
    <div class="clear"></div>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>skin/js/offers_front.js"></script>
<?php $this->load->view('footer')?>