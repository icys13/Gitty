#!/bin/bash 
# 创建派生项目
# 在项目库中复制相应的项目

# 进入醒目库中
cd /home/git/repositories/ 

# 复制相应的项目
cp -R $1@$2.git $3@$2.git

