
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
<li><b>File permissions</b></li>
<li>Database connection</li>
<li>Import SQL</li>
<li>Done</li>
</ol>
</div>
<?php 
// include("helper.php");
//****************************
define( "_PL_OS_SEP", "/" );
define( "_CUR_OS", substr( php_uname( ), 0, 7 ) == "Windows" ? "Win" : "_Nix" );

function checkCurrentOS( $_OS )
{
    if ( strcmp( $_OS, _CUR_OS ) == 0 ) {
        return true;
    }
    return false;
}

function isRelative( $_dir )
{
    if ( checkCurrentOS( "Win" ) ) {
        return ( preg_match( "/^\w+:/", $_dir ) <= 0 );
    }
    else {
        return ( preg_match( "/^\//", $_dir ) <= 0 );
    }
}

function unifyPath( $_path )
{
    if ( checkCurrentOS( "Win" ) ) {
        return str_replace( "\\", _PL_OS_SEP, $_path );
    }
    return $_path;
}

function getRealpath( $_path )
{
    /*
     * This is the starting point of the system root.
     * Left empty for UNIX based and Mac.
     * For Windows this is drive letter and semicolon.
     */
    $__path = $_path;
    if ( isRelative( $_path ) ) {
        $__curdir = unifyPath( realpath( "." ) . _PL_OS_SEP );
        $__path = $__curdir . $__path;
    }
    $__startPoint = "";
    if ( checkCurrentOS( "Win" ) ) {
        list( $__startPoint, $__path ) = explode( ":", $__path, 2 );
        $__startPoint .= ":";
    }
    # From now processing is the same for WIndows and Unix, and hopefully for others.
    $__realparts = array( );
    $__parts = explode( _PL_OS_SEP, $__path );
    for ( $i = 0; $i < count( $__parts ); $i++ ) {
        if ( strlen( $__parts[ $i ] ) == 0 || $__parts[ $i ] == "." ) {
            continue;
        }
        if ( $__parts[ $i ] == ".." ) {
            if ( count( $__realparts ) > 0 ) {
                array_pop( $__realparts );
            }
        }
        else {
            array_push( $__realparts, $__parts[ $i ] );
        }
    }
    return $__startPoint . _PL_OS_SEP . implode( _PL_OS_SEP, $__realparts );
}


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

//****************************
	$goToNextStep = true;
	clearstatcache();
	$showPermissions = array();
	$filePermissions = array();
	$filePermissions["deces/views/setup/database.php"] = "rw";
	$filePermissions["deces/views/setup/tmp"] = "rw";
	// echo '<pre>';
	// print_r ($filePermissions);
	// echo '</pre>';
	foreach ($filePermissions as $key => $value)
	{
		$error = "";
		$config['applicationPath'] = "../../../";
		$values = str_split($value);
		$file = getRealpath(dirname(getenv('SCRIPT_FILENAME'))."/".$config['applicationPath'].$key);
		
		if (file_exists($file))
		{
			foreach ($values as $char)
			{
				switch ($char)
				{
					case "r": if (!is_readable($file)) $error = "Not readable"; break;
					case "w": if (!is_writable($file)) $error = "Not writeable"; break;
					case "x": if (!is_executable($file)) $error = "Not executeable"; break;
				}
			}
		}
		else
			$error = "File doesnt exist!";
		// combine string for user easy reading
		$showRequired = array();
		foreach ($values as $char)
		{
			switch ($char)
			{
				case "r": $showRequired[] = "Read"; break;
				case "w": $showRequired[] = "Write"; break;
				case "x": $showRequired[] = "Execute"; break; 
			}
		}
		$showPermissions[$key] = array("required" => $value, "error" => $error, "showRequired" => implode(", ", $showRequired), "realpath" => $file);	
		if ($error != "") $goToNextStep = false;
	}	
?>
<h3>File permissions</h3>
<table  width='60%' >
	<thead>
		<tr>
			<th>Name</th>
			<th>Real Path</th>
			<th>Required</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($showPermissions as $filename => $permissions): ?>
		<tr bgcolor="white">
			<td align="left"  ><?php echo $filename; ?></td>
			<td align="left" ><?php echo $permissions['realpath']; ?></td>
			<td align="center" ><?php echo $permissions['showRequired']; ?></td>
			<td align="center" ><?php if ($permissions['error'] == "") { ?><img src="<?php echo URL;?>public/images/accept.png"> OK <?php } else { ?><img src="<?php echo URL;?>public/images/cancel.png"><?php echo $permissions['error']; ?> <?php } ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<hr>




<?php if ($goToNextStep) { ?>
<form action="<?php echo URL;?>setup/step4" method="post">
	
	<input type="hidden" name="nextStep" value="database">
	<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
<input  id="Clearb" type="submit" />
<button id="Clearl" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
<?php 
echo '</form>';
?>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
<hr>
<?php } else { ?>
	<form action="<?php echo URL;?>setup/step3" method="post">
		
		<input type="hidden" name="nextStep" value="filePermissions">
		<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
<input  id="Clearb" type="submit" />
<button id="Clearl" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
<?php 
echo '</form>';
?>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
<hr>
<?php } ?>


