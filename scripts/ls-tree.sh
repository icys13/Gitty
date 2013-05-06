#!/bin/bash 

cd /home/git/repositories/$1@$2.git

git ls-tree $3
