<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file module_template.php
 * @author ps
 */
defined('MYEXEC') or die('no acceess to this ressource');
?>
<fieldset>
	<legend>
		<h1>IV - MIGRATE MODULES</h1>
	</legend>
	<h2>Press button to start migration</h2>
	<form id="migrate-modules" name="migrate-modules" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="submit" value="START MODULES MIGRATION"/>
		<input type="hidden" name="task" value="write_jf_modules_to_modules"/>
	</form>
</fieldset>