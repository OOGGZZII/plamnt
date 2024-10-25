# Endpioints
## register
  - post
  - in: email, username, hash(password)
  - out:succes
## login
  - post
  - in: email / username, hash(password)
  - out: token
## logout
  - that wont be an endpoint just delete the token i guess 
## getArticle\{articlenumber}
  - get
  - out:
