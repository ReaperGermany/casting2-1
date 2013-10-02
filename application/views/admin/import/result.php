<?php $this->load->view('admin/header') ?>
<div class="left">
    <?php if(count($errors)):?>
        <div class="error">
            <?php echo implode('<br>',$errors)?>
        </div>
    <?php endif;?>
    <div>
        Total rows: <?php echo $result['total_rows'] ?><br>
        Saved rows: <?php echo $result['saved_rows'] ?>
    </div>
        <?php if($result['total_rows']!=$result['saved_rows']):?>
            Link to log file: <a href="<?php echo base_url().$log?>">Download log</a>
        <?php endif;?>
</div>
<?php $this->load->view('admin/footer') ?>