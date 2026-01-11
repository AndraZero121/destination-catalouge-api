# Postman Request Bodies

## Auth
- `POST /api/register`
```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "phone": "6281234567890",
  "password": "password123",
  "password_confirmation": "password123"
}
```
  Either `email` atau `phone` harus ada minimal salah satu.
  Response (201)
```json
{
  "message": "Registration successful",
  "user": {
    "id": 1,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "phone": "6281234567890"
  },
  "access_token": "token",
  "token_type": "Bearer"
}
```

- `POST /api/login`
```json
{
  "login": "budi@example.com",
  "password": "password123"
}
```
  Jika `require_otp` true, lanjutkan ke verifikasi OTP.
  Response (200) jika OTP diperlukan
```json
{
  "message": "OTP sent for login",
  "require_otp": true
}
```
  Response (200) jika OTP tidak diperlukan
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Budi Santoso",
    "email": "budi@example.com"
  },
  "access_token": "token",
  "token_type": "Bearer"
}
```

- `POST /api/login/verify-otp`
```json
{
  "login": "budi@example.com",
  "otp": "123456"
}
```
  Response (200)
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Budi Santoso",
    "email": "budi@example.com"
  },
  "access_token": "token",
  "token_type": "Bearer"
}
```

- `POST /api/password/forgot`
```json
{
  "email": "budi@example.com"
}
```
  Response (200)
```json
{
  "message": "OTP sent for password reset"
}
```

- `POST /api/password/reset`
```json
{
  "email": "budi@example.com",
  "otp": "123456",
  "password": "newPassword456",
  "password_confirmation": "newPassword456"
}
```
  Response (200)
```json
{
  "message": "Password reset successful"
}
```

- `POST /api/logout`
  Tidak membutuhkan body.
  Response (200)
```json
{
  "message": "Logged out successfully"
}
```

## Destinations (public)
- `GET /api/destinations`
```json
{
  "search": "pantai",
  "category_id": 1,
  "province_id": 1,
  "city_id": 1,
  "sort": "nearby",
  "latitude": -6.2088,
  "longitude": 106.8456
}
```
  Semua parameter opsional dan dikirim sebagai query string.
  Response (200)
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Pantai Kuta",
      "category_id": 1,
      "province_id": 1,
      "city_id": 1,
      "photos": [],
      "reviews": []
    }
  ]
}
```

- `GET /api/destinations/{id}` & `GET /api/destinations/slider`
  Tidak membutuhkan body.
  Response (200) `/api/destinations/{id}`
```json
{
  "id": 1,
  "name": "Pantai Kuta",
  "category": {
    "id": 1,
    "name": "Pantai"
  },
  "province": {
    "id": 1,
    "name": "Bali"
  },
  "city": {
    "id": 1,
    "name": "Badung"
  },
  "photos": [],
  "reviews": [],
  "average_rating": 4.5
}
```
  Response (200) `/api/destinations/slider`
```json
[
  {
    "id": 1,
    "name": "Pantai Kuta",
    "photos": []
  }
]
```

## Locations (public)
- `GET /api/provinces`
  Tidak membutuhkan body.
  Response (200)
```json
[
  {
    "id": 1,
    "name": "Jawa Tengah"
  }
]
```

- `GET /api/provinces/{id}/cities`
  Tidak membutuhkan body.
  Response (200)
```json
[
  {
    "id": 10,
    "province_id": 1,
    "name": "Semarang"
  }
]
```

## Profile (auth:sanctum)
- `GET /api/profile`
  Tidak membutuhkan body.
  Response (200)
```json
{
  "id": 1,
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "latest_review": null,
  "reviews_count": 0,
  "saved_count": 0
}
```

- `POST /api/profile/update`
```json
{
  "name": "Budi Santoso",
  "photo": "<file upload>"
}
```
  Kirim sebagai multipart/form-data; keduanya opsional.
  Response (200)
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 1,
    "name": "Budi Santoso",
    "photo_url": "profile-photos/example.jpg"
  }
}
```

- `POST /api/profile/password`
```json
{
  "otp": "123456",
  "current_password": "oldPassword123",
  "password": "newPassword456",
  "password_confirmation": "newPassword456"
}
```
  `current_password` opsional jika OTP sudah valid.
  Response (200)
```json
{
  "message": "Password updated successfully"
}
```

- `POST /api/profile/password/request-otp`
  Tidak membutuhkan body.
  Response (200)
```json
{
  "message": "OTP sent for password change"
}
```

## Reviews (auth:sanctum)
- `POST /api/reviews`
```json
{
  "destination_id": 5,
  "rating": 4,
  "description": "Tempatnya nyaman dan bersih."
}
```
  Response (201)
```json
{
  "message": "Review submitted successfully",
  "review": {
    "id": 1,
    "destination_id": 5,
    "rating": 4,
    "description": "Tempatnya nyaman dan bersih."
  }
}
```

- `GET /api/reviews/my` & `DELETE /api/reviews/{id}`
  Tidak membutuhkan body.
  Response (200) `/api/reviews/my`
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "destination_id": 5,
      "rating": 4,
      "description": "Tempatnya nyaman dan bersih."
    }
  ]
}
```
  Response (200) `DELETE /api/reviews/{id}`
```json
{
  "message": "Review deleted successfully"
}
```

## Saved Destinations (auth:sanctum)
- `GET /api/saved`
  Tidak membutuhkan body.
  Response (200)
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "destination_id": 5,
      "destination": {
        "id": 5,
        "name": "Pantai Kuta",
        "photos": []
      }
    }
  ]
}
```

- `POST /api/saved`
```json
{
  "destination_id": 5
}
```
  Response (201)
```json
{
  "message": "Destination saved successfully",
  "saved": {
    "id": 1,
    "destination_id": 5
  }
}
```

- `DELETE /api/saved/{id}`
  Tidak membutuhkan body.
  Response (200)
```json
{
  "message": "Destination removed from saved list"
}
```
