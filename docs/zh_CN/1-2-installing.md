# 安装 FastD 

> 如果使用浏览器访问入口，需要给项目配置虚拟域名，将路径指向项目的 web 目录

##### 1 如果没有安装 Composer 

```
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
$ chown +x /usr/local/bin/composer
```

##### 2 安装 Swoole 扩展

```
$ pecl install swoole
```

##### 3 安装 fastd/dobee

```
$ composer create-project "fastd/dobee" dobee -vvv 
```

##### 4 启动服务器

通过浏览器访问 PHP 内置 WEB 服务器或访问当前web目录

**启动内置 Web 服务器**

```shell
$ cd dobee
$ php -S 127.0.0.1:9527 -t ./web
$ curl http://127.0.0.1:9527/
```

**启动 Swoole**

```php
$ php bin/server start
$ curl http://127.0.0.1:9527/
```

### Windows 配置

因为 swoole 没有太多考虑 windows 环境，所以推荐使用虚拟机环境进行开发，Windows 仅支持传统 PHP 模式。

##### 1 安装 fastd/dobee
 
```
$ composer create-project "fastd/dobee" dobee -vvv 
```

##### 2 配置环境

##### 2.1 配置 apache 虚拟域名

修改 httpd.conf，开启 vhost.conf，添加虚拟与名到 vhost.conf 文件中，修改目录地址。

```apacheconfig
<VirtualHost *:80>
    DocumentRoot "/path/to/web"
    ServerName example.com
</VirtualHost>
```

映射本地 ip 到虚拟域名，修改 System32 下面的 hosts 文件

##### 2.2 配置 nginx 配置

```
server
{
    listen     {server port};
    index index.php;
    server_name {server name};
    root /path/to/dobee/web;
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }
    location ~ \.php {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include       fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

##### 3 Nginx + Swoole 代理 (推荐使用 Linux 环境)

```
server 
{
    listen     {server port};
    server_name {server name};
    location / {
        proxy_pass http://127.0.0.1:9527; # Swoole Server Listen
    }
}
```

下一节: [目录结构](1-3-directory-structure.md)
