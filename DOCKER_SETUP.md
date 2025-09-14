# Docker Setup for Placement Portal

This Docker setup works with your existing codebase without modifying any PHP or SQL files.

## Quick Start

1. **Start the application**:

   ```bash
   docker-compose up -d
   ```

2. **Access the application**:
   - **Main Application**: http://localhost:8080
   - **phpMyAdmin**: http://localhost:8081
   - **MySQL Database**: localhost:3306

## Services

- **Web Application**: PHP 8.2 + Apache on port 8080
- **MySQL Database**: MySQL 8.0 on port 3306
- **phpMyAdmin**: Database management on port 8081

## Database Configuration

The Docker setup automatically:

- Creates the `campushire` database
- Runs your existing `database/create.sql` to create tables
- Runs your existing `database/seed.sql` for initial data
- Uses your existing database connection settings in `db-functions.php`

## Default Credentials

- **Database**: root / rootpassword
- **phpMyAdmin**: root / rootpassword

## Common Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Rebuild containers
docker-compose up -d --build

# Reset database (removes all data)
docker-compose down -v
docker-compose up -d
```

## File Structure

Your existing files remain unchanged:

- `db-functions.php` - Uses localhost connection (works with Docker networking)
- `database/create.sql` - Automatically executed on first run
- `database/seed.sql` - Automatically executed on first run
- All your PHP files work as-is

The Docker setup adapts to your existing codebase configuration.
