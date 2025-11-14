# Use official PHP image
FROM php:8.4-cli

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Expose port (Render will set PORT environment variable)
EXPOSE 8080

# Start PHP built-in server
CMD php -S 0.0.0.0:${PORT:-8080}

