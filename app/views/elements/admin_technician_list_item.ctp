<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $data['Technician']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'technician-' . $data['Technician']['id']);
    
        echo $html->link($data['Technician']['name'], array('action' => 'edit', $data['Technician']['id']), array('title' => 'Edit this technician.')); 
    ?>
    <span class="row-actions"><?php echo $html->link('View', $data['Technician']['name'], array('class' => '', 'rel' => 'permalink', 'title' => 'View this technician.')) ?></span>
    <span class="cleaner"></span>
</div>