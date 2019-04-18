#!/usr/bin/env sh

# abort on errors
set -e

# build
vuepress build

# navigate into the build output directory
cd .vuepress/dist

git init
git add -A
git commit -m 'deploy'

git push -f git@github.com:mirrorsPhoto/server.git master:gh-pages