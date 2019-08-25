# nginx config

See https://nuxtjs.org/faq/nginx-proxy/

## local testing

in `sudo nano /etc/hosts` add:

```
127.0.0.1 www.ln.d ln.d api.ln.d
```

so that your computer will use this domain locally

## nginx config for `yarn run prod` (SSR server)

See https://nuxtjs.org/faq/nginx-proxy/

## nginx config for `yarn run nuxt generate`

Note that production, you'll most likely want to add parts from the "Using nginx with generated pages and a caching proxy as fallback" section. This is without the caching proxy.

Note that all requests starting with `/api` are proxied to apilnservices.

Note that this uses /dist/ instead of laravel's /public/

```nginx
server {
    listen 80 ;

    server_name ln.d www.ln.d;

    root /home/forge/code/ln-debate/dist; # put in your nuxt generate dist directory!!!

    index index.html;

    charset utf-8;

    ## Block download agents
    if ($http_user_agent ~* LWP::Simple|wget|libwww-perl) {
        return 403;
    }
    ## Block some nasty robots
    if ($http_user_agent ~ (msnbot|Purebot|Baiduspider|Lipperhey|Mail.Ru|scrapbot) ) {
        return 403;
    }

    access_log /var/log/www.ln.d-access.log;
    error_log  /var/log/www.ln.d-error.log;

    location / {
        try_files $uri $uri/index.html /index.html;
    }

    location ~* \.(png|jpg|jpeg|gif|ico)$ {
        access_log off;
        log_not_found off;
        expires 1y;
    }

    location /api {
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $host;
        proxy_pass http://apilnservices;
    }
}

upstream apilnservices {
    server api.ln.d;
}
```

## nuxt config for laravel

You can decide whether http://api.ln.d is publically accessible or hidden behind the other nginx config. Note that laravel's default /public/ folder still works in this context, but anything not in /api will only be available directly via http://api.ln.d. All http://www.ln.d/api goto http://api.ln.d/api.



```nginx
server {
    listen 80 ;

    server_name api.ln.d;

    root /home/forge/code/ln-debate/public; # put in your laravel public directory!!!

    index index.php;

    charset utf-8;

    ## Block download agents
    if ($http_user_agent ~* LWP::Simple|wget|libwww-perl) {
        return 403;
    }
    ## Block some nasty robots
    if ($http_user_agent ~ (msnbot|Purebot|Baiduspider|Lipperhey|Mail.Ru|scrapbot) ) {
        return 403;
    }

    access_log /var/log/api.ln.d-access.log;
    error_log  /var/log/api.ln.d-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~* \.(png|jpg|jpeg|gif|ico)$ {
        access_log off;
        log_not_found off;
        expires 1y;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;

        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param   SCRIPT_NAME     $fastcgi_script_name;

        fastcgi_buffer_size 128k;
        fastcgi_buffers 256 16k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;

        include fastcgi_params;
    }
}
```

After editing configs:

```shell
sudo nginx -t && sudo nginx service reload
```
