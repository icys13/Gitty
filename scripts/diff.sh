#!/bin/bash 

# 比较两次提交之间的差异 
cd /home/git/repositories/$1@$2.git

# S3 为较旧的提交 $4 为较新的提交
git diff -p $3 $4
