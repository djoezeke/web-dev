# 1. Project Title & Student Metadata

## 🚗 Vehicle Service Management System

- **Student Name:** `[Your Full Name]`
- **Student ID / Roll Number:** `[Your Student ID]`
- **Course Code:** `[Course Code]`
- **Assignment Name:** `Vehicle Service Management System`
- **Submission Date:** `[DD/MM/YYYY]`

# 2. Project Description

🧾 This web application digitizes the daily operations of a local automobile service centre by replacing manual record books with a centralized system for tracking customers, vehicles, and service jobs. It improves record accuracy, retrieval speed, and overall service workflow management for the administrator.

# 3. Assignment Requirements Checklist

- [x] **User Authentication** (Login and Logout for the administrator)
- [x] **Customer Management** (Register new customers, view all registered customers)
- [x] **Vehicle Management** (Register vehicles with Reg Number, Make, Model, Year, Owner; Edit and delete vehicle records)
- [x] **Service Management** (Record service details like Date, Type, Mechanic, Cost, Status; View and search service records)
- [x] **Database** (MySQL database with 4 related tables: users, customers, vehicles, services)
- [x] **Validation** (JavaScript input field validation)

# 4. Prerequisites

✅ Ensure the following are installed:

- **PHP:** `8.0+`
- **MySQL Server:** `8.0+` (or any compatible modern version)

Check PHP from terminal:

```bash
php -v
```

# 5. Setup & Execution Guide for Graders

## Step 1: Database Setup

From the project root, import the schema and seed files:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

## Step 2: Environment Configuration

Open and update database credentials in:

- `config/db.php`

Set the correct values for:

- `DB_HOST`
- `DB_USER`
- `DB_PASS`
- `DB_NAME`

## Step 3: Launching the Server

Run the application using PHP's native development server with the `public/` directory as the document root:

```bash
php -S localhost:8000 -t public
```

## Step 4: Accessing the App

Open this URL in your browser:

👉 **http://localhost:8000**

# 6. Default TA Grader Credentials

🔑 Credentials created by `database/seed.sql`:

- **Username:** `admin`
- **Password:** `admin123`

# 7. Directory Structure Tree

```text
web-dev/
├── config/
│   ├── auth.php
│   └── db.php
├── database/
│   ├── schema.sql
│   └── seed.sql
├── public/
│   ├── assets/
│   │   ├── style.css
│   │   └── validation.js
│   ├── index.php
│   ├── dashboard.php
│   ├── customers.php
│   ├── vehicles.php
│   ├── edit_vehicle.php
│   ├── delete_vehicle.php
│   ├── services.php
│   └── logout.php
├── src/
│   ├── controllers/
│   │   ├── index.php
│   │   ├── dashboard.php
│   │   ├── customers.php
│   │   ├── vehicles.php
│   │   ├── edit_vehicle.php
│   │   ├── delete_vehicle.php
│   │   ├── services.php
│   │   └── logout.php
│   └── helpers/
│       └── view.php
├── templates/
│   ├── layouts/
│   │   ├── header.php
│   │   ├── nav.php
│   │   └── footer.php
│   └── pages/
│       ├── login.php
│       ├── dashboard.php
│       ├── customers.php
│       ├── vehicles.php
│       ├── edit_vehicle.php
│       └── services.php
└── README.md
```

# 8. Academic Integrity & Technical Security Notice

📘 **Academic Integrity:** All code submitted for this assignment is original work prepared for academic evaluation.

🛡️ **Technical Security Notice:** The project follows Separation of Concerns by exposing only the `public/` directory through `php -S localhost:8000 -t public`. Core backend folders (`config/`, `src/`, `templates/`, `database/`) remain outside the web root, so internal files are not directly accessible and no `.htaccess` workaround is required.
