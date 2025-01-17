# Company Manager

This is the backend for the Company Manager application built with Laravel. It provides RESTful API endpoints for managing users, roles, companies, and other necessary operations.

## Requirements

- PHP 8.2+
- Composer
- MySQL or other supported databases
- Laravel 11.x

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/DenisChoirulR/company-manager.git
    cd company-manager
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:**

    Update the `.env` file with your database credentials and other necessary configurations:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=company_manager
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    You can simply copy the .env.example file as it already includes default configurations credential for the database, mailer, and Pusher.

   ```bash
    cp .env.example .env
    ```

4. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

5. **Run the Unit Test:**

    ```bash
    php artisan test
    ```

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

7. **Seed the database:**

    The project includes a seeder to create default admin and user accounts.

    ```bash
    php artisan db:seed
    ```

8. **Run the application:**

    Start the local development server:

    ```bash
    php artisan serve
    ```

    The application will be accessible at `http://localhost:8000`.

## Usage

### Roles

- **Admin:** Has full access.
- **Manager:** Can view managers and employees data.
- **Employee:** Only Can view employees data.

## The Database

The database seeder creates two roles: Admin and Regular users.

- **Admin Credentials:**
  - `admin@mail.com / password`
 
the default password for all generated user is "**password**"
