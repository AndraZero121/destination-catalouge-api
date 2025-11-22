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

- `POST /api/login`
```json
{
  "login": "budi@example.com",
  "password": "password123"
}
```

- `POST /api/logout`
  Tidak membutuhkan body.

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

- `GET /api/destinations/{id}` & `GET /api/destinations/slider`
  Tidak membutuhkan body.

## Profile (auth:sanctum)
- `GET /api/profile`
  Tidak membutuhkan body.

- `POST /api/profile/update`
```json
{
  "name": "Budi Santoso",
  "photo": "<file upload>"
}
```
  Kirim sebagai multipart/form-data; keduanya opsional.

- `POST /api/profile/password`
```json
{
  "current_password": "oldPassword123",
  "password": "newPassword456",
  "password_confirmation": "newPassword456"
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

- `GET /api/reviews/my` & `DELETE /api/reviews/{id}`
  Tidak membutuhkan body.

## Saved Destinations (auth:sanctum)
- `GET /api/saved`
  Tidak membutuhkan body.

- `POST /api/saved`
```json
{
  "destination_id": 5
}
```

- `DELETE /api/saved/{id}`
  Tidak membutuhkan body.
