Available functions:

* url() - make an url. Alias of the method ```$modx->makeUrl()```.
* redirect() - redirect to the url or site page if the id is passed. Wrapper for ```$modx->redirect```.
* forward() - forwards the request to another resource without changing the URL. The short call of ```$modx->forward```.
* [abort()](./en/abort.md) - redirect to the error or unauthorized page.
* [config()](./en/config.md) - manage the config settings.
* [session()](./en/session.md) - manage the session using dot notation.
* [cache()](./en/cache.md) - manage the MODX cache.
* parents() - gets all of the parent resource ids for a given resource. The short call of ```$modx->getParentIds```.
* children() - gets all of the child resource ids for a given resource. The short call of ```$modx->getChildIds```.
* [pls()](./en/pls.md) - to work with placeholders.
* [pls_delete()](./en/pls_delete.md) - removes the specified placeholders.
* lang() - to work with lexicon records. Can be used instead of ```$modx->lexicon()```.
* table_name() - gets the table name of the specified class. Can be used instead of ```xPDO::getTableName()```.
* columns() - gets select columns from a specific class for building a query. Can be used instead of ```xPDO::getSelectColumns()```.
* [email()](./en/email.md) - send email.
* [email_user()](./en/email_user.md) - send email to the specified user.
* [str_clean()](./en/str_clean.md) - sanitize the string. Wrapper for ```$modx->sanitizeString```
* quote() - quote the string.
* escape() - escapes the provided string using the platform-specific escape character.
* css() - register CSS to be injected inside the HEAD tag of a resource.
* [script()](./en/script.md) - register JavaScript to be injected inside the HEAD tag or before the closing BODY tag. Available the script attributes "async" and "defer".
* [html()](./en/html.md) - register HTML to be injected inside the HEAD tag or before the closing BODY tag.
* chunk() - gets the specified chunk or file. Can be used instead of ```$modx->getChunk()```.
* [snippet()](./en/snippet.md) - runs the specified snippet from DB or file. The result can be cached.
* processor() - runs the specified processor. Can be used instead of ```$modx->runProcessor()```.
* [is_auth()](./en/is_auth.md) - determines if the user is authenticated in a specific context.
* is_guest() - determines if the user is a guest. Checks equality ```$modx->user->id == 0```
* can() - returns true if user has the specified policy permission. Can be used instead of ```$modx->hasPermission()```.
* resource_id() | res_id() - gets the id of the current resource. Returns the value of $modx->resource->id. 
* template_id() - gets the template id of the current resource. Returns the value of $modx->resource->template.
* user_id() - gets the id of the current user. Returns the value of $modx->user->id.
* tv() - gets the specified TV of the current resource. 
* object() - to work with objects of MODX.
* collection() - to work with collections.
* resource() - works with a resource object.
* resources() - works with a resource collection.
* user() - works with a user object.
* users() - works with a user collection.
* object_exists() - checks if the specified object exists.
* user_exists() - checks if the specified user exists.
* resource_exists() - checks if the specified resource exists.
* is_email() - validates the email. Can be used to validate the form data.
* is_url() - validates the url.
* [log_error()](./en/logger.md) — logs to the error log for the ERROR log level.
* [log_warn()](./en/logger.md) — logs to the error log for the WARN log level.
* [log_info()](./en/logger.md) — logs to the error log for the INFO log level.
* [log_debug()](./en/logger.md) — logs to the error log for the DEBUG log level.
* context() - gets the specified property of the current context. By default, returns the key.
* [query()](./en/query.md) - works with raw SQL queries.
* [memory()](./en/memory.md) - returns the formatted string of the amount of memory allocated to PHP.
* [img()](./en/img.md) - prepares the HTML tag "img".
* [faker()](./en/faker.md) - creates a faked data.
* [load_model()](./en/load_model.md) - loads a model for a custom table.
* is_ajax() - returns true if the current request is asynchronous (ajax).
* [login()](./en/login.md) - force login the specified user to the current context.
* [logout()](./en/logout.md) - force logout the current user.
