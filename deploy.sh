#!/bin/bash
git push github
ssh bitfighter.org 'cd /var/www/html/pleiades ; git pull ; cd app/tmp/cache ; for file in `find .` ; do rm $file ; done'
