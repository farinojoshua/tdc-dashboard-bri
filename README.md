
# TDC Dashboard 

An internal application for monitoring through several modul of TDC, TDC is a part of Brifirst.

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- Laragon / XAMPP
- MySQL




## Run Locally

Clone the project

```bash
  git clone https://github.com/farinojoshua/tdc-dashboard-bri.git
```

Go to the project directory

```bash
  cd tdc-dashboard-bri
```

Install Laravel dependencies

```bash
  composer install
```

Install NPM dependencies

```bash
  npm install
```

Setup Environment

Copy the .env.example file and rename it to .env.
Configure your database connection in the .env file.

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

Generate App Key

```bash
  php artisan key:generate
```
Run Migrations

```bash
  php artisan migrate
```

Compile Assets (Tailwind CSS)

```bash
  npm run dev
```
Start the Application

```bash
  php artisan serve
```
