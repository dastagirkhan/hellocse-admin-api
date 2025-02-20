# HelloCSE API

## Introduction
This project is a Laravel 11.x API for managing administrators and profiles. It provides authentication functionality and profile management, including profile creation, updating, deletion, and public access to active profiles.

## Requirements
- PHP 8.3+
- Laravel 11.x
- MySQL or SQLite
- Composer
- Laravel Sanctum for API authentication

## Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/your-repo/hellocse-admin-api.git
   cd hellocse-admin-api
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Set up the environment file:
   ```sh
   cp .env.example .env
   ```
   Update database credentials in `.env`.

4. Run database migrations:
   ```sh
   php artisan migrate --seed
   ```

5. Generate the application key:
   ```sh
   php artisan key:generate
   ```

6. Serve the application:
   ```sh
   php artisan serve
   ```

## API Endpoints

### Authentication
- **Register:** `POST /api/register`
- **Login:** `POST /api/login`

### Profile Management
- **Create Profile (Authenticated):** `POST /api/profiles`
- **Update Profile (Authenticated):** `PUT /api/profiles/{profile}`
- **Delete Profile (Authenticated):** `DELETE /api/profiles/{profile}`
- **View Active Profiles (Public):** `GET /api/profiles`

## Validation Rules
- **Administrator Registration/Login:** Requires a unique email and password.
- **Profile:** Requires `nom`, `prenom`, `image` (jpeg/png/jpg/gif max 2MB), and a valid `statut`.

## Running Tests
To execute unit tests:
```sh
php artisan test
```

## License
MIT License

