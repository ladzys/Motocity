# 🏍 MotoCity – Motorbike Rental Web Application

MotoCity is a dynamic web application built using **Object-Oriented PHP** and **MySQL**.  
The system simulates a city-based motorbike rental platform with secure authentication, role-based access control, and automated rental cost calculation.

---

## 🚀 Project Overview

MotoCity allows users to rent and return motorbikes from different locations within a city.  
The system supports two roles:

- **Administrator**
- **User**

Each role has specific permissions and functionalities within the system.

---

## 👤 Authentication

- User & Administrator registration
- Secure login/logout using PHP sessions
- Role-based dashboards
- Input validation and secure password handling

---

## 🏍 Motorbike Management

**Administrator can:**
- Insert new motorbikes
- Edit existing motorbikes
- View all motorbikes
- View available motorbikes
- View currently rented motorbikes
- Search motorbikes (partial match supported)

Each motorbike includes:
- Code
- Renting Location
- Description
- Cost per hour
- Availability status

---

## 📄 Rental System

**User can:**
- View available motorbikes
- Search motorbikes (by code, location, description)
- Rent a motorbike (with start date/time input)
- Receive notification showing cost per hour
- Return rented motorbike
- Receive notification showing total rental cost
- View current rentals
- View completed rental history

**Administrator can:**
- Rent a motorbike for a specific user
- Return a motorbike for a specific user
- View users currently renting bikes

---

## 💰 Rental Cost Calculation

The total rental cost is calculated dynamically:

Total Cost = (Return Time - Start Time) × Cost Per Hour


The system ensures:
- Motorbike must be available before renting
- Proper date/time validation
- Accurate cost computation
- Secure database transactions

---

## 🗄 Database Structure

The system uses three main tables:

### `users`
- id (Primary Key)
- name
- surname
- phone
- email
- password
- type (admin/user)

### `motorbikes`
- code (Primary Key)
- renting_location
- description
- cost_per_hour
- status (available/rented)

### `rentals`
- id (Primary Key)
- user_id (Foreign Key)
- bike_code (Foreign Key)
- start_datetime
- end_datetime
- total_cost
- status (active/completed)

Relational structure ensures proper tracking of rentals and user activity.

---

## 🧠 Technologies Used

- PHP (Object-Oriented Programming)
- MySQL
- PDO (Prepared Statements)
- HTML / CSS
- PHP Sessions

---

## 🔐 Security Measures

- Prepared statements to prevent SQL injection
- Input validation on all forms
- Role-based access restrictions
- Session-based authentication

---

## 📦 Installation Guide

1. Clone this repository:
git clone https://github.com/yourusername/motocity.git


2. Move project folder into your XAMPP `htdocs` directory.

3. Start **Apache** and **MySQL** from XAMPP.

4. Import the provided `motocity.sql` file into phpMyAdmin.

5. Configure database credentials in:
config/database.php


6. Open in browser:
http://localhost/MotoCity


---

## 🎯 Learning Outcomes

This project demonstrates:

- Object-Oriented PHP design
- CRUD operations
- Relational database modeling
- Session management
- Backend validation logic
- Role-based system implementation

---

## 📌 Future Improvements

- Add payment gateway integration
- Implement booking time slots
- Improve UI with modern frontend framework
- Add reporting dashboard with analytics
- Implement API version for mobile support

---

## 👨‍💻 Author

Developed by Jiyavudeen  
---
