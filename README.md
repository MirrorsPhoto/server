[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/832a0e846beba25af5fe)

![DB diagram](https://api.genmymodel.com/projects/_mgeDUIHKEeeveJPbhFhy-g/diagrams/_mgeDUoHKEeeveJPbhFhy-g/svg)


# Install
## Postgresql
```shell
brew uninstall --ignore-dependencies postgresql
brew install postgresql

initdb /usr/local/var/postgres -E utf8

mkdir -p ~/Library/LaunchAgents
cp /usr/local/Cellar/postgresql/9.2.1/homebrew.mxcl.postgresql.plist ~/Library/LaunchAgents/
launchctl load -w ~/Library/LaunchAgents/homebrew.mxcl.postgresql.plist

gem install lunchy
lunchy start postgres
```

# PHP
```shell
brew install homebrew/php/php71-pdo-pgsql homebrew/php/php71-phalcon homebrew/php/php71 --with-httpd

export PATH="$(brew --prefix homebrew/php/php71)/bin:$PATH"
```

## Apache
 
#### `sudo nano /etc/apache2/extra/httpd-vhosts.conf`
```shell
<VirtualHost *:80>
    DocumentRoot "/Users/jonkofee/dev/mirrors/server"
    ServerName api.mirrors.local

<Directory "/Users/jonkofee/dev/mirrors/server">

AllowOverride All

</Directory>
</VirtualHost>


<VirtualHost *:80>
ServerName static.mirrors.local
DocumentRoot /Users/jonkofee/dev/mirrors/server/static

<Directory "/Users/jonkofee/dev/mirrors/server/static">
</Directory>
</VirtualHost>
```

#### `sudo nano /etc/apache2/httpd.conf`
```shell
LoadModule php7_module /usr/local/opt/php71/libexec/apache2/libphp7.so

DirectoryIndex index.php index.html
```

#### `sudo apachectl restart`

```shell
cd ~

git clone https://github.com/MirrorsPhoto/server.git

cd server/

php composer.phar update

cp ./api/config/config_example.ini ./api/config/config.ini
```
#### `nano ./api/config/config.ini`
```ini
[database]
host = localhost
username = jk
password =
dbname = mirrors
port = 5432
adapter = Postgresql

[application]
migrationsDir = api/migrations
modelsDir = api/models

[jwt]
key = devkey

[static]
url = http://static.jonkofee.ru
dir = static
```

```shell
./vendor/phalcon/devtools/phalcon.php migration run --config=./api/config/config.ini
``` 


