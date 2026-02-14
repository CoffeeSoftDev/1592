# Implementation Plan: Módulo Clientes Consolidado

## Overview

Este plan de implementación detalla las tareas necesarias para mejorar la vista consolidada del módulo de clientes, agregando tarjetas de resumen financiero, filas especiales de control (saldo inicial, saldo final) y una estructura de tabla mejorada con columnas alternadas de consumos y pagos por fecha.

## Tasks

- [x] 1. Implementar métodos del modelo para cálculos de saldo
  - [x] 1.1 Agregar método `getCustomerInitialBalance()` al modelo
    - Calcular deuda individual del cliente antes del período
    - Usar `_Read()` con query que sume consumos menos pagos antes de `fi`
    - _Requirements: 3.2, 6.1_

  - [x] 1.2 Agregar método `getTotalInitialBalance()` al modelo
    - Calcular deuda total de todos los clientes antes del período
    - Retornar float con el saldo inicial total
    - _Requirements: 1.2, 3.2_

  - [x] 1.3 Agregar método `getPeriodTotals()` al modelo
    - Calcular total de consumos y pagos dentro del período
    - Retornar array con `total_consumos` y `total_pagos`
    - _Requirements: 1.3, 1.4, 5.2, 5.4_

  - [x] 1.4 Agregar método `getCustomerPeriodTotals()` al modelo
    - Calcular consumos y pagos individuales por cliente en el período
    - Retornar array con `consumos` y `pagos`
    - _Requirements: 4.4, 4.5, 6.1_

  - [x] 1.5 Agregar método `getConsumosByDate()` al modelo
    - Obtener total de consumos para una fecha específica
    - Retornar float con el total
    - _Requirements: 3.4, 5.2_

  - [x] 1.6 Agregar método `getPagosByDate()` al modelo
    - Obtener total de pagos para una fecha específica
    - Retornar float con el total
    - _Requirements: 3.6, 5.4_

- [x] 2. Checkpoint - Verificar métodos del modelo
  - Métodos implementados correctamente
  - Verificar que los cálculos sean correctos

- [x] 3. Modificar controlador para filas especiales y tarjetas
  - [x] 3.1 Agregar método auxiliar `getDateRange()` al controlador
    - Generar array de fechas entre `fi` y `ff`
    - Retornar array de strings con formato 'Y-m-d'
    - _Requirements: 2.1_

  - [x] 3.2 Agregar método auxiliar `formatDateColumn()` al controlador
    - Formatear fecha como "DÍA DD/MES"
    - _Requirements: 2.3_

  - [x] 3.3 Modificar método `lsConsolidated()` para calcular totales de tarjetas
    - Llamar a `getTotalInitialBalance()` para saldo inicial
    - Llamar a `getPeriodTotals()` para consumos y pagos del período
    - Calcular saldo final: `saldo_inicial + consumos - pagos`
    - _Requirements: 1.2, 1.3, 1.4, 1.5_

  - [x] 3.4 Modificar método `lsConsolidated()` para insertar fila "Saldo inicial"
    - Crear fila con estilo especial (negrita, fondo amarillo)
    - Mostrar "-" en columnas de fecha
    - Mostrar saldo inicial en columna DEUDA
    - _Requirements: 3.1, 3.2, 3.10_

  - [x] 3.5 Modificar método `lsConsolidated()` para insertar fila "Consumo a crédito"
    - Calcular consumos por fecha usando `getConsumosByDate()`
    - Mostrar valores en columnas de CONSUMOS
    - Mostrar "-" en columnas de PAGOS
    - _Requirements: 3.3, 3.4_

  - [x] 3.6 Modificar método `lsConsolidated()` para insertar fila "Pagos y anticipos"
    - Calcular pagos por fecha usando `getPagosByDate()`
    - Mostrar "-" en columnas de CONSUMOS
    - Mostrar valores en columnas de PAGOS con formato negativo
    - _Requirements: 3.5, 3.6, 3.9_

  - [x] 3.7 Modificar método `lsConsolidated()` para insertar fila "Saldo final"
    - Calcular saldo final por fecha
    - Mostrar "-" en columnas de fecha
    - Mostrar saldo final en columna DEUDA
    - _Requirements: 3.7, 3.8_

  - [x] 3.8 Modificar método `lsConsolidated()` para insertar filas de totales
    - Agregar fila "Total de consumos a crédito" después de clientes
    - Agregar fila "Total de pagos y anticipos" después de total consumos
    - Aplicar estilo especial (negrita, fondo gris)
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

  - [x] 3.9 Modificar retorno de `lsConsolidated()` para incluir datos de tarjetas
    - Retornar estructura: `{ cards: {...}, thead: '', row: [...] }`
    - Incluir: `saldo_inicial`, `consumos_credito`, `pagos_anticipos`, `saldo_final`
    - _Requirements: 9.3, 9.4_

- [x] 4. Checkpoint - Verificar controlador
  - Controlador modificado correctamente

- [x] 5. Implementar componentes del frontend
  - [x] 5.1 Agregar contenedor de tarjetas al layout de `CustomersConsolidated`
    - Crear div con id `cardsCustomersConsolidated`
    - Posicionar antes del contenedor de tabla
    - _Requirements: 1.1_

  - [x] 5.2 Agregar método `renderSummaryCards()` a la clase `CustomersConsolidated`
    - Usar componente `infoCard()` de CoffeeSoft
    - Mostrar 4 tarjetas: Saldo inicial, Consumos, Pagos, Saldo final
    - Aplicar colores según tipo (amarillo, rojo, verde, azul)
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6_

  - [x] 5.3 Modificar método `lsConsolidated()` para renderizar tarjetas
    - Verificar si `response.cards` existe
    - Llamar a `renderSummaryCards(response.cards)`
    - _Requirements: 9.3_

  - [x] 5.4 Actualizar estilos de tabla para columnas alternadas
    - Aplicar `bg-red-50` a columnas de CONSUMOS
    - Aplicar `bg-orange-50` a columnas de PAGOS
    - _Requirements: 8.1, 8.2_

- [x] 6. Checkpoint - Verificar frontend
  - Frontend actualizado correctamente

- [x] 7. Implementar exportación a Excel
  - [x] 7.1 Agregar botón "Exportar a Excel" al filterBar
    - Usar método `createExcel()` de CoffeeSoft
    - Nombrar archivo con patrón "Consolidado-Clientes-[fecha].xlsx"
    - _Requirements: 10.1, 10.2, 10.5_

- [x] 8. Checkpoint final - Implementación completa
  - Todas las tareas completadas
  - Probar flujo completo con datos reales

## Notes

- Tasks marcadas con `*` son opcionales y pueden omitirse para un MVP más rápido
- Cada task referencia los requisitos específicos que implementa
- Los checkpoints permiten validación incremental con el usuario
- Se recomienda probar cada fase antes de continuar con la siguiente
