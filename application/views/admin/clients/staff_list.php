<div class="left" id="staff_list_container">
    <input type="button" value="New Staff" onclick="staff_add()"/>
    <ul id="staff_list" class="menu">
        <?php foreach($staff_list as $staff):?>
            <li onclick="load_staff_data(this,<?php echo $staff->getId()?>)"><?php echo $staff->getData('email')?></li>
        <?php endforeach;?>
    </ul>
</div>