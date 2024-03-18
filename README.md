# Laravel RBAC Contacts Access Control

## Description
This Laravel project implements role-based access control (RBAC) to manage access to contacts associated with organizations. RBAC is a method of regulating access to resources based on the roles of individual users within an organization. In this project, we utilize RBAC to ensure that only authorized users can view, create, update, or delete contacts that belong to specific organizations.

## Prerequisites

Before you begin, make sure you have the following installed on your system:

1. PHP (>= 7.4)
2. Composer
3. Node.js (>= 14.x)
4. NPM (>= 6.x)

## Getting Started

Follow these instructions to get a local copy of the project up and running.

### Step 1: Clone the repository

Open a terminal and clone the Git repository to your local machine:

```bash
git clone https://github.com/elio-bteich/rbac-project.git
cd rbac-project
```

### Step 2: Install PHP Dependencies

Next, install the PHP dependencies using Composer:

```bash
composer install
```

### Step 3: Install Frontend Dependencies

Now, install the frontend dependencies using NPM:

```bash
npm install
```

### Step 4: Set Up Environment

Duplicate the .env.example file and rename it to .env. Then, generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

### Step 5: Run Migrations

Run the database migrations to set up the database schema:

```bash
php artisan migrate
```

### Step 6: Seed the Database (Optional)

If you have any seeders for dummy data, you can run them to populate the database:

```bash
php artisan db:seed
```

### Step 7: Build Assets

Compile the assets (CSS, JS, etc.) using Laravel Mix:

```bash
npm run dev
```

### Step 8: Serve the Application

You can use the Laravel CLI to serve the application locally:

```bash
php artisan serve
```

By default, the application will be available at http://localhost:8000.

## Additional Information

For development, you can use this command to automatically recompile assets during development:

```bash
npm run watch
```