# Requirements Document

## Introduction

This document defines the requirements for the Digital Menu Admin Module, a system designed to manage categories, subcategories, and products for a digital menu. The module follows the CoffeeSoft framework architecture (PHP + jQuery + TailwindCSS) with MVC pattern and will be located at DEV/MENU/.

## Glossary

- **System**: The Digital Menu Admin Module
- **Category**: A top-level classification for menu items (e.g., Beverages, Food, Desserts)
- **Subcategory**: A secondary classification that belongs to a Category (e.g., Hot Drinks under Beverages)
- **Product**: A menu item with name, price, description, and media that belongs to a Subcategory
- **Active_State**: A boolean flag (1=active, 0=inactive) that determines visibility
- **Translation_Key**: An identifier used for internationalization of category names
- **Admin_User**: A user with permissions to manage the digital menu content

## Requirements

### Requirement 1: Category Management

**User Story:** As an Admin_User, I want to manage menu categories, so that I can organize products into logical groups.

#### Acceptance Criteria

1. WHEN the Admin_User accesses the Categories tab, THE System SHALL display a table listing all categories with their name, translation key, and active state
2. WHEN the Admin_User selects the "Active" filter, THE System SHALL display only categories where active_state equals 1
3. WHEN the Admin_User selects the "Inactive" filter, THE System SHALL display only categories where active_state equals 0
4. WHEN the Admin_User clicks "New Category", THE System SHALL display a modal form with fields for name and translation_key
5. WHEN the Admin_User submits a valid category form, THE System SHALL create the category record and refresh the table
6. WHEN the Admin_User clicks edit on a category, THE System SHALL display a modal form pre-filled with the category data
7. WHEN the Admin_User submits an edited category form, THE System SHALL update the category record and refresh the table
8. WHEN the Admin_User clicks the toggle button on an active category, THE System SHALL set active_state to 0 and refresh the table
9. WHEN the Admin_User clicks the toggle button on an inactive category, THE System SHALL set active_state to 1 and refresh the table
10. IF the Admin_User attempts to create a category with a duplicate name, THEN THE System SHALL display an error message and prevent creation

### Requirement 2: Subcategory Management

**User Story:** As an Admin_User, I want to manage subcategories within categories, so that I can create a hierarchical menu structure.

#### Acceptance Criteria

1. WHEN the Admin_User accesses the Subcategories tab, THE System SHALL display a table listing all subcategories with their name, parent category name, and active state
2. WHEN the Admin_User selects a category filter, THE System SHALL display only subcategories belonging to the selected category
3. WHEN the Admin_User selects the "Active" filter, THE System SHALL display only subcategories where active_state equals 1
4. WHEN the Admin_User selects the "Inactive" filter, THE System SHALL display only subcategories where active_state equals 0
5. WHEN the Admin_User clicks "New Subcategory", THE System SHALL display a modal form with fields for name and a category selector
6. WHEN the Admin_User submits a valid subcategory form, THE System SHALL create the subcategory record linked to the selected category and refresh the table
7. WHEN the Admin_User clicks edit on a subcategory, THE System SHALL display a modal form pre-filled with the subcategory data
8. WHEN the Admin_User submits an edited subcategory form, THE System SHALL update the subcategory record and refresh the table
9. WHEN the Admin_User clicks the toggle button on a subcategory, THE System SHALL toggle the active_state and refresh the table
10. IF the Admin_User attempts to create a subcategory with a duplicate name within the same category, THEN THE System SHALL display an error message and prevent creation

### Requirement 3: Product Management

**User Story:** As an Admin_User, I want to manage products within subcategories, so that I can maintain the digital menu catalog.

#### Acceptance Criteria

1. WHEN the Admin_User accesses the Products tab, THE System SHALL display a table listing all products with their name, price, subcategory name, and active state
2. WHEN the Admin_User selects a subcategory filter, THE System SHALL display only products belonging to the selected subcategory
3. WHEN the Admin_User selects the "Active" filter, THE System SHALL display only products where active_state equals 1
4. WHEN the Admin_User selects the "Inactive" filter, THE System SHALL display only products where active_state equals 0
5. WHEN the Admin_User clicks "New Product", THE System SHALL display a modal form with fields for name, price, description, subcategory selector, image URL, and video URL
6. WHEN the Admin_User submits a valid product form, THE System SHALL create the product record linked to the selected subcategory and refresh the table
7. WHEN the Admin_User clicks edit on a product, THE System SHALL display a modal form pre-filled with the product data
8. WHEN the Admin_User submits an edited product form, THE System SHALL update the product record and refresh the table
9. WHEN the Admin_User clicks the toggle button on a product, THE System SHALL toggle the active_state and refresh the table
10. THE System SHALL display product prices formatted as currency with two decimal places
11. IF the Admin_User submits a product with price less than or equal to zero, THEN THE System SHALL display a validation error and prevent submission

### Requirement 4: User Interface Structure

**User Story:** As an Admin_User, I want a clean tabbed interface, so that I can easily navigate between categories, subcategories, and products.

#### Acceptance Criteria

1. WHEN the Admin_User loads the module, THE System SHALL display a dashboard with three tabs: Categories, Subcategories, and Products
2. THE System SHALL display the Categories tab as active by default on initial load
3. WHEN the Admin_User clicks a tab, THE System SHALL display the corresponding content and hide other tab contents
4. THE System SHALL display a header with the title "🍽️ Menu Admin" and subtitle "Manage categories, subcategories, and products"
5. WHILE any tab is active, THE System SHALL display a filter bar with status filter and action buttons appropriate to that tab

### Requirement 5: Data Persistence

**User Story:** As an Admin_User, I want all changes to persist in the database, so that the digital menu reflects my updates.

#### Acceptance Criteria

1. WHEN a category is created, THE System SHALL insert a record into the category table with name, translation_key, and active_state
2. WHEN a subcategory is created, THE System SHALL insert a record into the subcategory table with name, category_id, and active_state
3. WHEN a product is created, THE System SHALL insert a record into the products table with name, price, description, img, video, subcategory_id, and active_state
4. WHEN any record is updated, THE System SHALL persist the changes to the corresponding database table
5. WHEN a status toggle is performed, THE System SHALL update only the active_state field of the corresponding record

### Requirement 6: Cascading Relationships

**User Story:** As an Admin_User, I want the system to maintain data integrity, so that relationships between entities remain consistent.

#### Acceptance Criteria

1. WHEN displaying subcategories, THE System SHALL show the parent category name for each subcategory
2. WHEN displaying products, THE System SHALL show the parent subcategory name for each product
3. WHEN the category selector is displayed in subcategory forms, THE System SHALL only show active categories
4. WHEN the subcategory selector is displayed in product forms, THE System SHALL only show active subcategories
5. IF a category is deactivated, THE System SHALL continue to display its subcategories but mark them as belonging to an inactive category
