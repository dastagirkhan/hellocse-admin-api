# HelloCSE API

## Introduction
This project is a Laravel 11.x API for managing administrators and profiles. It provides authentication functionality and profile management, including profile creation, updating, deletion, and public access to active profiles.

## Requirements
- PHP 8.3+
- Laravel 11.x
- MySQL or SQLite
- Composer
- Laravel Sanctum for API authentication


### Installation

1. **Clone the repository**
   ```sh
   git clone https://github.com/dastagirkhan/hellocse-admin-api
   cd hellocse-admin-api
   ```

2. **Install dependencies**
   ```sh
   composer install
   ```

3. **Set up environment variables**
   ```sh
   cp .env.example .env
   ```
   Configure database and other settings in the `.env` file.

4. **Generate application key**
   ```sh
   php artisan key:generate
   ```

5. **Run migrations and seed database**
   ```sh
   php artisan migrate --seed
   ```

6. **Run the server**
   ```sh
   php artisan serve
   ```

### API Endpoints

#### Authentication
- **Register Admin**
  ```
  POST /api/register
  {
    "email": "admin@example.com",
    "password": "password123"
  }
  ```

- **Login Admin**
  ```
  POST /api/login
  {
    "email": "admin@example.com",
    "password": "password123"
  }
  ```
  Response: `{ "token": "your_token_here" }`

#### Profile Management (Requires Authentication)
- **Create Profile**
  ```
  POST /api/profiles
  Authorization: Bearer {token}
  {
    "nom": "John",
    "prenom": "Doe",
    "image": [file]
  }
  ```

- **Update Profile**
  ```
  PUT /api/profiles/{id}
  Authorization: Bearer {token}
  {
    "nom": "Jane",
    "prenom": "Doe",
    "image": [file]
  }
  ```

- **Delete Profile**
  ```
  DELETE /api/profiles/{id}
  Authorization: Bearer {token}
  ```

#### Public Profile Viewing (No Authentication Required)
- **Get Active Profiles**
  ```
  GET /api/profiles
  ```

### Validation Rules
- `email`: Required, unique, valid format.
- `password`: Required, minimum 8 characters.
- `nom`: Required, string, max 255 characters.
- `prenom`: Required, string, max 255 characters.
- `image`: Required, must be an image (jpeg, png, jpg), max 2MB.

### Running Tests
Run feature tests to verify API functionality:
```sh
php artisan test
```

### Notes
- Uses Laravel Sanctum for authentication.
- Stores images in the `storage/app/public/profiles` directory.
- API responses follow RESTful standards.

### Code Comments

#### AuthController
- `register(Request $request)`: Handles administrator registration with email and password validation.
- `login(Request $request)`: Authenticates an admin and returns an API token if credentials are valid.

#### ProfileController
- `index()`: Fetches all active profiles.
- `publicIndex()`: Displays a public profile list.
- `store(ProfileRequest $request)`: Stores a new profile with an image upload and default status.
- `update(ProfileRequest $request, Profile $profile)`: Updates an existing profile and handles image replacement.
- `destroy(Profile $profile)`: Deletes a profile and its associated image.

#### ProfileRequest
- Defines validation rules for profile creation and updates.

#### Profile Model
- Specifies fillable fields and defines the relationship with the `Administrator` model.


