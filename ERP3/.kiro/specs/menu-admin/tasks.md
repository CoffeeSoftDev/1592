# Implementation Plan: Digital Menu Admin Module

## Overview

This implementation plan converts the design into discrete coding tasks for the Digital Menu Admin Module. The module follows CoffeeSoft framework patterns (PHP + jQuery + TailwindCSS) with MVC architecture. Tasks are organized to build incrementally, starting with database schema, then model, controller, and finally frontend.

## Tasks

- [x] 1. Set up project structure and database schema
  - [x] 1.1 Create directory structure for DEV/MENU/
    - Create folders: ctrl/, mdl/, js/
    - Create index.php with root container and script includes
    - _Requirements: 4.1, 4.4_
  
  - [x] 1.2 Create SQL schema and seed data
    - Create category table with id, name, translation_key, active, created_at
    - Create subcategory table with id, name, category_id (FK), active, created_at
    - Create products table with id, name, price, description, img, video, subcategory_id (FK), active, created_at
    - Add sample seed data for testing
    - _Requirements: 5.1, 5.2, 5.3_

- [x] 2. Implement Model Layer (mdl-menu.php)
  - [x] 2.1 Create base model structure
    - Extend CRUD class
    - Set database prefix $bd = "rfwsmqex_menu."
    - Initialize $util property
    - _Requirements: 5.1, 5.2, 5.3_
  
  - [x] 2.2 Implement Category model methods
    - listCategory($array) - SELECT with active filter
    - createCategory($array) - INSERT new category
    - updateCategory($array) - UPDATE category
    - getCategoryById($array) - SELECT single category
    - lsCategoryActive() - SELECT active categories for dropdowns
    - existsCategoryByName($array) - Check duplicate name
    - _Requirements: 1.1, 1.5, 1.7, 1.10, 6.3_
  
  - [x] 2.3 Implement Subcategory model methods
    - listSubcategory($array) - SELECT with category JOIN and filters
    - createSubcategory($array) - INSERT new subcategory
    - updateSubcategory($array) - UPDATE subcategory
    - getSubcategoryById($array) - SELECT single subcategory
    - lsSubcategoryActive() - SELECT active subcategories for dropdowns
    - existsSubcategoryByName($array) - Check duplicate name in category
    - _Requirements: 2.1, 2.6, 2.8, 2.10, 6.1, 6.4_
  
  - [x] 2.4 Implement Product model methods
    - listProduct($array) - SELECT with subcategory JOIN and filters
    - createProduct($array) - INSERT new product
    - updateProduct($array) - UPDATE product
    - getProductById($array) - SELECT single product
    - _Requirements: 3.1, 3.6, 3.8, 6.2_

- [x] 3. Implement Controller Layer (ctrl-menu.php)
  - [x] 3.1 Create base controller structure
    - Extend mdl class
    - Implement init() returning categories and subcategories for filters
    - Add helper functions: renderStatus(), actionButtons()
    - _Requirements: 4.5, 6.3, 6.4_
  
  - [x] 3.2 Implement Category controller methods
    - lsCategory() - Build table rows with action buttons
    - addCategory() - Validate and create category
    - editCategory() - Update category
    - getCategory() - Return single category data
    - statusCategory() - Toggle active state
    - _Requirements: 1.1, 1.2, 1.3, 1.5, 1.6, 1.7, 1.8, 1.9, 1.10_
  
  - [ ]* 3.3 Write property test for Category filter
    - **Property 1: Active Filter Consistency**
    - **Validates: Requirements 1.2, 1.3**
  
  - [x] 3.4 Implement Subcategory controller methods
    - lsSubcategory() - Build table rows with category name and action buttons
    - addSubcategory() - Validate and create subcategory
    - editSubcategory() - Update subcategory
    - getSubcategory() - Return single subcategory data
    - statusSubcategory() - Toggle active state
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.6, 2.7, 2.8, 2.9, 2.10_
  
  - [ ]* 3.5 Write property test for Subcategory filter
    - **Property 2: Category Filter for Subcategories**
    - **Validates: Requirements 2.2**
  
  - [x] 3.6 Implement Product controller methods
    - lsProduct() - Build table rows with subcategory name, formatted price, and action buttons
    - addProduct() - Validate price > 0 and create product
    - editProduct() - Update product
    - getProduct() - Return single product data
    - statusProduct() - Toggle active state
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.6, 3.7, 3.8, 3.9, 3.10, 3.11_
  
  - [ ]* 3.7 Write property test for Product filter and price formatting
    - **Property 3: Subcategory Filter for Products**
    - **Property 11: Price Currency Formatting**
    - **Validates: Requirements 3.2, 3.10**

- [x] 4. Checkpoint - Backend validation
  - Ensure all model and controller methods work correctly
  - Test CRUD operations via direct API calls
  - Verify filter logic returns correct results
  - Ask the user if questions arise

- [x] 5. Implement Frontend - App Class (menu.js)
  - [x] 5.1 Create base App class structure
    - Extend Templates class
    - Set PROJECT_NAME = "MenuAdmin"
    - Implement render(), layout(), layoutTabs() methods
    - Create tabLayout with Categories, Subcategories, Products tabs
    - Add header with title and subtitle
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_
  
  - [x] 5.2 Implement Category frontend methods
    - filterBarCategory() - Status filter and "New Category" button
    - lsCategory() - createTable with theme 'light'
    - addCategory() - createModalForm with name, translation_key fields
    - editCategory(id) - Fetch data and show pre-filled modal
    - statusCategory(id, active) - swalQuestion confirmation and toggle
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9_
  
  - [ ]* 5.3 Write property test for Category toggle
    - **Property 6: Toggle State Inversion**
    - **Property 7: Toggle Isolation**
    - **Validates: Requirements 1.8, 1.9, 5.5**

- [x] 6. Implement Frontend - Subcategory Class (menu.js)
  - [x] 6.1 Create Subcategory class structure
    - Extend Templates class
    - Set PROJECT_NAME = "Subcategory"
    - _Requirements: 2.1_
  
  - [x] 6.2 Implement Subcategory frontend methods
    - filterBarSubcategory() - Category filter, status filter, "New Subcategory" button
    - lsSubcategory() - createTable showing category name column
    - addSubcategory() - createModalForm with name, category_id selector (active only)
    - editSubcategory(id) - Fetch data and show pre-filled modal
    - statusSubcategory(id, active) - swalQuestion confirmation and toggle
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9, 6.1, 6.3_
  
  - [ ]* 6.3 Write property test for Subcategory parent selector
    - **Property 14: Active-Only Parent Selectors**
    - **Validates: Requirements 6.3**

- [x] 7. Implement Frontend - Product Class (menu.js)
  - [x] 7.1 Create Product class structure
    - Extend Templates class
    - Set PROJECT_NAME = "Product"
    - _Requirements: 3.1_
  
  - [x] 7.2 Implement Product frontend methods
    - filterBarProduct() - Subcategory filter, status filter, "New Product" button
    - lsProduct() - createTable showing subcategory name and formatted price
    - addProduct() - createModalForm with name, price, description, subcategory_id, img, video fields
    - editProduct(id) - Fetch data and show pre-filled modal
    - statusProduct(id, active) - swalQuestion confirmation and toggle
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 3.9, 3.10, 6.2, 6.4_
  
  - [ ]* 7.3 Write property test for Product parent selector
    - **Property 14: Active-Only Parent Selectors**
    - **Validates: Requirements 6.4**

- [x] 8. Checkpoint - Frontend validation
  - Ensure all tabs render correctly
  - Test CRUD operations through UI
  - Verify filters work as expected
  - Test modal forms and validation
  - Ask the user if questions arise

- [x] 9. Integration and validation
  - [x] 9.1 Implement duplicate name validation
    - Category: Check existsCategoryByName before create
    - Subcategory: Check existsSubcategoryByName with category_id scope
    - Display appropriate error messages
    - _Requirements: 1.10, 2.10_
  
  - [ ]* 9.2 Write property test for duplicate validation
    - **Property 9: Category Name Uniqueness**
    - **Property 10: Subcategory Name Uniqueness Within Category**
    - **Validates: Requirements 1.10, 2.10**
  
  - [x] 9.3 Implement cascading relationship display
    - Verify subcategories show category name
    - Verify products show subcategory name
    - Verify deactivated parent children remain visible
    - _Requirements: 6.1, 6.2, 6.5_
  
  - [ ]* 9.4 Write property test for relationship display
    - **Property 13: Parent Name Display in Child Lists**
    - **Property 15: Deactivated Parent Visibility**
    - **Validates: Requirements 6.1, 6.2, 6.5**

- [x] 10. Final checkpoint - Complete system validation
  - Run all property tests
  - Verify all CRUD operations work end-to-end
  - Test all filter combinations
  - Validate error handling and user feedback
  - Ensure all tests pass, ask the user if questions arise

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties
- Unit tests validate specific examples and edge cases
- Follow CoffeeSoft framework patterns from PIVOTE ADMIN.md
