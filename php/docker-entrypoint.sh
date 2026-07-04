#!/bin/bash
set -e

echo "🚀 Starting Laravel container setup..."

APP_DIR="/var/www/html"
ENV_FILE="$APP_DIR/.env"

get_env() {
  local key="$1"
  local default_value="${2:-}"
  local value="${!key:-}"

  if [ -n "$value" ]; then
    printf "%s" "$value"
  else
    printf "%s" "$default_value"
  fi
}

get_dotenv_value() {
  local key="$1"

  if [ -f "$ENV_FILE" ]; then
    grep -E "^${key}=" "$ENV_FILE" | tail -n 1 | cut -d '=' -f2- | sed -e "s/^['\"]//" -e "s/['\"]$//"
  fi
}

# Step 1: Create Laravel project if not already present
if [ ! -f "$APP_DIR/artisan" ]; then
  echo "📦 Creating Laravel project (fila-starter)..."
  composer create-project --prefer-dist raugadh/fila-starter^3.0 . --no-interaction
fi

create_default_env() {
  echo "📄 Creating .env file from container environment variables..."

  cat > "$ENV_FILE" <<EOF
APP_NAME="$(get_env APP_NAME "Winnicode")"
APP_ENV=$(get_env APP_ENV "local")
APP_KEY=$(get_env APP_KEY "")
APP_DEBUG=$(get_env APP_DEBUG "true")
APP_TIMEZONE=$(get_env APP_TIMEZONE "Asia/Jakarta")
APP_URL=$(get_env APP_URL "https://winnicode.test")
ASSET_URL=$(get_env ASSET_URL "https://winnicode.test")
DEBUGBAR_ENABLED=$(get_env DEBUGBAR_ENABLED "false")

ASSET_PREFIX=
# ASSET_PREFIX=/dev/kit/public example in case deployed inside a folder

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=$(get_env DB_CONNECTION "mariadb")
DB_HOST=$(get_env DB_HOST "winnicode_db")
DB_PORT=$(get_env DB_PORT "3306")
DB_DATABASE=$(get_env DB_DATABASE "$(get_env MYSQL_DATABASE "winnicode")")
DB_USERNAME=$(get_env DB_USERNAME "$(get_env MYSQL_USER "rassyz")")
DB_PASSWORD=$(get_env DB_PASSWORD "$(get_env MYSQL_PASSWORD "")")

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="$(get_env APP_NAME "Winnicode")"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="$(get_env APP_NAME "Winnicode")"
EOF
}

# Step 2: Ensure that .env file is copied from /php/.env if not already done
if [ ! -f "$ENV_FILE" ] && [ -f /php/.env ]; then
  echo "📄 Copying .env from /php to /var/www/html"
  cp /php/.env "$ENV_FILE"
fi

# Step 3: If .env file doesn't exist, create and add necessary environment variables.
# IMPORTANT: If .env already exists, do not overwrite it.
if [ ! -f "$ENV_FILE" ]; then
  create_default_env
else
  echo "✅ .env file already exists. Keeping existing .env without overwriting."
fi

# Step 4: Wait for DB connection (host should match DB_HOST in .env)
DB_HOST=$(get_dotenv_value DB_HOST)
DB_PORT=$(get_dotenv_value DB_PORT)

DB_HOST=${DB_HOST:-winnicode_db}
DB_PORT=${DB_PORT:-3306}

echo "⏳ Waiting for database at $DB_HOST:$DB_PORT..."

# Timeout after 30 attempts (1 minute)
RETRIES=30
until nc -z "$DB_HOST" "$DB_PORT"; do
  if [ "$RETRIES" -le 0 ]; then
    echo "❌ Timeout waiting for database. Exiting."
    exit 1
  fi

  echo "Waiting for DB..."
  sleep 2
  RETRIES=$((RETRIES - 1))
done

echo "✅ Database is ready!"

# Step 6: Install dependencies if not already installed
if [ ! -d "$APP_DIR/vendor" ]; then
  echo "📦 Installing composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Step 7: Generate app key if not already present
CURRENT_APP_KEY=$(get_dotenv_value APP_KEY)

if [ -z "$CURRENT_APP_KEY" ]; then
  echo "🔑 Generating Laravel app key..."
  php artisan key:generate --force
else
  echo "✅ Laravel APP_KEY already exists."
fi

# Step 8: Create necessary folders and set permissions
echo "🔧 Fixing permissions..."
mkdir -p "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
chown -R www-data:www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# Step 9: Run database migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Step 10: Run custom project init command
echo "⚙️ Running project:init..."
php artisan project:init || true

# Step 11: Create storage symbolic link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Step 12: Start cron
echo "⏰ Starting cron service..."
service cron start

# Step 13: Export development variables from .env to shell
for VAR in XDEBUG PHP_IDE_CONFIG REMOTE_HOST; do
  if [ -z "${!VAR}" ] && [ -f "$ENV_FILE" ]; then
    VALUE=$(grep "^$VAR=" "$ENV_FILE" | cut -d '=' -f2-)

    if [ -n "$VALUE" ]; then
      sed -i "/^export $VAR=/d" ~/.bashrc
      echo "export $VAR=$VALUE" >> ~/.bashrc
    fi
  fi
done

. ~/.bashrc

# Step 14: Set REMOTE_HOST default if still not defined
if [ -z "$REMOTE_HOST" ]; then
  REMOTE_HOST="host.docker.internal"

  sed -i "/^export REMOTE_HOST=/d" ~/.bashrc
  echo "export REMOTE_HOST=\"$REMOTE_HOST\"" >> ~/.bashrc

  . ~/.bashrc
fi

# Step 15: Toggle Xdebug support
XDEBUG_CONFIG="/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"

if [ "$XDEBUG" == "true" ] && [ ! -f "$XDEBUG_CONFIG" ]; then
  echo "🐞 Enabling Xdebug..."

  sed -i '/PHP_IDE_CONFIG/d' /etc/cron.d/laravel-scheduler

  if [ -n "$PHP_IDE_CONFIG" ]; then
    echo -e "PHP_IDE_CONFIG=\"$PHP_IDE_CONFIG\"\n$(cat /etc/cron.d/laravel-scheduler)" > /etc/cron.d/laravel-scheduler
  fi

  docker-php-ext-enable xdebug

  {
    echo "xdebug.remote_enable=1"
    echo "xdebug.remote_autostart=1"
    echo "xdebug.remote_connect_back=0"
    echo "xdebug.remote_host=$REMOTE_HOST"
  } >> "$XDEBUG_CONFIG"

elif [ -f "$XDEBUG_CONFIG" ]; then
  echo "🐞 Disabling Xdebug..."

  sed -i '/PHP_IDE_CONFIG/d' /etc/cron.d/laravel-scheduler
  rm -f "$XDEBUG_CONFIG"
fi

echo "✅ Laravel container setup complete. Ready to serve!"

exec "$@"
