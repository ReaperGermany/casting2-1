<?php $this->load->view('admin/header')?>
<h2 class="top_title">Edit Attributes</h2>
<div class="content_2col">
<div class="left">
    <ul id="left_nav">
        <?php foreach($av_model->getCodesList() as $code):?>
            <li onclick="load_attr(this,'<?php echo $code?>')"><?php echo $av_model->getLabel($code)?></li>
        <?php endforeach;?>
    </ul>
</div>
<div id="attribute_edit_container">
    <div id="messages"></div>
    <form id="attribute_edit">
        <input type="hidden" name="id" id="attr_id"/>
        <input type="hidden" name="code" id="attr_code" value=""/>
		<input type="hidden" name="image" id="attr_image" value=""/>
        <input type="text" class="text" name="value" id="attr_value"/>
    </form>
</div>
<div id="model_edit_container">
    <div id="messages"></div>
    <form id="model_edit">
        <table>
            <tr>
                <td>
                    <input type="hidden" name="id" id="model_id"/>
                    <input type="hidden" name="code" id="model_code" value=""/>
                    <label>Model</label>
                </td>
                <td>
                    <input type="text" class="text" name="value" id="model_value"/><br />
                </td>
            </tr>
            <tr>
                <td>
                    <label>Manufacturers</label>
                </td>
                <td>
                    <select name="model_manufacturers[]" id="model_manufacturers" multiple size="5"></select><br />
                </td>
            </tr>
            <tr>
                <td><label>Colors</label></td>
                <td><select name="model_colors[]" id="model_colors" multiple size="5"></select><br /></td>
            </tr>
            <tr>
                <td><label>Specs</label></td>
                <td><select name="model_specs[]" id="model_specs" multiple size="5"></select><br /></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo base_url()?>skin/js/attribute.js"></script>
<?php $this->load->view('admin/attributes/container')?>
<div class="clear"></div>
</div>
<?php $this->load->view('admin/footer')?>