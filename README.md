# Map filter backenD using: Docker, Lando, Wordpress Bedrock, ACF, and WP GraphQL

<h2>HOW TO INSTALL</h2>
<ul>
<li>Install Lando: https://docs.lando.dev/getting-started/installation.html</li>

<li>Install packages <code>composer install</code></li>
<li>Follow the instuctions here: https://roots.io/bedrock/docs/bedrock-with-lando/</li>
<li>Create .env file in root folder</li>
<li>Copy the content from .env.example to .env file</li>
 </ul>
<h3>Lando install</h2>
<ul>
<li>After cloning the repo to your folder and finish configure .env file using bedrock with lando tutorial, and after installing composer dependencies<br>
<li>Run script <code>lando init</code>
 <li>Answer the following questions in terminal:
  <ul>
   <li> From where should we get your app's codebase? - choose current working directory</li>
   <li> What recipe do you want to use? - choose wordpress</li>
   <li> Where is your webroot relative to the init destination? - write web</li>
   <li> What do you want to call this app? - i called it bedrock2</li>

  </ul>
 <br>

Your .env file should look like this:
```
DB_NAME='wordpress'
DB_USER='wordpress'
DB_PASSWORD='wordpress'
DB_HOST='database'

# Optionally, you can use a data source name (DSN)
# When using a DSN, you can remove the DB_NAME, DB_USER, DB_PASSWORD, and DB_HOST variables
# DATABASE_URL='mysql://database_user:database_password@database_host:database_port/database_name'

# Optional database variables
# DB_HOST='localhost'
# DB_PREFIX='wp_'

WP_ENV='development'
WP_HOME='https://bedrock2.lndo.site'
WP_SITEURL="${WP_HOME}/wp"

# Specify optional debug.log path
# WP_DEBUG_LOG='/path/to/debug.log'

# Generate your keys here: https://roots.io/salts.html
AUTH_KEY=
SECURE_AUTH_KEY=
LOGGED_IN_KEY=
NONCE_KEY=
AUTH_SALT=
SECURE_AUTH_SALT=
LOGGED_IN_SALT=
NONCE_SALT=
```

After installation done run script <code>lando start</code><br>
To stop lando server run <code>lando stop</code>

** Pass app url to the frontend .env.local NEXT_PUBLIC_WORDPRESS_API_URL constant, example:<br>
  <code>NEXT_PUBLIC_WORDPRESS_API_URL=http://localhost:00000/wp</code>
<br><br>
<ul>
<li>After installing wordpress, activate all install plugins, switch to bedrock theme</li>
<li>Install ACF/ACF Pro</li>
<li>Install JWT GraphQL from here: https://github.com/wp-graphql/wp-graphql-jwt-authentication just copy the repo to plugins folder and activate</li>
<li>In Theme Options, insert google maps api key and save</li>
 </ul>

<h3>WP GraphQL JWT Settings</h3>
  <ul>
<li>In wordpress admin -> GraphQL -> Settings</li>
<li>Check Restrict Endpoint to Authenticated Users checkbox</li>
<li>In the .env file generate the tokens from here:https://roots.io/salts.html</li>
<li>Afte updating the .env file, copy the NONCE_SALT.</li>
<li>In functions.php locate graphql_jwt_auth_secret_key filter and add the NONCE_SALT token.</li>
<li>Open graphql IDE from WordPress admin and run the query below to generate refresh token. Don't forget to change your credentials
```
mutation LoginUser {
  login(
    input: {clientMutationId: "uniqueId", 
      username: "wordpress username",
      password: "wordpress password"}
  )
    refreshToken

}
```
  </li>
<li>The response should contain your refreshToken. copy it and pass to NextJs .env.local file constant: NEXT_PUBLIC_WORDPRESS_AUTH_REFRESH_TOKEN</li>
 </ul>
