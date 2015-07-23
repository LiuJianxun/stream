#DEMO测试:
1. 该DEMO因为个人电脑原因, 暂时未做FALSH的支持, 只做了HTML5的后端
2. 支持PHP解析的WEB服务器
3. 自己的域名或个人配置的主机localhost
4. 把该目录复制WEB服务器根目录下面, 直接访问: 域名/webapp

#目录结构:
+ webapp                                    //项目目录
+ |---- css                                 //样式目录
+ |---- |---- stream-v1.css                 //样式文件
+ |---- |---- img                           //图片目录

> 
+ |---- js                                  //JS目录
+ |---- |---- stream-v1.js                  //JS文件

>
+ |---- php                                 //PHP后端文件目录
+ |---- |---- upload.php                    //PHP后端处理文件

>
* |---- |---- uploads                       //上传文件目录, 需要有写权限
* |---- |---- |---- files                   //上传文件保存目录
* |---- |---- |---- tokens                  //令牌保存目录

|---- swf                                 //FLASH目录
|---- |---- FlashUploader.swf             //上传FLASH

|---- index.html                          //上传显示页面
|---- readme                              //说明文件
