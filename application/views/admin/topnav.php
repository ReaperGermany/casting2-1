<?php
if ($user = Core::registry("current_user")) {
    $role = $user->getRole();
}
else {
    $role = Core::getModel("role");
}
?>

<ul class="topnav">
    <li><a href="<?php echo site_url("admin/offers"); ?>">Offers list</a></li>
    <?php if ($role->getData('code') == 'admin'):?>
        <li><a href="<?php echo site_url("admin/attributes"); ?>">Edit attributes</a></li>
        <li><a href="<?php echo site_url("admin/clients"); ?>">Edit Vendors</a></li>
		<li><a href="<?php echo site_url("admin/manager"); ?>">User Controls</a></li>
        <li><a href="<?php echo site_url("admin/offers/importForm"); ?>">Import</a></li>
        <li><a href="<?php echo site_url("admin/currencies/edit"); ?>">Currency Rates</a></li>
		<li><a href="<?php echo site_url("admin/visible"); ?>">Product Controls</a></li>
		<li><a href="<?php echo site_url("admin/cron2"); ?>">Subscribe</a></li>
		<li><a href="<?php echo site_url("admin/user/logout"); ?>">Log Out</a></li>
    <?php endif;?>
</ul>