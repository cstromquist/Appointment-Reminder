<?php if (isset($results) && !empty($results)): ?>
<ul id="sidebar-search-results">
<?php
	foreach ($results as $item) {
		$url = array('controller' => 'companies', 'action' => 'admin_edit', $item['Company']['id']);
		echo '<li>' . $html->link($item['Company']['name'], $url) . '</li>';
	}
?>
</ul>
<?php else: ?>
    <div id="sidebar-search-results" class="nomatch">No matches</div>
<?php endif; ?>
