# Endpoints
apiban egyelőre össze vissza vannak a responsek, idk idk

## Possible responses:
### Not gud
- 404 (Not Found)
- 400 (Bad Request)
- 401 (Not Authorised)
### GET: 
- 200 (OK) + data
### POST : (mostly to create new resource, called on a collection)
- 201 (Created) + ‘Location’ header (ex.: with link to /users/12/posts/{id} containing new ID)
- 200 (OK) - pl login, since we are not creating a new resource
  - *"Many times, the action performed by the POST method might not result in a resource that can be identified by a URI. In this case, either HTTP response code 200 (OK) or 204 (No Content) is the appropriate response status."*
### DELETE
- 204 (No Content)

<hr>

## LoginPage
### register
- post
- in: email, username, hash(password)
- out: 200 ok (email megerősítés)/ 400 

### ***POST: user/login***
in:
- email / (username), 
- password, 
out good request:
- 200,
- token (tokenben benne van az adminlevel)
- user
out bad request:
-  401, message: incorrect email or password

### logout
- requires token
- that wont be an endpoint just delete the token i guess
### passwordReset
- not a clue in the word

<hr>

## profilpage (ott vannak a fieldek, alján mentés gomb, mentésre azt küldjük tovább ami változott)
### PATCH: api/user (api/profile?)
- requires token to owner acount = > 401 unautorized ?
in: (pl)
- email
- city
out: 200 ok / 400 bad request

- 
## getContact/{user}
- get
- requires token = > 401 unautorized
- out: 200 conatct details()

# PostsPage 
## POST : `/api/post`
- requires token to owner acount = > 401 unautorized  ?
in:
- city(optional),
- title,
- plant,
- description,
- media(optional),
- sell
out: 200 ok / 400 incorrert type or insuficent information(bad request)
## delPost
- delete
- requires token to owner acount = > 401 unautorized
## getPost
- get
- requires token = > 401 unatorized
- out: all posts(username, title, description, plantName, media, sell)
## searchPost  search?q=user+search+terms
- get
- requires token => 401
- out: posts that are found (username, title, description, plantName, media, sell)
## filterPost
- get
- requires tokens => 401
- out: posts that are filtered (username, title, description, plantName, media, sell)
## getPlants
- get
- out: all pants ()
## getPlants\{user}
- get
- out: plants for user()
## getPlant\{plantnumber}
- get
- out: one plant(by id) - all data
## newArticle
 - put
 - requires authorized token => 401
 - 
## getArticle\{articlenumber}
- get
- out: link to article.html on  the server?
- 
