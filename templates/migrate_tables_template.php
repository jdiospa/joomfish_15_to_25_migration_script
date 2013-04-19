<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file migrate_tables_template.php
 * @author ps
 */
defined('MYEXEC') or die('no acceess to this ressource');
?>
<form id="table-migrate" name="db-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>
			<h1>II - MIGRATE old jos_jf_content and jos_jf_tableinfo to new prefix e.g. j25_</h1>
		</legend>
		<h2>Press button to start table migration</h2>
	<form name="form-1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input id="submit-tables" type="submit" value="START TABLE MIGRATION"/>
		<input type="hidden" name="task" value="migrate_joomfish_tables"/>
	</form>
	</fieldset>
</form>