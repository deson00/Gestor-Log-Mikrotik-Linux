# Gestor-Log-Mikrotik-Linux
Ferramenta para capturar logs de conexões em uma rede provida por um mikrotik (versão Linux).

[Video demonstrativo](https://www.youtube.com/watch?v=wG0e7yudzT0)

#### Instação em Debian 8
```
apt-get install apache2 mysql-server php5 php5-mysql phpmyadmin
apt-get install rsyslog-mysql
apt-get install git
```
Caminhe ate a pasta do apache cd /var/www/html/ depois faz o clone do repositorio do gestor log linux
```
cd /var/www/html/
git clone https://github.com/bylltec-projetos/Gestor-Log-Mikrotik-Linux.git
```
Também é necessário descomentar as linhas abaixo no arquivo /etc/rsyslog.conf do servidor:
```
nano /etc/rsyslog.conf
```
```
#provides UDP syslog reception 
$ModLoad imudp
$UDPServerRun 514
```
//reiniciar rsyslog
```
service rsyslog restart 
```
Definir o diretorio raiz para o apache2 caso necessario normalmente em /etc/apache2/sites-available/000-default.conf modificando a linha para /var/www/html/Gestor-Log-Mikrotik-Linux   
```
nano /etc/apache2/sites-available/000-default.conf
```
Exemplo abaixo de como deve ficar 
```
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/Gestor-Log-Mikrotik-Linux
</VirtualHost>
```
Reiniciar o apache
```
service apache2 restart
```
Estando dentro da pasta do sistema importar as tabelas de usuario
exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# mysql -p Syslog < usuarios.sql 
exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# mysql -p Syslog < usuario_log.sql 
```
cd /var/www/html/Gestor-Log-Mikrotik-Linux
mysql -p Syslog < usuarios.sql
mysql -p Syslog < usuario_log.sql
```
Editar o arquivo definindo o login e senha do banco de dados
Exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# nano site/Connections/site.php
```
nano /var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php
```
Permissoes conforme necessidade na pasta do projeto /var/www/html/Gestor-Log-Mikrotik-Linux
```
chmod -R 775 /var/www/html/Gestor-Log-Mikrotik-Linux
```
Agendar no crontab para o php execultar o backup a cada 2 hora comando abaixo e adicionando a linha conforme necessidade
```
crontab -e
```` 
0 */2 * * * php /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/action_backup_agendado.php

Criar uma pasta para o backup e dar as devidas permissao de leitura e escrita
```
mkdir /var/backups/gestorlog
chmod -R 775 /var/backups/gestorlog
```
Criar uma pasta onde vai conter apenas os backup do dia com as devidas permissoes 
```
mkdir /var/backups/gestorlog/diario
chmod -R 775 /var/backups/gestorlog/diario
```
Compactar backups e formar um unico arquivo diario criar um script .sh em /var/backups/gestorlog e agendar no crontab 
```
nano /var/backups/gestorlog/backup_diario.sh
```
Adicionar o conteudo abaixo no arquivo criado backup_diario.sh
```
#!/bin/bash
ORIGEM_PASTA=/var/backups/gestorlog/diario
DATA=$(date +"%m-%d-%Y")
ARQUIVO_DESTINO="/var/backups/gestorlog/$DATA.tar.gz"
echo "Backup esta sendo gerando em /var/backups/gestorlog/$DATA.tar.gz aquivo, por favor aguarde..."
/bin/tar -czvf  $ARQUIVO_DESTINO $ORIGEM_PASTA
#apaga backup apos compactar
rm /var/backups/gestorlog/diario/*
#reinicia syslog
service syslog restart
```
Agendar no crontab
```
crontab -e
```
Adicione a linha abaixo caso queira que seja feito todo dia as 1 da manhã
```
0 1 * * * /bin/bash /var/backups/gestorlog/backup_diario.sh
```

#### Instação em Debian 9
#Caso você use o APT, adicione a seguinte linha em /etc/apt/sources.list 

```
deb http://ftp.debian.org/debian/ jessie main contrib non-free
deb-src http://ftp.debian.org/debian/ jessie main contrib non-free
deb http://security.debian.org/ jessie/updates main contrib non-free
deb-src http://security.debian.org/ jessie/updates main contrib non-free 
```
```
apt-get install apache2 mysql-server php5 php5-mysql phpmyadmin
apt-get install rsyslog-mysql
apt-get install git
```
Caminhe ate a pasta do apache cd /var/www/html/ depois faz o clone do repositorio do gestor log linux
```
cd /var/www/html/
git clone https://github.com/bylltec-projetos/Gestor-Log-Mikrotik-Linux.git
```
Também é necessário descomentar as linhas abaixo no arquivo /etc/rsyslog.conf do servidor:
```
nano /etc/rsyslog.conf
```
```
#provides UDP syslog reception 
$ModLoad imudp
$UDPServerRun 514
```
//reiniciar rsyslog
```
service rsyslog restart 
```
Definir o diretorio raiz para o apache2 caso necessario normalmente em /etc/apache2/sites-available/000-default.conf modificando a linha para /var/www/html/Gestor-Log-Mikrotik-Linux   
```
nano /etc/apache2/sites-available/000-default.conf
```
Exemplo abaixo de como deve ficar 
```
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/Gestor-Log-Mikrotik-Linux
</VirtualHost>
```
Reiniciar o apache
```
service apache2 restart
```
Estando dentro da pasta do sistema importar as tabelas de usuario
exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# mysql -p Syslog < usuarios.sql 
exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# mysql -p Syslog < usuario_log.sql 
```
cd /var/www/html/Gestor-Log-Mikrotik-Linux
mysql -p Syslog < usuarios.sql
mysql -p Syslog < usuario_log.sql
```
Editar o arquivo definindo o login e senha do banco de dados
Exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# nano site/Connections/site.php
```
nano /var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php
```
Permissoes conforme necessidade na pasta do projeto /var/www/html/Gestor-Log-Mikrotik-Linux
```
chmod -R 775 /var/www/html/Gestor-Log-Mikrotik-Linux
```
Agendar no crontab para o php execultar o backup a cada 2 hora comando abaixo e adicionando a linha conforme necessidade
```
crontab -e
```` 
0 */2 * * * php /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/action_backup_agendado.php

Criar uma pasta para o backup e dar as devidas permissao de leitura e escrita
```
mkdir /var/backups/gestorlog
chmod -R 775 /var/backups/gestorlog
```
Criar uma pasta onde vai conter apenas os backup do dia com as devidas permissoes 
```
mkdir /var/backups/gestorlog/diario
chmod -R 775 /var/backups/gestorlog/diario
```
Compactar backups e formar um unico arquivo diario criar um script .sh em /var/backups/gestorlog e agendar no crontab 
```
nano /var/backups/gestorlog/backup_diario.sh
```
Adicionar o conteudo abaixo no arquivo criado backup_diario.sh
```
#!/bin/bash
ORIGEM_PASTA=/var/backups/gestorlog/diario
DATA=$(date +"%m-%d-%Y")
ARQUIVO_DESTINO="/var/backups/gestorlog/$DATA.tar.gz"
echo "Backup esta sendo gerando em /var/backups/gestorlog/$DATA.tar.gz aquivo, por favor aguarde..."
/bin/tar -czvf  $ARQUIVO_DESTINO $ORIGEM_PASTA
#apaga backup apos compactar
rm /var/backups/gestorlog/diario/*
#reinicia syslog
service syslog restart
```
Agendar no crontab
```
crontab -e
```
Adicione a linha abaixo caso queira que seja feito todo dia as 1 da manhã
```
0 1 * * * /bin/bash /var/backups/gestorlog/backup_diario.sh
```

Acesse a linha de comando e entre no servidor MySQL:
```
mysql
```
O Script vai retornar este resultado, que verifica que você está acessando um servidor MySQL.
```
mysql>
```
Então execute o seguinte comando:
```
CREATE USER 'novo_usuário'@'localhost' IDENTIFIED BY 'senha';
```
novo_usuário é o nome que damos para a nossa nova conta de usuário e a seção IDENTIFIED BY ‘senha’ define um código de acesso para esse usuário. Você pode substituir esses valores com os seus próprios, desde que só altere o que está dentro das aspas.
Para garantir todos os privilégios do banco de dados para um usuário recém-criado, execute o seguinte comando:
```
GRANT ALL PRIVILEGES ON * . * TO 'novo_usuario'@'localhost';
```
Para que as mudanças tenham efeito, execute imediatamente um flush dos privilégios ao executar o seguinte comando:
```
FLUSH PRIVILEGES;
```
