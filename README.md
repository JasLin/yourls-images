# yourls-images

this repo is a custom yourls based imaged, with plugin `always-302` build-in.

the dockerfile is simpe

```dockerfile
From yourls:latest
COPY ./Always-302 /var/www/html/user/plugins/Always-302
```

as you see, to add some other plugin, is simple.

1. create plugin folder or git clone plugin

```shell
git clone <url>
git submodule add <url> <plugin name>
```

2. change dockerfile to add new plugin to puglins folder

```dockerfile
From yourls:latest
COPY ./Always-302 /var/www/html/user/plugins/Always-302
COPY ./<plugin name> /var/www/html/user/plugins/<plugin name>
```

3. git add and git push to trigger docker hub build webhook