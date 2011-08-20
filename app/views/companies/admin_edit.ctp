<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
	if ($messages = $session->read('Message.multiFlash')) {
		foreach($messages as $k=>$v) $session->flash('multiFlash.'.$k);
	}
?>
<div id="company_details">
	<div class="company_info">
<?php
    echo 
    $form->create('Company', array('action' => 'admin_update', 'class' => 'editor_form')),
    $form->input('name', array('between' => '', 'label' => 'Company name')),
    $form->input('phone', array('between' => '', 'label' => 'Company phone')),
    $form->input('website_url', array('between' => '', 'label' => 'Company website')),
    $form->input('payment_methods', array('multiple' => 'checkbox', 'type' => 'select', 'label' => 'Company payment method', 'options' => $options, 'selected' => $selected));
    if($isAdmin) {
    	echo 
    	$form->input('status', array('label' => 'Company status')),
		$form->input('expire_date', array('label' => 'Subscription Expiration Date'));
	}
	echo $form->hidden('id');
	?>
	<br />
	<?php
	$options = array(
    	'label' => 'Save',
    	'name' => 'Save',
    	'div' => array(
        	'class' => 'wf-form-button',
    		)
		);
	echo $form->end($options);
?> <span>or</span> <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel')); ?>
	</div>
</div>

<span class="cleaner"></span>

<?php $partialLayout->blockStart('sidebar'); ?>
	<div class="company_logo">
	<?php	
		if($this->data['Company']['logo_path']) {
			echo $html->image('uploads/companies/logos/big/'.$this->data['Company']['logo_path']);
		} else {
			echo $html->image('company.png');
		}
	?>
	<br />
	<?php	
		echo $html->link(__('Change Logo', true), array('action' => 'change_logo/' . $this->data['Company']['id'])); 
	?>
	</div>
	<br />
    <div class="company_photo">
	<?php	
		if($this->data['Company']['photo_path']) {
			echo $html->image('uploads/companies/photos/big/'.$this->data['Company']['photo_path'], array('width' => 250));
		} else {
			echo $html->image('company.png');
		}
	?>
	<br />
	<?php	
		echo $html->link(__('Change Company Photo', true), array('action' => 'change_photo', $this->data['Company']['id'])); 
	?>
	</div>
<?php $partialLayout->blockEnd(); ?>
