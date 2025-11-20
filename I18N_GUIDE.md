# Internationalization (i18n) Guide

The project is currently set up with a structure to support multilingual content, but full automatic translation requires integrating a translation library or service.

## Current Status
*   **Content:** Currently hardcoded in English/Indonesian (Mixed).
*   **Database:** Product descriptions are single-field text.

## How to Implement Full i18n

### 1. Libraries
We recommend using `next-intl`.

```bash
npm install next-intl
```

### 2. Structure
Create a `messages/` folder:
*   `messages/en.json`
*   `messages/id.json`

```json
// en.json
{
  "HomePage": {
    "title": "Professional Templates",
    "subtitle": "Save time..."
  }
}
```

### 3. Database Strategy (For Dynamic Content)
To support multilingual product titles/descriptions, update the Prisma schema:

```prisma
model Product {
  id          String @id
  // ...
  title_en    String
  title_id    String
  desc_en     String
  desc_id     String
}
```

Or use a JSON field:

```prisma
model Product {
  // ...
  title       Json // { "en": "Title", "id": "Judul" }
  description Json
}
```

### 4. Automatic Translation
To achieve "Automatic translation from English", you can use the Google Cloud Translation API in your Admin Create Product flow.

1.  Admin enters English description.
2.  Server (`POST /api/products`) calls Google Translate API.
3.  Server saves both English and Indonesian versions to the database.
