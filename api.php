<?php

$VERSION = '1.0';

function read_exec($cmd) {
	$exec = '';
	$result = "\nExecuted command:\t";
	foreach ($cmd as $o) {
		$exec .= $o . ' ';
	}
	$result .= " $exec\n";
	$result .= system($exec);
	return $result;

}

$cmd = '';
$host = '';

$tools = array(
	0 => [ '/bin/ping', '-c', '5', '-w', '5' ],
	1 => [ '/usr/bin/traceroute', '-m', '14', '-q', '1' ],
	2 => [ '/usr/bin/host' ],
	3 => [ '/usr/bin/dig', '+trace' ],
	4 => [ '/usr/bin/whois' ] 
);

$cmd  = $_REQUEST['cmd'];
$host = $_REQUEST['host'];

#print_r($_REQUEST);

if (!isset($cmd)) {
	echo("Error: missing cmd $cmd\n");
	exit(0);
}

if ($cmd == 'ip') {
	echo($_SERVER['REMOTE_ADDR']);
	exit(0);
}

if (!isset($host)) {
	echo("Error: missing host\n");
	exit(0);
}

if (!preg_match('/^[0-9]$/', $cmd)) {
	echo("Error: invalid cmd format\n");
	exit(0);
}

if (!preg_match('/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$|^[-a-z0-9_\.]+$/', $host)) {
	echo("Error: invalid host format\n");
	exit(0);
}

# Get the tool array, and add the host to it, then execute the command with the provided parameters and return the result of the command
$tool = $tools[$cmd];
array_push($tool, escapeshellarg($host));
echo(read_exec($tool));
