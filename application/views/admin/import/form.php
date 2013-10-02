<?php $this->load->view('admin/header') ?>
<div><h2 class="top_title">Import</h2></div>
    <div>
        <form method="POST" class="import" action="<?php echo site_url('admin/offers/import')?>" enctype="multipart/form-data">
            <label for="match_value">File</label>
            <input type="file" name="import_file" id="import_file"/>
            <input type="submit" class="btn" value="Submit" name="Match"/>
        </form>
    </div>

<?php $this->load->view('admin/footer') ?>