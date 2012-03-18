    <?php if (isset($sidebar_for_layout)): ?>
    <div id="sidebar">
        <ul>
            <?php echo $sidebar_for_layout; ?>
        </ul>
    </div>
    <?php endif; ?>

	<?php if (isset($form_for_layout)) echo '</form>'; ?>
        
    <div class="cleaner"></div>
</div>

</body>
</html>