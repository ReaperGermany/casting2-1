<fieldset>
    <input type="hidden" name="staff_id" id="staff_id" />
    <input id="company_id" type="hidden" name="company_id" value="" />
    <table>
        <tr>
            <td>Company</td>
            <td><input id="email" type="text" name="email" value="<?php echo $staff->getData('email')?>"/></td>
        </tr>		
		<tr  >
            <td>Email</td>
            <td><input id="appeal" type="text" name="appeal" value="<?php echo $staff->getData('appeal')?>" /></td>
        </tr>
        <tr  >
            <td>Skype</td>
            <td><input id="skype" type="text" name="skype" value="<?php echo $staff->getData('skype')?>"/></td>
        </tr>
        <tr  >
            <td>MSN</td>
            <td><input id="msn" type="text" name="msn" value="<?php echo $staff->getData('msn')?>"/></td>
        </tr>
        <tr  >
            <td>Jahoo</td>
            <td><input id="jahoo" type="text" name="jahoo" value="<?php echo $staff->getData('jahoo')?>"/></td>
        </tr>
        <tr  >
            <td>Phone</td>
            <td><input id="phone" type="text" name="phone" value="<?php echo $staff->getData('phone')?>"/></td>
        </tr>
        <tr  >
            <td>Cell</td>
            <td><input id="cell" type="text" name="cell" value="<?php echo $staff->getData('cell')?>"/></td>
        </tr>
        <tr  >
            <td>BBM</td>
            <td><input id="bbm" type="text" name="bbm" value="<?php echo $staff->getData('bbm')?>"/></td>
        </tr>
        <tr  >
            <td>AIM</td>
            <td><input id="aim" type="text" name="aim" value="<?php echo $staff->getData('aim')?>"/></td>
        </tr>
        <tr  >
            <td>ICQ</td>
            <td><input id="icq" type="text" name="icq" value="<?php echo $staff->getData('icq')?>"/></td>
        </tr>
    </table>
</fieldset>

