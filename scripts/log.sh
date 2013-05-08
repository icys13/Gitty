#!/bin/bash  
# 简洁方式显示某次提交与其父提交的差异

# 进入项目仓库
cd /home/git/repositories/$1@$2.git

# $3 变量为某次提交的 id (SHA 表示)
git log --stat --oneline -1 $3

