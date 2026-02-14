# MotoCity Implementation Details

## Overview

MotoCity is a fully functional Object-Oriented PHP web application for motorbike rentals. It demonstrates modern PHP development practices with a focus on security, maintainability, and user experience.

## Architecture

### Object-Oriented Design

The application follows OOP principles with the following class structure:

#### 1. Database Class (Singleton Pattern)
- **File:** `classes/Database.php`
- **Purpose:** Manages database connection using PDO
- **Pattern:** Singleton - ensures only one database connection exists
- **Features:**
  - Lazy initialization
  - PDO configuration with error handling
  - Prevents cloning and unserialization

#### 2. User Class
- **File:** `classes/User.php`
- **Purpose:** Handles user authentication and authorization
- **Key Methods:**
  - `register()` - User registration with validation
  - `login()` - User authentication with password verification
  - `logout()` - Session cleanup
  - `isLoggedIn()` - Check authentication status
  - `isAdmin()` - Check admin role
  - `requireLogin()` - Enforce authentication
  - `requireAdmin()` - Enforce admin role

#### 3. Motorbike Class
- **File:** `classes/Motorbike.php`
- **Purpose:** CRUD operations for motorbikes
- **Key Methods:**
  - `getAll()` - Retrieve all bikes
  - `getAvailable()` - Get available bikes only
  - `search()` - Search with partial match (LIKE query)
  - `getById()` - Get single bike
  - `insert()` - Add new bike
  - `update()` - Edit existing bike
  - `delete()` - Remove bike
  - `updateAvailability()` - Change availability status

#### 4. Rental Class
- **File:** `classes/Rental.php`
- **Purpose:** Manage rental operations
- **Key Methods:**
  - `create()` - Create new rental with datetime validation
  - `returnRental()` - Complete rental with automatic cost calculation
  - `getAll()` - Get all rentals
  - `getByUserId()` - Get user's rental history
  - `getById()` - Get single rental
  - `getActive()` - Get active rentals

## Security Implementation

### 1. SQL Injection Prevention
- **All** database queries use prepared statements
- No raw SQL concatenation with user input
- PDO parameterized queries throughout

Example:
```php
$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
```

### 2. Password Security
- Passwords hashed using `password_hash()` with PASSWORD_DEFAULT
- Verification using `password_verify()`
- Minimum password length enforced (6 characters)

### 3. Session Security
- HTTP-only cookies enabled
- Session regeneration on login
- Proper session cleanup on logout
- Role-based access control (RBAC)

### 4. Input Validation
- Server-side validation for all inputs
- Email format validation using `filter_var()`
- Numeric validation for prices, years, etc.
- Empty field checks
- Date/time format validation

### 5. Access Control
- Role-based authentication (Admin/User)
- `requireLogin()` enforces authentication
- `requireAdmin()` enforces admin role
- Automatic redirection for unauthorized access

## Database Schema

### Users Table
```sql
- id (PRIMARY KEY, AUTO_INCREMENT)
- username (UNIQUE)
- email (UNIQUE)
- password (hashed)
- role (ENUM: 'Admin', 'User')
- created_at (TIMESTAMP)
```

### Motorbikes Table
```sql
- id (PRIMARY KEY, AUTO_INCREMENT)
- brand
- model
- year
- price_per_day (DECIMAL)
- availability (ENUM: 'Available', 'Rented')
- description (TEXT)
- created_at (TIMESTAMP)
```

### Rentals Table
```sql
- id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY -> users.id)
- motorbike_id (FOREIGN KEY -> motorbikes.id)
- start_datetime (DATETIME)
- end_datetime (DATETIME)
- total_cost (DECIMAL)
- status (ENUM: 'Active', 'Completed')
- created_at (TIMESTAMP)
```

## Features Implementation

### 1. User Registration
- **File:** `register.php`
- Username uniqueness check
- Email format validation
- Password strength requirement (min 6 chars)
- Duplicate prevention
- Default role assignment (User)

### 2. User Login
- **File:** `login.php`
- Credential validation
- Password verification
- Session creation
- Role-based redirection
- Session regeneration for security

### 3. Admin Dashboard
- **File:** `admin/dashboard.php`
- Statistics display (total bikes, available, active rentals)
- Active rentals overview
- Quick action buttons
- Role verification

### 4. Motorbike Management (Admin)
- **File:** `admin/motorbikes.php`
- Add new motorbikes
- Edit existing bikes
- Delete bikes
- View all bikes with status
- Form validation

### 5. Rental Management (Admin)
- **File:** `admin/rentals.php`
- View all rentals
- Complete active rentals
- Automatic cost calculation on return

### 6. Browse Bikes (User)
- **File:** `user/bikes.php`
- Display available bikes
- **Search functionality with partial match**
- Visual availability indicators
- Responsive grid layout

### 7. Rent Bike (User)
- **File:** `user/rent.php`
- Select start datetime
- Future datetime validation
- Availability check
- Transaction handling

### 8. Rental History (User)
- **File:** `user/rentals.php`
- View all user rentals
- Active and completed rentals
- Duration calculation
- Cost display

### 9. Cost Calculation Algorithm
```php
// Location: classes/Rental.php - returnRental()
1. Calculate difference between start and end datetime
2. Extract days and hours
3. If hours > 0 or minutes > 0, round up to next day
4. Minimum rental period: 1 day
5. Total cost = days × price_per_day
```

## User Interface

### Design Principles
- Clean and modern interface
- Gradient backgrounds for visual appeal
- Card-based layouts for content organization
- Consistent color scheme (purple/blue theme)
- Responsive tables and grids
- Clear call-to-action buttons
- Status badges for visual feedback

### Navigation
- Consistent navbar across all pages
- Role-specific menu items
- Logout always accessible
- Breadcrumb-style navigation

## File Structure

```
/motocity
├── admin/                  # Admin-only pages
│   ├── dashboard.php      # Admin overview
│   ├── motorbikes.php     # Bike CRUD operations
│   └── rentals.php        # Rental management
├── classes/               # OOP classes
│   ├── Database.php       # DB connection (Singleton)
│   ├── User.php          # Authentication & authorization
│   ├── Motorbike.php     # Bike operations
│   └── Rental.php        # Rental operations
├── user/                  # User pages
│   ├── dashboard.php     # User overview
│   ├── bikes.php         # Browse & search bikes
│   ├── rent.php          # Rental booking
│   └── rentals.php       # Rental history
├── config.php            # Configuration
├── database.sql          # Database schema
├── index.php            # Landing page
├── login.php            # Authentication
├── register.php         # User registration
├── logout.php           # Session cleanup
├── .gitignore           # Git exclusions
├── INSTALLATION.md      # Setup guide
└── IMPLEMENTATION.md    # This file
```

## Testing Checklist

- [x] Database connection works
- [x] User registration creates new users
- [x] Login authenticates users correctly
- [x] Role-based redirection works
- [x] Admin can add/edit/delete bikes
- [x] Admin can view and manage rentals
- [x] Users can browse available bikes
- [x] Search functionality works (partial match)
- [x] Users can rent bikes with datetime selection
- [x] Cost calculation is accurate
- [x] Rental history displays correctly
- [x] Session management works properly
- [x] Access control enforces roles
- [x] All queries use prepared statements
- [x] Input validation prevents invalid data

## Future Enhancements (Optional)

1. **Email Notifications** - Send rental confirmations
2. **Payment Integration** - Process actual payments
3. **Image Upload** - Add bike photos
4. **Rating System** - User reviews and ratings
5. **Availability Calendar** - Visual booking calendar
6. **Advanced Search** - Filter by price range, year, brand
7. **User Profiles** - Edit profile information
8. **Maintenance Tracking** - Track bike maintenance
9. **Reports** - Generate rental and revenue reports
10. **API** - RESTful API for mobile apps

## Conclusion

MotoCity demonstrates a complete, secure, and functional web application built with Object-Oriented PHP and MySQL. It implements all required features including authentication, role-based access, CRUD operations, search functionality, and automatic cost calculation while maintaining security best practices throughout.
