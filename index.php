RewriteEngine On

RewriteCond %{REQUEST_URI} !^/public/ [NC]
RewriteCond %{REQUEST_URI} !^/admin/ [NC]
RewriteRule ^(.*)$ /public/$1 [L]
