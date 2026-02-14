# MotoCity 🏍️

**Premium Motorbike Rental Management System**

MotoCity is a fully functional, Object-Oriented PHP web application for managing motorbike rentals. Built with security and user experience in mind, it demonstrates modern PHP development practices including OOP design, PDO database connections, role-based access control, and comprehensive input validation.

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## ✨ Features

### For Users
- 🔐 **Secure Registration & Login** - Password hashing, session management
- 🔍 **Smart Search** - Find bikes by brand, model, or description (partial match)
- 🏍️ **Browse Motorbikes** - View all available bikes with details and pricing
- 📅 **Easy Booking** - Rent bikes with custom start datetime
- 📊 **Rental History** - View all your past and active rentals
- 💰 **Transparent Pricing** - Automatic cost calculation based on duration

### For Administrators
- 📈 **Dashboard** - Overview of system statistics
- ➕ **Bike Management** - Add, edit, and delete motorbikes
- 🔄 **Rental Management** - View all rentals and process returns
- 👥 **User Overview** - Monitor all user activities
- ⚡ **Quick Actions** - Fast access to common tasks

## 🛠️ Technology Stack

- **Backend:** PHP 7.4+ (Object-Oriented)
- **Database:** MySQL 5.7+ with PDO
- **Architecture:** MVC-inspired with OOP classes
- **Security:** Prepared statements, password hashing, session security
- **UI:** Responsive HTML5/CSS3

## 🔒 Security Features

✓ **SQL Injection Prevention** - All queries use prepared statements  
✓ **Password Security** - BCrypt hashing with `password_hash()`  
✓ **Session Security** - HTTP-only cookies, session regeneration  
✓ **Input Validation** - Server-side validation on all inputs  
✓ **XSS Prevention** - Output escaping with `htmlspecialchars()`  
✓ **Role-Based Access** - Admin and User roles with proper checks

## 📦 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx or PHP built-in server

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/ladzys/Motocity.git
   cd Motocity
   ```

2. **Configure database**
   ```bash
   # Edit config.php with your database credentials
   mysql -u username -p < database.sql
   ```

3. **Start the server**
   ```bash
   php -S localhost:8000
   ```

4. **Access the application**
   ```
   http://localhost:8000
   ```

5. **Login with default admin**
   - Username: `admin`
   - Password: `admin123`

📖 **For detailed installation instructions, see [INSTALLATION.md](INSTALLATION.md)**

## 📚 Documentation

- **[INSTALLATION.md](INSTALLATION.md)** - Complete setup guide
- **[IMPLEMENTATION.md](IMPLEMENTATION.md)** - Technical architecture details
- **[USER_GUIDE.md](USER_GUIDE.md)** - How to use the system
- **[test_connection.php](test_connection.php)** - Database verification script

## 🗂️ Project Structure

```
/motocity
├── admin/                 # Admin panel pages
│   ├── dashboard.php     # Admin overview
│   ├── motorbikes.php    # Bike CRUD operations
│   └── rentals.php       # Rental management
├── classes/              # OOP classes
│   ├── Database.php      # PDO connection (Singleton)
│   ├── User.php          # Authentication & authorization
│   ├── Motorbike.php     # Bike operations
│   └── Rental.php        # Rental operations
├── user/                 # User pages
│   ├── dashboard.php     # User overview
│   ├── bikes.php         # Browse & search bikes
│   ├── rent.php          # Rental booking
│   └── rentals.php       # Rental history
├── config.php           # Configuration
├── database.sql         # Database schema
├── index.php           # Landing page
├── login.php           # Authentication
├── register.php        # User registration
└── logout.php          # Session cleanup
```

## 💻 Code Quality

- **Lines of Code:** 2000+
- **Architecture:** Object-Oriented PHP
- **Design Pattern:** Singleton for database connection
- **Security:** Industry-standard practices
- **Documentation:** Comprehensive inline comments

## 🚀 Features Implementation

### Cost Calculation Algorithm
```php
Duration = End DateTime - Start DateTime
Days = Complete days + Round up partial days
Minimum = 1 day
Total Cost = Days × Price per Day
```

### Search Implementation
Uses SQL LIKE queries for partial matching across:
- Brand names
- Model names
- Descriptions

### Session Management
- HTTP-only cookies
- Session regeneration on login
- Secure session cleanup
- Role-based access control

## 🧪 Testing

Run the connection test after installation:
```bash
http://localhost:8000/test_connection.php
```

This verifies:
- Database connection
- Table structure
- Sample data
- Admin account

## 📸 Screenshots

### Landing Page
Modern gradient design with call-to-action buttons

### Admin Dashboard
Statistics overview with quick access to management features

### User Interface
Clean, intuitive interface for browsing and renting bikes

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open source and available under the MIT License.

## 👨‍💻 Author

**ladzys**

## 🙏 Acknowledgments

- Built as a demonstration of Object-Oriented PHP development
- Implements security best practices
- Focuses on user experience and clean code

---

**Made with ❤️ for motorbike enthusiasts**
