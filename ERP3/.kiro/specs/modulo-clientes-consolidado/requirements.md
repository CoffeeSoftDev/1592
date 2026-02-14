# Requirements Document - Módulo Clientes Consolidado

## Introduction

Este documento define los requisitos para mejorar la vista consolidada del módulo de clientes, adaptando la lógica actual para mostrar un formato más completo con tarjetas de resumen, filas especiales de control (saldo inicial, saldo final) y una estructura de tabla mejorada con columnas alternadas de consumos y pagos por fecha.

## Glossary

- **Sistema**: Módulo de clientes del sistema ERP CoffeeSoft
- **Cliente**: Entidad que realiza consumos a crédito y pagos
- **Consumo**: Cargo a crédito realizado por un cliente
- **Pago**: Abono realizado por un cliente para reducir su deuda
- **Saldo_Inicial**: Deuda acumulada del cliente al inicio del período
- **Saldo_Final**: Deuda acumulada del cliente al final del período
- **Período**: Rango de fechas (fi, ff) para el reporte consolidado
- **UDN**: Unidad de Negocio

## Requirements

### Requirement 1: Tarjetas de Resumen Financiero

**User Story:** Como usuario del sistema, quiero ver tarjetas de resumen con totales financieros, para tener una vista rápida de los indicadores clave del período.

#### Acceptance Criteria

1. THE Sistema SHALL mostrar cuatro tarjetas en la parte superior del reporte
2. WHEN se carga el reporte consolidado, THE Sistema SHALL calcular y mostrar el saldo inicial total
3. WHEN se carga el reporte consolidado, THE Sistema SHALL calcular y mostrar el total de consumos a crédito
4. WHEN se carga el reporte consolidado, THE Sistema SHALL calcular y mostrar el total de pagos y anticipos
5. WHEN se carga el reporte consolidado, THE Sistema SHALL calcular y mostrar el saldo final total
6. THE Sistema SHALL aplicar formato de moneda a todos los valores de las tarjetas

### Requirement 2: Estructura de Tabla con Columnas Alternadas

**User Story:** Como usuario del sistema, quiero ver una tabla con columnas alternadas de consumos y pagos por fecha, para analizar fácilmente los movimientos diarios de cada cliente.

#### Acceptance Criteria

1. THE Sistema SHALL generar columnas dinámicas basadas en el rango de fechas seleccionado
2. WHEN se genera una columna de fecha, THE Sistema SHALL crear dos subcabeceras: CONSUMOS y PAGOS
3. THE Sistema SHALL formatear las fechas en el encabezado con el patrón "DÍA DD/MES"
4. THE Sistema SHALL aplicar color rojo a las columnas de CONSUMOS
5. THE Sistema SHALL aplicar color verde a las columnas de PAGOS
6. THE Sistema SHALL alinear los valores numéricos a la derecha
7. THE Sistema SHALL mostrar "-" cuando el valor sea cero o nulo

### Requirement 3: Filas Especiales de Control

**User Story:** Como usuario del sistema, quiero ver filas especiales de control (saldo inicial, consumo a crédito, pagos y anticipos, saldo final), para entender el flujo financiero del período.

#### Acceptance Criteria

1. THE Sistema SHALL insertar una fila "Saldo inicial" al inicio de la tabla
2. THE Sistema SHALL calcular el saldo inicial como la deuda acumulada antes del período
3. THE Sistema SHALL insertar una fila "Consumo a crédito" después del saldo inicial
4. THE Sistema SHALL sumar todos los consumos del período en la fila "Consumo a crédito"
5. THE Sistema SHALL insertar una fila "Pagos y anticipos" después de consumo a crédito
6. THE Sistema SHALL sumar todos los pagos del período en la fila "Pagos y anticipos"
7. THE Sistema SHALL insertar una fila "Saldo final" después de pagos y anticipos
8. THE Sistema SHALL calcular el saldo final como: saldo_inicial + consumos - pagos
9. THE Sistema SHALL aplicar formato negativo (rojo) a los valores de pagos
10. THE Sistema SHALL aplicar estilo especial (negrita) a las filas de control

### Requirement 4: Filas de Clientes Individuales

**User Story:** Como usuario del sistema, quiero ver el detalle de cada cliente con sus movimientos por fecha, para analizar el comportamiento individual de cada cliente.

#### Acceptance Criteria

1. WHEN un cliente tiene movimientos en el período, THE Sistema SHALL crear una fila para ese cliente
2. THE Sistema SHALL mostrar el nombre del cliente en la primera columna
3. THE Sistema SHALL calcular y mostrar la deuda actual del cliente
4. WHEN un cliente tiene consumos en una fecha específica, THE Sistema SHALL mostrar el monto en la columna correspondiente
5. WHEN un cliente tiene pagos en una fecha específica, THE Sistema SHALL mostrar el monto en la columna correspondiente
6. THE Sistema SHALL aplicar color rojo a los valores de consumos
7. THE Sistema SHALL aplicar color verde a los valores de pagos
8. THE Sistema SHALL permitir expandir/colapsar el detalle del cliente

### Requirement 5: Filas de Totales Generales

**User Story:** Como usuario del sistema, quiero ver filas de totales que sumen los consumos y pagos de todos los clientes, para tener una vista consolidada del período.

#### Acceptance Criteria

1. THE Sistema SHALL insertar una fila "Total de consumos a crédito" después de todos los clientes
2. THE Sistema SHALL sumar todos los consumos por fecha en esta fila
3. THE Sistema SHALL insertar una fila "Total de pagos y anticipos" después de total de consumos
4. THE Sistema SHALL sumar todos los pagos por fecha en esta fila
5. THE Sistema SHALL aplicar estilo especial (negrita, fondo gris) a las filas de totales
6. THE Sistema SHALL mostrar el total general de consumos
7. THE Sistema SHALL mostrar el total general de pagos

### Requirement 6: Cálculo de Deuda por Cliente

**User Story:** Como usuario del sistema, quiero ver la deuda actualizada de cada cliente, para conocer el saldo pendiente de pago.

#### Acceptance Criteria

1. THE Sistema SHALL calcular la deuda como: saldo_inicial + consumos_período - pagos_período
2. WHEN la deuda es positiva, THE Sistema SHALL mostrar el valor en rojo
3. WHEN la deuda es cero o negativa, THE Sistema SHALL mostrar el valor en verde
4. THE Sistema SHALL aplicar formato de moneda a los valores de deuda
5. THE Sistema SHALL mostrar la deuda en la columna "DEUDA" de cada cliente

### Requirement 7: Filtros y Período de Consulta

**User Story:** Como usuario del sistema, quiero filtrar el reporte por UDN y rango de fechas, para analizar períodos específicos.

#### Acceptance Criteria

1. THE Sistema SHALL permitir seleccionar una UDN específica
2. THE Sistema SHALL permitir seleccionar un rango de fechas (fi, ff)
3. WHEN se cambia la UDN, THE Sistema SHALL recargar el reporte con los datos filtrados
4. WHEN se cambia el rango de fechas, THE Sistema SHALL recargar el reporte con los datos filtrados
5. THE Sistema SHALL mostrar el período seleccionado en la interfaz

### Requirement 8: Formato y Presentación Visual

**User Story:** Como usuario del sistema, quiero ver un reporte con formato profesional y colores distintivos, para facilitar la lectura y análisis de la información.

#### Acceptance Criteria

1. THE Sistema SHALL aplicar fondo verde claro a las columnas de consumos
2. THE Sistema SHALL aplicar fondo naranja claro a las columnas de pagos
3. THE Sistema SHALL aplicar fondo gris a las filas de totales
4. THE Sistema SHALL usar fuente negrita para valores destacados
5. THE Sistema SHALL alinear correctamente los valores numéricos
6. THE Sistema SHALL aplicar bordes y separadores visuales entre secciones
7. THE Sistema SHALL mantener el diseño responsive para diferentes tamaños de pantalla

### Requirement 9: Integración con Backend

**User Story:** Como desarrollador, quiero que el frontend consuma correctamente los datos del backend, para asegurar la integridad de la información mostrada.

#### Acceptance Criteria

1. THE Sistema SHALL consultar el método `lsConsolidated()` del controlador
2. THE Sistema SHALL enviar los parámetros: fi, ff, udn
3. THE Sistema SHALL recibir la estructura de datos con thead y row
4. THE Sistema SHALL procesar correctamente las columnas dinámicas por fecha
5. THE Sistema SHALL manejar correctamente los valores nulos o vacíos
6. THE Sistema SHALL aplicar los estilos CSS según el tipo de celda

### Requirement 10: Exportación de Datos

**User Story:** Como usuario del sistema, quiero exportar el reporte consolidado a Excel, para realizar análisis adicionales fuera del sistema.

#### Acceptance Criteria

1. THE Sistema SHALL proporcionar un botón "Exportar a Excel"
2. WHEN se presiona el botón, THE Sistema SHALL generar un archivo Excel con los datos del reporte
3. THE Sistema SHALL mantener el formato de colores en el archivo exportado
4. THE Sistema SHALL incluir las tarjetas de resumen en la exportación
5. THE Sistema SHALL nombrar el archivo con el patrón: "Consolidado-Clientes-[fecha].xlsx"
