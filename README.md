# Vehicle Service Management System

A PHP + MySQL web application for managing customers, vehicles, and vehicle service records.

## Features
- Admin login/logout
- Customer registration and listing
- Vehicle registration, editing, and deletion
- Service recording and searchable service history
- Client-side JavaScript form validation

## Setup
1. Create the database and tables:
   - Import `/home/runner/work/web-dev/web-dev/schema.sql` into MySQL.
2. Update database credentials in `/home/runner/work/web-dev/web-dev/config/db.php`.
3. Serve `/home/runner/work/web-dev/web-dev` with PHP (Apache/Nginx or PHP built-in server).
4. Open the app and log in with:
   - Username: `admin`
   - Password: `admin123`
