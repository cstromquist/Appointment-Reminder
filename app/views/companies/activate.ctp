	<div id="register"> 
        <h1 class="success">Activation</h1>
        <p><?php echo $message ?></p>
        <?php if($flag): ?>
        <p><a href="<?php echo $this->base ?>/users/login">Click here to login.</a></p>
        <?php endif; ?>
    </div>