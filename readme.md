##ReverseProxy PHP Script

A simple PHP script that can be used as a ReverseProxy. Although you can use Nginx, mod_proxy or a dedicated software like Squid for this purpose, there are instances that you favor the ability to just do it in a PHP script that can be dropped in a single folder. There could be various reasons:

* You are in a shared hosting wherein tweaking server-wide stuffs is not possible
* For some reason, you have to serve the contents of a subdomain to be as sub-folder of another. For example you have site.yourdomain.com (server 1 ) and you want it to be accesible as www.domain2.com/site in another server
* You wanted to cache the contents
* The web server is inaccessible publicly
* You want to provide a simple proxyserver
* Visitor's country is restricted from visiting the actual site
* Anonymize your users


Requirements
============

* PHP >= 5.2
* Apache mod_rewrite enabled
* cURL extension enabled


Installation
============

Use composer to install it

    composer create-project buonzz/reverseproxy

edit config.php

    define('MASKED_DOMAIN', 'http://www.google.com');
    define('PROXY_SUBFOLDER', 'reverseproxy');
    define('FOLLOW_LOCATION', FALSE);

* MASKED_DOMAIN is the site where this script will be getting its contents from, should have no traliling slashes.
* PROXY_SUBFOLDER this is in case you had placed your script in a subfolder, like for example http://www.yourdomain.com/reverseproxy
Leave this to blank if you had placed this in the root of the domain
* FOLLOW_LOCATION - indicates whether the script will follow the redirect returned by the source site

edit .htaccess

    RewriteEngine on 
    RewriteCond $1 !^(index\.php) 
    RewriteRule ^(.*)$ /reverseproxy/index.php/$1 [L]

This directives allows the reverseproxy to catch all the URI intented for the original domain, like for example

    http://www.originaldomain.com/blog/102014/hello-world

With this setting, you can use

    http://www.yourdomain.com/reverseproxy/blog/102014/hello-world

and the uri will be passed to the index.php and can then be passed to the original domain to rerieve the contents.
Remember that if you are serving the contents from the root domain you only need to use this

    RewriteEngine on 
    RewriteCond $1 !^(index\.php) 
    RewriteRule ^(.*)$ /index.php/$1 [L]






