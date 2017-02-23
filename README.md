云游点评
===============

云游点评是由MUI和CI框架搭建的旅游景点评价应用，形式借鉴于应用商店。


主要功能
----
 - 使用手机号进行注册和登录，并保存登录状态
 - 用户可以创建景点信息，其他用户则可以进行评价
 - 用户可以对他人的评价表态也可以进行评论
 - 游客也可以浏览评价或者点赞但是不能评论
 - 首页展示的景点按照创建时间和推荐数进行更新
 - 图片以BASE64编码的方式进行上传以节省流量
 - 用户填写的评论内容会被三次转义防止恶意攻击

 
开发环境
----

 - 操作系统：Windows 7
 - 开发环境：WampServer
 - 编辑工具：PhpStorm IDE
 - 前端框架：MUI
 - 后端框架：CodeIgniter



目录结构
----

```
YunYou                      部署目录
├─application               应用目录
│  │
│  ├─config
│  │  ├─autoload.php        自动加载
│  │  ├─database.php        数据库配置
│  │  └─routes.php          路由配置
│  │
│  ├─controllers
│  │  ├─Assess.php          添加评价
│  │  ├─Detail.php          景点详情
│  │  ├─Discuss.php         评价讨论
│  │  ├─Guide.php           登录引导
│  │  ├─Home.php            用户主页
│  │  ├─Login.php           用户登录
│  │  ├─Main.php            网站入口
│  │  ├─Navbar.php          导航栏
│  │  ├─Senic.php           创建景点
│  │  ├─Setup.php           资料设置
│  │  └─Test.php            调试专用
│  │
│  ├─helpers
│  │  ├─img_helper.php      图片函数
│  │  └─tool_helper.php     工具函数
│  │
│  ├─libraries
│  │  └─Yunyou.php          应用工具库
│  │
│  ├─models
│  │  ├─Comment.php         评论模型
│  │  ├─Opinion.php         观点模型
│  │  ├─Picture.php         图片模型
│  │  ├─Review.php          评价模型
│  │  ├─Senery.php          景点模型
│  │  └─User.php            用户模型
│  │
│  └─views                  视图目录
│     └─ ...
|
├─source                    资源目录
|  └─ ...
├─system                    框架目录
|  └─ ...
├─vendor                    第三方库
|  └─ ...
├─database.sql              SQL命令
└─index.php                 入口文件
```



部署指导
----

 1. 在 `httpd.conf` 文件中设置网站根目录为 `./YunYou/`
 2. 在 `phpMyAdmin` 中创建数据库并导入 `database.sql` 文件
 3. 在 `application/config/database.php` 中设置数据库连接参数
 4. 在 `config`目录中创建 `server.php` 保存 appkey 和 secret
 5. 在 `index.php` 第56行将 **development** 改为 **production**
 
> 如果你不需要开启「阿里大于」短信验证服务，则第四第五步可以忽略，开发模式下短信验证码统一为 123456 。
