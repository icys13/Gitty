#!/bin/bash 

# $1@$2.git 代表要被操作的项目
# $3 参数 -t 返回类型 blob commit 或 tree
#         -p 返回内容
# $4 参数  40位 SHA 值
cd /home/git/repositories/$1@$2.git

git cat-file $3  $4

