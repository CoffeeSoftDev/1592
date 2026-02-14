# Requirements Document

## Introduction

Este documento define los requisitos para implementar un sistema de auditoría (Audit Log) en el módulo de compras del sistema de contabilidad. El sistema registrará todas las acciones de creación, edición y eliminación de compras, permitiendo trazabilidad completa de los movimientos realizados por los usuarios.

## Glossary

- **Audit_Log**: Tabla de base de datos que almacena el registro histórico de todas las acciones realizadas sobre las compras
- **Purchase**: Registro de compra en el sistema de contabilidad
- **UDN**: Unidad de Negocio (sucursal o punto de venta)
- **Action**: Tipo de operación realizada (CREATE, UPDATE, DELETE)
- **Change_Items**: Registro JSON de los campos modificados en una operación de edición
- **Record_ID**: Identificador del registro de compra afectado por la acción

## Requirements

### Requirement 1

**User Story:** Como administrador del sistema, quiero que se registre automáticamente cada vez que se crea una compra, para tener un historial completo de las compras ingresadas al sistema.

#### Acceptance Criteria

1. WHEN a user creates a new purchase THEN the system SHALL insert a record in audit_log with action = 'CREATE'
2. WHEN a purchase is created THEN the system SHALL store the record_id corresponding to the new purchase ID
3. WHEN a purchase is created THEN the system SHALL capture the user_id from the session cookie
4. WHEN a purchase is created THEN the system SHALL store the udn_id, name_user, name_udn and name_collaborator
5. WHEN a purchase is created THEN the system SHALL store the creation_date with the current timestamp

### Requirement 2

**User Story:** Como administrador del sistema, quiero que se registre cada modificación realizada a una compra, incluyendo los campos que fueron cambiados, para poder auditar los cambios históricos.

#### Acceptance Criteria

1. WHEN a user edits a purchase THEN the system SHALL insert a record in audit_log with action = 'UPDATE'
2. WHEN a purchase is edited THEN the system SHALL store in change_items a JSON with the previous and new values of modified fields
3. WHEN a purchase is edited THEN the system SHALL capture the user_id from the session cookie
4. WHEN a purchase is edited THEN the system SHALL store the record_id of the edited purchase
5. WHEN a purchase is edited THEN the system SHALL store the udn_id, name_user, name_udn and name_collaborator

### Requirement 3

**User Story:** Como administrador del sistema, quiero que se registre cada eliminación de compra, para mantener un registro de qué compras fueron eliminadas y por quién.

#### Acceptance Criteria

1. WHEN a user deletes a purchase THEN the system SHALL insert a record in audit_log with action = 'DELETE'
2. WHEN a purchase is deleted THEN the system SHALL store the record_id of the deleted purchase
3. WHEN a purchase is deleted THEN the system SHALL capture the user_id from the session cookie
4. WHEN a purchase is deleted THEN the system SHALL store the udn_id, name_user, name_udn and name_collaborator
5. WHEN a purchase is deleted THEN the system SHALL store in change_items the complete data of the deleted purchase

### Requirement 4

**User Story:** Como desarrollador, quiero un método centralizado para crear registros de auditoría, para mantener consistencia en el formato de los logs.

#### Acceptance Criteria

1. WHEN the addLog method is called THEN the system SHALL validate that all required fields are present
2. WHEN the addLog method is called THEN the system SHALL insert a record in the audit_log table with all provided data
3. WHEN the addLog method is called THEN the system SHALL use the name_table value as 'purchase' for purchase operations
4. WHEN the addLog method is called THEN the system SHALL return the result of the insert operation
