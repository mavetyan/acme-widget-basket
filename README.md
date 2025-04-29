# Acme Widget Basket

Implemented by Mher Avetyan. 

This project is a **proof-of-concept** for an e-commerce basket system designed to handle products, special offers, and delivery rules. The example is intentionally concise, aiming to demonstrate good engineering practices, modular code, and easily testable components.

---

## Table of Contents

1. [Overview](#overview)
2. [Core Features](#core-features)
3. [Architecture & Design Patterns](#architecture--design-patterns)
    - [Folder Structure](#folder-structure)
    - [Key Classes](#key-classes)
    - [Used Principles](#used-principles)
4. [How to Install](#how-to-install)
5. [How to Test](#how-to-test)
6. [Running index.php](#running-indexphp)
7. [How to Run via Docker (Optional)](#how-to-run-via-docker-optional)
8. [Further Extensions](#further-extensions)

---

## Overview

**Acme Widget Co** sells three widget products:

| Product Code | Name          | Price  |
|--------------|---------------|--------|
| R01          | Red Widget    | $32.95 |
| G01          | Green Widget  | $24.95 |
| B01          | Blue Widget   | $7.95  |

They have **tiered delivery rules**:
- Orders **under $50** => $4.95 shipping
- Orders **under $90** => $2.95 shipping
- Orders **$90 or more** => free shipping

They also have a special **offer**:
> Buy one red widget, get the second half price.

The app creates a **basket** that:
1. Accepts **product codes** (e.g., “R01”)
2. **Calculates** the total including offers and delivery
3. Produces the **correct totals** for sample scenarios

A full breakdown and sample results:

| Products (in basket)         | Final Total |
|------------------------------|------------:|
| B01, G01                     | 37.85       |
| R01, R01                     | 54.37       |
| R01, G01                     | 60.85       |
| B01, B01, R01, R01, R01      | 98.27       |

---

## Core Features

1. **Clean OOP Design**: Code is split into multiple classes, each handling a single concern (Offers, Delivery, Products, etc.).
2. **Type-Safe Money**: A `Money` value object ensures we store prices as integer cents, avoiding floating-point errors.
3. **Flexible Offer System**: The `OfferStrategy` pattern can handle multiple discount logic classes (e.g., `RedWidgetOffer`).
4. **Tiered Delivery**: The `DeliveryChargeCalculator` uses a simple rule-based approach to compute shipping costs.
5. **Unit Tested**: With PHPUnit, we verify correctness for a handful of example baskets.

---

## Architecture & Design Patterns

### Folder Structure

# Project Structure: acme-widget-basket

```
acme-widget-basket/
├─ composer.json
├─ phpunit.xml
├─ phpstan.neon
├─ Dockerfile
├─ docker-compose.yml
├─ README.md
├─ index.php
├─ src/
│  ├─ Types/
│  │  └─ Money.php
│  ├─ Products/
│  │  ├─ Product.php
│  │  └─ ProductCatalogue.php
│  ├─ Exceptions/
│  │  └─ ProductNotFoundException.php
│  ├─ Offers/
│  │  ├─ OfferInterface.php
│  │  ├─ RedWidgetOffer.php
│  │  └─ OfferStrategy.php
│  ├─ Delivery/
│  │  ├─ DeliveryRule.php
│  │  ├─ DeliveryChargeCalculatorInterface.php
│  │  └─ DeliveryChargeCalculator.php
│  ├─ BasketInterface.php
│  └─ Basket.php
└─ tests/
   ├─ BasketTest.php
   ├─ Delivery/
   │  └─ DeliveryChargeCalculatorTest.php
   ├─ Types/
   │  └─ MoneyTest.php
   ├─ Offers/
   │  ├─ OfferStrategyTest.php
   │  └─ RedWidgetOfferTest.php
   └─ Products/
      └─ ProductCatalogueTest.php
```

### Key Classes
- `Basket` – Central class, Basket that adds products, applies offers, delivery charges, and calculates the totals.
- `BasketInterface` – Interface for basket operations.
- `Product` – Represents a product with a code and price.
- `ProductCatalogue` – Looks up products by code.
- `Money` – Value object to safely represent money in cents (avoids float errors).
- `RedWidgetOffer` – Specific offer: “Buy one red widget, get the second half price.”
- `OfferStrategy` – Applies offer rules to the basket.
- `DeliveryRule` – Allows adding delivery rules, e.g. free delivery after a threshold, etc. 
- `DeliveryChargeCalculator` – Applies delivery charges based on subtotal.
- `ProductNotFoundException` – Thrown when a product code is not in the catalogue, more exceptions can be added.

### Used Principles

- **Domain-Driven Design (DDD-lite)**: The design uses value objects, entities, aggregate roots, and domain services to model business logic meaningfully and cleanly.
- **Dependency Injection (DI)**: Key collaborators like the product catalogue, delivery strategy, and offer logic are injected.
- **Strategy Pattern**: Used for flexible offer and delivery logic (`OfferStrategy`, `DeliveryChargeCalculator`).
- **Value Object**: `Money` represents price immutably and safely in cents.
- **Sensible Types & Contracts**: All types are strongly typed with clear interfaces.
- **PSR-4 Autoloading & PSR-12 Style**: Codebase follows modern PHP standards.

---

## How to Install

```bash
composer install
```

---

## How to Test

```bash
vendor/bin/phpunit
```

You can also run:

```bash
vendor/bin/phpstan analyse src tests
```

---


## Running index.php

In addition to tests you can also run a CLI in the console. A simple CLI runner is provided that accepts product codes as input arguments:

For example: 

```bash
php index.php B01, R01, R01, G01
```

This will:

1. Instantiate the product catalogue, delivery rules, and offer strategy.
2. Add the provided product codes to the basket.
3. Apply all offers and delivery charges.
4. Print the final total to the console.

### Example Output

```bash
Basket: B01, R01, R01, G01
Total: $85.27
```

If no arguments are passed, it will display usage instructions:

```bash
Usage: php index.php <PRODUCT_CODE> [<PRODUCT_CODE> ...]
```


---

## How to Run via Docker (Optional)

```bash
docker-compose build
docker-compose up
```

---

## Further Extensions

- Additional offers
- Multi-currency
- More complex delivery logic
- Event sourcing / domain events
- Frontend or API integration

---

**Thank you** for checking out **Acme Widget Basket**!

