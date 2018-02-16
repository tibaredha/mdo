<h1>Installation steps</h1><hr><br/>
<fieldset id="fieldset0">
        <legend>***</legend>
		<?php
		HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');
		?>
		</fieldset>
<div>  
<ol>
<li>Introduction</li>
<li>EULA</li>
<li>Server requirements</li>
<li>File permissions</li>
<li><b>Database connection</b></li>
<li>Import SQL</li>
<li>Done</li>
</ol>
</div>

<h3>Database connection</h3>

<p>
	We need some information on the database. In all likelihood, these items were supplied to you by your Web Host.</br> If you do not have this information, then you will need to contact them before you can continue.<br><br>
	Below you should enter your database connection details.
</p>
<hr>

<?php 

    $error = false;
	$goToNextStep = false;
	
	if (isset($_POST['database']))
	{
		$database = $_POST['database'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$host = $_POST['host'];
		
		// check connection
		$connection = @mysql_connect($host, $username, $password);
		if ($connection)
		{
			$error = !mysql_select_db($database, $connection);
			@mysql_close($connestion);
			
			if (!$error)
			{
				// save settings in database config file
				// load template
				// $template = file_get_contents("config/database_template.php");
				// $template = str_replace("%%host%%", $host, $template);
				// $template = str_replace("%%username%%", $username, $template);
				// $template = str_replace("%%password%%", $password, $template);
				// $template = str_replace("%%database%%", $database, $template);
				
				// write config file
				// $dbFile = dirname(getenv('SCRIPT_FILENAME'))."/".$config['applicationPath'].$config['database_file'];
				// file_put_contents($dbFile, $template);
				
				// save login in session for further use
				// $_SESSION['db_host'] = $host;
				// $_SESSION['db_user'] = $username;
				// $_SESSION['db_pass'] = $password;
				// $_SESSION['db_name'] = $database;
				
				// allow user to proceed
				$goToNextStep = true;
			}
			else $error = mysql_error();
		}
		else
			$error = mysql_error();
	}
	else
	{
		if (isset($_SESSION['db_host']))
		{
			$host = $_SESSION['db_host'];
			$username = $_SESSION['db_user'];
			$password = $_SESSION['db_pass'];
			$database = $_SESSION['db_name'];
		}
		else
		{
			$database = "deces";
			$username = "root";
			$password = "";
			$host = "localhost";
		}
	}

if ($error) {
 ?>
	<div class="error">
		<b>Error establishing a database connection: <?php echo $error; ?></b><br><br>
		This either means that the username and password information is incorrect or we can't contact the database server at <?php echo $host; ?>. Maybe your host's database server is down.<br><br>
		
		<ul>
			<li>Are you sure you have the correct username and password?</li>
    		<li>Are you sure that you have typed the correct hostname?</li>
    		<li>Are you sure that the database server is running?</li>
		</ul>
		
		If you're unsure what these terms mean you should probably contact your host. 
	</div>
<?php } ?>

<form action="<?php echo URL;?>setup/step5" method="post">
	<p>
		<label>Database name </label> 
		<input class="title" type="text" name="database" value="<?php echo $database; ?>">
	</p>
	<p>
		<label>Username</label> 
		<input class="title" type="text" name="username" value="<?php echo $username; ?>">
	</p>
	<p>
		<label>Password</label> 
		<input class="title" type="password" name="password" value="<?php echo $password; ?>">
	</p>
	<p>
		<label>Host</label> 
		<input class="title" type="text" name="host" value="<?php echo $host; ?>">
	</p>
	
	<hr>
	
	<?php if ($goToNextStep) { ?>
		<div class="success">Everything is ok! Go to next step...</div>

		<a href="<?php echo URL;?>setup/" id="Cancel"><img src="<?php echo URL;?>public/images/cross.png" alt=""/> Cancel</a>	
		
		<input type="hidden" name="nextStep" value="importSQL">
		<button type="submit" class="button positive">
			<img src="<?php echo URL;?>public/images/tick.png" alt=""/> Next
		</button>
	<?php } else { ?>
		<a href="<?php echo URL;?>setup/" id="Cancel"><img src="<?php echo URL;?>public/images/cross.png" alt=""/> Cancel</a>
		
		<input type="hidden" name="nextStep" value="database">
		<button type="submit" class="button positive">
			<img src="<?php echo URL;?>public/images/icons/tick.png" alt=""/> Test connection
		</button>
	<?php } ?>
</form>
</br></br></br></br>

