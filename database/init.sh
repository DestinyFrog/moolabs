#!/usr/bin/bash

# Criar diretório de logs do MySQL
mkdir -p /var/log/mysql
chown -R mysql:mysql /var/log/mysql

# Iniciar MySQL
echo "🔄 Iniciando MySQL..."
service mysql start

# Aguardar MySQL inicializar
sleep 5

# Configurar MySQL
echo "⚙️ Configurando MySQL..."
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';" || true

# Criar usuário e banco de dados
echo "🗃️ Criando banco de dados e usuário..."
mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS moolabs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" || true
mysql -uroot -proot -e "CREATE USER IF NOT EXISTS 'moolabs'@'%' IDENTIFIED BY 'senha';" || true
mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON moolabs.* TO 'moolabs'@'%';" || true
mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON moolabs.* TO 'moolabs'@'localhost';" || true
mysql -uroot -proot -e "FLUSH PRIVILEGES;" || true

# Executar script SQL se existir
if [ -f /tmp/setup.sql ]; then
    echo "📥 Executando script SQL..."
    mysql -uroot -proot moolabs < /tmp/setup.sql
    echo "✅ Script SQL executado com sucesso!"
fi

# Configurar permissões do MySQL
chown -R mysql:mysql /var/lib/mysql
chown -R mysql:mysql /var/run/mysqld
chown -R mysql:mysql /var/log/mysql

# Iniciar Apache
echo "🌐 Iniciando Apache..."
service apache2 start

# Verificar status dos serviços
echo "🔍 Verificando status dos serviços..."
service mysql status
service apache2 status

echo ""
echo "🎉 Ambiente configurado com sucesso!"
echo "📍 Aplicação: http://localhost:8080"
echo "🗃️  MySQL:"
echo "   - Host: 127.0.0.1 ou localhost"
echo "   - Porta: 3306"
echo "   - Usuário: moolabs"
echo "   - Senha: senha"
echo "   - Banco: moolabs"

# Manter container rodando e mostrar logs
echo ""
echo "📋 Iniciando monitoramento de logs..."
tail -f /var/log/apache2/access.log /var/log/apache2/error.log /var/log/mysql/error.log