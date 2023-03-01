#Imagem Ubuntu com Nginx
FROM bitnami/php-fpm:8.1-debian-11

# Arguments defined in docker-compose.yml
ARG user=developer
ARG uid=1000

#Instala dependencias do sistema
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y \
    nano \
    sudo \
    composer \
    php-xdebug

#Adiciona arquivos do projeto para o container
ADD . /var/www/html

#Cria usuário
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

#Copia permissões da pasta do projeto para o usuário developer
RUN chown -R developer:developer /var/www/html