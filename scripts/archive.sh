#!/bin/bash 

# 进入到 $1@S2.git 项目代码版本库
cd /home/git/repositories/$1@$2.git

# 打包 提交 id 为$3的版本代码
# 只包含相应提交工作区的文件 不包含其版本库的代码
#
git archive --format=tar $3 | gzip
