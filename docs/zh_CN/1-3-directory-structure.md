# 目录结构 

```
config
    app.php             应用默认配置
    config.php          用户自定义配置
    database.php        数据库配置
    routes.php          路由配置
    server.php          swoole 服务器配置
    cache.php           缓存配置
database
    seeds               数据库结构文件，通过命令 php bin/console seed:create {tableN} 创建 
    dataset             数据库测试数据
src
    Console             控制台命令
    Controller          控制器
    Middleware          中间件
    ServiceProvider     自定义服务提供者
    Model               数据模型
    Testing             单元测试
bin
    console
    server
web
    index.php
runtime                 程序运行数据目录
    pid                 服务器 pid 文件目录
    logs                日志目录
```

源代码均放置在 src 目录，如果目录并不能满足业务需求，可以通过手动修改方式进行调整。

下一节: [路由与控制器](2-1-routing-and-controllers.md)

