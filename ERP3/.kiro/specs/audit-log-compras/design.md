# Design Document - Audit Log para Módulo de Compras

## Overview

Este diseño implementa un sistema de auditoría para el módulo de compras que registra automáticamente todas las operaciones CRUD (Create, Update, Delete) realizadas sobre las compras. El sistema utiliza la tabla `audit_log` existente para almacenar el historial de cambios con información detallada del usuario, la unidad de negocio y los datos modificados.

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Frontend (JS)                             │
│                     compras.js / purchases                       │
└─────────────────────────┬───────────────────────────────────────┘
                          │ AJAX Request
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Controller (PHP)                              │
│                   ctrl-compras.php                               │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐  │
│  │ addPurchase │  │editPurchase │  │    deletePurchase       │  │
│  │     +       │  │     +       │  │          +              │  │
│  │  addLog()   │  │  addLog()   │  │       addLog()          │  │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘  │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Model (PHP)                                 │
│                    mdl-compras.php                               │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │              createAuditLog($array)                      │    │
│  │              getUserInfo($user_id)                       │    │
│  │              getUdnInfo($udn_id)                         │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Database                                    │
│              rfwsmqex_gvsl_finanzas3.audit_log                   │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │ id | udn_id | user_id | record_id | name_table |        │    │
│  │ name_user | name_udn | name_collaborator | action |     │    │
│  │ change_items | creation_date                             │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### Controller: ctrl-compras.php

#### Método: addLog()
```php
function addLog($params) {
    // Parámetros esperados:
    // - action: string ('CREATE', 'UPDATE', 'DELETE')
    // - record_id: int (ID de la compra)
    // - udn_id: int (ID de la unidad de negocio)
    // - change_items: array|null (datos de cambios)
    
    // Retorna: array con status y message
}
```

#### Modificaciones a métodos existentes:

**addPurchase()** - Agregar llamada a addLog después de crear la compra
**editPurchase()** - Agregar llamada a addLog con los cambios realizados
**deletePurchase()** - Agregar llamada a addLog antes de eliminar

### Model: mdl-compras.php

#### Método: createAuditLog($array)
```php
function createAuditLog($array) {
    // Inserta registro en tabla audit_log
    // Retorna: bool (resultado de la inserción)
}
```

#### Método: getUserInfo($user_id)
```php
function getUserInfo($user_id) {
    // Obtiene información del usuario
    // Retorna: array con name_user y name_collaborator
}
```

#### Método: getUdnInfo($udn_id)
```php
function getUdnInfo($udn_id) {
    // Obtiene información de la UDN
    // Retorna: string con nombre de la UDN
}
```

## Data Models

### Tabla: audit_log

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | int | Primary key, auto-increment |
| udn_id | int | ID de la unidad de negocio |
| user_id | int | ID del usuario que realizó la acción |
| record_id | int | ID del registro afectado (purchase.id) |
| name_table | varchar(255) | Nombre de la tabla afectada ('purchase') |
| name_user | varchar(50) | Nombre del usuario |
| name_udn | varchar(50) | Nombre de la UDN |
| name_collaborator | varchar(255) | Nombre del colaborador |
| action | enum | Tipo de acción ('CREATE', 'UPDATE', 'DELETE') |
| change_items | longtext | JSON con los cambios realizados |
| creation_date | datetime | Fecha y hora de la acción |

### Estructura de change_items (JSON)

**Para CREATE:**
```json
{
    "action": "CREATE",
    "data": {
        "supplier_id": 1,
        "gl_account_id": 2,
        "subaccount_id": 3,
        "subtotal": 1000,
        "tax": 160,
        "total": 1160
    }
}
```

**Para UPDATE:**
```json
{
    "action": "UPDATE",
    "changes": [
        {
            "field": "subtotal",
            "old_value": 1000,
            "new_value": 1500
        },
        {
            "field": "tax",
            "old_value": 160,
            "new_value": 240
        }
    ]
}
```

**Para DELETE:**
```json
{
    "action": "DELETE",
    "deleted_data": {
        "id": 123,
        "supplier_id": 1,
        "gl_account_id": 2,
        "subtotal": 1000,
        "tax": 160,
        "total": 1160,
        "description": "Compra de insumos"
    }
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Audit log creation on purchase create
*For any* purchase creation operation, the system should create exactly one audit_log record with action='CREATE' and record_id matching the new purchase ID
**Validates: Requirements 1.1, 1.2**

### Property 2: Audit log metadata completeness
*For any* audit log record created, all metadata fields (udn_id, user_id, name_user, name_udn, name_collaborator, creation_date) should be non-null and valid
**Validates: Requirements 1.4, 1.5, 2.5, 3.4**

### Property 3: Change tracking on update
*For any* purchase edit operation, the change_items JSON should contain both old and new values for all modified fields
**Validates: Requirements 2.2**

### Property 4: Audit log creation on purchase delete
*For any* purchase deletion operation, the system should create exactly one audit_log record with action='DELETE' and change_items containing the complete deleted purchase data
**Validates: Requirements 3.1, 3.5**

### Property 5: Record ID consistency
*For any* audit log record, the record_id should correspond to a valid purchase ID (existing or previously existing)
**Validates: Requirements 1.2, 2.4, 3.2**

## Error Handling

1. **Fallo en obtención de información de usuario**: Si no se puede obtener la información del usuario, usar valores por defecto ('Sistema', 'N/A')
2. **Fallo en inserción de audit_log**: No debe bloquear la operación principal (compra), pero debe registrar el error en logs del servidor
3. **Datos incompletos**: Validar que los campos requeridos estén presentes antes de intentar la inserción

## Testing Strategy

### Unit Tests
- Verificar que `createAuditLog()` inserta correctamente en la base de datos
- Verificar que `getUserInfo()` retorna datos válidos
- Verificar que `getUdnInfo()` retorna el nombre correcto de la UDN

### Integration Tests
- Verificar flujo completo de addPurchase + addLog
- Verificar flujo completo de editPurchase + addLog con detección de cambios
- Verificar flujo completo de deletePurchase + addLog con datos completos

### Property-Based Tests
- Usar PHPUnit para verificar las propiedades de correctness definidas
- Generar datos aleatorios de compras y verificar que los logs se crean correctamente
