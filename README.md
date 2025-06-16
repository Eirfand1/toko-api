# 🛍️ Toko API

API sederhana berbasis CodeIgniter 4 yang menyediakan fitur **register/login member**, serta **CRUD produk**.

---

## 🔐 Autentikasi

Gunakan token yang didapat saat login untuk akses endpoint yang dilindungi.

**Format header:**

```http
Authorization: Bearer <token>
```

---

## 📋 Daftar Endpoint

### 1. 📥 Register Member

**URL:** `POST /api/auth/register`
**Content-Type:** `application/x-www-form-urlencoded`

**Body:**

| Key      | Value                                                   |
| -------- | ------------------------------------------------------- |
| nama     | Ego                                                     |
| email    | [name.fandi07@proton.me](mailto:name.fandi07@proton.me) |
| password | 12345                                                   |

**Response:**

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "nama": "Ego",
    "email": "name.fandi07@proton.me"
  }
}
```

---

### 2. 🔓 Login Member

**URL:** `POST /api/auth/login`
**Content-Type:** `application/x-www-form-urlencoded`

**Body:**

| Key      | Value                                                   |
| -------- | ------------------------------------------------------- |
| email    | [name.fandi07@proton.me](mailto:name.fandi07@proton.me) |
| password | 12345                                                   |

**Response:**

```json
{
  "success": true,
  "message": "Login berhasil",
  "token": "<your_token_here>",
  "user": {
    "id": "1",
    "nama": "Ego",
    "email": "name.fandi07@proton.me"
  }
}
```

---

### 3. 📦 Tambah Produk

**URL:** `POST /api/produk`
**Headers**: `Authorization: Bearer <token>`
**Content-Type:** `application/x-www-form-urlencoded`

**Body:**

| Key          | Value    |
| ------------ | -------- |
| nama\_produk | gundam   |
| harga        | 10000000 |

**Response:**

```json
{
  "success": true,
  "data": {
    "kode_produk": "P003",
    "nama_produk": "gundam",
    "harga": "10000000"
  }
}
```

---

### 4. 📥 Ambil Semua Produk

**URL:** `GET /api/produk`
**Headers**: `Authorization: Bearer <token>`

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": "1",
      "kode_produk": "P002",
      "nama_produk": "action figure",
      "harga": "500000"
    },
    {
      "id": "3",
      "kode_produk": "P003",
      "nama_produk": "gundam",
      "harga": "10000000"
    }
  ]
}
```

---


### ✅ **5. Update Produk**


**URL:** `PUT /api/produk`
**Headers**: `Authorization: Bearer <token>`
**Content-Type:** `application/x-www-form-urlencoded`

**Body:**

| Key          | Value    |
| ------------ | -------- |
| nama\_produk | gundam   |
| harga        | 10000000 |


 **Respons**:

```json
{
  "success": true,
  "message": "Produk berhasil diupdate",
  "data": {
    "kode_produk": "P005",
    "nama_produk": "lemari",
    "harga": "500000",
    "id": "4"
  }
}
```

---

### 🗑️ **6. Hapus Produk**

**URL:** `PUT /api/produk/<id>`
**Headers**: `Authorization: Bearer <token>`

**Respons**:

```json
{
  "success": true,
  "message": "Produk berhasil dihapus"
}
```

---


## 🔒 Middleware

Gunakan middleware untuk melindungi endpoint dengan validasi token:

```php
// app/Filters/AuthFilter.php
public function before(RequestInterface $request, $arguments = null)
{
    $header = $request->getHeaderLine('Authorization');
    // Validasi token, lanjutkan hanya jika token valid
}
```

Tambahkan ke `app/Config/Filters.php`:

```php
public $aliases = [
    'auth' => AuthFilter::class,
];
```

Gunakan di route:

```php

$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->resource('member', ['controller' => 'MemberController']);
    $routes->resource('produk', ['controller' => 'ProdukController']);
});

```

---
