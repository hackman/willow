#!/usr/bin/perl -T
use strict;
use warnings;
use CGI qw(param);

$ENV{PATH}='/usr/bin:/bin';

sub read_exec {
    my $result = "\nExecuted command:\t";
	foreach my $o(@_) {
		$result .= ' ' . $o;
	}
	$result .= "\n";
	$result .= do {
        local $/;
		open my $cmd, '|-', @_ or die 'Error: unable to execute ' . $_[0];
        <$cmd>;
    };
	return $result;
}

print "Content-type: text/plain\r\n\r\n";

my $cmd = '';
my $host = '';

my %tools = (
	0 => [ '/bin/ping', '-c', '5', '-w', '5' ],
	1 => [ '/usr/bin/traceroute', '-m', '14', '-q', '1' ],
	2 => [ '/usr/bin/host', ],
	3 => [ '/usr/bin/dig', '+trace' ],
	4 => [ '/usr/bin/whois' ] 
);

$cmd  = param('cmd');
$host = param('host');

if (!defined($cmd) or !defined($host)) {
	print "Error: missing host or cmd\n";
	exit 0;
}

if ($cmd !~ /^[0-9]$/) {
	print "Error: invalid cmd format\n";
	exit 0;
}

if ($host !~ /^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$|^[-a-z0-9_\.]+$/) {
	print "Error: invalid host format\n";
	exit 0;
}

# Get the tool array, and add the host to it, then execute the command with the provided parameters and return the result of the command
my @tool = @{$tools{$cmd}};
push(@tool, $host);
print read_exec(@tool);
