FROM ubuntu:22.04

# Evitar prompts interativos
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    apache2 \
    php8.1 \
    php8.1-mysql \
    php8.1-curl \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-zip \
    php8.1-gd \
    php8.1-intl \
    mysql-server \
    mysql-client \
    curl \
    wget \
    git \
    nano \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurar Apache
RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configurar PHP
RUN echo "display_errors = On" >> /etc/php/8.1/apache2/php.ini
RUN echo "display_startup_errors = On" >> /etc/php/8.1/apache2/php.ini
RUN echo "error_reporting = E_ALL" >> /etc/php/8.1/apache2/php.ini
RUN echo "upload_max_filesize = 10M" >> /etc/php/8.1/apache2/php.ini
RUN echo "post_max_size = 10M" >> /etc/php/8.1/apache2/php.ini

# Configurar MySQL - my.cnf personalizado
COPY /database/my.cnf /etc/mysql/my.cnf

# Configurar diretórios do MySQL
RUN mkdir -p /var/run/mysqld
RUN chown mysql:mysql /var/run/mysqld
RUN chown -R mysql:mysql /var/lib/mysql

# Copiar script de inicialização
COPY database/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# Configurar banco de dados
COPY database/init.sql /tmp/setup.sql

# Configurar diretório da aplicação
WORKDIR /var/www/html
RUN rm -r /var/www/html/*
COPY . /var/www/html/

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expor portas
EXPOSE 80

# Comando de inicialização
CMD ["/usr/local/bin/init.sh"]