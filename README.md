## MODX Helpers
Functions-helpers for MODX. It's made as composer package.

### Installation
-	Create a plugin which loads composer files.
```php
switch ($modx->event->name) {
    case 'OnMODXInit':
        $file = MODX_CORE_PATH . 'vendor/autoload.php';

        if (file_exists($file)) {
            require_once $file;
        }
        break;
}
```
- Put the composer.json to the *core* folder
```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/sergant210/modxhelpers"
    }
  ],
  "minimum-stability": "dev",
  "require": {
    "sergant210/modxhelpers": "1.*"
  }
}
```
- Use a terminal tool and run ```composer install``` or ```php composer.phar install``` within the folder *core*.

### Examples
**Check the user exists**
```php
if (user_exists(['email'=>'admin@mail.com']) {
    // Exists
}
```

**Get the data from the cache**
```php
$value = cache('key', 'my_data');
// Or 
$value = cache()->get('key', 'my_data');
```

**Send an email**
```php
email('pussycat@mail.ru', 'Subject','Email content');
// To the user
email_user('admin', $subject, $content);
```

**Redirect**
```php
redirect($url);
//To the resource with the id = 5
redirect(5);
```

**The latest resource**
```php
$resourceObject = resource()->last(); // Resource object
$resourceArray = resource()->last()->toArray(); // Resource data as array
```

**The last 10 resources**
```php
$resObjects = resources()->last(10); 
```

**Array of the resource pagetitles of the parent with the id = 20.**
```php
$titles= resources()->where(['parent'=>20])->get('pagetitle'); // array('pagetitle 1', 'pagetitle 2', 'pagetitle 3')
```

**Set a value to the session**
```php
session('key1.key2', 'value'); // => $_SESSION['key1']['key2'] = $value;
```
**Get the value from session**
```php
$value = session('key1.key2');  // $value = $_SESSION['key1']['key2']
```

**Validates the email**
```php
if (is_email($_POST['email'])) {
   // Valid
}
```
**Remove child resources of the one with the id = 10**
```php
resources()->where(['parent'=>10])->remove();
```

**Count blocked users**
```php
$count = users()->profile()->where(['Profile.blocked'=>1])->count();
```
**Load script with the async attribute**
```php
script('/path/to/script.js', 'async'); // <script async type="text/javascript" src="/path/to/script.js"></script>
```
**Get an array of users**
```php
// Can use the prepared query
$userArray = query('select * from ' . table_name('modUser'). ' WHERE id < ?')->execute(( (int) $_POST['user_id']);
```
**Log error to the error log**
```php
log_error($array); // Convert the array to string using print_r().
log_error($message, 'HTML'); // Show message on the page.
```
**Get the list of the pagetitles**
```php
return resources()->where(['id:IN'=>children(5)])->each(function($resource, $idx){ return "<li>{$idx}. ".$resource['pagetitle']."</li>";});
```
**Get all users which are members of the "Manager" group**
```php
$usersArray = users()->members('Managers')->toArray();
# Get all users from "ContentManagers" and "SaleManagers" groups 
$users = users()->members('%Managers')->get();
foreach($users as $user) {
  echo $user->username;
}
```

[Russian documentation](./docs/ru.md)  
[English documentation](./docs/en.md)