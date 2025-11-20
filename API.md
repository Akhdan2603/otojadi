# API Documentation

Base URL: `/api`

## Authentication

### POST `/api/register`
Registers a new user.

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "securepassword",
  "phone": "+628123456789"
}
```

**Response:**
*   201 Created: User created.
*   400 Bad Request: Validation error or email exists.

### GET/POST `/api/auth/*`
Handled by NextAuth.js. See [NextAuth Docs](https://next-auth.js.org/) for details.

---

## Products (Planned)

### GET `/api/products`
List all available templates.

**Query Params:**
*   `category`: Filter by category slug.
*   `search`: Search by title.

### POST `/api/products` (Admin Only)
Create a new template product.

**Body:**
```json
{
  "title": "Business Deck",
  "price": 15.00,
  "fileUrl": "...",
  "categoryId": "..."
}
```

---

## Transactions

### POST `/api/checkout`
Initiate a purchase.

**Body:**
```json
{
  "items": [{ "productId": "123", "quantity": 1 }]
}
```

**Response:**
*   Returns Midtrans Snap Token.

### POST `/api/midtrans-callback`
Webhook for Midtrans payment status updates.
