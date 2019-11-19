#!/bin/bash
set -eo pipefail
shopt -s nullglob

create_test_database="
CREATE DATABASE IF NOT EXISTS \`$MYSQL_TEST_DATABASE\` ;
GRANT ALL ON \`$MYSQL_TEST_DATABASE\`.* TO '$MYSQL_USER'@'%' ;
"

if [ "$MYSQL_TEST_DATABASE" ]; then
    # アスタリスクの展開を無効化
    set -f
    echo $create_test_database | mysql -u root -p$MYSQL_ROOT_PASSWORD
    set +f
fi