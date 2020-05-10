# Lumen-Passport with Login and Registration

This is a boilerplate setup using Lumen as the backend framework for creating REST APIs and Passport for authentication and token generation

## Installation

 - Run composer install

```bash
composer install
```
 - Set your API URL and DB credentials
```env
APP_API_URL=http://localhost/api/v1

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=dbname
DB_USERNAME=root
DB_PASSWORD=
```
 - Generate your application key

```bash
php artisan key:generate
```
 - Trigger your migrations
```bash
php artisan migrate
```
 - Install Passport configuration. This will populate the OAuth tables with config data such as OAuth client keys.
```bash
php artisan passport:install
```
 - Set the OAUTH environment variables in your `.env` file
```env
# id and secret fields in oauth_client table where name field is 'Lumen Password Grant Client'
OAUTH_CLIENT_ID=2
OAUTH_CLIENT_SECRET=CKAGqSX5sadamHYj5DS4DApsohreW5m8xm2JfaYbNh
# leave this as is
OAUTH_GRANT_TYPE=password
```

 - Also, don't forget to give read and write permissions to the web server for your `storage` directory

## Usage
Register using the `[POST] {{your_api_url}}/auth/register` route
```json
{
	"full_name": "John Doe",
	"email": "john.doe@gmail.com",
	"password": 123456,
	"password_confirmation": 123456,
	"position": "CEO",
	"company_name": "JD Company"
}
```
Login using the `[POST] {{your_api_url}}/auth/login` route
```json
{
	"email": "john.doe@gmail.com",
	"password": "123456"
}
```
Use the Bearer token returned from the login api to access other secured endpoints that you create.

Request Header:
```json
{
	"Authorization": "Bearer aBc....xYz"
}
```
Be sure to secure your api's with the `auth` middleware the routes file
```php
// .... //
$router->group(['prefix' => 'users', 'middleware' => ['auth:api']], function () use ($router) {
    $router->get('/', function () {
// .... //
```
