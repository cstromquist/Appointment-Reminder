<?php if (isset($results) && !empty($results)): ?>
<ul id="sidebar-search-results">
<?php
	foreach ($results as $item) {
		$model = $controller = '';
		if (isset($item['Technician']) && isset($item['Technician']['name'])) {
			$model = 'Technician';
			$controller = 'technicians';
			$action = 'admin_edit';
		} else if (isset($item['Company']) && isset($item['Company']['name'])) {
			$model = 'Company';
			$controller = 'companies';
			$action = 'admin_edit';
		} else if (isset($item['Reminder']) && isset($item['Reminder']['fname'])) {
			$model = 'Reminder';
			$controller = 'reminders';
			$item['Reminder']['name'] = $item['Reminder']['fname'] . " " . $item['Reminder']['lname'];
			$action = 'admin_email_details';
		} else {
			continue;
		}
		
		$url = array('controller' => $controller, 'action' => $action, $item[$model]['id']);
		echo '<li>' . $html->link($item[$model]['name'] . ' - ' . $model, $url) . '</li>';
	}
?>
</ul>
<?php else: ?>
    <div id="sidebar-search-results" class="nomatch">No matches</div>
<?php endif; ?>
