FROM mysql:8.0

COPY my.cnf /etc/mysql/conf.d/
COPY init.sql /docker-entrypoint-initdb.d/

ENV MYSQL_ROOT_PASSWORD=senha
ENV MYSQL_DATABASE=moolabs
ENV MYSQL_USER=moolabs
ENV MYSQL_PASSWORD=senha

EXPOSE 3306