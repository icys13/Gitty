#!/bin/bash 

# 比较两次提交之间，路径为 path 的文件的差异 
# 进入项目版本库
cd /home/git/repositories/$1@$2.git

# 比较 旧版本 $3,新版本 $4，在 path 路径下文件差异
# 参数 u 表示显示带上下文的比较结果
git diff -u $3 $4 -- $5
