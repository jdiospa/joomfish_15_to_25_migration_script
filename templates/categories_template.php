<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file menu_template.php
 * @author ps
 */
defined('MYEXEC') or die('no acceess to this ressource');
?>
<fieldset>
	<legend>
		<h1>IV - MIGRATE CATEGORIES</h1>
	</legend>
	<h2>Press button to start migration</h2>
	<form id="migrate-categories" name="migrate-categories" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="submit" value="START CATEGORIES MIGRATION"/>
		<input type="hidden" name="task" value="write_jf_categories_to_categories"/>
	</form>
</fieldset>