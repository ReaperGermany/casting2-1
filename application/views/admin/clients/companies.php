<ul id="left_nav" class="menu">
    <?php
        if (isset($session)) {
            $current_company = $session->userdata('company_id');
        }
        else {
            $current_company = null;
        }
     ?>
    <?php foreach($companies as $company):?>
        <li id="company_<?php echo $company->getId()?>" class="<?php echo $current_company == $company->getId()?'active':'';?>"
            onclick="load_staff_list(this,<?php echo $company->getId()?>)"
        >
            <input type="button" value="Del" onclick="delete_company(<?php echo $company->getId()?>)">
            <input type="button" value="Edit" onclick='edit_company(<?php echo $company->getId()?>,<?php echo json_encode($company->getData())?>)'>
            <?php echo $company->getData('name')?>
        </li>
    <?php endforeach;?>
</ul>