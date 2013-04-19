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
		<h1>IV - MIGRATE MENUS</h1>
	</legend>
	<h2>Press button to start migration</h2>
	<form id="migrate-menus" name="migrate-menus" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="submit" value="START MENU MIGRATION"/>
		<input type="hidden" name="task" value="write_jf_menus_to_menus"/>
	</form>
</fieldset>