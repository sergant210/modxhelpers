Available functions:

* url() - make an url.
* redirect() - redirect to the url or site page if the id is passed.
* forward() - forwards the request to another resource without changing the URL.
* abort() - redirect to the error page.
* config() - manage the config settings.
* session() - manage the session using dot notation.
* cache() - manage the cache.
* parents() - gets all of the parent resource ids for a given resource. 
* children() - gets all of the child resource ids for a given resource.
* pls() - to work with placeholders.
* pls_delete() - removes the specified placeholders.
* lang() - to work with lexicon records.
* email() - send email.
* email_user() - send email to the specified user.
* pdotools() - get the pdoTools object.
* pdofetch() - get the pdoFetch object.
* clean - sanitize the string.
* quote() - quote the string.
* esc() - escapes the provided string using the platform-specific escape character.
* css() - register CSS to be injected inside the HEAD tag of a resource.
* script() - register JavaScript to be injected inside the HEAD tag or before the closing BODY tag.
* html() - register HTML to be injected inside the HEAD tag or before the closing BODY tag.
* chunk() - gets the specified chunk.
* snippet() - runs the specified snippet.
* processor() - runs the specified processor.
* is_auth() - determines if this user is authenticated in a specific context.
* is_guest() - determines if the user is a guest.
* can() - returns true if user has the specified policy permission.
* resource_id() - gets the id of the current resource. 
* template_id() - gets the template id of the current resource.
* user_id() - gets the id of the current user. 
* tv() - gets the specified of the current resource. 
* object() - to work with objects of MODX.
* collection() - to work with object collections of MODX.
* resource() - works with a resource object.
* resources() - works with a resource collection.
* user() - works with a user object.
* users() - works with a user collection.
* object_exists() - checks if the specified object exists.
* user_exists() - checks if the specified user exists.
* resource_exists() - checks if the specified resource exists.
* str_clean() — sanitize the string.
* table_name() - gets the table name of the specified class.
* columns() - gets select columns from a specific class for building a query.
* is_url() — validates the url.
* is_email() — validates the email.
* log_error() — logs to the error log for the ERROR log level.
* log_warn() — logs to the error log for the WARN log level.
* log_info() — logs to the error log for the INFO log level.
* log_debug() — logs to the error log for the DEBUG log level.
* context() - gets the name of the current context.
* query() - runs a raw query.
* memory() - returns the amount of memory allocated to PHP.

