# Requirements Document

## Introduction

Este documento define los requisitos para expandir el schema de base de datos del módulo de menú digital existente hacia un sistema POS-VENTAS completo. La expansión mantendrá compatibilidad con las tablas existentes (category, subcategory, products) mientras agrega las entidades necesarias para gestionar ventas, clientes, entregas y funcionalidades adicionales del punto de venta.

## Glossary

- **Schema_Expansion**: Conjunto de nuevas tablas SQL que extienden la funcionalidad del menú digital hacia un sistema POS
- **Sale**: Entidad central que representa una transacción de venta
- **Order_Products**: Detalle de productos incluidos en una venta
- **Client**: Entidad que almacena información de clientes del restaurante
- **Tipo_Venta**: Clasificación del tipo de venta (Para llevar, En sitio, Delivery)
- **Delivery**: Información de entregas a domicilio
- **Price_List**: Historial y gestión de precios por producto
- **Allergen**: Información de alérgenos para productos
- **Product_Allergens**: Relación muchos a muchos entre productos y alérgenos

## Requirements

### Requirement 1: Gestión de Clientes

**User Story:** As a cajero, I want to registrar y consultar clientes, so that I can asociar ventas a clientes y gestionar programas de fidelidad.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL include a client table with fields: id, name, phone, email, address, birthday, points, active, created_at
2. WHEN a client is created, THE Schema_Expansion SHALL set default values for points (0) and active (1)
3. THE Schema_Expansion SHALL include indexes on client phone and email for fast lookups
4. THE Schema_Expansion SHALL allow nullable fields for email, address, and birthday

### Requirement 2: Tipos de Venta

**User Story:** As a administrador, I want to clasificar las ventas por tipo, so that I can generar reportes diferenciados y aplicar lógica de negocio específica.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL include a tipo_venta table with fields: id, name, active
2. THE Schema_Expansion SHALL include seed data for tipos: Para llevar, En sitio, Delivery
3. WHEN a tipo_venta is referenced, THE Schema_Expansion SHALL enforce referential integrity with ON DELETE RESTRICT

### Requirement 3: Registro de Ventas

**User Story:** As a cajero, I want to registrar ventas completas, so that I can procesar transacciones y generar tickets.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL include a sale table with fields: id, date, time, total, subtotal, tax, discount, status, client_id, tipo_venta_id, delivery_id, payment_method, notes, created_at
2. THE Schema_Expansion SHALL enforce foreign key relationships to client (nullable), tipo_venta (required), and delivery (nullable)
3. THE Schema_Expansion SHALL include indexes on date, status, and client_id for reporting queries
4. WHEN a sale is created, THE Schema_Expansion SHALL set default status to 'pending'
5. THE Schema_Expansion SHALL support payment_method values: efectivo, tarjeta, transferencia, mixto

### Requirement 4: Detalle de Productos en Venta

**User Story:** As a cajero, I want to agregar múltiples productos a una venta, so that I can registrar todos los items consumidos.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL include an order_products table with fields: id, sale_id, product_id, quantity, unit_price, subtotal, notes, status
2. THE Schema_Expansion SHALL enforce foreign key relationships to sale and products tables
3. THE Schema_Expansion SHALL include composite index on (sale_id, product_id) for efficient queries
4. WHEN an order_product is created, THE Schema_Expansion SHALL calculate subtotal as quantity * unit_price
5. THE Schema_Expansion SHALL support status values: pending, preparing, ready, delivered, cancelled

### Requirement 5: Gestión de Entregas

**User Story:** As a repartidor, I want to consultar información de entregas, so that I can realizar las entregas correctamente.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL include a delivery table with fields: id, sale_id, address, phone, delivery_person, status, delivery_time, notes, created_at
2. THE Schema_Expansion SHALL enforce foreign key relationship to sale table
3. THE Schema_Expansion SHALL support status values: pending, assigned, in_transit, delivered, cancelled
4. THE Schema_Expansion SHALL include index on status for filtering active deliveries

### Requirement 6: Historial de Precios

**User Story:** As a administrador, I want to gestionar precios por producto con fechas de vigencia, so that I can programar cambios de precios y mantener historial.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL include a price_list table with fields: id, product_id, price, start_date, end_date, active, created_at
2. THE Schema_Expansion SHALL enforce foreign key relationship to products table
3. THE Schema_Expansion SHALL include composite index on (product_id, start_date, end_date) for price lookups
4. THE Schema_Expansion SHALL allow nullable end_date for indefinite pricing

### Requirement 7: Gestión de Alérgenos

**User Story:** As a cliente, I want to ver información de alérgenos en productos, so that I can tomar decisiones informadas sobre mi consumo.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL include an allergens table with fields: id, name, icon, description, active
2. THE Schema_Expansion SHALL include a product_allergens junction table with fields: id, product_id, allergen_id
3. THE Schema_Expansion SHALL enforce foreign key relationships to products and allergens tables
4. THE Schema_Expansion SHALL include seed data for alérgenos comunes: Gluten, Lácteos, Mariscos, Nueces, Huevo, Soya, Pescado, Cacahuate
5. THE Schema_Expansion SHALL include unique constraint on (product_id, allergen_id) to prevent duplicates

### Requirement 8: Integridad y Compatibilidad

**User Story:** As a desarrollador, I want to mantener compatibilidad con el schema existente, so that I can integrar el POS sin afectar el menú digital.

#### Acceptance Criteria

1. THE Schema_Expansion SHALL NOT modify existing tables (category, subcategory, products)
2. THE Schema_Expansion SHALL use InnoDB engine and utf8mb4 charset for all new tables
3. THE Schema_Expansion SHALL use consistent naming conventions in English with snake_case
4. THE Schema_Expansion SHALL include appropriate indexes for all foreign keys
5. IF a referenced record is deleted, THEN THE Schema_Expansion SHALL prevent deletion with ON DELETE RESTRICT for critical relationships
