# To be continued →


```bash
PS D:\USM\Second year\ContainerizationVirtualization\containers06> git clone https://github.com/AndreiKlinchev/PHP-labs.git
```

```bash
PS D:\USM\Second year\ContainerizationVirtualization\containers06> cd .\PHP-labs\    
PS D:\USM\Second year\ContainerizationVirtualization\containers06\PHP-labs> git switch main 
```

```bash
PS D:\USM\Second year\ContainerizationVirtualization\containers06> cp -r .\PHP-labs\phpLab5\* .\mounts\site\
```

```bash
PS D:\USM\Second year\ContainerizationVirtualization\containers06> docker network create internal                                
87b2349c7d0bc6753499d6023128180434c8fa766b8006ac7c35f82ae21c44fd
PS D:\USM\Second year\ContainerizationVirtualization\containers06> docker network ls 
NETWORK ID     NAME       DRIVER    SCOPE
8a682368d887   bridge     bridge    local
3c462d704bdc   host       host      local
87b2349c7d0b   internal   bridge    local
7e934f09907c   none       null      local
```

```bash
PS D:\USM\Second year\ContainerizationVirtualization\containers06> docker run -d --name backend --network internal -v ./mounts/site/:/var/www/html php:8.4-fpm
```

```bash
PS D:\USM\Second year\ContainerizationVirtualization\containers06> docker run -d --name frontend --network internal -v ./mounts/site/:/var/www/html -v ./nginx/default.conf:/etc/nginx/conf.d/default.conf -p 80:80 nginx:1.23-alpine
```