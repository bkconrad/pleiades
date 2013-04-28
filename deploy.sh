#!/bin/bash
# you should configure this stuff
MYSQL_USER=root
MYSQL_DB=pleiades

REMOTE_MYSQL_USER=bf_pleiades
REMOTE_MYSQL_DB=pleiades

PUSH_REPOSITORY=github
REMOTE_HOST=bitfighter.org
REMOTE_DIRECTORY=/var/www/html/pleiades

# find base path
pushd `dirname $0` > /dev/null
project_root=`pwd`
popd > /dev/null

# make schema, add a commit
echo 'Creating schema dump, please enter local MySQL password'
mysqldump -u $MYSQL_USER -p $MYSQL_DB --add-drop-database --no-data > $project_root/schema.sql
git add  $project_root/schema.sql
git commit

git push $PUSH_REPOSITORY

# read -s -p "Remote MySQL Password: " REMOTE_PASSWORD

ssh_commands=`cat <<EOF
cd $REMOTE_DIRECTORY
git pull
cd app/tmp/cache
for file in \\\`find .\\\`
do
	rm \\\$file
done
EOF`

echo "$ssh_commands"

ssh $REMOTE_HOST "$ssh_commands"
# ssh $REMOTE_HOST "mysql -u $REMOTE_MYSQL_USER -p $REMOTE_MYSQL_DB < $REMOTE_DIRECTORY/schema.sql"
