# Endpioints
## register
  - post
  - in: email, username, hash(password)
  - out:succes
## login
  - post
  - in: email / username, hash(password), (adminMode?)
  - out: token or whyNotWorky
## logout
  - that wont be an endpoint just delete the token i guess
## getPlanst
  - get
  - out: all pants - some data
## getPlant\{plantnumber}
  - get
  - out: one plant(by id) - all data
## getArticle\{articlenumber}
  - get
  - out: link to article.html on  the server?
  - 
