Mysql配置参考  my.cnf 
入口目录 ： www.didi5188.com/main/
nginx配置：
    server {
        listen       80;
        server_name  localhost;

        access_log  logs/didi28.com.access.log;
        error_log logs/didi28.com.error.log;

        location / {
            root   /alidata/www/didi8888/main;
            index  index.html index.htm index.php;

            if (!-e $request_filename) {
                rewrite  ^/(.*)$  /index.php/$1  last;
                break;
            }
        }
        
        location ~ \.php.* {
            root           /alidata/www/didi8888/main;
            fastcgi_pass   127.0.0.1:9000;

            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include        fastcgi_params;

            set $fastcgi_script_name2 $fastcgi_script_name;
            if ($fastcgi_script_name ~ "^(.+\.php)(/.+)$") {
                set $fastcgi_script_name2 $1;
                set $path_info $2;
            }
            fastcgi_param   PATH_INFO $path_info;
            fastcgi_param   SCRIPT_FILENAME   $document_root$fastcgi_script_name2;
            fastcgi_param   SCRIPT_NAME   $fastcgi_script_name2;
        }


        location ~ /\.ht {
            deny  all;
        }
    }

