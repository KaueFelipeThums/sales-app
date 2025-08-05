#!/bin/bash

# Nome do container Docker
CONTAINER_NAME="salesapp-api"

# Verifica se o container está rodando
if docker ps --format '{{.Names}}' | grep -q "^${CONTAINER_NAME}$"; then
  echo "Acessando o container '${CONTAINER_NAME}' e executando 'composer install'..."
  docker exec -it "$CONTAINER_NAME" composer install
else
  echo "❌ Container '${CONTAINER_NAME}' não está rodando."
  echo "Use 'docker ps' para verificar os containers ativos."
fi