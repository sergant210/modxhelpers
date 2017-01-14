## MODX Helpers
Functions-helpers for MODX.

### Examples
**Check the user exists**
```
if (user_exists(['email'=>'admin@mail.com']) {
    // Exists
}
```

**Get the data from the cache**
```
$value = cache('key', 'my_data');
// Or 
$value = cache()->get('key', 'my_data');
```

**Send an email**
```
email('pussycat@mail.ru', 'Subject','Email content');
// To the user
email_user('admin', $subject, $content);
```

**Redirect**
```
redirect($url);
//To the resource with the id = 5
redirect(5);
```

**The latest resource**
```
$resourceObject = resource()->last(); // Resource object
$resourceArray = resource()->last()->toArray(); // Resource data as array
```

**The last 10 resources**
```
$resObjects = resources()->last(10); 
```

**Array of the resource pagetitles of the parent with the id = 20.**
```
$titles= resources()->where(['parent'=>20])->get('pagetitle'); // array('pagetitle 1', 'pagetitle 2', 'pagetitle 3')
```

**Set a value to the session**
```
session('key1.key2', 'value'); // => $_SESSION['key1']['key2'] = $value;
```
**Get the value from session**
```
$value = session('key1.key2');  // $value = $_SESSION['key1']['key2']
```

**Validates the email**
```
if (is_email($_POST['email'])) {
   // Valid
}
```
**Remove child resources of the one with the id = 10**
```
resources()->where(['parent'=>10])->remove();
```

**Count blocked users**
```
$count = users()->profile()->where(['Profile.blocked'=>1])->count();
```
**Load script with the async attribute**
```
script('/path/to/script.js', 'async'); // <script async type="text/javascript" src="/path/to/script.js"></script>
```
**Get an array of users**
```
// Can use the prepared query
$userArray = query('select * from ' . table_name('modUser'). ' WHERE id < ?')->execute(( (int) $_POST['user_id']);
```
**Log error to the error log**
```
log_error($array); // Convert the array to string using print_r().
log_error($message, 'HTML'); // Show message on the page.
```
**Get the list of the pagetitles**
```
return resources()->where(['id:IN'=>children(5)])->each(function($resource, $idx){ return "<li>{$idx}. ".$resource['pagetitle']."</li>";});
```
**Get all users which are members of the "Manager" group**
```
$usersArray = users()->members('Managers')->toArray();
// Get all users from "ContentManagers" and "SaleManagers" groups 
$users = users()->members('%Managers')->get();
foreach($users as $user) {
  echo $user->username;
}
```

[Russian documentation](./docs/ru.md)  
[English documentation](./docs/en.md)