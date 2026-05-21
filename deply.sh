#!/bin/bash

# ── Configuración ──────────────────────────────────────
SERVER_USER="tu_usuario"
SERVER_HOST="tu.servidor.com"
SERVER_PATH="/var/www/tu_proyecto"
SSH_KEY="$HOME/.ssh/id_rsa"        # En Git Bash $HOME apunta a C:/Users/TuUsuario
BRANCH="main"
# ───────────────────────────────────────────────────────

echo "🚀 Iniciando deploy de rama '$BRANCH'..."

git checkout $BRANCH && git pull origin $BRANCH

ssh -i $SSH_KEY $SERVER_USER@$SERVER_HOST << EOF
  set -e

  echo "📂 Entrando al proyecto..."
  cd $SERVER_PATH

  echo "⬇️  Actualizando código..."
  git pull origin $BRANCH

  echo "📦 Instalando dependencias..."
  composer install --no-dev --optimize-autoloader

  echo "⚙️  Limpiando caché..."
  php artisan cache:clear 2>/dev/null || true
  php artisan config:cache 2>/dev/null || true

  echo "✅ Deploy finalizado!"
EOF