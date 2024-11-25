## Setting Up GraphQL with Laravel
To use GraphQL in Laravel, you'd typically use a package like **Lighthouse** or graphql-laravel. Here’s a basic overview of how the structure changes with GraphQL:

**Schema**: You define a GraphQL schema that contains types (models) and the queries and mutations associated with them.
**Types**: For each model (User, Post, Plant, etc.), you define a GraphQL type that represents the model. This type will map to the columns in your database.
**Queries**: You define GraphQL queries to retrieve data. For example, getPost(id: ID!) could fetch a specific post by its ID.
**Mutations**: Mutations are used for modifying data (e.g., creating or updating posts).

## Resolvers
A resolver is a function or class method that is responsible for resolving a query or mutation. Resolvers define the logic for querying the database or manipulating data.

For example, instead of a PostController with resource methods, in GraphQL, you would have a resolver for your Post query:

`app/GraphQL/Queries/PostQuery.php`
```php
namespace App\GraphQL\Queries;

use App\Models\Post;

class PostQuery
{
    public function resolve($root, array $args)
    {
        return Post::find($args['id']);
    }
}
```

For mutations, you would similarly define resolver classes that handle creating or updating records:

`app/GraphQL/Mutations/CreatePostMutation.php`
```php
namespace App\GraphQL\Mutations;

use App\Models\Post;

class CreatePostMutation
{
    public function resolve($root, array $args)
    {
        return Post::create($args);
    }
}
```
## Setting Up GraphQL Schema
In GraphQL, you define the schema with types, queries, and mutations. For example:

`app/GraphQL/schemas/schema.graphql`
```cs
type Post {
    id: ID!
    title: String!
    description: String
    user: User
}

type Query {
    getPost(id: ID!): Post
    getPosts: [Post]
}

type Mutation {
    createPost(title: String!, description: String): Post
}
```

Then, link these queries and mutations to the corresponding resolver classes in your schema configuration.

## Example Workflow with GraphQL in Laravel
Let’s say you want to allow users to create a new post via GraphQL:

### 1. Define the GraphQL Mutation:

In your schema file (`schema.graphql`), define a mutation for creating a post:

```php
type Mutation {
    createPost(title: String!, description: String!): Post
}
```

### 2. Create the Mutation Resolver:

In `app/GraphQL/Mutations/CreatePostMutation.php`, create a method to handle the mutation:

```php
namespace App\GraphQL\Mutations;

use App\Models\Post;

class CreatePostMutation
{
    public function resolve($root, array $args)
    {
        return Post::create([
            'title' => $args['title'],
            'description' => $args['description'],
            'user_id' => auth()->id(),  // Assuming the user is logged in
        ]);
    }
}
```

### 3. Link the Mutation in the Schema:
In your graphql schema definition, associate the mutation with the resolver:

```php
type Mutation {
    createPost: CreatePostMutation
}
```


### 4. Handle Querying: 
Similarly, for a getPost query, you would define a query resolver that fetches a post based on the ID:

`app/GraphQL/Queries/PostQuery.php`
```php
namespace App\GraphQL\Queries;

use App\Models\Post;

class PostQuery
{
    public function resolve($root, array $args)
    {
        return Post::find($args['id']);
    }
}
```

### Conclusion:
In GraphQL, you do not need to create resource controllers for your models. Instead, you define queries and mutations, which are resolved by custom resolver classes. This is a more flexible and granular way to manage your data operations and is well-suited for the kind of dynamic querying and updating your application needs.

If you’re already familiar with Laravel, packages like Lighthouse make it easy to integrate GraphQL and handle everything from schema definition to resolvers. This allows you to focus on defining the schema and resolving logic rather than having to manage traditional controller actions.



# TODO
## Set Up Authentication
### Actions:

- Use Laravel's built-in Sanctum or Passport for API authentication (tokens).
- Implement middleware to protect routes and enforce authentication.

```cmd
composer require laravel/sanctum
php artisan migrate
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

(For testing authentication with both GraphQL and REST, you can define a login route and register functionality that generates an API token.

Example of registering a user and generating a token:

```php
// In AuthController
public function login(Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        return response()->json([
            'token' => Auth::user()->createToken('API Token')->plainTextToken
        ]);
    }
    return response()->json(['error' => 'Unauthorized'], 401);
}
```
)


## Define Common Business Logic (Service Layer)
To keep your API logic decoupled from your controllers (whether using GraphQL or REST), create service classes to handle the core logic.

### Actions:

Create service classes that handle the business logic for your models.
Example: A PostService that handles the logic for creating, updating, and deleting posts.

```cmd
php artisan make:service PostService
```

In the PostService, you might have methods like:

```php
// In PostService
public function createPost($data) {
    return Post::create($data);
}

public function updatePost($id, $data) {
    $post = Post::find($id);
    $post->update($data);
    return $post;
}
```
By abstracting business logic into services, you can call these methods in both GraphQL resolvers and REST controllers, ensuring code reuse.

## Set Up GraphQL -- dddig olyanok coltak, ami resthez is kell

### Actions:

1. Install a GraphQL package like Lighthouse or graphql-laravel.

```cmd
composer require nuwave/lighthouse
php artisan lighthouse:install
```

2. Define your GraphQL schema (`schema.graphql`) and types based on the models you’ve created. A PostType could look like this:

```php
type Post {
    id: ID!
    title: String!
    description: String
    user: User
    plant: Plant
}

type Query {
    posts: [Post]
    post(id: ID!): Post
}

type Mutation {
    createPost(title: String!, description: String!): Post
}
```

3. Define queries and mutations that map to the Eloquent model logic (or service layer).
   
`app/GraphQL/Queries/PostQuery.php`
```php
namespace App\GraphQL\Queries;

use App\Models\Post;

class PostQuery
{
    public function resolve($root, array $args)
    {
        return Post::find($args['id']);
    }
}
```

##  Test and Debug
Before finalizing the decision on which API type to use, test your API endpoints (both REST and GraphQL) using a tool like Postman or **GraphQL Playground**. Ensure that:

- Authentication is working.
- Queries return correct data.
- Mutations modify the data as expected.

## Documenting the API (Optional)
Regardless of whether you choose REST or GraphQL, good documentation is essential. Tools like Swagger (for REST) or GraphQL Playground (for GraphQL) can help document and test your API endpoints.

For REST API documentation, you can use Swagger:

```cmd
composer require darkaonline/l5-swagger
php artisan l5-swagger:generate
```

For GraphQL, you can use **GraphQL Playground** to interactively test and document your queries.

