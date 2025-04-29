This is a simple web interface to the follwing commands:
- mtr
- ping
- traceroute
- host
- dig
- whois

It's main purpose is to help diagnose network issues, without the requirement of a shell on the machine.

# Installation

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
