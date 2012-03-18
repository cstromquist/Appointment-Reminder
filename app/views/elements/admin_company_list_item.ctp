<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $data['Company']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'company-' . $data['Company']['id']);
    
        echo $html->link($data['Company']['name'], array('action' => 'edit', $data['Company']['id']), array('title' => 'Edit this company.')); 
    ?>
    <span class="row-actions"><?php echo $html->link('View', $data['Company']['name'], array('class' => '', 'rel' => 'permalink', 'title' => 'View this company.')) ?></span>
    <span class="cleaner"></span>
</div>