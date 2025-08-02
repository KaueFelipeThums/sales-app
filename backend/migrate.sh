#!/bin/bash

# Verifica se o parâmetro foi passado
if [ -z "$1" ]; then
  echo "Uso: ./migrate.sh [up|down]"
  exit 1
fi

# Nome do container
CONTAINER_NAME="salesapp-api"

# Caminho para o migrate.php dentro do container
MIGRATE_PATH="/var/www/html/scripts/migrate.php"

# Roda o comando no container
docker exec -it "$CONTAINER_NAME" php "$MIGRATE_PATH" "$1"
