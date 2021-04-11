# Note
## createToken
```php
$user = User::first();
$user->createToken('developer-access');
```

## createToken with Permission
```php
$user = User::first();
$user->createToken('developer-access', ['categories-list']);
```

# Viewing
done https://www.youtube.com/watch?v=gyWLxpYWxFQ
