## Installation steps

#### 1. Composer installation in console:

`php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');".
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e5325b19b381bfd88ce90a5ddb7823406b2a38cff6bb704b0acc289a09c8128d4a8ce2bbafcd1fcbdc38666422fe2806') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"`

#### 2. Laravel with dependencies:

#### 2.1 Setup configuration for constants:

// .env.
`APP_URL="your hostname"

DB_CONNECTION=mysql
DB_HOST="database host name"
DB_PORT=3306
DB_DATABASE="database name"
DB_USERNAME="username"
DB_PASSWORD="password"

TWILIO_SID="INSERT YOUR TWILIO SID HERE"
TWILIO_AUTH_TOKEN="INSERT YOUR TWILIO TOKEN HERE"
TWILIO_NUMBER="INSERT YOUR TWILIO NUMBER IN [E.164] FORMAT"`

#### 2.2 Setup web server (means using php7.4-fpm version):

// NGINX -- /etc/nginx/sites-enabled/your-site.conf
`server {
        listen 80;
        listen [::]:80;

        root /var/www/root-directory-of-your-site/public;

        index index.php;

        server_name domain-name-of-your.site;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-XSS-Protection "1; mode=block";
        add_header X-Content-Type-Options "nosniff";

        charset utf-8;

        location / {
                try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
                fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
                fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
                deny all;
        }

        error_log /var/log/nginx/greekfre-messages_error.log;
        access_log /var/log/nginx/greekfre-messages_access.log;
}`
