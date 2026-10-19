# REST API Fundamentals

**DecodeLabs | Backend Development | Project 1 | Batch 2026**

A beginner-friendly REST API built with Node.js and Express.js. It demonstrates a local server, GET and POST routes, JSON responses, basic validation, and testing with Postman.

## Requirements

- Node.js (LTS version recommended)
- npm (included with Node.js)
- Postman (for API testing)

## Run the project

1. Extract the ZIP file.
2. Open the `REST-API-Fundamentals` folder in VS Code.
3. Open the terminal in that folder.
4. Install dependencies:

   ```bash
   npm install
   ```

5. Start the server:

   ```bash
   npm start
   ```

6. Keep the terminal running. The API will be available at `http://localhost:3000`.

## API endpoints

### 1. Check the API

- Method: `GET`
- URL: `http://localhost:3000/`

### 2. Get all products

- Method: `GET`
- URL: `http://localhost:3000/products`
- Expected status: `200 OK`

Example response:

```json
{
  "success": true,
  "count": 2,
  "data": [
    {
      "id": 1,
      "name": "Wireless Mouse",
      "price": 1200,
      "category": "Electronics"
    },
    {
      "id": 2,
      "name": "Notebook",
      "price": 250,
      "category": "Stationery"
    }
  ]
}
```

### 3. Create a product

- Method: `POST`
- URL: `http://localhost:3000/products`
- In Postman, select **Body → raw → JSON**.
- Expected status: `201 Created`

Request body:

```json
{
  "name": "Keyboard",
  "price": 1800,
  "category": "Electronics"
}
```

The response includes the newly created product and its generated ID.

### 4. Test validation

Send a POST request with a missing or invalid field, for example:

```json
{
  "name": "",
  "price": -10,
  "category": "Electronics"
}
```

Expected status: `400 Bad Request`.

### 5. Test an unknown route

Try `GET http://localhost:3000/unknown`.

Expected status: `404 Not Found`, with a JSON error message.

## Postman collection

Import `postman/REST-API-Fundamentals.postman_collection.json` into Postman. Start the server first, then run the requests.

## Notes

- Products are stored in memory, not a database. Products created through POST disappear when the server restarts.
- The project is intended for local learning and demonstration.
