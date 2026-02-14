# Refactoring: Unificación de Métodos de Formularios de Sección

## Descripción General

Refactorizar los métodos `createCashConceptsForm()` y `createBankAccountsForm()` en `add-sales.js` para utilizar el componente reutilizable `createSectionForm()`, eliminando código duplicado y mejorando la mantenibilidad.

## Análisis del Estado Actual

### Métodos a Refactorizar

| Método | Título | Prefijo | Data Source | Data Attr | Label Format |
|--------|--------|---------|-------------|-----------|--------------|
| `createCashConceptsForm()` | Conceptos de Efectivo | `cash_` | `this.cashConcepts` | `data-concept-id` | `concept.name` |
| `createBankAccountsForm()` | Cuentas Bancarias | `bank_` | `this.bankAccounts` | `data-account-id` | `${bank_name} - ${account}` |

### Método Base Existente

`createSectionForm()` actualmente soporta:
- `title`: Título de la sección
- `prefix`: Prefijo para IDs de inputs
- `labelPrefix`: Prefijo opcional para labels
- `gridCols`: Clases de grid CSS
- `data`: Array de datos para generar inputs
- `collapse`: Habilitar colapso
- `collapsed`: Estado inicial colapsado

### Limitaciones Actuales de `createSectionForm()`

1. Usa `createCurrencyInput()` internamente (no `createPaymentInput()`)
2. Data attribute fijo: `data-category-id`
3. Callback fijo: `calculateTaxes()` + `calculateDifference()`
4. Label generation: Solo usa `category.name`

---

## Requisitos Funcionales

### REQ-001: Soporte para Método de Input Configurable

**EARS Pattern**: When `createSectionForm()` is called with an `inputMethod` option, the system shall use the specified method (`createCurrencyInput` or `createPaymentInput`) to generate input fields.

**Acceptance Criteria**:
- [ ] AC-001.1: Si `inputMethod: 'payment'`, usar `createPaymentInput()`
- [ ] AC-001.2: Si `inputMethod: 'currency'` o no especificado, usar `createCurrencyInput()` (comportamiento actual)
- [ ] AC-001.3: Los inputs generados deben mantener la funcionalidad de formateo de moneda

### REQ-002: Soporte para Data Attribute Configurable

**EARS Pattern**: When `createSectionForm()` is called with a `dataAttrName` option, the system shall use the specified attribute name for input data attributes.

**Acceptance Criteria**:
- [ ] AC-002.1: Si `dataAttrName: 'concept-id'`, generar `data-concept-id`
- [ ] AC-002.2: Si `dataAttrName: 'account-id'`, generar `data-account-id`
- [ ] AC-002.3: Si no especificado, usar `data-category-id` (comportamiento actual)

### REQ-003: Soporte para Callback Configurable

**EARS Pattern**: When `createSectionForm()` is called with an `onChangeCallback` option, the system shall execute the specified callback function on input change events.

**Acceptance Criteria**:
- [ ] AC-003.1: Si `onChangeCallback` es una función, ejecutarla en el evento change
- [ ] AC-003.2: Si no especificado, ejecutar `calculateTaxes()` + `calculateDifference()` (comportamiento actual)
- [ ] AC-003.3: El callback debe recibir el evento como parámetro

### REQ-004: Soporte para Generador de Labels Personalizado

**EARS Pattern**: When `createSectionForm()` is called with a `labelGenerator` function, the system shall use that function to generate input labels from data items.

**Acceptance Criteria**:
- [ ] AC-004.1: Si `labelGenerator` es una función, usarla para generar el label de cada input
- [ ] AC-004.2: La función debe recibir el item de datos como parámetro
- [ ] AC-004.3: Si no especificado, usar `item.name` (comportamiento actual)

### REQ-005: Refactorizar `createCashConceptsForm()`

**EARS Pattern**: The `createCashConceptsForm()` method shall be refactored to use `createSectionForm()` with appropriate configuration options.

**Acceptance Criteria**:
- [ ] AC-005.1: Usar `createSectionForm()` con `inputMethod: 'payment'`
- [ ] AC-005.2: Configurar `dataAttrName: 'concept-id'`
- [ ] AC-005.3: Configurar `prefix: 'cash'`
- [ ] AC-005.4: Mantener el comportamiento visual y funcional actual
- [ ] AC-005.5: El método refactorizado debe tener menos de 10 líneas de código

### REQ-006: Refactorizar `createBankAccountsForm()`

**EARS Pattern**: The `createBankAccountsForm()` method shall be refactored to use `createSectionForm()` with appropriate configuration options.

**Acceptance Criteria**:
- [ ] AC-006.1: Usar `createSectionForm()` con `inputMethod: 'payment'`
- [ ] AC-006.2: Configurar `dataAttrName: 'account-id'`
- [ ] AC-006.3: Configurar `prefix: 'bank'`
- [ ] AC-006.4: Configurar `labelGenerator` para formato `${bank_name} - ${account}`
- [ ] AC-006.5: Mantener el comportamiento visual y funcional actual
- [ ] AC-006.6: El método refactorizado debe tener menos de 15 líneas de código

---

## Requisitos No Funcionales

### REQ-NF-001: Compatibilidad Retroactiva

El refactoring no debe romper ninguna funcionalidad existente que use `createSectionForm()` con la configuración actual.

### REQ-NF-002: Reducción de Código

El refactoring debe reducir el código duplicado en al menos un 50% comparado con la implementación actual.

### REQ-NF-003: Mantenibilidad

Los nuevos parámetros deben seguir el patrón de configuración existente usando `Object.assign()` con defaults.

---

## Fuera de Alcance

- **`createForeignCurrenciesForm()`**: Este método tiene una estructura de doble input (monto + equivalente MXN) con lógica de conversión de moneda que no es compatible con el patrón de `createSectionForm()`. Se mantendrá como implementación separada.

---

## Dependencias

- `contabilidad2/js/add-sales.js` - Archivo principal a modificar
- Métodos existentes: `createPaymentInput()`, `createCurrencyInput()`, `createSectionForm()`

---

## Riesgos

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| Romper cálculos de totales | Media | Alto | Testing exhaustivo de flujo completo |
| Incompatibilidad con código existente | Baja | Medio | Mantener defaults para comportamiento actual |

