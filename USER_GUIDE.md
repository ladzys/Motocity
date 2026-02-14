# MotoCity User Guide

## Quick Start

### First Time Setup
1. Install the application (see INSTALLATION.md)
2. Access the application at `http://localhost/motocity`
3. Login with default admin credentials:
   - **Username:** admin
   - **Password:** admin123

## User Roles

### Regular User
- Browse and search motorbikes
- Rent available bikes
- View rental history
- Return bikes (via admin)

### Administrator
- All user capabilities
- Add/edit/delete motorbikes
- View all rentals
- Complete active rentals
- Manage the entire system

## How to Use

### For Regular Users

#### 1. Register an Account
1. Click "Register" on the homepage
2. Fill in:
   - Username (unique)
   - Email (valid format)
   - Password (minimum 6 characters)
3. Click "Register"
4. Success! You can now login

#### 2. Browse Motorbikes
1. Login to your account
2. Click "Browse Bikes" in navigation
3. View all available motorbikes with:
   - Brand and model
   - Year
   - Price per day
   - Description
   - Availability status

#### 3. Search for Bikes
1. Go to "Browse Bikes"
2. Enter search term in search box
3. Searches match:
   - Brand name
   - Model name
   - Description text
4. Click "Search" or press Enter
5. View matching results
6. Click "Clear" to see all bikes again

**Example searches:**
- "Harley" - finds Harley-Davidson bikes
- "sport" - finds sport bikes in descriptions
- "2024" - finds all 2024 models

#### 4. Rent a Bike
1. Browse or search for bikes
2. Find a bike with "Available" status
3. Click "Rent Now"
4. Select start date and time:
   - Must be in the future
   - Use the datetime picker
5. Click "Confirm Rental"
6. Success! View in "My Rentals"

#### 5. View Your Rentals
1. Click "My Rentals" in navigation
2. View all your rentals with:
   - Bike details
   - Start and end dates
   - Duration (for completed rentals)
   - Total cost (for completed rentals)
   - Status (Active/Completed)

### For Administrators

#### 1. Access Admin Panel
1. Login with admin credentials
2. Automatically redirected to Admin Dashboard
3. View statistics:
   - Total motorbikes
   - Available bikes
   - Active rentals

#### 2. Add New Motorbike
1. Go to "Manage Bikes"
2. Click "Add New Motorbike"
3. Fill in details:
   - Brand (required)
   - Model (required)
   - Year (required, 1900 to current year + 1)
   - Price per Day (required, must be positive)
   - Description (optional)
4. Click "Add Motorbike"
5. Bike added and available for rent!

#### 3. Edit Motorbike
1. Go to "Manage Bikes"
2. Find the bike to edit
3. Click "Edit" button
4. Modify any fields
5. Click "Update Motorbike"
6. Changes saved!

#### 4. Delete Motorbike
1. Go to "Manage Bikes"
2. Find the bike to delete
3. Click "Delete" button
4. Confirm deletion
5. Bike removed from system

**Note:** Cannot delete bikes with active rentals

#### 5. View All Rentals
1. Go to "Manage Rentals"
2. See all rentals (all users) with:
   - Rental ID
   - User who rented
   - Motorbike details
   - Start and end dates
   - Total cost
   - Status

#### 6. Complete a Rental
1. Go to "Manage Rentals"
2. Find active rental
3. Click "Return" button
4. System automatically:
   - Calculates rental duration
   - Rounds up partial days
   - Computes total cost
   - Updates bike to "Available"
   - Marks rental as "Completed"

## Cost Calculation

### How Rental Cost is Calculated
1. **Duration** = End DateTime - Start DateTime
2. **Days** = Number of complete days
3. **Partial Days** = Rounded up to next full day
4. **Minimum** = 1 day (even for short rentals)
5. **Total Cost** = Days × Price per Day

### Examples
- Rent for 2 days = 2 days × price
- Rent for 2 days and 1 hour = 3 days × price (rounded up)
- Rent for 30 minutes = 1 day × price (minimum)

## Tips and Best Practices

### For Users
1. **Plan Ahead** - Select future start datetime
2. **Check Prices** - Compare different bikes
3. **Read Descriptions** - Understand bike features
4. **Search Smart** - Use keywords for specific bikes

### For Admins
1. **Regular Updates** - Keep bike information current
2. **Monitor Rentals** - Check active rentals regularly
3. **Prompt Returns** - Process returns quickly
4. **Accurate Data** - Ensure prices are up-to-date

## Troubleshooting

### Cannot Login
- Check username and password
- Ensure caps lock is off
- Verify account exists

### Cannot Rent a Bike
- Bike must be "Available"
- Start datetime must be in future
- Must be logged in

### Search Returns No Results
- Try broader search terms
- Check spelling
- Use "Clear" to reset

### Page Access Denied
- Verify you're logged in
- Check if page requires admin role
- Re-login if session expired

## Security Notes

1. **Never share your password**
2. **Logout after use** on shared computers
3. **Use strong passwords** (min 6 characters)
4. **Change default admin password** immediately
5. **Keep credentials secure**

## Support

For technical issues or questions:
1. Check INSTALLATION.md for setup issues
2. Review IMPLEMENTATION.md for technical details
3. Run test_connection.php to verify setup
4. Open an issue on GitHub

## Default Credentials

**Admin Account:**
- Username: `admin`
- Password: `admin123`

**Important:** Change the admin password after first login!

## Sample Motorbikes

The system includes 8 sample bikes:
1. Harley-Davidson Street 750 - $85/day
2. Honda CBR600RR - $95/day
3. Yamaha MT-07 - $75/day
4. Ducati Monster 821 - $120/day
5. Kawasaki Ninja 400 - $70/day
6. BMW R1250GS - $140/day
7. Suzuki GSX-R750 - $90/day
8. Triumph Bonneville T120 - $100/day

All bikes are initially available for rent!

---

**Enjoy your ride with MotoCity! 🏍️**
