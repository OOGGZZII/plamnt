# Áttekintés
## API áttekintés
### Lehet külön apik kellenének [[#Microservices Architecture - külön apik]]
## GraphQl ? [[#What Is GraphQL?]]
### Létrehozás lépései:

#### New project
   - Install Laravel: `composer create-project --prefer-dist laravel/laravel api_project`.
   - Set up environment variables in `.env` for database connection and other configurations.

#### Database Creation and Migrations [[#Migrations]]
- **Should the API Create the Database?**.
   - After importing the SQL file, create migrations that match your existing schema using `php artisan make:migration`.
	 
#### Implement Models and Controllers: 
   - Create models for users, articles, and any other required entities using `php artisan make:model ModelName -m`.
	   - `-m` modellt is csinál
   - Create controllers using `php artisan make:controller ControllerName`.
	   - ***`-resource` is lehet?***

#### Define Routes:
   - Use `routes/api.php` to define all API routes. Group routes by functionality and add middleware for authentication and roles.
	   ==what is middleware?==

#### Authenticate users: [[#Authentication]]
   - Use ==Laravel Sanctum== for token-based authentication.
   - Install Sanctum: `composer require laravel/sanctum`.
   - Configure Sanctum in `config/sanctum.php` and use it in middleware for API routes.
   - Implement login, registration, and role-based access control in your authentication system.

#### Test the API:
   - Use tools like Postman or Laravel’s built-in testing capabilities to validate the API.

#### (Documentation):
   - Document your API using tools like Swagger or Laravel’s API Resource documentation capabilities.

## MAUI

### Using the API from MAUI
1. **HTTP Client**:
   - Use `HttpClient` in .NET MAUI to send requests to the API.
   - Example:
     ```csharp
     HttpClient client = new HttpClient();
     HttpResponseMessage response = await client.GetAsync("https://your-api-url.com/api/endpoint");
     if (response.IsSuccessStatusCode)
     {
         var data = await response.Content.ReadAsStringAsync();
     }
     ```

2. **Authentication**:
   - Implement token-based authentication using Sanctum.
   - Store the token securely in the MAUI application (e.g., using `SecureStorage`).

3. **Consume API Endpoints**:
   - Map API endpoints to functionalities in your MAUI applications (e.g., fetching articles, managing user data).

4. **Handle Errors**:
   - Implement error handling for failed requests or unauthorized access.


---
# Authentication
^756852

### 1. Set up Sanctum
To implement user authentication with three levels (Regular, Developer, Admin) in your Laravel API, follow these steps:

Laravel Sanctum provides a lightweight API authentication system using personal access tokens. It is ideal for your project. 

1. **Install Sanctum**:
   ```bash
   composer require laravel/sanctum
```

2. **Publish Sanctum Configuration**: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`

3. **Run Sanctum Migrations**: `php artisan migrate`

5. **Configure Sanctum Middleware**: Add Sanctum’s middleware to your `api` middleware group in 
***app/Http/Kernel.php***

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],

```

### Modify the user model
Add `HasApiTokens` to the `User` model:
```php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    protected $fillable = [
        'username', 'email', 'password', 'role_id', 'active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}

```


### 2. Define User Roles

You can define roles for your three levels of users in the `users` table by adding a `role` column.
Modify the `users` table migration:

```php
Schema::table('users', function (Blueprint $table) {
     $table->string('role')->default('regular'); // regular, developer, admin 
     });
```

Run the migration: `php artisan migrate`

<hr>

# innen lehet érdekes


### 3. Role-Based Middleware

Create a custom middleware to restrict access based on roles.

#### Middleware Example:

1. Generate Middleware: `php artisan make:middleware RoleMiddleware`
    
2. Implement Middleware Logic in 
	***`app/Http/Middleware/RoleMiddleware.php`:***
    
    
    ```php
namespace App\Http\Middleware;  
use Closure;
use Illuminate\Http\Request;
class RoleMiddleware {
     public function handle(Request $request, Closure $next, $role)
          {         
	          if ($request->user() && $request->user()->role !== $role) {
				           return response()->json(['error' => 'Unauthorized'], 403);         }
		        return $next($request);     
		        } 
		        }
```
    
3. Register Middleware in
***`app/Http/Kernel.php`:***

```php
protected $routeMiddleware = [     // Other middleware...     'role' => \App\Http\Middleware\RoleMiddleware::class, ];
```


### 4. Implement Authentication Endpoints

#### Login Endpoint:

1. Create a controller for authentication:
    
    `php artisan make:controller AuthController`
    
2. Implement login functionality:
    
    ```php
    namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

```
vagy 
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

```

### Routes:

Add routes in `routes/api.php`:

```php
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

```


### Add role based middleware
`php artisan make:middleware RoleMiddleware`

1. Define the middleware logic in `app/Http/Middleware/RoleMiddleware.php`:
```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user()->role->role_name !== $role) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}

```

2. Register it in `app/Http/Kernel.php`:
```php
   protected $routeMiddleware = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

3. use the middleware in routes:
```php
   Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Admin-only routes
});

Route::middleware(['auth:sanctum', 'role:developer'])->group(function () {
    // Developer-only routes
});

```
### 5. Protect API Routes by Role

Use Sanctum and custom middleware to protect routes for specific roles.

### Example Route Grouping:

```php
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    // Routes for all authenticated users
    Route::get('/profile', [UserController::class, 'profile']);

    // Developer-specific routes
    Route::middleware('role:developer')->group(function () {
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::get('/statistics', [ArticleController::class, 'statistics']);
    });

    // Admin-specific routes
    Route::middleware('role:admin')->group(function () {
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::put('/database/tables', [AdminController::class, 'updateTables']);
    });
});

```
### 6.  Testing and Debugging 

- Use tools like **Postman** to test each endpoint.
- Check role-based access by logging in as different users with varying roles and attempting restricted actions.
- Implement comprehensive API tests using Laravel's testing framework:
    `php artisan make:test RoleAccessTest`
    

### Authentication Best Practices

- **Use Policies**: For finer-grained access control, you can implement Laravel policies. They work well alongside roles for permission checks.
- **Secure Tokens**: Ensure tokens are stored securely on the client-side (e.g., SecureStorage in MAUI).
- **Validate Inputs**: Use Laravel’s validation rules to protect against invalid or malicious input.



---


# Migrations

#### Migration for `articles` Table

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('plant_id')->nullable();
            $table->text('source');
            $table->timestamps();

            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
}

```

#### Migration for `users` Table

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

```

---

# Microservices Architecture - külön apik

### Concept

Microservices divide your system into smaller, independent services that communicate via APIs. Instead of handling all features (e.g., user management, article management) in one application, you could split them into separate services.

#### Advantages

1. **Scalability**: Scale individual services independently.
2. **Maintainability**: Smaller codebases are easier to manage and test.
3. **Flexibility**: Use different technologies for different services.

#### Example

- **User Service**: Handles authentication and user management.
- **Article Service**: Manages articles and their data.
- **Admin Service**: Admin-specific operations (e.g., database updates).

***Each service would expose its own API***, and your MAUI and website applications would consume these APIs.
==külön apik a külön appokhoz? mert nem kellenek ugyanazok a kérések a külön appokból==

---

# What Is GraphQL?

### Overview

GraphQL is a query language for APIs that allows clients to request exactly the data they need. Unlike REST, which delivers fixed data structures, GraphQL provides flexibility.

#### Example:

**GraphQL Query**:
```php
query {
  article(id: 1) {
    title
    source
    plant {
      name
      latin_name
    }
  }
}

```

**Response**:
```json
{
  "data": {
    "article": {
      "title": "Gardening Tips",
      "source": "example.com",
      "plant": {
        "name": "Rose",
        "latin_name": "Rosa"
      }
    }
  }
}

```

#### Benefits Over REST

1. Fetch multiple resources in one request.
2. Flexible queries reduce over-fetching or under-fetching data.
3. Better suited for dynamic UI needs.

#### Tools for GraphQL

1. **Apollo Server** for backend.
2. **Laravel Lighthouse** for GraphQL integration in Laravel.
3. **GraphiQL** for testing queries.
