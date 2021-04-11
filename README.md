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

# Viewing
done https://www.youtube.com/watch?v=gyWLxpYWxFQ
