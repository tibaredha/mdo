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
<li>Database connection</li>
<li><b>Import SQL</b></li>
<li>Done</li>
</ol>
</div>
<h3>Importing SQL</h3>

<p>Now we are importing the needed data into our database...</p>
<hr>

<?php
 function PMA_splitSqlFile(&$ret, $sql)
{
    // do not trim, see bug #1030644
    //$sql          = trim($sql);
    $sql          = rtrim($sql, "\n\r");
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = FALSE;
    $nothing      = TRUE;
    $time0        = time();

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = array('query' => $sql, 'empty' => $nothing);
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // lets skip comments (/*, -- and #)
        else if (($char == '-' && $sql_len > $i + 2 && $sql[$i + 1] == '-' && $sql[$i + 2] <= ' ') || $char == '#' || ($char == '/' && $sql_len > $i + 1 && $sql[$i + 1] == '*')) {
            $i = strpos($sql, $char == '/' ? '*/' : "\n", $i);
            // didn't we hit end of string?
            if ($i === FALSE) {
                break;
            }
            if ($char == '/') $i++;
        }

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
            $nothing    = TRUE;
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        } // end else if (is delimiter)

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $nothing      = FALSE;
            $string_start = $char;
        } // end else if (is start of string)

        elseif ($nothing) {
            $nothing = FALSE;
        }

        // loic1: send a fake header each 30 sec. to bypass browser timeout
        $time1     = time();
        if ($time1 >= $time0 + 30) {
            $time0 = $time1;
            header('X-pmaPing: Pong');
        } // end if
    } // end for

    // add any rest to the returned array
    if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql)) {
        $ret[] = array('query' => $sql, 'empty' => $nothing);
    }

    return TRUE;
} 
    $errors = array();
	$goToNextStep = false;

	$host = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'deces';
	
	
	
	// $host = $_SESSION['db_host'];
	// $username = $_SESSION['db_user'];
	// $password = $_SESSION['db_pass'];
	// $database = $_SESSION['db_name'];

	// connect to db
	$con = mysql_connect($host, $username, $password);
	mysql_select_db($database, $con);
	
	// read import sql
	
	$import = file_get_contents(URL."views\setup\import.sql");
	$queries = array();
	PMA_splitSqlFile($queries, $import);
	
	foreach ($queries as $query)
	{
		if (!mysql_query($query['query']))
		{
			$errors[] = "<b>".mysql_error()."</b><br>(".substr($query['query'], 0, 200)."...)";
		}
	}
   
	




if (count($errors) > 0) { 



?>
	<div class="error">Some errors occured while importing the SQL data!</div>
	
	<ul>
		<?php foreach ($errors as $error): ?>
			<li><?php echo $error; ?></li>
		<?php endforeach; ?>
	</ul>
<?php } else { ?>
	<div class="success">Data import succeeded!</div>
<?php } ?>

<hr>

		

<?php if (count($errors) == 0) { ?>
	<form action="<?php echo URL;?>setup/step6"   method="post">
		<a href="<?php echo URL;?>setup/" id="Cancel"><img src="<?php echo URL;?>public/images/icons/cross.png" alt=""/> Cancel</a>	

		<input type="hidden" name="nextStep" value="done">
		<button type="submit" class="button positive">
			<img src="<?php echo URL;?>public/images/icons/tick.png" alt=""/> Next
		</button>
	</form>
<?php } else { ?>
	<form action="<?php echo URL;?>setup/step5"  method="post">
		<a href="<?php echo URL;?>setup/" id="Cancel"><img src="<?php echo URL;?>public/images/icons/cross.png" alt=""/> Cancel</a>	

		<input type="hidden" name="nextStep" value="importSQL">
		<button type="submit" class="button positive">
			<img src="<?php echo URL;?>public/images/icons/tick.png" alt=""/> Retry
		</button>
	</form>
<?php } ?>

</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
