#!/bin/bash
cd /home/ubuntu/camagru/

env_file=".env"

while IFS= read -r line; do
  if [[ "$line" =~ ^[^[:space:]] && ! "$line" =~ ^# ]]; then
    
    key=$(echo "$line" | cut -d '=' -f 1)
    value=$(echo "$line" | cut -d '=' -f 2-)

    export "$key"="$value"
  fi
done < "$env_file"

service nginx start
service php8.1-fpm start
service mariadb start
service postfix start

echo "CREATE DATABASE $DB_DATABASE;" | mysql
echo "CREATE USER '$DB_USER'@'$DB_HOST' IDENTIFIED BY '$DB_PASSWORD';" | mysql
echo "GRANT ALL PRIVILEGES ON *.* TO '$DB_USER'@'$DB_HOST' WITH GRANT OPTION;" | mysql
mysql -p$DB_PASSWORD $DB_DATABASE < dump.sql

service mariadb reload