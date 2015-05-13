<?php
define(BUF_SIZ, 1024);        # max buffer size
define(FD_WRITE, 0);        # stdin
define(FD_READ, 1);        # stdout
define(FD_ERR, 2);

function proc_exec($cmd)
{
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );
    /*
    [SHELL] => /bin/bash
    [TMPDIR] => /var/folders/tv/gg3svsjs585bps66zmz9756r0000gn/T/
    [Apple_PubSub_Socket_Render] => /tmp/launch-g6Ygsc/Render
    [USER] => nilay
    [COMMAND_MODE] => unix2003
    [SSH_AUTH_SOCK] => /tmp/launch-3xBLea/Listeners
    [Apple_Ubiquity_Message] => /tmp/launch-OhSOoI/Apple_Ubiquity_Message
    [__CF_USER_TEXT_ENCODING] => 0x1F5:0:0
    [PATH] => /usr/bin:/bin:/usr/sbin:/sbin
    [PWD] => /
    [HOME] => /Users/nilay
    [SHLVL] => 2
    [DYLD_LIBRARY_PATH] => /Applications/MAMP/Library/lib:
    [LOGNAME] => nilay
    [_] => /Applications/MAMP/Library/bin/httpd
    */
	#$env_arr = $_ENV;
	$key_arr = Array('SHELL' , 'TMPDIR','Apple_PubSub_Socket_Render','COMMAND_MODE','PATH','PWD','HOME','SHLVL','LOGNAME');
	foreach ( $key_arr as $tV ) {
		$env_arr[$tV] = $_ENV[$tV];
	}
	#$env_arr['PATH'] = "/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin";
    $ptr = proc_open($cmd, $descriptorspec, $pipes, NULL, $env_arr);
    if (!is_resource($ptr))
        return false;

    while (($buffer = fgets($pipes[FD_READ], BUF_SIZ)) != NULL 
            || ($errbuf = fgets($pipes[FD_ERR], BUF_SIZ)) != NULL) {
        if (!isset($flag)) {
            $pstatus = proc_get_status($ptr);
            $first_exitcode = $pstatus["exitcode"];
            $flag = true;
        }
        if ( IS_DUBUG ) { 
        if (strlen($buffer))
            echo $buffer;
        if (strlen($errbuf))
            echo "ERR: " . $errbuf;
        }
    }

    foreach ($pipes as $pipe)
        fclose($pipe);

    /* Get the expected *exit* code to return the value */
    $pstatus = proc_get_status($ptr);
    if (!strlen($pstatus["exitcode"]) || $pstatus["running"]) {
        /* we can trust the retval of proc_close() */
        if ($pstatus["running"])
            proc_terminate($ptr);
        $ret = proc_close($ptr);
    } else {
        if ((($first_exitcode + 256) % 256) == 255 
                && (($pstatus["exitcode"] + 256) % 256) != 255)
            $ret = $pstatus["exitcode"];
        elseif (!strlen($first_exitcode))
            $ret = $pstatus["exitcode"];
        elseif ((($first_exitcode + 256) % 256) != 255)
            $ret = $first_exitcode;
        else
            $ret = 0; /* we "deduce" an EXIT_SUCCESS ;) */
        proc_close($ptr);
    }

    return ($ret + 256) % 256;
}

/*
$cmd = "ls -al;";
$cmd = 'cd Actev8;xcodebuild -alltargets -configuration Debug -sdk iphoneos CODE_SIGN_IDENTITY="iPhone Developer: Chris Folayan (TNX7ST8L8D)" OTHER_CODE_SIGN_FLAGS="--keychain /Users/nilay/Library/Keychains/nilay" PROVISIONING_PROFILE=C3514756-D37E-4946-A874-06D103EF95A0;';
echo "<pre>";
///Library/Frameworks:/Network/Library/Frameworks:/System/Library/Frameworks
#print_r($_ENV);exit;
if (($ret = proc_exec($cmd)) === false)
        die("Error: not enough FD or out of memory.\n");
elseif ($ret == 127)
        die("Command not found (returned by sh).\n");
else {
	echo "Done";
}
//        exit($ret);
        

//proc_exec($cmd);
*/