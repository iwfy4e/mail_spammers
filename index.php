<?php
	error_reporting(E_ALL^E_NOTICE);
	ini_set("display_errors", "on");
	include_once('run.php');
?>
<!DOCTYPE html>
<html>
	<body>
		<div>
			Calling python code
			<?php
				proc_exec('python sendmails.py');
			?>
		</div>
	</body>
</html>