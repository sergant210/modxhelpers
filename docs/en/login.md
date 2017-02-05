##login
Force login the specified user to the current context.
```login($user);```
- $user (int|modUser) - User id or modUser object. 

```php
login(4);
// OR
$user = user(4);
login($user);
```