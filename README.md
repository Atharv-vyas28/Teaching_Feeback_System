<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# A Smart Feedback System

A Laravel 13 application for managing staff attendance and collecting anonymous student feedback. The current implementation includes authentication, a protected dashboard, Sanctum API support, and an interactive attendance sheet interface.

## Features

- User registration, login, remember-me authentication, and logout.
- Protected dashboard available to authenticated users.
- Attendance sheet controls for course, topic, date, and lecture number.
- Toggle student attendance between present and absent.
- Enable anonymous feedback for individual present students or all present students.
- Save attendance data in the browser console while the persistence workflow is being developed.
- Sanctum-protected `/api/user` endpoint.
- Laravel migrations for users, sessions, jobs, cache, personal access tokens, and posts.

## Requirements

- PHP 8.3 or later
- Composer
- Node.js and npm
- A database supported by Laravel

## Installation

Clone the repository, enter the project directory, and run the setup script:

```bash
composer run setup
```

The setup script installs PHP and JavaScript dependencies, creates `.env` when needed, generates the application key, runs migrations, and builds the Vite assets.

If you prefer to run the steps separately:

```bash
composer install
copy .env.example .env       # Windows
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Configure the database connection in `.env` before running migrations. For local development, the default SQLite configuration can be used after creating `database/database.sqlite`.

## Development

Start the application, queue worker, and Vite development server together:

```bash
composer run dev
```

Or run the services independently:

```bash
php artisan serve
npm run dev
```

Open the URL reported by `php artisan serve`, then register an account at `/register` and sign in at `/login`. Authenticated users are redirected to `/dashboard`.

## Routes

| Method | URI | Purpose | Access |
| --- | --- | --- | --- |
| GET | `/login` | Login form | Public |
| POST | `/login` | Authenticate a user | Public |
| GET | `/register` | Registration form | Public |
| POST | `/register` | Create an account | Public |
| POST | `/logout` | End the current session | Authenticated |
| GET | `/dashboard` | View the dashboard | Authenticated |
| GET | `/api/user` | Return the authenticated API user | Sanctum |

## Testing

Run the Laravel test suite with:

```bash
composer run test
```

## Project Structure

- `app/Http/Controllers` contains application controllers, including authentication.
- `app/Models` contains the Eloquent models.
- `database/migrations` contains the database schema.
- `resources/views` contains Blade pages and components.
- `resources/css` and `resources/js` contain Vite-managed frontend assets.
- `routes/web.php` and `routes/api.php` define the web and API endpoints.

## License

This project is based on Laravel and is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
