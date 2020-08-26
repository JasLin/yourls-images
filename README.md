# yourls-images

this repo is a custom yourls image, with some plugins build-in(such as `always-302`). it is based on [offical image](https://hub.docker.com/_/yourls?tab=description)

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