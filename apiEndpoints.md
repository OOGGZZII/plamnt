# Endpioints
## register
- post
- in: email, username, hash(password)
- out: 200 ok (email megerősítés)/ 400 
## login
- post
- in: email / username, hash(password), 
- out: 200 token (adminMode?)/ 401 incorrect email or password
## logout
- requires token
- that wont be an endpoint just delete the token i guess
## passwordReset
- not a clue in the word
## locationUpdate
- post
- requires token to owner acount = > 401 unautorized
- in: newLocation
- out: 200 ok / 400 bad request
## emailUpdate
- post
- requires token to owner acount = > 401 unautorized
- in: neweamil
- out: 200 ok / 400 bad request
## telNumUpdate
- post
- requires token to owner acount = > 401 unautorized
- in: newTelNum
- out: 200 ok / 400 bad request
## getContact/{user}
- get
- requires token = > 401 unautorized
- out: 200 conatct details
## newPost
- put
- requires token to owner acount = > 401 unautorized 
- in: city(optional), title, plant, description, media(optional), sell
- out: 200 ok / 400 incorrert type or insuficent information(bad request)
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
## getArticle\{articlenumber}
- get
- out: link to article.html on  the server?
- 
