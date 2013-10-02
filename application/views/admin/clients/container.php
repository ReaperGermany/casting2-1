<?php $this->load->view('admin/header')?>
<div><h2 class="top_title">Edit Vendors</h2></div>
<div class="left" id="company_list_container">
    <!--input type="button" value="New Vendor" onclick="add_company()"/-->
    <div id="company_edit_container">
        <form id="company_edit">
            <label for="company_name">Name</label>
            <input type="text" class="text" name="value" id="company_name"/>
            <input type="hidden" name="id" id="company_id"/>
        </form>
    </div>
    <?php if (isset($companies)): ?>
        <?php //$this->load->view('admin/clients/companies', array('companies'=>$companies))?>
    <?php endif;?>
</div>
<div class="left" id="staff_list_container"></div>
<div id="staff_data"></div>
<div id="staff_edit_container">
    <form id="staff_edit">
        <fieldset>
            <input type="hidden" name="staff_id" id="staff_id" />
            <input id="staff_company_id" type="hidden" name="company_id" value="" />
            <table>
                <tr>
                    <td>Name</td>
                    <td><input class="clearable text" id="email" type="text" name="email" /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input class="clearable text" id="appeal" type="text" name="appeal" /></td>
                </tr>
                <tr>
                    <td>Skype</td>
                    <td><input class="clearable text" id="skype" type="text" name="skype" /></td>
                </tr>
                <tr>
                    <td>MSN</td>
                    <td><input class="clearable text" id="msn" type="text" name="msn" /></td>
                </tr>
                <tr>
                    <td>Yahoo</td>
                    <td><input class="clearable text" id="jahoo" type="text" name="jahoo" /></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><input class="clearable text" id="phone" type="text" name="phone" /></td>
                </tr>
                <tr>
                    <td>Cell</td>
                    <td><input class="clearable text" id="cell" type="text" name="cell" /></td>
                </tr>
                <tr>
                    <td>BBM</td>
                    <td><input class="clearable text" id="bbm" type="text" name="bbm" /></td>
                </tr>
                <tr>
                    <td>AIM</td>
                    <td><input class="clearable text" id="aim" type="text" name="aim" /></td>
                </tr>
                <tr>
                    <td>ICQ</td>
                    <td><input class="clearable text" id="icq" type="text" name="icq" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<script type="text/javascript" src="<?php echo base_url()?>skin/js/clients.js"></script>
<?php $this->load->view('admin/footer')?>