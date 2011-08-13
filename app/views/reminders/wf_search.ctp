<?php if (isset($results) && !empty($results)): ?>
<ul id="sidebar-search-results">
<?php
	foreach ($results as $item) {
		$url = array('controller' => 'reminders', 'action' => 'email_details', $item['Reminder']['id']);
		echo '<li>' . $html->link($item['Reminder']['fname'] . " " . $item['Reminder']['lname'], $url) . '</li>';
	}
?>
</ul>
<?php else: ?>
    <div id="sidebar-search-results" class="nomatch">No matches</div>
<?php endif; ?>
