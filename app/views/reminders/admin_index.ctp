<h2 class="section">Appointment Reminders</h2>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<?php if(!count($reminders)): ?>
	<div class="first_item">
	<?php 
        $url = '/' . Configure::read('Routing.admin') . '/reminders/init';
        echo $html->link("CREATE YOUR FIRST REMINDER", $url, array('escape' => false)); 
    ?>
    </div>
<?php else: ?>
<?php
	echo 
    $form->create('Reminder', array('action' => 'mass_update'));
?>
<?php echo $this->element('admin_select_actions'); ?>

<ul class="list">
    <?php foreach ($reminders as $reminder): ?>
        <li class="post-row actions-handle">
            <span class="row-check"><?php echo $form->checkbox('id.' . $reminder['Reminder']['id']) ?></span>
            <span class="title-row"><?php echo $html->link($reminder['Reminder']['fname'] . " " . $reminder['Reminder']['lname'], array('action' => 'email_details', $reminder['Reminder']['id']), array('title' => __('Edit this Reminder account.', true))) ?></span>
            <span class="cleaner"></span>
        </li>
    <?php endforeach; ?>
</ul>

<?php
    echo
    $this->element('admin_select_actions'),
    $form->end();
?>

<?php endif; ?>



<?php $partialLayout->blockStart('sidebar'); ?>
	<li>
        <?php
            echo
            $form->create('Reminders', array('url' => $html->url(array('action' => 'admin_search', 'base' => false)), 'class' => 'search')),
            $form->input('query', array('label' => __('Find an appointment reminder', true), 'id' => 'SearchQuery')),
            $form->end();
        ?>
    </li>
    <li>
        <?php echo $html->link(
            '<span>' . __('Create a new reminder', true) . '</span>', 
            array('action' => 'admin_init'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
<?php $partialLayout->blockEnd(); ?>