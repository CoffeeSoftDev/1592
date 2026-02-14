# Design Document: Digital Menu Admin Module

## Overview

The Digital Menu Admin Module is a web-based administration system for managing a digital menu's content hierarchy. Built on the CoffeeSoft framework (PHP + jQuery + TailwindCSS), it follows the MVC architecture pattern with three main entities: Categories, Subcategories, and Products.

The system provides a tabbed interface for administrators to perform CRUD operations on menu items, with cascading relationships maintained through foreign keys. The module will be deployed at `DEV/MENU/` and integrates with the existing ERP infrastructure.

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Frontend (Browser)                        │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │                     menu.js                              │   │
│  │  ┌─────────┐  ┌──────────────┐  ┌─────────────┐        │   │
│  │  │   App   │  │  Subcategory │  │   Product   │        │   │
│  │  │ (Tabs)  │  │    Class     │  │    Class    │        │   │
│  │  └────┬────┘  └──────┬───────┘  └──────┬──────┘        │   │
│  └───────┼──────────────┼─────────────────┼────────────────┘   │
│          │              │                 │                     │
│          └──────────────┼─────────────────┘                     │
│                         │ AJAX (useFetch)                       │
└─────────────────────────┼───────────────────────────────────────┘
                          │
┌─────────────────────────┼───────────────────────────────────────┐
│                    ctrl-menu.php                                 │
│  ┌──────────────────────┴────────────────────────────────────┐  │
│  │  init() │ ls() │ add*() │ edit*() │ get*() │ status*()   │  │
│  └──────────────────────┬────────────────────────────────────┘  │
│                         │ extends                                │
└─────────────────────────┼───────────────────────────────────────┘
                          │
┌─────────────────────────┼───────────────────────────────────────┐
│                    mdl-menu.php                                  │
│  ┌──────────────────────┴────────────────────────────────────┐  │
│  │  list*() │ create*() │ update*() │ get*ById() │ ls*()    │  │
│  └──────────────────────┬────────────────────────────────────┘  │
│                         │ extends CRUD                           │
└─────────────────────────┼───────────────────────────────────────┘
                          │
┌─────────────────────────┼───────────────────────────────────────┐
│                      MySQL Database                              │
│  ┌──────────┐    ┌──────────────┐    ┌──────────────┐          │
│  │ category │───<│ subcategory  │───<│   products   │          │
│  └──────────┘    └──────────────┘    └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### Frontend Components (menu.js)

#### Class: App (extends Templates)
Main application class managing the tabbed interface and category operations.

```javascript
class App extends Templates {
    PROJECT_NAME: "MenuAdmin"
    
    // Core Methods
    render()              // Initialize layout and components
    layout()              // Create primary layout with tabs
    layoutTabs()          // Configure tab navigation
    
    // Category Operations
    filterBarCategory()   // Create category filter bar
    lsCategory()          // List categories in table
    addCategory()         // Show add category modal
    editCategory(id)      // Show edit category modal
    statusCategory(id, active)  // Toggle category status
}
```

#### Class: Subcategory (extends Templates)
Manages subcategory operations with category relationship.

```javascript
class Subcategory extends Templates {
    PROJECT_NAME: "Subcategory"
    
    // Core Methods
    filterBarSubcategory()  // Create subcategory filter bar
    lsSubcategory()         // List subcategories in table
    addSubcategory()        // Show add subcategory modal
    editSubcategory(id)     // Show edit subcategory modal
    statusSubcategory(id, active)  // Toggle subcategory status
}
```

#### Class: Product (extends Templates)
Manages product operations with subcategory relationship.

```javascript
class Product extends Templates {
    PROJECT_NAME: "Product"
    
    // Core Methods
    filterBarProduct()    // Create product filter bar
    lsProduct()           // List products in table
    addProduct()          // Show add product modal
    editProduct(id)       // Show edit product modal
    statusProduct(id, active)  // Toggle product status
}
```

### Backend Controller (ctrl-menu.php)

```php
class ctrl extends mdl {
    // Initialization
    init()                    // Return categories and subcategories for filters
    
    // Category Operations
    lsCategory()              // List categories with action buttons
    addCategory()             // Create new category
    editCategory()            // Update existing category
    getCategory()             // Get single category by ID
    statusCategory()          // Toggle category active state
    
    // Subcategory Operations
    lsSubcategory()           // List subcategories with action buttons
    addSubcategory()          // Create new subcategory
    editSubcategory()         // Update existing subcategory
    getSubcategory()          // Get single subcategory by ID
    statusSubcategory()       // Toggle subcategory active state
    
    // Product Operations
    lsProduct()               // List products with action buttons
    addProduct()              // Create new product
    editProduct()             // Update existing product
    getProduct()              // Get single product by ID
    statusProduct()           // Toggle product active state
}
```

### Backend Model (mdl-menu.php)

```php
class mdl extends CRUD {
    $bd = "rfwsmqex_menu."
    
    // Category Methods
    listCategory($array)          // SELECT categories with filters
    createCategory($array)        // INSERT new category
    updateCategory($array)        // UPDATE category
    getCategoryById($array)       // SELECT single category
    lsCategoryActive()            // SELECT active categories for dropdowns
    existsCategoryByName($array)  // Check duplicate name
    
    // Subcategory Methods
    listSubcategory($array)       // SELECT subcategories with category join
    createSubcategory($array)     // INSERT new subcategory
    updateSubcategory($array)     // UPDATE subcategory
    getSubcategoryById($array)    // SELECT single subcategory
    lsSubcategoryActive()         // SELECT active subcategories for dropdowns
    existsSubcategoryByName($array)  // Check duplicate name in category
    
    // Product Methods
    listProduct($array)           // SELECT products with subcategory join
    createProduct($array)         // INSERT new product
    updateProduct($array)         // UPDATE product
    getProductById($array)        // SELECT single product
    existsProductByName($array)   // Check duplicate name
}
```

## Data Models

### Entity Relationship Diagram

```
┌─────────────────────┐
│      category       │
├─────────────────────┤
│ id (PK)             │
│ name                │
│ translation_key     │
│ active              │
│ created_at          │
└─────────┬───────────┘
          │ 1
          │
          │ N
┌─────────┴───────────┐
│    subcategory      │
├─────────────────────┤
│ id (PK)             │
│ name                │
│ category_id (FK)    │
│ active              │
│ created_at          │
└─────────┬───────────┘
          │ 1
          │
          │ N
┌─────────┴───────────┐
│      products       │
├─────────────────────┤
│ id (PK)             │
│ name                │
│ price               │
│ description         │
│ img                 │
│ video               │
│ subcategory_id (FK) │
│ active              │
│ created_at          │
└─────────────────────┘
```

### SQL Schema

```sql
-- Database: rfwsmqex_menu

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    translation_key VARCHAR(100),
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subcategory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    img VARCHAR(255),
    video VARCHAR(255),
    subcategory_id INT NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subcategory_id) REFERENCES subcategory(id)
);
```

### Data Transfer Objects

#### Category DTO
```javascript
{
    id: number,
    name: string,
    translation_key: string,
    active: number (0|1),
    created_at: string
}
```

#### Subcategory DTO
```javascript
{
    id: number,
    name: string,
    category_id: number,
    category_name: string,  // Joined from category table
    active: number (0|1),
    created_at: string
}
```

#### Product DTO
```javascript
{
    id: number,
    name: string,
    price: number,
    description: string,
    img: string,
    video: string,
    subcategory_id: number,
    subcategory_name: string,  // Joined from subcategory table
    active: number (0|1),
    created_at: string
}
```



## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Active Filter Consistency

*For any* entity list (categories, subcategories, or products) and *for any* active filter value (0 or 1), all returned records SHALL have an active_state matching the filter value.

**Validates: Requirements 1.2, 1.3, 2.3, 2.4, 3.3, 3.4**

### Property 2: Category Filter for Subcategories

*For any* category selection in the subcategory filter, all returned subcategories SHALL have a category_id matching the selected category.

**Validates: Requirements 2.2**

### Property 3: Subcategory Filter for Products

*For any* subcategory selection in the product filter, all returned products SHALL have a subcategory_id matching the selected subcategory.

**Validates: Requirements 3.2**

### Property 4: Create Persistence Round-Trip

*For any* valid entity data (category, subcategory, or product), creating the record and then retrieving it by ID SHALL return equivalent data.

**Validates: Requirements 5.1, 5.2, 5.3**

### Property 5: Update Persistence Round-Trip

*For any* existing entity and *for any* valid update data, updating the record and then retrieving it SHALL return the updated values.

**Validates: Requirements 5.4**

### Property 6: Toggle State Inversion

*For any* entity with active_state = X, performing a toggle operation SHALL result in active_state = (1 - X).

**Validates: Requirements 1.8, 1.9, 2.9, 3.9**

### Property 7: Toggle Isolation

*For any* entity, performing a toggle operation SHALL modify only the active_state field; all other fields SHALL remain unchanged.

**Validates: Requirements 5.5**

### Property 8: Edit Form Pre-Population

*For any* entity retrieved for editing, the form data SHALL contain all fields matching the entity's current database values.

**Validates: Requirements 1.6, 2.7, 3.7**

### Property 9: Category Name Uniqueness

*For any* existing category name, attempting to create a new category with the same name SHALL be rejected with an error.

**Validates: Requirements 1.10**

### Property 10: Subcategory Name Uniqueness Within Category

*For any* existing subcategory name within a category, attempting to create a new subcategory with the same name in the same category SHALL be rejected with an error.

**Validates: Requirements 2.10**

### Property 11: Price Currency Formatting

*For any* product price value, the displayed format SHALL include exactly two decimal places and currency symbol.

**Validates: Requirements 3.10**

### Property 12: Tab Content Exclusivity

*For any* tab selection, exactly one tab content container SHALL be visible and all others SHALL be hidden.

**Validates: Requirements 4.3**

### Property 13: Parent Name Display in Child Lists

*For any* subcategory displayed, the category_name field SHALL match the name of the category with id = category_id. *For any* product displayed, the subcategory_name field SHALL match the name of the subcategory with id = subcategory_id.

**Validates: Requirements 6.1, 6.2**

### Property 14: Active-Only Parent Selectors

*For any* category selector in subcategory forms, all options SHALL have active = 1. *For any* subcategory selector in product forms, all options SHALL have active = 1.

**Validates: Requirements 6.3, 6.4**

### Property 15: Deactivated Parent Visibility

*For any* category that is deactivated, its child subcategories SHALL remain queryable and displayable in the subcategory list.

**Validates: Requirements 6.5**

## Error Handling

### Frontend Error Handling

| Error Type | Trigger | Response |
|------------|---------|----------|
| Network Error | AJAX request fails | Display SweetAlert with "Connection error. Please try again." |
| Validation Error | Required field empty | Highlight field with red border, show inline message |
| Duplicate Name | Server returns 409 | Display SweetAlert with specific duplicate message |
| Not Found | Server returns 404 | Display SweetAlert with "Record not found" |
| Server Error | Server returns 500 | Display SweetAlert with "Server error. Please contact support." |

### Backend Error Handling

| Error Type | HTTP Status | Response Format |
|------------|-------------|-----------------|
| Success | 200 | `{ status: 200, message: "Success message", data: {...} }` |
| Validation Failed | 400 | `{ status: 400, message: "Validation error description" }` |
| Not Found | 404 | `{ status: 404, message: "Record not found" }` |
| Duplicate | 409 | `{ status: 409, message: "Name already exists" }` |
| Server Error | 500 | `{ status: 500, message: "Internal server error" }` |

### Database Constraints

- Foreign key violations handled by checking parent existence before insert
- Unique constraint violations caught and returned as 409 status
- NULL constraint violations prevented by frontend validation

## Testing Strategy

### Dual Testing Approach

The testing strategy combines unit tests for specific examples and edge cases with property-based tests for universal properties.

### Unit Tests

Unit tests focus on:
- Specific UI interactions (modal display, tab switching)
- Edge cases (empty strings, zero prices, special characters)
- Error condition handling
- Integration points between frontend and backend

### Property-Based Tests

Property-based tests SHALL:
- Use a property-based testing library (fast-check for JavaScript, or PHPUnit with data providers for PHP)
- Run minimum 100 iterations per property
- Reference design document properties with tags

**Tag Format:** `Feature: menu-admin, Property {number}: {property_text}`

### Test Categories

#### Category Tests
- P1: Filter by active state returns correct records
- P4: Create category persists to database
- P5: Update category persists changes
- P6: Toggle category inverts active state
- P7: Toggle only modifies active field
- P9: Duplicate category name rejected

#### Subcategory Tests
- P1: Filter by active state returns correct records
- P2: Filter by category returns correct subcategories
- P4: Create subcategory persists with category_id
- P5: Update subcategory persists changes
- P6: Toggle subcategory inverts active state
- P10: Duplicate name within category rejected
- P13: Category name displayed correctly
- P14: Only active categories in selector

#### Product Tests
- P1: Filter by active state returns correct records
- P3: Filter by subcategory returns correct products
- P4: Create product persists with subcategory_id
- P5: Update product persists changes
- P6: Toggle product inverts active state
- P11: Price formatted correctly
- P13: Subcategory name displayed correctly
- P14: Only active subcategories in selector

#### UI Tests
- P8: Edit forms pre-populated correctly
- P12: Tab switching shows correct content
- P15: Deactivated parent children visible

### Test File Structure

```
DEV/MENU/
├── tests/
│   ├── unit/
│   │   ├── CategoryTest.php
│   │   ├── SubcategoryTest.php
│   │   └── ProductTest.php
│   └── property/
│       ├── FilterPropertyTest.php
│       ├── CrudPropertyTest.php
│       └── TogglePropertyTest.php
```
