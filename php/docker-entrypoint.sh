#!/bin/bash
set -e

echo "🚀 Starting Laravel container setup..."

APP_DIR="/var/www/html"
ENV_FILE="$APP_DIR/.env"

# Default:
# true  = keep Docker development values synchronized into Laravel .env
# false = do not touch existing Laravel .env except APP_KEY generation
AUTO_SYNC_ENV="${AUTO_SYNC_ENV:-true}"

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

escape_env_value() {
  printf "%s" "$1" | sed -e 's/\\/\\\\/g' -e 's/"/\\"/g'
}

set_env_value() {
  local key="$1"
  local value="$2"
  local quoted_value

  quoted_value="\"$(escape_env_value "$value")\""

  touch "$ENV_FILE"

  if grep -qE "^${key}=" "$ENV_FILE"; then
    sed -i "s|^${key}=.*|${key}=${quoted_value}|" "$ENV_FILE"
  else
    printf "\n%s=%s\n" "$key" "$quoted_value" >> "$ENV_FILE"
  fi
}

set_env_value_raw() {
  local key="$1"
  local value="$2"

  touch "$ENV_FILE"

  if grep -qE "^${key}=" "$ENV_FILE"; then
    sed -i "s|^${key}=.*|${key}=${value}|" "$ENV_FILE"
  else
    printf "\n%s=%s\n" "$key" "$value" >> "$ENV_FILE"
  fi
}

sync_laravel_env_for_docker() {
  echo "📄 Syncing Laravel .env for Docker development..."

  # App
  set_env_value APP_NAME "$(get_env APP_NAME "Winnicode")"
  set_env_value_raw APP_ENV "$(get_env APP_ENV "local")"
  set_env_value_raw APP_KEY "$(get_env APP_KEY "")"
  set_env_value_raw APP_DEBUG "$(get_env APP_DEBUG "true")"
  set_env_value APP_TIMEZONE "$(get_env APP_TIMEZONE "Asia/Jakarta")"
  set_env_value APP_URL "$(get_env APP_URL "https://winnicode.test")"
  set_env_value ASSET_URL "$(get_env ASSET_URL "https://winnicode.test")"
  set_env_value_raw DEBUGBAR_ENABLED "$(get_env DEBUGBAR_ENABLED "false")"

  # Locale
  set_env_value_raw APP_LOCALE "$(get_env APP_LOCALE "en")"
  set_env_value_raw APP_FALLBACK_LOCALE "$(get_env APP_FALLBACK_LOCALE "en")"
  set_env_value_raw APP_FAKER_LOCALE "$(get_env APP_FAKER_LOCALE "en_US")"

  # Log
  set_env_value_raw LOG_CHANNEL "$(get_env LOG_CHANNEL "stack")"
  set_env_value_raw LOG_STACK "$(get_env LOG_STACK "single")"
  set_env_value_raw LOG_LEVEL "$(get_env LOG_LEVEL "debug")"

  # Database
  set_env_value_raw DB_CONNECTION "$(get_env DB_CONNECTION "mariadb")"
  set_env_value_raw DB_HOST "$(get_env DB_HOST "winnicode_db")"
  set_env_value_raw DB_PORT "$(get_env DB_PORT "3306")"
  set_env_value_raw DB_DATABASE "$(get_env DB_DATABASE "$(get_env MYSQL_DATABASE "winnicode")")"
  set_env_value_raw DB_USERNAME "$(get_env DB_USERNAME "$(get_env MYSQL_USER "rassyz")")"

  # Password is read from Docker environment.
  # No password is hardcoded inside this script.
  set_env_value DB_PASSWORD "$(get_env DB_PASSWORD "$(get_env MYSQL_PASSWORD "")")"

  # Session / queue / cache
  set_env_value_raw SESSION_DRIVER "$(get_env SESSION_DRIVER "database")"
  set_env_value_raw SESSION_LIFETIME "$(get_env SESSION_LIFETIME "120")"
  set_env_value_raw SESSION_ENCRYPT "$(get_env SESSION_ENCRYPT "true")"
  set_env_value_raw SESSION_PATH "$(get_env SESSION_PATH "/")"
  set_env_value_raw SESSION_DOMAIN "$(get_env SESSION_DOMAIN "null")"

  set_env_value_raw BROADCAST_CONNECTION "$(get_env BROADCAST_CONNECTION "log")"
  set_env_value_raw FILESYSTEM_DISK "$(get_env FILESYSTEM_DISK "local")"
  set_env_value_raw QUEUE_CONNECTION "$(get_env QUEUE_CONNECTION "database")"
  set_env_value_raw CACHE_STORE "$(get_env CACHE_STORE "database")"

  # Redis / mail
  set_env_value_raw REDIS_CLIENT "$(get_env REDIS_CLIENT "phpredis")"
  set_env_value_raw REDIS_HOST "$(get_env REDIS_HOST "127.0.0.1")"
  set_env_value_raw REDIS_PASSWORD "$(get_env REDIS_PASSWORD "null")"
  set_env_value_raw REDIS_PORT "$(get_env REDIS_PORT "6379")"

  set_env_value_raw MAIL_MAILER "$(get_env MAIL_MAILER "log")"
  set_env_value_raw MAIL_SCHEME "$(get_env MAIL_SCHEME "null")"
  set_env_value_raw MAIL_HOST "$(get_env MAIL_HOST "127.0.0.1")"
  set_env_value_raw MAIL_PORT "$(get_env MAIL_PORT "2525")"
  set_env_value_raw MAIL_USERNAME "$(get_env MAIL_USERNAME "null")"
  set_env_value_raw MAIL_PASSWORD "$(get_env MAIL_PASSWORD "null")"
  set_env_value MAIL_FROM_ADDRESS "$(get_env MAIL_FROM_ADDRESS "hello@example.com")"
  set_env_value MAIL_FROM_NAME "$(get_env MAIL_FROM_NAME "$(get_env APP_NAME "Winnicode")")"

  # Vite
  set_env_value VITE_APP_NAME "$(get_env VITE_APP_NAME "$(get_env APP_NAME "Winnicode")")"
}

# Step 1: Create Laravel project if not already present
if [ ! -f "$APP_DIR/artisan" ]; then
  echo "📦 Creating Laravel project (fila-starter)..."
  composer create-project --prefer-dist raugadh/fila-starter:4.0.1 . --no-interaction
fi

# Step 2: Ensure that .env file exists
if [ ! -f "$ENV_FILE" ] && [ -f /php/.env ]; then
  echo "📄 Copying .env from /php to /var/www/html"
  cp /php/.env "$ENV_FILE"
elif [ ! -f "$ENV_FILE" ] && [ -f "$APP_DIR/.env.example" ]; then
  echo "📄 Creating .env from Laravel .env.example"
  cp "$APP_DIR/.env.example" "$ENV_FILE"
elif [ ! -f "$ENV_FILE" ]; then
  echo "📄 Creating empty .env file"
  touch "$ENV_FILE"
else
  echo "✅ .env file already exists."
fi

# Step 3: Automatically prepare .env for Docker development without overwriting the whole file
if [ "$AUTO_SYNC_ENV" = "true" ]; then
  sync_laravel_env_for_docker
else
  echo "✅ AUTO_SYNC_ENV=false. Keeping existing .env values."
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

echo "🔎 Discovering Laravel packages..."
php artisan package:discover --ansi || true

echo "🎨 Upgrading/publishing Filament assets..."
php artisan filament:upgrade || true

echo "🎨 Upgrading/publishing theme assets..."
php artisan themes:upgrade || true

echo "🎨 Building frontend assets..."
if [ -f /var/www/html/package.json ]; then
  npm install
  npm run build
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