
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
