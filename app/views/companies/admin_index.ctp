<h2 class="section">Companies</h2>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<?php if (empty($companies)): ?>
    <p>No companies available yet.</p>
<?php else: ?>
	<div id="companies">
		<?php foreach($companies as $company): ?>
			<div class="company">
			<h3><?php echo $company['Company']['name']; ?></h3>
			<div class="button_add_new">
				<?php echo $html->link(
            		'<span>' . __('Add a new technician', true) . '</span>', 
            		'/' . Configure::read('Routing.admin') . '/technicians/create/' . $company['Company']['id'],
            		array('class' => 'add', 'escape' => false)) ?>
			</div>
			<div class="technician_column">
				<div class="photo">
				<?php	
					//if($company['Company']['logo_path']) {
					//	echo $html->image('uploads/companies/logos/small/'.$company['Company']['logo_path']);
					//} else {
						echo $html->image('company.png');
					//}
				?>
				</div>
				<div class="info">
					<h4><?php echo $company['Company']['name']; ?></h4>
					<?php if($company['Company']['phone'] != ""): ?><span>P: <?php echo $company['Company']['phone']; ?></span><br /><?php endif; ?>
					<?php if($company['Company']['website_url'] != ""): ?><span>W: <a href="http://<?php echo $company['Company']['website_url']; ?>" target="_blank"><?php echo $company['Company']['website_url']; ?></a></span><?php endif; ?>
					<div class="company-edit"><?php echo $html->link('Edit', '/' . Configure::read('Routing.admin') . '/companies/edit/' . $company['Company']['id'], array('title' => 'Edit this company.')); ?> this company</div>
					<div class="company-delete"><?php echo $html->link('Delete', '/' . Configure::read('Routing.admin') . '/companies/delete/' . $company['Company']['id'], array('title' => 'Delete this company.')); ?> this company</div>
				</div>
			</div>
			<?php foreach($company['Technician'] as $tech): ?>
			<div class="technician_column">
				<div class="photo">
				<?php if($tech['image_path'] != "") { echo $html->image('uploads/technicians/'.$tech['image_path'] .'?' . time(), array('width' => '75')); } ?>
				</div>
				<div class="info">
					<h4><?php echo $tech['name'] ?></h4>
					<?php echo $html->link('Edit', '/' . Configure::read('Routing.admin') . '/technicians/edit/' . $tech['id'], array('title' => 'Edit this technician.')); ?> 
				</div>
			</div>
			<?php endforeach; ?>
			<div class="clear"></div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php
		    echo
		    $form->create('Company', array('url' => $html->url(array('action' => 'admin_search', 'base' => false)), 'class' => 'search')),
		    $form->input('query', array('label' => __('Find a company by typing', true), 'id' => 'SearchQuery')),
		    $form->end();
		?>
    </li>
    <li>
        <?php echo $html->link(
            '<span>' . __('Create a new company', true) . '</span>', 
            array('action' => 'admin_create'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
<?php $partialLayout->blockEnd(); ?>