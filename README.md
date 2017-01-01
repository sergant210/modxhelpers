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

**Send email**
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

**Array of the resource pagetitles of the parent with id = 20.**
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
if (is_email($email)) {
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

[Russian documentation](./docs/ru.md)  
[English documentation](./docs/en.md)