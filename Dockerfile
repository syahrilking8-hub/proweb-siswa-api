FROM php:8.2-cli

# Install SQLite & PDO SQLite
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Copy semua file ke folder aplikasi
COPY . /app
WORKDIR /app

# Beri izin penuh ke database & folder
RUN chmod -R 777 /app

# Jalankan PHP Built-in Server langsung di port yang dikasih Railway
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /app"]
