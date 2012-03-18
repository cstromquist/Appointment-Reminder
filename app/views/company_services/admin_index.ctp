<?php
	echo 
    $form->create('CompanyService', array('action' => 'mass_update'));
?>
<h2 class="section">Company Services</h2>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<?php echo $this->element('admin_select_actions'); ?>

<ul class="list">
    <?php foreach ($companyservices as $company_service): ?>
        <li class="post-row actions-handle">
            <span class="row-check"><?php echo $form->checkbox('id.' . $company_service['CompanyService']['id']) ?></span>
            <span class="title-row"><?php echo $html->link($company_service['CompanyService']['name'], array('action' => 'edit', $company_service['CompanyService']['id']), array('title' => __('Edit this Service.', true))) ?></span>
            <span class="cleaner"></span>
        </li>
    <?php endforeach; ?>
</ul>

<?php
    echo
    $this->element('admin_select_actions'), 
	//$this->element('admin_pagination'),
    $form->end();
?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $html->link(
            '<span>' . __('Add a new service', true) . '</span>', 
            array('action' => 'admin_add'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
<?php $partialLayout->blockEnd(); ?>