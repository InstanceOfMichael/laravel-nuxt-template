#upstream client1broadcasters {
    # ip_hash: enables sticky sessions which is needed for websockets
#    ip_hash;
#    server 127.0.0.1:0;
    #server 127.0.0.1:6002;
#}
#upstream client1services {
#    server 127.0.0.1:0;
    #server 127.0.0.1:8002;
#}

map $http_upgrade $connection_upgrade {
    default upgrade;
    ''      close;
}


server {
    listen 80 ;

    server_name www.ln.d ln.d;

    add_header X-my-header lndebate;

    root /home/forge/code/ln/public; # put in your public directory!!!

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

    #access_log /home/forge/code/ln/nginx/access.log combined buffer=32k;

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
