const express = require("express");

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware: parse incoming JSON request bodies.
app.use(express.json());

// Sample in-memory product data.
// Note: data added with POST is reset when the server restarts.
let products = [
  { id: 1, name: "Wireless Mouse", price: 1200, category: "Electronics" },
  { id: 2, name: "Notebook", price: 250, category: "Stationery" }
];
let nextId = 3;

// Basic home route.
app.get("/", (req, res) => {
  res.status(200).json({
    message: "Welcome to the REST API Fundamentals project",
    endpoints: {
      getProducts: "GET /products",
      createProduct: "POST /products"
    }
  });
});

// GET: return all products as JSON.
app.get("/products", (req, res) => {
  res.status(200).json({
    success: true,
    count: products.length,
    data: products
  });
});

// POST: validate and create a product.
app.post("/products", (req, res) => {
  const { name, price, category } = req.body;

  if (
    typeof name !== "string" ||
    name.trim() === "" ||
    typeof price !== "number" ||
    !Number.isFinite(price) ||
    price < 0 ||
    typeof category !== "string" ||
    category.trim() === ""
  ) {
    return res.status(400).json({
      success: false,
      message: "Please provide a non-empty name, a non-negative numeric price, and a non-empty category."
    });
  }

  const newProduct = {
    id: nextId++,
    name: name.trim(),
    price,
    category: category.trim()
  };

  products.push(newProduct);

  return res.status(201).json({
    success: true,
    message: "Product created successfully",
    data: newProduct
  });
});

// Handle unknown routes with a JSON response.
app.use((req, res) => {
  res.status(404).json({
    success: false,
    message: `Route not found: ${req.method} ${req.originalUrl}`
  });
});

// Handle malformed JSON and unexpected errors.
app.use((err, req, res, next) => {
  if (err instanceof SyntaxError && "body" in err) {
    return res.status(400).json({
      success: false,
      message: "Invalid JSON body. Please check your request format."
    });
  }

  console.error(err);
  return res.status(500).json({
    success: false,
    message: "Internal server error"
  });
});

app.listen(PORT, () => {
  console.log(`REST API is running at http://localhost:${PORT}`);
  console.log(`GET  http://localhost:${PORT}/products`);
  console.log(`POST http://localhost:${PORT}/products`);
});
