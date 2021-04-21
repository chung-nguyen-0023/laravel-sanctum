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

# Questions
1. Nếu token được lưu vài năm, mà token thì thường được lưu ở localStorage phía client thì khi hacker hack được thì sẽ bị lỗi bảo mật à. (Xem xét cách fix lại thời gian sống cho token ngắn lại)

# Refresh token
https://www.google.com/search?q=laravel+refresh+token&oq=laravel+refresh+token&aqs=chrome..69i57j69i60.4925j0j7&sourceid=chrome&ie=UTF-8
Laravel Passport Refresh Token: https://morioh.com/p/49e5ab43d157

# Diagram Laravel Sanctum
https://app.diagrams.net/#G1S-RV8xqk4Ay2ZpJDvYaeKWnQw2x4sLpg

# Viewing
done https://www.youtube.com/watch?v=gyWLxpYWxFQ
