<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file content_template.php
 * @author ps
 */
defined('MYEXEC') or die('no acceess to this ressource');
?>
<fieldset>
	<legend>
		<h1>III - MIGRATE CONTENT</h1>
	</legend>
	<h2>Press button to start migration</h2>
	<form id="content-migration" name="content-migration" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="submit" value="START CONTENT MIGRATION"/>
		<input type="hidden" name="task" value="write_jf_content_to_content"/>
	</form>
</fieldset>