# MotoCity Installation Guide

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- PDO PHP Extension

## Installation Steps

### 1. Clone or Download the Repository

```bash
git clone https://github.com/ladzys/Motocity.git
cd Motocity
```

### 2. Configure Database Connection

Edit `config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'motocity');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Create Database and Import Schema

Run the following command in MySQL:

```bash
mysql -u your_username -p < database.sql
```

Or manually:
1. Open MySQL client or phpMyAdmin
2. Create a new database named `motocity`
3. Import the `database.sql` file

### 4. Configure Web Server

#### For Apache:

Place the project in your web server's document root (e.g., `/var/www/html/motocity` or `C:\xampp\htdocs\motocity`)

Make sure mod_rewrite is enabled (if needed).

#### For PHP Built-in Server (Development Only):

```bash
php -S localhost:8000
```

### 5. Update Base URL (Optional)

In `config.php`, update the BASE_URL if needed:

```php
define('BASE_URL', 'http://localhost/motocity');
```

### 6. Access the Application

Open your browser and navigate to:
- `http://localhost/motocity` (Apache)
- `http://localhost:8000` (PHP built-in server)

### 7. Default Admin Credentials

```
Username: admin
Password: admin123
```

**Important:** Change the admin password after first login!

## Features Overview

### For Users:
- Register and create an account
- Browse available motorbikes
- Search bikes by brand, model, or description (partial match)
- Rent bikes with custom start datetime
- View rental history
- Automatic cost calculation on return

### For Admins:
- All user features
- Add, edit, and delete motorbikes
- View all rentals
- Manage active rentals
- Return bikes and complete rentals

## Security Features

- Password hashing using PHP's `password_hash()`
- Prepared statements for all database queries (SQL injection prevention)
- Input validation and sanitization
- Session security with HTTP-only cookies
- Role-based access control (RBAC)

## Project Structure

```
/motocity
├── admin/              # Admin panel pages
│   ├── dashboard.php
│   ├── motorbikes.php
│   └── rentals.php
├── classes/            # OOP classes
│   ├── Database.php
│   ├── User.php
│   ├── Motorbike.php
│   └── Rental.php
├── user/              # User panel pages
│   ├── dashboard.php
│   ├── bikes.php
│   ├── rent.php
│   └── rentals.php
├── config.php         # Configuration file
├── database.sql       # Database schema
├── index.php         # Landing page
├── login.php         # Login page
├── register.php      # Registration page
└── logout.php        # Logout handler
```

## Troubleshooting

### Database Connection Issues
- Verify database credentials in `config.php`
- Ensure MySQL service is running
- Check that the database exists

### Permission Issues
- Ensure web server has read access to all files
- Check file permissions (755 for directories, 644 for files)

### Session Issues
- Check that session.save_path is writable
- Verify session configuration in php.ini

## Support

For issues or questions, please open an issue on GitHub.
