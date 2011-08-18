<h2 class="section">Technicians</h2>
<?php 

    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<?php if (empty($technicians)): ?>
    <div class="first_item">
	<?php 
        $url = '/' . Configure::read('Routing.admin') . '/technicians/create';
        echo $html->link("CREATE YOUR FIRST TECHNICIAN", $url, array('escape' => false, 'class' => 'add')); 
    ?>
    </div>
<?php else: ?>
    
<?php foreach($technicians as $tech): ?>
			<div class="technician_column">
				<div class="photo">
				<?php if($tech['Technician']['image_path'] != "") { echo $html->image('uploads/technicians/'.$tech['Technician']['image_path'] .'?' . time(), array('width' => '75')); } ?>
				</div>
				<div class="info">
					<h4><?php echo $tech['Technician']['name'] ?></h4>
					<?php echo $html->link('Edit', '/' . Configure::read('Routing.admin') . '/technicians/edit/' . $tech['Technician']['id'], array('title' => 'Edit this technician.')); ?>
					<p><?php echo $tech['Technician']['bio'] ?></p> 
				</div>
			</div>
			<?php endforeach; ?>
			<div class="clear"></div>
<?php endif; ?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php
		    echo
		    $form->create('Technician', array('url' => $html->url(array('action' => 'admin_search', 'base' => false)), 'class' => 'search')),
		    $form->input('query', array('label' => __('Find a technician by typing', true), 'id' => 'SearchQuery')),
		    $form->end();
		?>
    </li>
    <li>
        <?php echo $html->link(
            '<span>' . __('Create a new technician', true) . '</span>', 
            array('action' => 'admin_create'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
<?php $partialLayout->blockEnd(); ?>