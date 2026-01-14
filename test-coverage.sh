#!/bin/bash

# Script para executar testes com coverage usando PCOV
# PCOV é mais rápido que Xdebug para coverage

echo "Verificando se o container tem PCOV instalado..."

# Ir para o diretório do docker-compose
cd /home/ricardo/Documents/projects/mindease/mindease_environment

# Verificar se PCOV está disponível
HAS_PCOV=$(docker compose run --rm api php -m | grep pcov || echo "")

if [ -z "$HAS_PCOV" ]; then
    echo "PCOV não encontrado. Fazendo rebuild do container..."
    docker compose build api
    echo "Rebuild concluído!"
fi

echo "Executando testes com coverage usando PCOV..."
echo "Nota: Xdebug será desabilitado temporariamente para melhor performance"

# Executar testes com coverage, desabilitando Xdebug e habilitando PCOV
docker compose run --rm api php \
    -d zend_extension= \
    -d pcov.enabled=1 \
    -d pcov.directory=/var/www/html/app \
    artisan test --coverage --coverage-html=coverage-report

echo "Coverage report gerado em: mindease_api/coverage-report/"
echo "Abra mindease_api/coverage-report/index.html no navegador para visualizar o relatório."
