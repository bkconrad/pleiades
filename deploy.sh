#!/bin/bash
# you should configure this stuff
MYSQL_USER=root
MYSQL_DB=pleiades

REMOTE_MYSQL_USER=bf_pleiades
REMOTE_MYSQL_DB=pleiades

PUSH_REPOSITORY=origin
REMOTE_HOST=bitfighter.org
REMOTE_DIRECTORY=/var/www/html/pleiades

# find base path
pushd `dirname $0` > /dev/null
project_root=`pwd`
popd > /dev/null

if [ "$1" == "-s" ]
then
	echo 'updating schema'
	UPDATE_SCHEMA=yes
fi

if [ $UPDATE_SCHEMA ]
then
	# make schema, add a commit
	echo 'Creating schema dump...'
	$project_root/app/Console/cake schema generate --snapshot
	git add .
	git commit -am 'updated schema'
fi

git push $PUSH_REPOSITORY

ssh_commands=`cat <<EOF
cd $REMOTE_DIRECTORY
git pull
git submodule init
git submodule update
cd app/tmp/cache
for file in \\\`find .\\\`
do
	rm \\\$file
done
EOF`

if [ $UPDATE_SCHEMA ]
then
	ssh_commands="$ssh_commands ; ./app/Console/cake schema update --dry-run"
fi

ssh $REMOTE_HOST "$ssh_commands"
