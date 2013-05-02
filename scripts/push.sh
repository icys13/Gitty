#! /bin/bash
# 1.创建者的信息写入gitosis.config
# 2.将对gitosis.conf的修改提交给server

cd /var/www/Gitty/gitosis-conf/gitosis-admin/
git add *
git commit -a -m "www-data"
git push  origin
echo 'asdf'
