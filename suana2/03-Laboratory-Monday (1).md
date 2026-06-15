# Week 2 — Monday Lab: Multi-Table Database

**IM102 – Advanced Database Systems | 3 Hours | Computer Lab**

---

## Today's Goal

Design and build a database with multiple related tables — the foundation of a real inventory system.

---

## Setup

Copy your Week 1 config and CSS into a new folder `im102_lab2`. Create a new database.

---

## Example: Why Multiple Tables?

Imagine a store with everything in ONE table:

| product | price | category | supplier | supplier_phone |
|---------|-------|----------|----------|---------------|
| Laptop | 45000 | Electronics | TechCorp | 0912-345-6789 |
| Mouse | 500 | Electronics | TechCorp | 0912-345-6789 |
| Bread | 50 | Food | Bakeshop | 0918-765-4321 |

See the problem? "TechCorp" and its phone are repeated in every electronics row. If the phone number changes, you have to update multiple rows. If you delete all electronics, you lose TechCorp's info entirely.

**The fix: split into separate tables.**

```
categories:    id | name
suppliers:     id | name | phone
products:      id | name | price | category_id | supplier_id
```

---

## Example: JOIN Query

To get product names with actual category and supplier names:

```sql
SELECT p.name, p.price, c.name AS category, s.name AS supplier
FROM products p
JOIN categories c ON p.category_id = c.id
JOIN suppliers s ON p.supplier_id = s.id
```

The `JOIN` matches rows by their foreign keys and brings in the related data. Without it, you'd only see ID numbers.

---

## Your Turn

Design these tables for an inventory system:

- `categories` (id, name)
- `suppliers` (id, name, contact_person, phone)
- `products` (id, name, description, price, stock, category_id FK, supplier_id FK, created_at)

Write the CREATE TABLE statements. Insert at least 5 categories, 3 suppliers, and 12 products. Make sure the `category_id` and `supplier_id` in products point to real IDs from the other tables.

Build `index.php` that shows all products with their category and supplier **names** (not IDs) using JOINs. If your table shows "Electronics" instead of "1", you did it right.
