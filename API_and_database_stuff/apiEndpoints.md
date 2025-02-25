# Endpoints
apiban egyelőre össze vissza vannak a responsek, idk idk

## Possible responses:
### Not gud
- 404 (Not Found)
- 400 (Bad Request)
- 401 (Not Authorised)
### GET 
- 200 (OK) + data
### Listing (mostly to create new resource, called on a collection ex: `users/12/Listings`)
- 201 (Created) + ‘Location’ header (ex.: with link to `/users/12/Listings/{id}` containing new ID)
- 200 (OK) - pl login, since we are not creating a new resource
  - *"Many times, the action performed by the Listing method might not result in a resource that can be identified by a URI. In this case, either HTTP response code 200 (OK) or 204 (No Content) is the appropriate response status."*
### DELETE
- 204 (No Content)

<hr>

## LoginPage
### `Listing : /users/` - resgistration
*ebben nem vagyok biztos, így gondolom de majd a levi tudja videóbol pl ugy is*
(email megerősítés)
in: 
- email,
- username,
- password (hash?)
out: 201 created 

### ***`Listing: /user/login`***
in:
- email / (username), 
- password, 
out good request:
- 200,
- token (tokenben benne van az adminlevel)
- user
out bad request:
-  401, message: incorrect credentials
  
### passwordReset ??
- not a clue in the word

<hr>

## ProfilePage (ott vannak a fieldek, alján mentés gomb, mentésre azt küldjük tovább ami változott)
### `PATCH: /user` or `PATCH: /users/{username}` (/profile?)
- requires token to owner acount = > 401 unautorized ?
in: (pl)
- email
- city
out: 200 ok / 400 bad request

### + logout ??
- requires token
- that wont be an endpoint just delete the token i guess

<hr>

## ListingsPage / Marketplace / Listings (a grid view)
kérdés hogy hogyan továbbítjuk az aktuális felhasználót. adná magát a /users/{username}/... de lehet hogy pl az aktuális tokent lekérni egyszerűbb/úgy kell
### `GET listings/search?q=user+search+terms` filtering is valahogy
out: Listings that are found, pagination
- (username, title, description, plantName, media, sell)

### `GET listings/{id}`
<hr>

## ListingDetailsPage
### `GET: /{user}/contactinfo` ?? telszám, email
- get
- requires token = > 401 unautorized
- out: 200 conatct details()

<hr>
  
## MyListingsPage
### `Listing : /Listings` or `Listing : users/{username}/Listings` - új poszt létrehozása gomb 
- requires token to owner acount = > 401 unautorized  ?
in:
- city(optional),
- title,
- plant,
- description,
- media(optional),
- sell
out: 200 ok / 400 incorrert type or insuficent information(bad request)

### GET Listings/search?q=....username={username}
fejjebb megírt get használata a saját posztok lekérésére

## Website
### GET /articles (with filter) 
- all the matching articles (short descripition, these will be on "small" cards)
### GET /article/{title}
- gets all the data of the selected articles


### `DELETE Listings/{Listingid}`
- requires token to owner account = > 401 unautorized

 <hr>

## plantek, articlek, később ig
nem írtam át még őket
### getPlants
- get
- out: all pants ()
### getPlants\{user}
- get
- out: plants for user()
### getPlant\{plantnumber}
- get
- out: one plant(by id) - all data
### newArticle
 - put
 - requires authorized token => 401




