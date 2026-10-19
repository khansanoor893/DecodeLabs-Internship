# DecodeLabs — Database Integration (CRUD)

A Laravel user directory demonstrating permanent SQL storage, Eloquent ORM, server-side validation, and complete web and JSON CRUD workflows. The dashboard opens in dark mode and remembers the theme choice in the browser.

## Requirements

- PHP 8.3 or newer
- Composer
- MySQL 8+ (or another Laravel-supported SQL database)
- Node.js and npm (only needed when changing/building frontend assets)

## Run locally

1. Install dependencies: `composer install`.
2. Copy `.env.example` to `.env`, set `APP_KEY` with `php artisan key:generate`, and configure `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for a running database.
3. Create the configured database, then apply migrations: `php artisan migrate`.
4. Start the application: `php artisan serve`.
5. Open `http://127.0.0.1:8000`.

The schema uses a unique email address, a nonnegative age, an active status that defaults to true, a securely hashed password, and automatic creation/update timestamps. Validation runs on the server for browser forms and API requests.

## JSON API

Requests use the `/api` prefix and should send `Accept: application/json`. Create and update requests should send JSON.

| Method | Endpoint | Operation |
| --- | --- | --- |
| `GET` | `/api/users` | List users (paginated; optional `per_page`, 1–100) |
| `POST` | `/api/users` | Create a user |
| `GET` | `/api/users/{id}` | Read one user |
| `PUT` / `PATCH` | `/api/users/{id}` | Update supplied fields |
| `DELETE` | `/api/users/{id}` | Delete a user |

Example create request:

```json
{
  "name": "Ayesha Khan",
  "email": "ayesha@example.com",
  "age": 24,
  "is_active": true,
  "password": "a-long-password",
  "password_confirmation": "a-long-password"
}
```

The API returns `201` on create, `200` on reads and updates, `204` on delete, `409` if an email is already in use, `422` for other invalid input, and `404` for a missing user. Passwords and remember tokens are never included in user responses.

Import [`postman/Database Integration CRUD.postman_collection.json`](postman/Database%20Integration%20CRUD.postman_collection.json) into Postman and run its requests in order to exercise create, duplicate rejection, list/read, update, delete, and the deleted-record check. It generates and then removes a uniquely named verification user.

## Database design notes

- SQL is a good fit for user profiles because the fields are structured and email uniqueness can be enforced by a database constraint.
- Eloquent maps the `User` model to the `users` table and parameterizes queries, which avoids assembling SQL from untrusted input.
- Migrations version the schema; `created_at` and `updated_at` provide an audit trail.
- Server-side validation complements database constraints. Age is restricted to 0–130 and email uniqueness is checked before save, while the unique index remains the final guard against duplicate requests arriving at the same time.
