<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>skin/css/main.css" media="all">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>skin/css/ui.css" media="all">
        <script type="text/javascript">
            var base_url = '<?php echo base_url()?>';
        </script>
        <script type="text/javascript" src="<?php echo base_url()?>skin/js/functions.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>skin/js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>skin/js/jquery-ui-1.8.7.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>skin/js/brocker.js"></script>
    </head>
    <body>
        <div id="main">
            <div id="content">
            <div class="top">
            	<h1 id="login"><a href="<?php echo base_url()?>"></a></h1>
            </div>
<?php $this->load->view('admin/topnav')?>