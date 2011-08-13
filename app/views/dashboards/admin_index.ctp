<h2 class="section">Welcome to the <?php echo $siteName ?> administration</h2>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>

<?php
    $labels = array(
        'Company' => array(
            'name' => __('Company', true),
            'link' => '/companies/edit/:id',
            'class' => 'company'
        ),
        'Technician' => array(
            'name' => __('Technician', true),
            'link' => '/technicians/edit/:id',
            'class' => 'technician'
        ),
        'Reminder' => array(
            'name' => __('Reminder', true),
            'link' => '/reminders/email_details/:id',
            'class' => 'reminder'
        )
    );
?>

<?php if (empty($items)): ?>
    <div class="first_item">
	<?php 
        $url = '/' . Configure::read('Routing.admin') . '/technicians/create';
        echo $html->link("CREATE YOUR FIRST TECHNICIAN", $url, array('escape' => false, 'class' => 'add')); 
    ?>
    </div>
<?php else: ?>
	<p><?php __('Recently added or changed information:'); ?></p>
    <table class="recently_changed" cellspacing="0">
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr class="recent_<?php echo $labels[$item['class']]['class']; ?>">
                <th><?php echo $labels[$item['class']]['name']; ?></th>
                <td>
                <?php 
                    $label = empty($item['item']['title']) ? '<em>untitled</em>' : hsc($item['item']['title']);
                    $url = '/' . Configure::read('Routing.admin') . '/' . r(':id', $item['item']['id'], $labels[$item['class']]['link']);
                    echo $html->link($label, $url, array('escape' => false)); 
                ?>
                </td>
                <td class="recent_date"><?php echo $time->niceShort($item['item']['updated']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php
            echo
            $form->create('Dashboard', array('url' => $html->url(array('action' => 'admin_search', 'base' => false)), 'class' => 'search')),
            $form->input('query', array('label' => __('Find a company or technician by typing', true), 'id' => 'SearchQuery')),
            $form->end();
        ?>
    </li>
    <li class="main_sidebar category_sidebar">
        <h4 class="sidebar_heading">User activity</h4>
        <ul>
        <?php foreach ($users as $user): ?>
            <?php
                $userText = __(' logged in ', true) . $time->niceShort($user['User']['last_login']);
                if (empty($user['User']['last_login'])) {
                    $userText = __(' not seen recently.', true);
                }
            ?>
            <li><strong><?php echo $user['User']['name'] ?></strong> <?php echo  $userText; ?></li>
        <?php endforeach; ?>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
