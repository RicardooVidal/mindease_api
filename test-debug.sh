#!/bin/bash

# Script para executar testes com Xdebug habilitado (para debugging)

echo "Executando testes com Xdebug habilitado para debugging..."

# Ir para o diretório do docker-compose
cd /home/ricardo/Documents/projects/mindease/mindease_environment

# Executar testes com Xdebug habilitado
docker compose run --rm api php \
    -d pcov.enabled=0 \
    -d xdebug.mode=develop,debug \
    artisan test

echo "Testes executados com Xdebug habilitado."
echo "Para usar debugging, configure seu IDE para escutar na porta 9003."
