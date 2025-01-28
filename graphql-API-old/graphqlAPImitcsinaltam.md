## User
### Modify the User *Model* - ez már létezik alapból
The User model needs to be updated to reflect the fields in the user table and the relationship with the roles table. Here's how to modify the User model:

`app/Models/User.php`
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Add the new columns to the fillable array to allow mass assignment
    protected $fillable = [
        'username', 'email', 'password', 'city', 'birthdate', 'role_id', 'active',
    ];

    // You can also use guarded to prevent certain attributes from mass-assignment
    // protected $guarded = ['id'];

    // Add hidden attributes (like password, so it won't be returned in the API response)
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Add date casts for any date fields
    protected $dates = [
        'birthdate',
    ];

    /**
     * Define a relationship with the Role model (User belongs to Role)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);  // Assuming Role model exists
    }
}
```
#### Explanation:
- **fillable**: Specifies which fields are mass-assignable (to prevent mass-assignment vulnerabilities). Here, we added username, email, password, city, birthdate, role_id, and active.
- **hidden**: Password and the remember_token are hidden by default when the User model is returned (e.g., in an API response).
- **dates**: Specifies that birthdate should be treated as a Carbon date instance.
- **Relationship**: We've added a role() method, which defines the relationship between the User model and the Role model (which you’ll create for the roles table).


### Create a *Migration* for User Table
You need to create a migration to reflect the structure of the user table, adding the role_id and other necessary fields. Here's how to write the migration:
```cmd
php artisan make:migration create_users_table --create=users
```

- modified `Migration File (xxxx_xx_xx_xxxxxx_create_users_table.php)`

#### ezek nem biztos hogy kellenek, egyelőre benne van:
- `$table->boolean('active')->default(1)`: A boolean field to indicate whether the user account is active (default is 1).
- `timestamps()`: Laravel’s default created_at and updated_at fields for the model


## Role
model és migration és seeder (három szint, admin, developer, user)

## Többi tábla (article, post, user_plants, plants)
- migration
- model
#### Notes:

***Eloquent Relationships:***
- In the `Plant` model, I defined a one-to-many relationship with the Post model and a many-to-many relationship with the User model via the UserPlant pivot table.
- In the `Article` model, I defined a many-to-one relationship with the Plant model.
- In the `Post` model, I defined belongsTo relationships with both User and Plant.
- In the `UserPlant` model, I defined a belongsTo relationship with both User and Plant, since this is a pivot table.

***Composite Primary Key:***
- The UserPlant model uses a composite primary key (user_id and plant_id). Laravel doesn't support composite keys natively, so you need to set $incrementing = false and specify the primary key.

***adatok még nincsenek benne***



## TODO
database creation


<hr>

# 11.25 hétfő

## 1. Install Lighthouse:

```cmd
composer require nuwave/lighthouse
php artisan vendor:publish --tag=lighthouse-config
php artisan vendor:publish --tag=lighthouse-schema
```

This creates:

- A configuration file at config/lighthouse.php.
- A default schema file at graphql/schema.graphql.

## 2. Register Lighthouse routes - Steps to Define Lighthouse Routes in Laravel 11

In Laravel 11, the `RouteServiceProvider` file no longer exists as it did in previous versions. Instead, Laravel has shifted its routing logic to a cleaner and more centralized approach, typically utilizing the **routes directory** for route definitions.

### 2.1. Define a New GraphQL Routes File
Create a new file in the **routes directory**, for example, `routes/graphql.php`. This will hold your GraphQL routes.

Ensure the `routes/graphql.php` file exists and contains the routes for your GraphQL server. 


```php
<?php

use Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson;
use Illuminate\Support\Facades\Route;


Route::prefix('graphql')
    ->middleware([AcceptJson::class]) // Apply middleware for JSON handling
    ->group(function () {
        Route::post('/', function () {
            return app('graphql')->handle(); // Lighthouse handles GraphQL requests internally
        });

        Route::get('/playground', function () {
            return view('lighthouse-playground'); // Serve the Playground view
        });
    });
```

#### **Explanation:**
- **Playground View**: The view('lighthouse-playground') function serves Lighthouse's built-in Playground interface. You don't need a controller for this.

- **GraphQL Query Handling:** The app('graphql')->handle() method directly invokes Lighthouse's internal handler for executing GraphQL queries.

- **Middleware:** The AcceptJson::class middleware ensures that only requests with the Accept: application/json header are allowed.




Or, if you're using Lighthouse's built-in configuration:

```php
// Rely entirely on Lighthouse's routing by removing custom routes from `graphql.php`.
// Ensure `config/lighthouse.php` is properly configured:
'route' => [
    'uri' => '/graphql',
    'middleware' => ['api'],
],

```


!If your GraphQL endpoint requires ***custom middleware (e.g., authentication)***, you can define it in the middleware array during route registration in the graphql.php file.!



### 2.2. Include the GraphQL Routes in `api.php`
Since Laravel 11 doesn’t use RouteServiceProvider, you’ll need to include the `graphql.php` routes file in `routes/api.php`

***create `routes/api.php` with***
``` php 
artisan install:api
```

***`routes/api.php`***
```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// | Here is where you can register API routes for your application. These
// | routes are typically stateless and use the "api" middleware group.

require base_path('routes/graphql.php');
```




## 3. Define the GraphQL 
### Schema done in `graphql/schema.graphql`


### Queries
Define queries to fetch data:

```php
type Query {
    users: [User]
    user(id: ID!): User
    posts: [Post]
    post(id: ID!): Post
    plants: [Plant]
    plant(id: ID!): Plant
    articles: [Article]
    article(id: ID!): Article
}
```

### Mutations
Define mutations for data manipulation:

```php
type Mutation {
    createUser(username: String!, email: String!, password: String!, city: String, birthdate: String): User
    createPost(user_id: Int!, title: String!, description: String!, city: String, plant: Int, media: String, sell: Boolean): Post
    createPlant(name: String!, latin_name: String!): Plant
}
```



## 4. Create Resolvers
Resolvers connect GraphQL operations to your Laravel models.
1. Create the Folder Structure
You need to create the `app/GraphQL/Queries` directory

```cmd
app/
├── GraphQL/
│   ├── Queries/
```

### **Example: Query Resolver**
For `users` query:

#### 1. Create app/GraphQL/Queries/UsersQuery.php:

```php
<?php
namespace App\GraphQL\Queries;

use App\Models\User;

class UsersQuery {
    public function resolve() {
        return User::all();
    }
}
```

#### 2. Link it in the schema:

```php
type Query {
    users: [User] @field(resolver: "App\\GraphQL\\Queries\\UsersQuery@resolve")
}
```


### **Example: Mutation Resolver**
For `createUser` mutation:

#### 1. Create app/GraphQL/Mutations/CreateUserMutation.php:

```php
namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserMutation {
    public function resolve($root, array $args) {
        return User::create([
            'username' => $args['username'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
            'city' => $args['city'],
            'birthdate' => $args['birthdate'],
        ]);
    }
}
```

<hr>

#### 2. Link it in the schema:

```php
type Mutation {
    createUser(username: String!, email: String!, password: String!, city: String, birthdate: String): User @field(resolver: "App\\GraphQL\\Mutations\\CreateUserMutation@resolve")
}
```



<hr>



## Próbálom futtatni, errorokat oldok meg
Ensure your lighthouse.php configuration file allows the Playground to be served. Check the config/lighthouse.php file and verify the following settings:

### 1. a /graphql/lighthouse not found 
`config/lighhouse.php`

```php
'route' => [
    'uri' => '/graphql', // Base URI for GraphQL
    'name' => 'graphql', // Named route for convenience
    'middleware' => [
        'api', // Default Laravel API middleware group
        Nuwave\Lighthouse\Http\Middleware\AcceptJson::class,
        Nuwave\Lighthouse\Http\Middleware\AttemptAuthentication::class,
    ],
],

'playground' => [
    'enabled' => true, // Enable the Playground
    'path' => '/graphql/playground', // Path for the Playground
],
```


#### Changes in Lighthouse Route Handling:


**Updated Configuration**
***Replace your graphql.php*** file with the following, relying on Lighthouse's internal routing:



#### Next Steps
**Verify Lighthouse Configuration:** Ensure your `config/lighthouse.php` file is correctly set up, especially the route and playground sections:

```php
'route' => [
    'uri' => '/graphql',
    'middleware' => ['api'],
],

'playground' => [
    'enabled' => true,
    'path' => '/graphql/playground',
],
```

**Clear Cache**: 
```cmd
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan route:cache
```

**Test is there is a registered route**
```cmd
php artisan route:list
```
Ensure you see entries for /graphql and /graphql/playground.

Look for routes similar to:

```cmd
Method	    URI	Name	            Action
---------------------------------------------
POST	    graphql		            Closure
GET	        graphql/playground		Closure
```




nincs playground idc











































































<hr>



### 5. Configure Authentication
To protect routes:

#### 1. Install Sanctum for API authentication:

```php
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

#### 2. Add Sanctum middleware in app/Http/Kernel.php:

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

#### 3. Protect GraphQL endpoints by adding middleware to graphql.php routes:

```php
'middleware' => ['auth:sanctum'],
```



### 6. Test GraphQL API
Use GraphQL Playground or Insomnia to test your API.

Sample queries:

```php
query {
    users {
        id
        username
        email
    }
}
```

```php
mutation {
    createUser(username: "JohnDoe", email: "john@example.com", password: "password123") {
        id
        username
    }
}
```


### 7. Integrate API with Mobile, Web, and Desktop Apps
Use GraphQL queries and mutations in your frontend applications.
Utilize tools like Apollo Client or Relay for seamless GraphQL integration.
