# Companies Management System

<p align="center">
<img style="background-color: white;" src="https://www.aicoll.co/landingpage/assets/img/logo_main.png" width="400" alt="aicoll Logo">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">

</p>

## Overview

This Laravel-based application provides a comprehensive solution for managing company information. The system offers both a user-friendly web interface and a RESTful API for integrating with other systems.

## Features

- Complete company management (creation, viewing, updating, and deletion)
- Interactive web interface with real-time updates
- RESTful API with comprehensive documentation
- Validation to ensure data integrity
- Status-based company management (active/inactive)
- Bulk operations for inactive companies

## Technology Stack

- **Frontend**: Blade templates, Tailwind CSS, JavaScript
- **Backend**: Laravel 12
- **Database**: MySQL
- **Documentation**: Swagger/OpenAPI via L5-Swagger

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL database

### Installation

1. Clone the repository:

    ```bash
    git clone [repository-url]
    cd technical-test
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install JavaScript dependencies:

    ```bash
    npm install
    ```

4. Create a copy of the environment file:

    ```bash
    cp .env.example .env
    ```

5. Generate application key:

    ```bash
    php artisan key:generate
    ```

6. Configure your database in the `.env` file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

7. Run database migrations:

    ```bash
    php artisan migrate --seed
    ```

8. Build assets:

    ```bash
    npm run build
    ```

9. Start the development server:
    ```bash
    php artisan serve
    ```

### Quick Start with Dev Command

For a more streamlined development experience, you can use the built-in dev command:

```bash
composer run dev
```

This command will start the Laravel server, queue worker, log viewer, and Vite in parallel.

## Accessing the System

### Web Interface

The web interface is available at:

- Local development: `http://localhost:8000`
- The main screen provides access to all functionality:
    - View all companies
    - Add new companies
    - Edit existing companies
    - Delete inactive companies
    - Change company status

### API Documentation

The API documentation is automatically generated using Swagger/OpenAPI and is available at:

- Local development: `http://localhost:8000/api/documentation`

### API Endpoints

| Method | Endpoint                   | Description                         |
| ------ | -------------------------- | ----------------------------------- |
| GET    | /api/v1/companies          | List all companies                  |
| POST   | /api/v1/companies          | Create a new company                |
| GET    | /api/v1/companies/{nit}    | Get details of a specific company   |
| PUT    | /api/v1/companies/{nit}    | Update a company                    |
| DELETE | /api/v1/companies/{nit}    | Delete a company (only if inactive) |
| DELETE | /api/v1/companies/inactive | Delete all inactive companies       |

## Testing

Run the automated tests using:

```bash
php artisan test
```

To view a code coverage report, you need to have xDebug installed and enabled. Once it's set up, run:

```bash
php artisan test --coverage
```

> 💡 If you don't have xDebug installed, you can follow the [official xDebug installation guide](https://xdebug.org/docs/install) for your environment.

This will execute all feature and unit tests to ensure the application is functioning correctly.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
