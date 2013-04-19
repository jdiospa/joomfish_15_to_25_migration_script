<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file db_template.php
 * @author ps
 */
defined('MYEXEC') or die('no acceess to this ressource');
?>
<form id="db-data" name="db-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>
			<h1>I - BASIC DB CONNECTION DATA</h1>
		</legend>
		<p>
			<label for="host">Host, e.g.localhost:</label>
			<input type="text" name="host" value="mysql.local.host"/>
		</p>
		<p>
			<label for="database">Database-Name:</label>
			<input type="text" name="database" value="database_name"/>
		</p>
		<p>
			<label for="username">Database-User:</label>
			<input type="text" name="username" value="database_username"/>
		</p>
		<p>
			<label for="password">Database-Password:</label>
			<input type="text" name="password" value="data_base_password"/>
		</p>

		<p>
			<label for="db-prefix-before">Database-Prefix-old:</label>
			<input type="text" name="db-prefix-before" value="pfx_old_"/>
		</p>
		<p>
			<label for="db-prefix-new">Database-Prefix-new:</label>
			<input type="text" name="db-prefix-new" value="pfx_"/>
		</p>
		<p>
			<label for="db-prefix-new">Module Type:</label>
			<input type="text" name="module_type" value="mod_custom"/>
		</p>
		<p>
			<label for="db-prefix-new">Module Position:</label>
			<input type="text" name="module_position" value="hidden"/>
		</p>
		<input id="submit-conn-data" type="submit" value="SET DATABASE CONNECTION DATA"/>
		<input type="hidden" name="PATH_CONFIG" value="<?php echo PATH_CONFIG?>"/>
		<input type="hidden" name="table" value="jf_content"/>
		<input type="hidden" name="table_content" value="content"/>
	</fieldset>
</form>