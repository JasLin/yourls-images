# yourls-images

this repo is used to build a [custom yourls image](https://hub.docker.com/repository/docker/climbing/yourls/general), with some plugins build-in(such as `always-302`). it is based on [official image](https://hub.docker.com/_/yourls?tab=description)

the dockerfile is simple

```dockerfile
From yourls:latest
COPY ./Always-302 /var/www/html/user/plugins/Always-302
```

as you see, to add some other plugins, is simple.

1. create plugin folder or git clone plugin

```shell
git clone <url>
git submodule add <url> <plugin name>
```

2. change dockerfile to add new plugin to plugins folder

```dockerfile
From yourls:latest
COPY ./Always-302 /var/www/html/user/plugins/Always-302
COPY ./<plugin name> /var/www/html/user/plugins/<plugin name>
```

3. git add and git push to trigger docker hub build webhook

# docker compose 

there is a docker compose file in repo, you can run the demo with the following docker command:

```
docker-compose -f compose.yaml up
```

this template compose file use the custom image

```yml
version: '3.1'

services:

  yourls:
    image: climbing/yourls:latest
    restart: always
    ports:
      - 8080:80 # apache image
    environment:
      YOURLS_DB_PASS: example
      YOURLS_SITE: http://localhost:8080
      YOURLS_USER: example_username
      YOURLS_PASS: example_password
      YOURLS_UNIQUE_URLS: 'False'
      YOURLS_HOURS_OFFSET: '+8'
    

  mysql:
    image: mysql:5.7
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: example
```

if you want to debug dockerfile locally, just change replace image filed with build filed:

```yml
version: '3.1'

services:

  yourls:
    build: 
      context: ./
      dockerfile: Dockerfile
    restart: always
    ports:
      - 8080:80 # apache image
    environment:
      YOURLS_DB_PASS: example
      YOURLS_SITE: http://localhost:8080
      YOURLS_USER: example_username
      YOURLS_PASS: example_password
      YOURLS_UNIQUE_URLS: 'False'
      YOURLS_HOURS_OFFSET: '+8'
    

  mysql:
    image: mysql:5.7
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: example
```

also, you can use the official image instead of custom image, and mount plugin with volumes:

```yml
version: '3.1'

services:

  yourls:
    image: yourls
    image: climbing/yourls:latest
    restart: always
    ports:
      - 8080:80 # apache image
    environment:
      YOURLS_DB_PASS: example
      YOURLS_SITE: http://localhost:8080
      YOURLS_USER: example_username
      YOURLS_PASS: example_password
      YOURLS_UNIQUE_URLS: 'False'
      YOURLS_HOURS_OFFSET: '+8'
    volumes:
      - ./Always-302:/var/www/html/user/plugins/Always-302
    

  mysql:
    image: mysql:5.7
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: example
```