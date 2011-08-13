<?php if (isset($results) && !empty($results)): ?>
<ul id="sidebar-search-results">
<?php
	foreach ($results as $item) {
		$url = array('controller' => 'technicians', 'action' => 'admin_edit', $item['Technician']['id']);
		echo '<li>' . $html->link($item['Technician']['name'], $url) . '</li>';
	}
?>
</ul>
<?php else: ?>
    <div id="sidebar-search-results" class="nomatch">No matches</div>
<?php endif; ?>
