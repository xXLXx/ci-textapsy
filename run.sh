#!/bin/bash

/Applications/MAMP/Library/bin/mysql -u root -p -e 'GRANT ALL PRIVILEGES ON *.* TO "root"@"10.8.8.8"; FLUSH PRIVILEGES;' mysql

sudo ifconfig lo0 alias 10.8.8.8 netmask 255.255.255.255 up

docker build -t txtaspy-php7nginx docker
docker kill txtapsy
docker rm txtapsy
docker run -p 8080:80 -v $( pwd ):/data/www -d --name txtapsy --hostname txtapsy txtaspy-php7nginx

echo "Logging until server start..."
docker logs txtapsy -f