# Requirements Document

## Introduction

Sistema de sincronización de categorías de productos Fogaza en el módulo de ventas del ERP. Esta funcionalidad permite capturar y sincronizar ventas de productos de panadería Fogaza con el sistema de folios de soft_restaurant.

## Glossary

- **System**: Módulo de ventas KPI del ERP Varoch
- **Fogaza**: Unidad de negocio especializada en panadería y repostería
- **Folio**: Registro diario de ventas en soft_restaurant
- **Category**: Clasificación de productos de venta (Abarrotes, Bizcocho, Bocadillos, etc.)
- **Sync**: Proceso de transferencia de datos de ventas capturadas al sistema de folios

## Requirements

### Requirement 1: Captura de Ventas por Categoría Fogaza

**User Story:** Como usuario del sistema de ventas, quiero capturar ventas de productos Fogaza por categoría, para que el sistema registre correctamente los ingresos de la panadería.

#### Acceptance Criteria

1. THE System SHALL support the following Fogaza product categories:
   - Abarrotes
   - Bizcocho
   - Bocadillos
   - Francés (with accent variation: Frances)
   - Pastelería Normal (with accent variation: Pasteleria Normal)
   - Pastelería Premium (with accent variation: Pasteleria Premium)
   - Refrescos
   - Velas

2. WHEN a user captures a sale, THE System SHALL accept category names with or without Spanish accents

3. WHEN a sale is captured, THE System SHALL calculate taxes automatically:
   - 8% IVA for all categories
   - No IEPS for Fogaza products

4. THE System SHALL store the sale amount including calculated taxes

### Requirement 2: Sincronización Individual de Ventas

**User Story:** Como usuario del sistema, quiero sincronizar las ventas de un día específico al folio de soft_restaurant, para mantener actualizado el registro contable.

#### Acceptance Criteria

1. WHEN a user triggers sync for a specific date, THE System SHALL retrieve all sales for that date

2. WHEN processing sales, THE System SHALL accumulate amounts by category:
   - Each Fogaza category SHALL be accumulated separately
   - Amounts SHALL include calculated taxes (base + 8% IVA)

3. IF a folio does not exist for the date, THE System SHALL create a new folio automatically

4. WHEN a folio exists, THE System SHALL update the existing venta record

5. WHEN a folio does not have a venta record, THE System SHALL create a new venta record

6. THE System SHALL handle category name variations (with/without accents) correctly

### Requirement 3: Sincronización Mensual de Ventas

**User Story:** Como administrador del sistema, quiero sincronizar todas las ventas de un mes completo, para actualizar masivamente los registros de soft_restaurant.

#### Acceptance Criteria

1. WHEN a user triggers monthly sync, THE System SHALL process all unique dates in the selected month

2. FOR EACH date in the month, THE System SHALL:
   - Retrieve all sales for that date
   - Accumulate amounts by category including taxes
   - Create or update folio and venta records

3. THE System SHALL track sync results:
   - Count of successful syncs
   - Count of failed syncs
   - Detailed status per date

4. WHEN sync completes, THE System SHALL return a summary report with:
   - Total successful operations
   - Total failed operations
   - Detailed results per date

5. IF no sales exist for the selected month, THE System SHALL return status 404 with appropriate message

### Requirement 4: Cálculo de Impuestos

**User Story:** Como sistema contable, necesito calcular correctamente los impuestos de cada venta, para cumplir con las regulaciones fiscales.

#### Acceptance Criteria

1. THE System SHALL calculate IVA at 8% for all Fogaza categories

2. THE System SHALL NOT apply IEPS to Fogaza products

3. WHEN calculating total amount, THE System SHALL use formula:
   - Total = Base Amount + (Base Amount × 0.08)

4. THE System SHALL store amounts with taxes included in the folio system

### Requirement 5: Manejo de Variaciones de Nombres

**User Story:** Como usuario del sistema, quiero que el sistema reconozca nombres de categorías con o sin acentos, para evitar errores de captura.

#### Acceptance Criteria

1. THE System SHALL recognize "Francés" and "Frances" as the same category

2. THE System SHALL recognize "Pastelería Normal" and "Pasteleria Normal" as the same category

3. THE System SHALL recognize "Pastelería Premium" and "Pasteleria Premium" as the same category

4. WHEN processing categories, THE System SHALL convert to lowercase for comparison

5. THE System SHALL trim whitespace from category names before processing

### Requirement 6: Integración con Sistema de Folios

**User Story:** Como sistema de contabilidad, necesito que las ventas sincronizadas se registren correctamente en soft_restaurant_ventas, para mantener la integridad de los datos contables.

#### Acceptance Criteria

1. WHEN syncing to folio, THE System SHALL use the mdlVentas model for database operations

2. THE System SHALL check if a folio exists before creating a new one

3. WHEN creating a folio, THE System SHALL initialize with:
   - fecha_folio: Date of sales
   - id_udn: Business unit ID
   - file_productos_vendidos: 0
   - file_ventas_dia: 0
   - monto_productos_vendidos: 0
   - monto_ventas_dia: 0

4. WHEN updating venta records, THE System SHALL preserve existing id_venta

5. THE System SHALL return folio_id and id_venta in the response

### Requirement 7: Respuesta del Sistema

**User Story:** Como usuario del sistema, quiero recibir retroalimentación clara sobre el resultado de la sincronización, para saber si la operación fue exitosa.

#### Acceptance Criteria

1. WHEN sync is successful, THE System SHALL return status 200

2. WHEN sync fails, THE System SHALL return status 500

3. WHEN no sales are found, THE System SHALL return status 404

4. THE System SHALL include a descriptive message in all responses

5. FOR individual sync, THE System SHALL return:
   - status
   - message
   - folio_id
   - Category amounts breakdown
   - total and subtotal

6. FOR monthly sync, THE System SHALL return:
   - status
   - message
   - exitosos count
   - fallidos count
   - resultados array with per-date details
