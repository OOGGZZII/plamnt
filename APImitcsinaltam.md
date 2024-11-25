
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
- fillable: Specifies which fields are mass-assignable (to prevent mass-assignment vulnerabilities). Here, we added username, email, password, city, birthdate, role_id, and active.
- hidden: Password and the remember_token are hidden by default when the User model is returned (e.g., in an API response).
- dates: Specifies that birthdate should be treated as a Carbon date instance.
- Relationship: We've added a role() method, which defines the relationship between the User model and the Role model (which you’ll create for the roles table).


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
The most critical route to define is the one pointing to the GraphQLController for processing queries.

```php
<?php
use Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson;
use Illuminate\Support\Facades\Route;
use Nuwave\Lighthouse\Http\Controllers\GraphQLController;

Route::prefix('graphql')
    ->middleware([AcceptJson::class])
    ->group(base_path('routes/graphql.php'));

Route::post('/', [\Nuwave\Lighthouse\Http\Controllers\GraphQLController::class, 'query']);
Route::get('/playground', [GraphQLController::class, 'playground']);

// Route::post('/', [GraphQLController::class, 'query']);
```


#### **The Code Breakdown**
- **Importing Middleware**: The AcceptJson middleware ensures that incoming requests are in JSON format (a requirement for GraphQL queries).
If a client doesn't send requests in JSON format, the middleware will reject them.
**Middleware**:
Middleware are classes that sit between the request and the application logic. They process incoming requests before they reach the controller or other logic.
Example: Middleware can handle authentication, modify requests, or enforce specific formats (like ensuring requests are in JSON).

- **Route Prefix**: prefix('graphql') specifies that all routes under this group will start with /graphql. For example:
/graphql will be the endpoint for sending queries or mutations.

- **Middleware Assignment**: The middleware([AcceptJson::class]) applies the AcceptJson middleware to all routes within the group. This ensures only JSON requests are processed.
  
- **Route Grouping**: group(base_path('routes/graphql.php')) includes a separate file (routes/graphql.php) that defines the GraphQL routes.
This is a modular approach, keeping the main route file clean and separating concerns.

#### **Additional Configuration (Optional)**
If you want to add a playground or introspection route, you can extend your graphql.php file with routes like:

```php
Route::get('/playground', [GraphQLController::class, 'playground']);
```

!If your GraphQL endpoint requires ***custom middleware (e.g., authentication)***, you can define it in the middleware array during route registration in the graphql.php file.!



### 2.2. Include the GraphQL Routes in `api.php`
Since Laravel 11 doesn’t use RouteServiceProvider, you’ll need to include the `graphql.php` routes file in `routes/api.php`

***create `routes/api.php` with***
``` php 
artisan install:api
```

***In `routes/api.php`, add:***
```php
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



<hr>



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

# Itt tartok

#### 2. Link it in the schema:

```php
type Mutation {
    createUser(username: String!, email: String!, password: String!, city: String, birthdate: String): User @field(resolver: "App\\GraphQL\\Mutations\\CreateUserMutation@resolve")
}
```


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
