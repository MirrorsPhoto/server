[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/832a0e846beba25af5fe)

![DB diagram](https://api.genmymodel.com/projects/_mgeDUIHKEeeveJPbhFhy-g/diagrams/_mgeDUoHKEeeveJPbhFhy-g/svg)


# Install

```shell
brew update
brew doctor
brew uninstall --ignore-dependencies postgresql
brew install postgresql homebrew/php/php71-pdo-pgsql homebrew/php/php71-pdo-phalcon homebrew/php/php71 --with-httpd

initdb /usr/local/var/postgres -E utf8

mkdir -p ~/Library/LaunchAgents
cp /usr/local/Cellar/postgresql/9.2.1/homebrew.mxcl.postgresql.plist ~/Library/LaunchAgents/
launchctl load -w ~/Library/LaunchAgents/homebrew.mxcl.postgresql.plist

gem install lunchy
lunchy start postgres

git clone https://github.com/MirrorsPhoto/server.git & cd 

``` 


