![image](https://github.com/user-attachments/assets/d7a102e5-0d8d-4ad8-8b43-b377bc249407)

This is a [looking glass](https://en.wikipedia.org/wiki/Looking_Glass_server) like interface aimed to provide access to network tools over the web, without requiring
you to login over shell.

Initially it was build to allow people without shell access to diagnose network issues from the server to
different destinations.

Currently this simple web interface provides access to the follwing commands:
- mtr
- ping
- traceroute
- host
- dig
- whois

# Usage

When you load the page in the top left corner you will see the IP from which you have accessed the tool.

If you click on the IP, it will automatically run the currently selected tool with your IP.

If you click on the clipboard icon next to the IP, it will copy the IP in your clipboard.

## mtr
[Matt's traceroute](https://www.bitwizard.nl/mtr/) is an advanced version of the typical traceroute (or tracepath, tracert, tcptraceroute) command. mtr combines ping and a traditional traceroute and will ping each hop in the network path.

## host
This command allows you to resolve a host name to an address or check the reverse(PTR) record for an IP.

## dig
dig is improved version of host, but here we use it with the +trace flag, which allows you to see the full path of the DNS resolution. You tipically need this tool, when you are checking for DNS propagation or issues related to your registrar.

## whois
This tool provides you information regarding a domain or IP.

If domain is provided, it will give you information for the domain, such as:
* registration date
* expire date
* authorative name servers
* registrar

## ping
This is the most common tool used to validate connectivity and latency between machines.

## traceroute
With this tool you can see what is the network path between the server that runs Willow and the destination address.

## Name Servers
This simply uses the host command with the -t NS option, which tells the command to list only the name server(NS) records for the provided address.

# Installation

Installation should be done as root

## Ubuntu
```
apt-get install nginx fcgiwrap libcgi-pm-perl traceroute mtr bind9-host bind9-dnsutils whois iputils-ping git
cd /var/www
git clone https://github.com/hackman/willow.git
systemctl enable fcgiwrap
systemctl start fcgiwrap nginx
chmod 755 /var/www/willow/api.pl
```
For MTR to work you would also need to add CAP_NET_RAW:
```
setcap cap_net_raw+ep /usr/bin/mtr-packet
```

## Nginx
Add this to your Nginx server configuration:
```
	location /willow {
		root /var/www;
		index index.html;
		try_files $uri $uri/ /index.html;
		location ~ \.pl {
			fastcgi_pass unix:/run/fcgiwrap.socket;
			fastcgi_index api.pl;
			include fastcgi.conf;
		}
	}
```
