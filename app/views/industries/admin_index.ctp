<h2 class="section">Service Settings</h2>

<?php
	echo 
	$form->create('Industry', array('action' => 'admin_mass_update'));
?>

<?php echo $this->element('admin_select_actions'); ?>

<ul class="list">
    <?php foreach ($industries as $industry): ?>
        <li class="post-row actions-handle">
            <span class="row-check"><?php echo $form->checkbox('id.' . $industry['Industry']['id']) ?></span>
            <span class="title-row"><?php echo $html->link($industry['Industry']['name'], array('action' => 'admin_edit', $industry['Industry']['id']), array('title' => __('Edit this setting.', true))) ?></span>
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
            '<span>' . __('Create a new service', true) . '</span>', 
            array('action' => 'admin_add'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
<?php $partialLayout->blockEnd(); ?>