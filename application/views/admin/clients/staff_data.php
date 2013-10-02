<div id="staff_data" class="left">
    
    <table>
        <tr>
            <td>Name</td>
            <td><?php echo $staff->getData('email')?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo $staff->getData('appeal')?></td>
        </tr>
        <tr>
            <td>Skype</td>
            <td><?php echo $staff->getData('skype')?></td>
        </tr>
        <tr>
            <td>MSN</td>
            <td><?php echo $staff->getData('msn')?></td>
        </tr>
        <tr>
            <td>Yahoo</td>
            <td><?php echo $staff->getData('jahoo')?></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><?php echo $staff->getData('phone')?></td>
        </tr>
        <tr>
            <td>Cell</td>
            <td><?php echo $staff->getData('cell')?></td>
        </tr>
        <tr>
            <td>BBM</td>
            <td><?php echo $staff->getData('bbm')?></td>
        </tr>
        <tr>
            <td>AIM</td>
            <td><?php echo $staff->getData('aim')?></td>
        </tr>
        <tr>
            <td>ICQ</td>
            <td><?php echo $staff->getData('icq')?></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" value="Edit" onclick='staff_edit(<?php echo $staff->getId();?>,<?php echo json_encode($staff->getData());?>)'/>
                <input type="button" value="Delete" onclick="staff_delete(<?php echo $staff->getId();?>)"/>
            </td>
        </tr>
    </table>    
</div>
