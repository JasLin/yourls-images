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

## docker compose 

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

## kubernetes deploy

```shell
kubectl apply -f mysql-pvc.yml
kubectl apply -f k8s.yml
```

## database operate

```
mysql> show tables;
+------------------+
| Tables_in_yourls |
+------------------+
| yourls_log       |
| yourls_options   |
| yourls_url       |
+------------------+

mysql> describe yourls_log;
+--------------+--------------+------+-----+---------+----------------+
| Field        | Type         | Null | Key | Default | Extra          |
+--------------+--------------+------+-----+---------+----------------+
| click_id     | int(11)      | NO   | PRI | NULL    | auto_increment |
| click_time   | datetime     | NO   |     | NULL    |                |
| shorturl     | varchar(200) | NO   | MUL | NULL    |                |
| referrer     | varchar(200) | NO   |     | NULL    |                |
| user_agent   | varchar(255) | NO   |     | NULL    |                |
| ip_address   | varchar(41)  | NO   |     | NULL    |                |
| country_code | char(2)      | NO   |     | NULL    |                |
+--------------+--------------+------+-----+---------+----------------+


mysql> describe yourls_options;
+--------------+---------------------+------+-----+---------+----------------+
| Field        | Type                | Null | Key | Default | Extra          |
+--------------+---------------------+------+-----+---------+----------------+
| option_id    | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
| option_name  | varchar(64)         | NO   | PRI |         |                |
| option_value | longtext            | NO   |     | NULL    |                |
+--------------+---------------------+------+-----+---------+----------------+

describe yourls_url;
+-----------+------------------+------+-----+-------------------+-------+
| Field     | Type             | Null | Key | Default           | Extra |
+-----------+------------------+------+-----+-------------------+-------+
| keyword   | varchar(200)     | NO   | PRI | NULL              |       |
| url       | text             | NO   |     | NULL              |       |
| title     | text             | YES  |     | NULL              |       |
| timestamp | timestamp        | NO   | MUL | CURRENT_TIMESTAMP |       |
| ip        | varchar(41)      | NO   | MUL | NULL              |       |
| clicks    | int(10) unsigned | NO   |     | NULL              |       |
+-----------+------------------+------+-----+-------------------+-------+


mysql> select * from yourls_log where shorturl='tencent' order by click_id desc limit 3;
+----------+---------------------+----------+--------------------------------------------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+---------------+--------------+
| click_id | click_time          | shorturl | referrer                                               | user_agent                                                                                                                                            | ip_address    | country_code |
+----------+---------------------+----------+--------------------------------------------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+---------------+--------------+
|      258 | 2020-10-16 16:37:10 | tencent  | direct                                                 | Mozilla/5.0 (Linux; Android 5.0) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Safari/537.36 (compatible; Bytespider; https://zhanzhang.toutiao.com/) | 60.8.123.249  | CN           |
|      255 | 2020-10-16 10:26:31 | tencent  | direct                                                 | Mozilla/5.0 (Ubuntu; X11; Linux x86_64; rv:8.0) Gecko/20100101 Firefox/8.0                                                                            | 47.93.7.171   | CN           |

```