# 🛒 PIVOTE COMPRAS — Sistema de Gestión de Compras

---

## 🎯 Propósito General
El **módulo de compras** centraliza la administración de compras, gastos y proveedores dentro del sistema ERP CoffeeSoft.  
Permite registrar, consultar, editar y eliminar compras organizadas por tipo de pago (Fondo Fijo, Crédito, Corporativo), con control de cuentas contables, subcuentas, proveedores y métodos de pago.

---

## ⚙️ Key Features

- 🔹 **Dashboard con KPIs** mostrando totales por tipo de compra (Fondo Fijo, Crédito, Corporativo)
- 🔹 **Gestión de compras** con operaciones CRUD completas
- 🔹 **Organización por cuentas contables** y subcuentas
- 🔹 **Filtros inteligentes** por fecha, UDN, tipo de pago y método de pago
- 🔹 **Vista de concentrado** con reporte consolidado por cuenta y fecha
- 🔹 **Exportación a Excel** del concentrado de compras
- 🔹 **Subida de archivos** asociados a compras
- 🔹 **Detalle de compra** con información completa de facturación
- 🔹 **Diseño responsive** con TailwindCSS y tema corporativo

---

## 🧩 Estructura Técnica

| Clase / Archivo | Descripción | Función Principal |
|------------------|-------------|-------------------|
| `Purchases` | Clase principal para gestión de compras individuales | `purchases.lsPurchases()` |
| `Consolidated` | Controla vista de concentrado con reporte consolidado | `consolidated.lsConsolidated()` |
| `ctrl-compras.php` | Controlador PHP para operaciones CRUD y reportes | `opc: "init", "ls", "addPurchase", "editPurchase"` |
| `mdl-compras.php` | Modelo de base de datos para consultas y operaciones | Métodos de lectura y escritura SQL |

---

## 🧭 Notas de Diseño

- 🧩 El módulo usa **dos vistas** intercambiables: Compras individuales y Concentrado
- 💡 Sistema de **tipos de pago**: Fondo Fijo, Crédito, Corporativo
- 🖌️ **Paleta de colores** por tipo:
  - Fondo Fijo: Verde (`bg-green-100`, `text-green-700`)
  - Crédito: Rojo (`bg-red-100`, `text-red-700`)
  - Corporativo: Verde (`bg-green-100`, `text-green-700`)
- 🔒 **Validación**: Ventana de edición limitada (mismo día o día siguiente antes de 12:00)
- 📊 **KPIs dinámicos** que se actualizan automáticamente tras operaciones

---

## 💻 Código del Pivote

### FRONT-JS [compras.js]

```javascript
class Purchases extends Templates {

    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Purchases";
        this.currentView = "purchases";
        let cookies = getCookies();
        this.udn = cookies.IDE;
    }

    render() {
        this.layout();
        this.showCards();
        this.filterBar();
        this.lsPurchases();
    }

    layout() {
        this.createLayout({
            parent: "container-compras",
            design: false,
            data: {
                id: this.PROJECT_NAME,
                class: "w-full",
                container: [
                    {
                        type: "div",
                        id: `cards${this.PROJECT_NAME}`,
                        class: "w-full mb-4"
                    },
                    {
                        type: "div",
                        id: `filterBar${this.PROJECT_NAME}`,
                        class: "w-full p-3 border rounded mb-3"
                    },
                    {
                        type: "div",
                        id: `container${this.PROJECT_NAME}`,
                        class: "w-full h-full p-2"
                    }
                ]
            }
        });
    }

    filterBar() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            data: [
                {
                    opc: "button",
                    id: "btnToggleView",
                    text: "Concentrado",
                    class: "col-sm-3 col-4 col-lg-2",
                    icon: 'icon-toggle-off',
                    className: 'w-100',
                    color_btn: "outline-info",
                    onClick: () => this.toggleView()
                },
                {
                    opc: "button",
                    id: "btnUploadFiles",
                    text: "Subir archivos",
                    class: "col-sm-3 col-4 col-lg-2",
                    className: 'w-100',
                    color_btn: "success",
                    onClick: () => purchases.uploadFiles()
                },
                {
                    opc: "button",
                    id: "btnNewPurchase",
                    text: "Nueva compra",
                    class: "col-sm-3 col-4 col-lg-2",
                    className: 'w-100',
                    color_btn: "primary",
                    onClick: () => this.addPurchase()
                },
                {
                    opc: "select",
                    id: "payment_type_id",
                    lbl: "Filtrar por:",
                    class: "col-sm-3",
                    data: [
                        { id: "", valor: "Todos los tipos" },
                        ...paymentTypes
                    ],
                    onchange: `purchases.lsPurchases()`
                },
                {
                    opc: "select",
                    id: "method_pay_id",
                    lbl: "Método de pago:",
                    class: "col-sm-3",
                    data: [
                        { id: "", valor: "Todos los métodos" },
                        ...paymentMethods
                    ],
                    onchange: `purchases.lsPurchases()`
                }
            ]
        });
    }

    async showCards() {
        const rangePicker = getDataRangePicker(`calendarContabilidad`);
        const udn = $(`#udn`).val();

        const data = await useFetch({
            url: api_compras,
            data: {
                opc: "showPurchase",
                fi: rangePicker.fi,
                ff: rangePicker.ff,
                udn: udn || this.udn
            }
        });

        if (data.status === 200) {
            this.renderInfoCards(data.counts);
        }
    }

    renderInfoCards(counts) {
        this.infoCard({
            parent: `cards${this.PROJECT_NAME}`,
            theme: "light",
            style: "file",
            class: "mb-4",
            json: [
                {
                    id: "kpiTotal",
                    title: "Total de compras",
                    bgColor: " ",
                    borderColor: "border-gray-300",
                    data: { value: formatPrice(counts.total || 0), color: "text-gray-700" }
                },
                {
                    id: "kpiFondoFijo",
                    title: "Compras de Fondo Fijo",
                    bgColor: "bg-green-100",
                    borderColor: "border-green-300",
                    data: { value: formatPrice(counts.fondo_fijo || 0), color: "text-green-700" }
                },
                {
                    id: "kpiCredito",
                    title: "Compras a Crédito",
                    bgColor: "bg-red-100",
                    borderColor: "border-red-300",
                    data: { value: formatPrice(counts.credito || 0), color: "text-red-700" }
                },
                {
                    id: "kpiCorporativo",
                    title: "Compras Corporativo",
                    bgColor: "bg-green-100",
                    borderColor: "border-green-300",
                    data: { value: formatPrice(counts.corporativo || 0), color: "text-green-700" }
                }
            ]
        });
    }

    // Compras.

    lsPurchases() {
        const rangePicker = getDataRangePicker(`calendarContabilidad`);
        const udn = $(`#udn`).val();

        this.createTable({
            parent: `container${this.PROJECT_NAME}`,
            idFilterBar: `filterBarPurchases`,
            data: {
                opc: 'ls',
                fi: rangePicker.fi,
                ff: rangePicker.ff,
                udn: udn || this.udn,
            },
            coffeesoft: true,
            conf: { datatable: true, pag: 25 },
            attr: {
                id: `tbPurchases`,
                theme: 'corporativo',
                striped: true,
                center: [1, 5],
                right: [7]
            }
        });
    }

    addPurchase() {
        this.createModalForm({
            id: 'formPurchaseAdd',
            data: { opc: 'addPurchase', daily_closure_id: daily_closure_id },
            bootbox: {
                title: '<span class="text-lg font-bold"> Nueva Compra </span>',
                closeButton: true
            },
            json: this.jsonPurchase(),
            success: (response) => {
                if (response.status === 200) {
                    alert({ icon: "success", text: response.message });
                    this.lsPurchases();
                    this.showCards();
                } else {
                    alert({ icon: "error", text: response.message, btn1: true, btn1Text: "Ok" });
                }
            }
        });

        setTimeout(() => {
            $('#supplier_id').option_select({
                data: suppliers,
                placeholder: 'Seleccione un proveedor',
                father: true,
                select2: true
            });

            $('#gl_account_id').option_select({
                data: accounts,
                placeholder: 'Seleccione una cuenta',
                father: true,
                select2: true
            });

            $('#subaccount_id').option_select({
                data: [],
                placeholder: 'Seleccione una subcuenta',
                father: true,
                select2: true
            });

            $('#formPurchaseAdd #method_pay_id').option_select({
                data: paymentMethods,
                placeholder: 'Seleccione un metodo de pago',
                father: true,
                select2: true
            });

            this.togglePaymentMethod();

            $('#payment_type_id').on('change', () => {
                this.togglePaymentMethod();
            });

        }, 300);
    }

    async editPurchase(id) {
        const request = await useFetch({ 
            url: this._link, 
            data: { opc: "getPurchase", id: id } 
        });

        const purchase = request.data;

        this.createModalForm({
            id: 'formPurchase',
            data: { opc: 'editPurchase', id: id },
            autofill: purchase,
            bootbox: {
                title: 'Editar Compra',
                closeButton: true
            },
            json: this.jsonPurchaseEdit(purchase),
            success: (response) => {
                if (response.status === 200) {
                    alert({ icon: "success", text: response.message });
                    this.lsPurchases();
                    this.showCards();
                } else {
                    alert({ icon: "error", text: response.message });
                }
            }
        });

        setTimeout(() => {
            this.initSelect2(purchase);
        }, 300);
    }

    deletePurchase(id) {
        this.swalQuestion({
            opts: {
                title: "¿Está seguro de eliminar esta compra?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning"
            },
            data: { opc: "deletePurchase", id: id },
            methods: {
                send: (response) => {
                    if (response.status === 200) {
                        alert({ icon: "success", text: response.message });
                        this.lsPurchases();
                        this.showCards();
                    } else {
                        alert({ icon: "error", text: response.message });
                    }
                }
            }
        });
    }

    async viewDetail(id) {
        const request = await useFetch({
            url: this._link,
            data: { opc: "getPurchase", id: id }
        });

        if (request.status !== 200) {
            alert({ icon: "error", text: request.message });
            return;
        }

        const purchase = request.data;

        const modalContent = `
            <div class="p-3">
                <div class="mb-3">
                    <h4 class="font-semibold text-gray-800 mb-4 uppercase text-sm">Información de Cuenta</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-bold text-gray-800 mb-1">Cuenta</p>
                            <p class="text-sm font-semibold text-gray-500">${purchase.account_name}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 mb-1">Subcuenta</p>
                            <p class="text-sm font-semibold text-gray-500">${purchase.subaccount_name || ''}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 mb-1">Tipo de compra</p>
                            <p class="text-sm font-semibold text-gray-500">${purchase.payment_type_name}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 mb-1">Método de pago</p>
                            <p class="text-sm font-semibold text-gray-500">${purchase.method_pay_name || 'N/A'}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-gray-800 mb-4 uppercase text-sm">Información de Facturación</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-bold text-gray-800 mb-1">Proveedor</p>
                            <p class="text-sm font-semibold text-gray-500">${purchase.supplier_name}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 mb-1">Número de Ticket/Factura</p>
                            <p class="text-sm font-semibold text-gray-500">${purchase.reason || ''}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-gray-800 mb-4 uppercase text-sm">Descripción</h4>
                    <p class="text-sm font-semibold text-gray-600">${purchase.description || 'Sin descripción'}</p>
                </div>

                <div class="border-t pt-4">
                    <h4 class="font-semibold text-gray-800 mb-4 uppercase text-sm">Resumen Financiero</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-bold text-gray-800">Subtotal:</span>
                            <span class="text-sm font-semibold text-gray-500">${formatPrice(purchase.subtotal)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-bold text-gray-800">Impuesto:</span>
                            <span class="text-sm font-semibold text-gray-500">${formatPrice(purchase.tax)}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t pt-3">
                            <span class="text-gray-800">Total:</span>
                            <span class="font-semibold text-gray-600">${formatPrice(purchase.total)}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        bootbox.dialog({
            title: `
                <div class="mb-2">
                    <h3 class="text-lg font-bold text-gray-800">Detalle de la Compra</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Última actualización: ${purchase.created_at}
                    </p>
                </div>
            `,
            message: modalContent,
            closeButton: true
        });
    }

    uploadFiles() {
        const input = document.createElement('input');
        input.type = 'file';
        input.multiple = true;
        input.accept = '.pdf,.xlsx,.xls,.doc,.docx,.png,.jpg,.jpeg';

        input.onchange = async (e) => {
            const files = e.target.files;

            if (!files || files.length === 0) return;

            Swal.fire({
                title: 'Subiendo archivos...',
                html: `
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <p class="text-sm text-gray-600">Procesando ${files.length} archivo(s)</p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const formData = new FormData();

            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            formData.append('opc', 'uploadPurchaseFiles');
            formData.append('section_id', '2');
            formData.append('daily_closure_id', daily_closure_id);

            const udn = $('#udn').val();
            if (udn) formData.append('udn_id', udn);

            const response = await fetch(api_compras, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Archivos subidos!',
                    text: `Se subieron ${result.uploaded} de ${result.total} archivo(s) correctamente`,
                    confirmButtonText: 'Aceptar'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Error al subir archivos',
                    confirmButtonText: 'Aceptar'
                });
            }
        };

        input.click();
    }

    jsonPurchase() {
        return [
            {
                opc: "select",
                id: "supplier_id",
                lbl: "Proveedor",
                class: "col-12 mb-3",
                data: suppliers,
                required: true
            },
            {
                opc: "select",
                id: "gl_account_id",
                lbl: "Cuenta",
                class: "col-6 mb-5",
                data: accounts,
                required: true,
                onchange: "purchases.loadSubaccounts()"
            },
            {
                opc: "select",
                id: "subaccount_id",
                lbl: "Subcuenta",
                class: "col-6 mb-5",
                data: [],
                required: true
            },
            {
                opc: "select",
                id: "payment_type_id",
                lbl: "Tipo de Compra",
                class: "col-12 col-md-6 mb-3",
                data: paymentTypes,
                required: true,
                onchange: "purchases.togglePaymentMethod()"
            },
            {
                opc: "select",
                id: "method_pay_id",
                lbl: "Método de Pago",
                class: "col-12 col-md-6 mb-3",
                data: paymentMethods
            },
            {
                opc: "input",
                id: "subtotal",
                lbl: "Subtotal",
                tipo: "cifra",
                class: "col-12 col-md-6 mb-3",
                required: true,
                onkeyup: "validationInputForNumber('#subtotal')"
            },
            {
                opc: "input",
                id: "tax",
                lbl: "Impuesto",
                class: "col-12 col-md-6 mb-3",
                required: false,
                onkeyup: "validationInputForNumber('#tax')"
            },
            {
                opc: "textarea",
                id: "description",
                lbl: "Descripción",
                class: "col-12 mb-3",
                rows: 3
            }
        ];
    }

    jsonPurchaseEdit(purchase) {
        const filteredSubaccounts = subaccounts.filter(sub => sub.gl_account_id == purchase.gl_account_id);

        return [
            {
                opc: "select",
                id: "supplier_id",
                lbl: "Proveedor",
                class: "col-12 mb-3",
                data: suppliers,
                required: true
            },
            {
                opc: "select",
                id: "gl_account_id",
                lbl: "Cuenta",
                class: "col-6 mb-3",
                data: accounts,
                required: true,
                onchange: "purchases.loadSubaccounts()"
            },
            {
                opc: "select",
                id: "subaccount_id",
                lbl: "Subcuenta",
                class: "col-6 mb-3",
                data: filteredSubaccounts,
                required: true
            },
            {
                opc: "select",
                id: "payment_type_id",
                lbl: "Tipo de Compra",
                class: "col-12 col-md-6 mb-3",
                data: paymentTypes,
                required: true,
                onchange: "purchases.togglePaymentMethod()"
            },
            {
                opc: "select",
                id: "method_pay_id",
                lbl: "Método de Pago",
                class: "col-12 col-md-6 mb-3",
                data: paymentMethods
            },
            {
                opc: "input",
                id: "reason",
                lbl: "No. Factura",
                class: "col-12 mb-3",
                required: false
            },
            {
                opc: "input",
                id: "subtotal",
                lbl: "Subtotal",
                tipo: "cifra",
                class: "col-12 col-md-6 mb-3",
                required: true
            },
            {
                opc: "input",
                id: "tax",
                lbl: "Impuesto",
                tipo: "cifra",
                class: "col-12 col-md-6 mb-3",
                required: true
            },
            {
                opc: "textarea",
                id: "description",
                lbl: "Descripción",
                class: "col-12 mb-3",
                rows: 3
            }
        ];
    }

    // Métodos auxiliares

    async loadSubaccounts() {
        const accountId = $('#gl_account_id').val();

        if (!accountId) {
            $('#subaccount_id').html('<option value="">Seleccione una cuenta mayor primero</option>');
            return;
        }

        const filteredSubaccounts = subaccounts.filter(sub => sub.gl_account_id == accountId);

        $('#subaccount_id').html('<option value="">Seleccione una subcuenta</option>');
        filteredSubaccounts.forEach(sub => {
            $('#subaccount_id').append(`<option value="${sub.id}">${sub.valor}</option>`);
        });
    }

    togglePaymentMethod() {
        const paymentTypeName = $('#formPurchaseAdd #payment_type_id option:selected').text().trim();

        if (paymentTypeName === 'Corporativo') {
            $('#formPurchaseAdd #method_pay_id').prop('disabled', false);
        } else {
            $('#formPurchaseAdd #method_pay_id').prop('disabled', true);
        }
    }

    initSelect2(purchase) {
        this.setupSelect('#supplier_id', 'Seleccione un proveedor', purchase.supplier_id);
        this.setupSelect('#gl_account_id', 'Seleccione una cuenta', purchase.gl_account_id);
        this.setupSelect('#subaccount_id', 'Seleccione una subcuenta', purchase.subaccount_id);
        this.setupSelect('#payment_type_id', 'Seleccione un tipo de pago', purchase.payment_type_id);
        this.setupSelect('#method_pay_id', 'Seleccione un método de pago', purchase.method_pay_id);

        this.togglePaymentMethod();

        $('#payment_type_id').on('change', () => {
            this.togglePaymentMethod();
        });
    }

    setupSelect(selector, placeholder, selectedValue) {
        $(`#formPurchase ${selector}`).option_select({
            placeholder: placeholder,
            select2: true,
            father: true
        });

        $(`#formPurchase ${selector}`).val(selectedValue).trigger('change');
    }

    toggleView() {
        if (this.currentView === "purchases") {
            this.showConcentrado();
        } else {
            this.showCompras();
        }
    }

    showConcentrado() {
        this.currentView = "consolidated";
        $(`#container${this.PROJECT_NAME}`).html('');
        consolidated.render();
    }

    showCompras() {
        this.currentView = "purchases";
        $(`#container${this.PROJECT_NAME}`).html('');
        this.render();
    }
}
```

---

### CLASE CONSOLIDATED [compras.js]

```javascript
class Consolidated extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Consolidated";
        let cookies = getCookies();
        this.udn = cookies.IDE;
    }

    render() {
        this.layoutConsolidated();
        this.filterBarConsolidated();
        this.lsConsolidated();
    }

    layoutConsolidated() {
        this.primaryLayout({
            parent: "container-compras",
            id: this.PROJECT_NAME,
            class: "w-full",
            card: {
                filterBar: { class: "w-full p-3 mb-3 border rounded-lg", id: `filterBar${this.PROJECT_NAME}` },
                container: { class: "w-full h-full p-3 border rounded-lg overflow-x-auto", id: `container${this.PROJECT_NAME}` }
            }
        });
    }

    filterBarConsolidated() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            data: [
                {
                    opc: "button",
                    id: "btnBackToPurchases",
                    text: "Concentrado",
                    class: "col-sm-2",
                    className: 'w-100',
                    icon: 'icon-toggle-on',
                    color_btn: "outline-primary",
                    onClick: () => purchases.toggleView()
                },
                {
                    opc: "button",
                    id: "btnExportExcel",
                    text: "Exportar a Excel",
                    class: "col-sm-2",
                    className: 'w-100',
                    icon: 'icon-file-excel',
                    color_btn: "success",
                    onClick: () => this.exportToExcel()
                },
                {
                    opc: "select",
                    id: "paymentType",
                    lbl: "Tipo de pago:",
                    class: "col-sm-3 offset-sm-5",
                    data: [
                        { id: "", valor: "Todos los tipos" },
                        ...paymentTypes
                    ],
                    onchange: `consolidated.lsConsolidated()`
                }
            ]
        });
    }

    exportToExcel() {
        this.createExcel({
            tableId: "tbConsolidated",
            fileName: "Concentrado-compras"
        });
    }

    async lsConsolidated() {
        const rangePicker = getDataRangePicker(`calendarContabilidad`);
        const udn = $(`#filterBarContabilidad #udn`).val() || this.udn;
        const paymentType = $(`#filterBar${this.PROJECT_NAME} #paymentType`).val();

        $(`#container${this.PROJECT_NAME}`).html('<div class="flex justify-center items-center py-8"><i class="icon-spin4 animate-spin text-2xl text-gray-500"></i></div>');

        const response = await useFetch({
            url: api_compras,
            data: {
                opc: 'lsConsolidated',
                fi: rangePicker.fi,
                ff: rangePicker.ff,
                udn: udn,
                payment_type_id: paymentType
            }
        });

        this.createCoffeTable2({
            parent: `container${this.PROJECT_NAME}`,
            id: `tbConsolidated`,
            theme: 'light',
            title: `Concentrado de Compras`,
            subtitle: `Reporte consolidado por cuenta y fecha`,
            data: response,
            collapsed: true,
            color_group: 'bg-blue-50',
            fixed: [1],
            folding: true,
            f_size: 12,
            selectable: true
        });
    }
}
```

---

### CTRL [ctrl-compras.php]

```php
<?php
session_start();
if (empty($_POST['opc'])) exit(0);

require_once '../mdl/mdl-compras.php';
require_once '../../../conf/coffeSoft.php';

class ctrl extends mdl {

    function init() {
        $date = $_POST['date'];
        $udn  = $_POST['udn'];

        return [
            'sections'       => $this->lsSections(),
            'accounts'       => $this->lsAccounts([1]),
            'subaccounts'    => $this->lsSubaccounts([]),
            'suppliers'      => $this->lsSuppliers([1]),
            'paymentTypes'   => $this->lsPaymentTypes(),
            'paymentMethods' => $this->lsPaymentMethods(),
            'udn'            => $this->lsUDN(),
            'dailyClosure'   => $this->getOrCreateDailyClosure($date, $udn)
        ];
    }

    private function getOrCreateDailyClosure($date, $udn) {
        $dailyClosure = $this->getDailyClosureByDate([$date, $udn]);

        if (!$dailyClosure) {
            $newClosure = [
                'udn_id'                 => $udn,
                'employee_id'            => '',
                'total_sale_without_tax' => 0,
                'total_sale'             => 0,
                'subtotal'               => 0,
                'tax'                    => 0,
                'cash'                   => 0,
                'bank'                   => 0,
                'credit_consumer'        => 0,
                'credit_payment'         => 0,
                'total_payment'          => 0,
                'difference'             => 0,
                'created_at'             => $date . ' ' . date('H:i:s'),
                'turn'                   => 1,
                'total_suite'            => 0
            ];

            $closureId = $this->createDailyClosure($this->util->sql($newClosure));

            if ($closureId) {
                $dailyClosure = $this->getDailyClosureByDate([$date, $udn]);
            }
        }

        return $dailyClosure ?? [];
    }

    // Compras

    function ls() {
        $fi              = $_POST['fi'];
        $ff              = $_POST['ff'];
        $udn             = $_POST['udn'];
        $payment_type_id = $_POST['payment_type_id'];
        $method_pay_id   = $_POST['method_pay_id'];

        $data = $this->listPurchases([
            'fi'              => $fi,
            'ff'              => $ff,
            'udn'             => $udn,
            'payment_type_id' => $payment_type_id,
            'method_pay_id'   => $method_pay_id
        ]);

        $rows = [];

        if (is_array($data) && !empty($data)) {
            foreach ($data as $item) {
                $rows[] = [
                    'id'             => $item['id'],
                    'Folio'          => generateFolio($item['id']),
                    'Proveedor'      => htmlspecialchars($item['supplier_name']),
                    'Cuenta'         => htmlspecialchars($item['account_name']),
                    'Subcuenta'      => htmlspecialchars($item['subaccount_name']),
                    'T. compra'      => renderPurchaseType($item['payment_type_name']),
                    'Método de pago' => $item['method_pay_name'] ? htmlspecialchars($item['method_pay_name']) : '',
                    'Monto'          => [
                        'html'  => evaluar($item['subtotal'] + $item['tax']),
                        'class' => 'text-end'
                    ],
                    'a'              => actionButtons($item['id'], $item['created_at'])
                ];
            }
        }

        return ['row' => $rows];
    }

    function showPurchase() {
        $fi  = $_POST['fi'];
        $ff  = $_POST['ff'];
        $udn = $_POST['udn'];

        $counts = $this->getPurchaseCounts([
            'fi'  => $fi,
            'ff'  => $ff,
            'udn' => $udn
        ]);

        return [
            'status' => 200,
            'counts' => $counts
        ];
    }

    function addPurchase() {
        $status  = 500;
        $message = 'Error al crear la compra';

        $_POST['created_at']       = date('Y-m-d H:i:s');
        $_POST['total']            = $_POST['subtotal'] + $_POST['tax'];
        $_POST['active']           = 1;
        $_POST['daily_closure_id'] = $_POST['daily_closure_id'];

        $create = $this->createPurchase($this->util->sql($_POST));

        if ($create) {
            $status  = 200;
            $message = 'Compra registrada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function editPurchase() {
        $status  = 500;
        $message = 'Error al editar la compra';
        
        $data = [
            'supplier_id'     => $_POST['supplier_id'],
            'gl_account_id'   => $_POST['gl_account_id'],
            'subaccount_id'   => $_POST['subaccount_id'],
            'payment_type_id' => $_POST['payment_type_id'],
            'method_pay_id'   => $_POST['method_pay_id'],
            'reason'          => $_POST['reason'],
            'subtotal'        => $_POST['subtotal'],
            'tax'             => $_POST['tax'],
            'description'     => $_POST['description'],
            'total'           => $_POST['subtotal'] + $_POST['tax'],
            'id'              => $_POST['id']
        ];

        $edit = $this->updatePurchase($this->util->sql($data, 1));

        if ($edit) {
            $status  = 200;
            $message = 'Compra actualizada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function deletePurchase() {
        $status  = 500;
        $message = 'Error al eliminar la compra';
        $id      = $_POST['id'];

        $delete = $this->deletePurchaseById([$id]);

        if ($delete) {
            $status  = 200;
            $message = 'Compra eliminada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function getPurchase() {
        $status  = 404;
        $message = 'Compra no encontrada';
        $id      = $_POST['id'];

        $purchase = $this->getPurchaseById([$id]);

        if ($purchase) {
            $status                 = 200;
            $message                = 'Compra encontrada';
            $formatDate             = formatSpanishDate($purchase['created_at'], 'normal') . ' ' . $purchase['time'];
            $purchase['created_at'] = $formatDate;
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $purchase
        ];
    }

    // Concentrado

    function lsConsolidated() {
        $fi  = $_POST['fi'];
        $ff  = $_POST['ff'];
        $udn = $_POST['udn'];

        $rows             = [];
        $dateColumns      = $this->getDateRange($fi, $ff);
        $dateColumnTotals = array_fill_keys($dateColumns, 0);
        $reportTotal      = 0;

        $groups = $this->listAccountsWithPurchases([
            'udn' => $udn,
            'fi'  => $fi,
            'ff'  => $ff
        ]);

        foreach ($groups as $group) {
            $groupTotal = 0;

            $groupRow = [
                'id'       => $group['account_id'],
                'Concepto' => [
                    'html'  => $group['account_name'],
                    'class' => ''
                ]
            ];

            foreach ($dateColumns as $date) {
                $amount = floatval($this->getAccountTotalByDate([
                    'account_id' => $group['account_id'],
                    'fecha'      => $date
                ]));

                $groupTotal              += $amount;
                $dateFormatted            = $this->formatDateColumn($date);
                $groupRow[$dateFormatted] = [
                    'html'  => $amount > 0 ? evaluar($amount) : '-',
                    'class' => 'text-end'
                ];
            }

            $groupRow['TOTAL'] = [
                'html'  => evaluar($groupTotal),
                'class' => 'text-end'
            ];

            $groupRow['opc'] = 1;
            $rows[]          = $groupRow;

            // Subcuentas
            $subgroups = $this->listSubaccountsByAccount([
                'account_id' => $group['account_id'],
                'udn'        => $udn,
                'fi'         => $fi,
                'ff'         => $ff
            ]);

            foreach ($subgroups as $sub) {
                $subgroupRow = [
                    'id'       => $sub['subaccount_id'],
                    'Concepto' => [
                        'html'  => $sub['subaccount_name'],
                        'class' => 'bg-green-100'
                    ]
                ];

                $subgroupTotal = 0;

                foreach ($dateColumns as $date) {
                    $amount = floatval($this->getPurchaseByDate([
                        'subaccount_id' => $sub['subaccount_id'],
                        'fecha'         => $date
                    ]));

                    $subgroupTotal               += $amount;
                    $dateColumnTotals[$date]     += $amount;
                    $dateFormatted                = $this->formatDateColumn($date);
                    $subgroupRow[$dateFormatted]  = [
                        'html'  => $amount > 0 ? evaluar($amount) : '-',
                        'class' => 'text-end bg-green-100'
                    ];
                }

                $reportTotal += $subgroupTotal;

                $subgroupRow['TOTAL'] = [
                    'html'  => evaluar($subgroupTotal),
                    'class' => 'text-end bg-green-100'
                ];

                $subgroupRow['opc'] = 0;
                $rows[]             = $subgroupRow;
            }
        }

        // Fila de total general
        $totalRow = [
            'id'       => 'total_general',
            'Concepto' => [
                'html'  => '<strong>TOTAL GENERAL</strong>',
                'class' => 'bg-gray-200 font-bold'
            ]
        ];

        foreach ($dateColumns as $date) {
            $dateFormatted            = $this->formatDateColumn($date);
            $totalRow[$dateFormatted] = [
                'html'  => '<strong>' . evaluar($dateColumnTotals[$date]) . '</strong>',
                'class' => 'text-end bg-gray-200 font-bold'
            ];
        }

        $totalRow['TOTAL'] = [
            'html'  => '<strong>' . evaluar($reportTotal) . '</strong>',
            'class' => 'text-end bg-gray-200 font-bold'
        ];

        $totalRow['opc'] = 1;
        $rows[]          = $totalRow;

        return [
            'thead' => '',
            'row'   => $rows
        ];
    }

    private function getDateRange($fi, $ff) {
        $dates   = [];
        $current = new DateTime($fi);
        $end     = new DateTime($ff);

        while ($current <= $end) {
            $dates[] = $current->format('Y-m-d');
            $current->modify('+1 day');
        }

        return $dates;
    }

    private function formatDateColumn($fecha) {
        $timestamp = strtotime($fecha);
        $dias  = ['DOM', 'LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB'];
        $meses = ['', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 
                  'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];

        $diaSemana = $dias[date('w', $timestamp)];
        $dia       = date('d', $timestamp);
        $mes       = $meses[intval(date('m', $timestamp))];

        return "{$diaSemana} {$dia}/{$mes}";
    }

    // Upload Files

    function uploadPurchaseFiles() {
        $status           = 500;
        $message          = 'Error al subir archivos';
        $uploaded         = 0;
        $total            = 0;
        $errors           = [];
        
        $section_id       = $_POST['section_id'];
        $daily_closure_id = $_POST['daily_closure_id'];
        $user_id          = $_COOKIE['IDU'];
        $udn_id           = $_POST['udn_id'];

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/DEV/uploads/compras/';
                
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $files = $_FILES['files'];
        $total = count($files['name']);

        foreach ($files['name'] as $i => $fileName) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = "Error al subir {$fileName}";
                continue;
            }

            $temporal      = $files['tmp_name'][$i];
            $fileSize      = $files['size'][$i];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $newFileName  = 'compras_' . time() . '.' . $fileExtension;
            $destino      = $uploadDir . $newFileName;
            $relativePath = 'uploads/compras/' . $newFileName;

            if (!move_uploaded_file($temporal, $destino)) {
                $errors[] = "Error al mover {$fileName} al directorio";
                continue;
            }

            $fileData = [
                'file_name'        => $fileName,
                'size_bytes'       => $fileSize,
                'path'             => $relativePath,
                'extension'        => $fileExtension,
                'created_at'       => date('Y-m-d H:i:s'),
                'section_id'       => $section_id,
                'user_id'          => $user_id,
                'udn_id'           => $udn_id,
                'daily_closure_id' => $daily_closure_id
            ];

            $create = $this->createFile($this->util->sql($fileData));

            if ($create) {
                $uploaded++;
            } else {
                $errors[] = "Error al registrar {$fileName} en la base de datos";
                unlink($destino);
            }
        }

        if ($uploaded > 0) {
            $status  = 200;
            $message = $uploaded === $total
                ? "Todos los archivos se subieron correctamente ({$uploaded}/{$total})"
                : "Se subieron {$uploaded} de {$total} archivos";
        }

        return [
            'status'   => $status,
            'message'  => $message,
            'uploaded' => $uploaded,
            'total'    => $total,
            'errors'   => $errors
        ];
    }
}

// Complements

function actionButtons($id, $created_at) {
    return [
        [
            'class'   => 'btn btn-sm btn-info me-1',
            'html'    => '<i class="icon-eye"></i>',
            'onclick' => "purchases.viewDetail($id)"
        ],
        [
            'class'   => 'btn btn-sm btn-primary me-1',
            'html'    => '<i class="icon-pencil"></i>',
            'onclick' => "purchases.editPurchase($id)"
        ],
        [
            'class'   => 'btn btn-sm btn-danger',
            'html'    => '<i class="icon-trash"></i>',
            'onclick' => "purchases.deletePurchase($id)"
        ]
    ];
}

function renderPurchaseType($type) {
    $colors = [
        'Fondo Fijo'  => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
        'Crédito'     => ['bg' => 'bg-red-100',   'text' => 'text-red-700'],
        'Corporativo' => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700']
    ];
    
    $style = $colors[$type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
    
    return '<span class="inline-block px-3 py-1 rounded text-xs font-semibold ' . $style['bg'] . ' ' . $style['text'] . '">' . htmlspecialchars($type) . '</span>';
}

$obj = new ctrl();
echo json_encode($obj->{$_POST['opc']}());
```

---

### MDL [mdl-compras.php]

```php
<?php
require_once '../../../conf/_CRUD.php';
require_once '../../../conf/_Utileria.php';

class mdl extends CRUD {
    protected $util;
    public $bd;

    public function __construct() {
        $this->util = new Utileria;
        $this->bd   = "rfwsmqex_gvsl_finanzas3.";
    }

    function lsSections() {
        $query = "
            SELECT id, name AS valor
            FROM {$this->bd}section
            ORDER BY name ASC
        ";
        return $this->_Read($query, []);
    }

    function listPurchases($array) {
        $where = '1=1';
        $data  = [];

        if (!empty($array['fi']) && !empty($array['ff'])) {
            $where  .= ' AND DATE(dc.created_at) BETWEEN ? AND ?';
            $data[]  = $array['fi'];
            $data[]  = $array['ff'];
        }

        if (!empty($array['udn'])) {
            $where  .= ' AND dc.udn_id = ?';
            $data[]  = $array['udn'];
        }

        if (!empty($array['payment_type_id'])) {
            $where  .= ' AND p.payment_type_id = ?';
            $data[]  = $array['payment_type_id'];
        }

        if (!empty($array['method_pay_id'])) {
            $where  .= ' AND p.method_pay_id = ?';
            $data[]  = $array['method_pay_id'];
        }

        $query = "
            SELECT 
                p.id,
                p.udn_id,
                p.gl_account_id,
                p.subaccount_id,
                p.supplier_id,
                p.payment_type_id,
                p.method_pay_id,
                p.subtotal,
                p.tax,
                p.total,
                p.reason,
                p.description,
                p.created_at,
                p.active,
                ga.name AS account_name,
                sa.name AS subaccount_name,
                s.name AS supplier_name,
                pt.name AS payment_type_name,
                mp.name AS method_pay_name
            FROM {$this->bd}purchase p
            LEFT JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            LEFT JOIN {$this->bd}gl_account ga ON p.gl_account_id = ga.id
            LEFT JOIN {$this->bd}subaccount sa ON p.subaccount_id = sa.id
            LEFT JOIN {$this->bd}supplier s ON p.supplier_id = s.id
            LEFT JOIN {$this->bd}payment_type pt ON p.payment_type_id = pt.id
            LEFT JOIN {$this->bd}method_pay mp ON p.method_pay_id = mp.id
            WHERE {$where}
            AND p.active = 1
            ORDER BY p.created_at DESC
        ";

        return $this->_Read($query, $data);
    }

    function getPurchaseById($array) {
        $query = "
            SELECT 
                p.*,
                DATE_FORMAT(p.created_at, '%h:%i %p') AS time,
                ga.name AS account_name,
                sa.name AS subaccount_name,
                s.name AS supplier_name,
                pt.name AS payment_type_name,
                mp.name AS method_pay_name
            FROM {$this->bd}purchase p
            LEFT JOIN {$this->bd}gl_account ga ON p.gl_account_id = ga.id
            LEFT JOIN {$this->bd}subaccount sa ON p.subaccount_id = sa.id
            LEFT JOIN {$this->bd}supplier s ON p.supplier_id = s.id
            LEFT JOIN {$this->bd}payment_type pt ON p.payment_type_id = pt.id
            LEFT JOIN {$this->bd}method_pay mp ON p.method_pay_id = mp.id
            WHERE p.id = ?
        ";

        $result = $this->_Read($query, $array);
        return !empty($result) ? $result[0] : null;
    }

    function createPurchase($array) {
        return $this->_Insert([
            'table'  => $this->bd . 'purchase',
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updatePurchase($array) {
        return $this->_Update([
            'table'  => $this->bd . 'purchase',
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function deletePurchaseById($array) {
        return $this->_Update([
            'table'  => $this->bd . 'purchase',
            'values' => 'active = ?',
            'where'  => 'id = ?',
            'data'   => [0, $array[0]]
        ]);
    }

    function lsAccounts($array) {
        $query = "
            SELECT id, name AS valor
            FROM {$this->bd}gl_account
            WHERE active = ?
            ORDER BY name ASC
        ";
        return $this->_Read($query, $array);
    }

    function lsSubaccounts($array) {
        $where = '1=1';
        $data  = [];

        if (!empty($array['gl_account_id'])) {
            $where  .= ' AND gl_account_id = ?';
            $data[]  = $array['gl_account_id'];
        }

        $query = "
            SELECT id, name AS valor, gl_account_id
            FROM {$this->bd}subaccount
            WHERE {$where}
            AND active = 1
            ORDER BY name ASC
        ";
        return $this->_Read($query, $data);
    }

    function lsSuppliers($array) {
        $query = "
            SELECT id, name AS valor
            FROM {$this->bd}supplier
            WHERE active = ?
            ORDER BY name ASC
        ";
        return $this->_Read($query, $array);
    }

    function lsPaymentTypes() {
        $query = "
            SELECT id, name AS valor
            FROM {$this->bd}payment_type
            WHERE active = 1
            ORDER BY name ASC
        ";
        return $this->_Read($query, []);
    }

    function lsPaymentMethods() {
        $query = "
            SELECT id, name AS valor
            FROM {$this->bd}method_pay
            WHERE active = 1
            ORDER BY name ASC
        ";
        return $this->_Read($query, []);
    }

    function lsUDN() {
        $query = "
            SELECT idUDN AS id, UDN AS valor
            FROM udn
            WHERE Stado = 1 AND idUDN NOT IN (8, 10, 7)
            ORDER BY UDN DESC
        ";
        return $this->_Read($query, []);
    }

    function getPurchaseCounts($array) {
        $where = '1=1';
        $data  = [];

        if (!empty($array['udn'])) {
            $where  .= ' AND dc.udn_id = ?';
            $data[]  = $array['udn'];
        }

        if (!empty($array['fi']) && !empty($array['ff'])) {
            $where  .= ' AND DATE(dc.created_at) BETWEEN ? AND ?';
            $data[]  = $array['fi'];
            $data[]  = $array['ff'];
        }

        $query = "
            SELECT 
                SUM(p.total) AS total,
                SUM(CASE WHEN pt.name = 'Fondo Fijo' THEN p.total ELSE 0 END) AS fondo_fijo,
                SUM(CASE WHEN pt.name = 'Crédito' THEN p.total ELSE 0 END) AS credito,
                SUM(CASE WHEN pt.name = 'Corporativo' THEN p.total ELSE 0 END) AS corporativo
            FROM {$this->bd}purchase p
            LEFT JOIN {$this->bd}payment_type pt ON p.payment_type_id = pt.id
            LEFT JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            WHERE {$where}
            AND p.active = 1
        ";

        $result = $this->_Read($query, $data);
        return !empty($result) ? $result[0] : [
            'total'       => 0,
            'fondo_fijo'  => 0,
            'credito'     => 0,
            'corporativo' => 0
        ];
    }

    // Concentrado - Métodos de consulta

    function listAccountsWithPurchases($array) {
        $query = "
            SELECT DISTINCT
                ga.id AS account_id,
                ga.name AS account_name
            FROM {$this->bd}purchase p
            INNER JOIN {$this->bd}gl_account ga ON p.gl_account_id = ga.id
            INNER JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            WHERE dc.udn_id = ?
            AND DATE(dc.created_at) BETWEEN ? AND ?
            AND p.active = 1
            ORDER BY ga.name ASC
        ";
        return $this->_Read($query, [
            $array['udn'],
            $array['fi'],
            $array['ff']
        ]);
    }

    function listSubaccountsByAccount($array) {
        $query = "
            SELECT DISTINCT
                sa.id AS subaccount_id,
                sa.name AS subaccount_name,
                sa.gl_account_id AS account_id
            FROM {$this->bd}purchase p
            INNER JOIN {$this->bd}subaccount sa ON p.subaccount_id = sa.id
            INNER JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            WHERE p.gl_account_id = ?
            AND dc.udn_id = ?
            AND DATE(dc.created_at) BETWEEN ? AND ?
            AND p.active = 1
            ORDER BY sa.name ASC
        ";
        return $this->_Read($query, [
            $array['account_id'],
            $array['udn'],
            $array['fi'],
            $array['ff']
        ]);
    }

    function getPurchaseByDate($array) {
        $query = "
            SELECT IFNULL(SUM(p.total), 0) AS total
            FROM {$this->bd}purchase p
            INNER JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            WHERE p.subaccount_id = ?
            AND DATE(dc.created_at) = ?
            AND p.active = 1
        ";
        $result = $this->_Read($query, [
            $array['subaccount_id'],
            $array['fecha']
        ]);
        return $result[0]['total'];
    }

    function getAccountTotalByDate($array) {
        $query = "
            SELECT IFNULL(SUM(p.total), 0) AS total
            FROM {$this->bd}purchase p
            INNER JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            WHERE p.gl_account_id = ?
            AND DATE(dc.created_at) = ?
            AND p.active = 1
        ";
        $result = $this->_Read($query, [
            $array['account_id'],
            $array['fecha']
        ]);
        return $result[0]['total'];
    }

    function getSubtotalByDate($array) {
        $query = "
            SELECT IFNULL(SUM(p.subtotal), 0) AS subtotal
            FROM {$this->bd}purchase p
            INNER JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            WHERE p.subaccount_id = ?
            AND DATE(dc.created_at) = ?
            AND p.active = 1
        ";
        $result = $this->_Read($query, [
            $array['subaccount_id'],
            $array['fecha']
        ]);
        return $result[0]['subtotal'];
    }

    function getTaxByDate($array) {
        $query = "
            SELECT IFNULL(SUM(p.tax), 0) AS tax
            FROM {$this->bd}purchase p
            INNER JOIN {$this->bd}daily_closure dc ON p.daily_closure_id = dc.id
            WHERE p.subaccount_id = ?
            AND DATE(dc.created_at) = ?
            AND p.active = 1
        ";
        $result = $this->_Read($query, [
            $array['subaccount_id'],
            $array['fecha']
        ]);
        return $result[0]['tax'];
    }

    // Daily Closure

    function getDailyClosureByDate($array) {
        $query = "
            SELECT 
                id, udn_id, employee_id, total_sale_without_tax, total_sale,
                subtotal, tax, cash, bank, credit_consumer, credit_payment,
                total_payment, difference, created_at, turn, total_suite
            FROM {$this->bd}daily_closure
            WHERE DATE(created_at) = ?
            AND udn_id = ?
            ORDER BY created_at DESC
            LIMIT 1
        ";

        $result = $this->_Read($query, $array);
        return !empty($result) ? $result[0] : null;
    }

    function createDailyClosure($array) {
        return $this->_Insert([
            'table'  => $this->bd . 'daily_closure',
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function createFile($array) {
        return $this->_Insert([
            'table'  => $this->bd . 'file',
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }
}
```

---

## 🔧 Reglas de Implementación

### ✅ OBLIGATORIO

1. **Estructura MVC:**
   - SIEMPRE usar la arquitectura MVC (Modelo-Vista-Controlador)
   - Frontend extiende `Templates` de CoffeeSoft
   - Controlador extiende `mdl` (modelo)
   - Modelo extiende `CRUD`

2. **Nomenclatura:**
   - Controlador: `ctrl-[nombre].php`
   - Modelo: `mdl-[nombre].php`
   - Frontend: `[nombre].js`
   - Métodos del controlador: `init()`, `ls()`, `showPurchase()`, `addPurchase()`, `editPurchase()`, `deletePurchase()`, `getPurchase()`
   - Métodos del modelo: `listPurchases()`, `getPurchaseById()`, `createPurchase()`, `updatePurchase()`, `deletePurchaseById()`, `getPurchaseCounts()`

3. **Componentes CoffeeSoft:**
   - `createLayout()` para estructura personalizada
   - `primaryLayout()` para estructura principal
   - `createfilterBar()` para filtros
   - `createTable()` para tablas
   - `createModalForm()` para formularios modales
   - `swalQuestion()` para confirmaciones
   - `useFetch()` para peticiones AJAX
   - `infoCard()` para KPIs

4. **Filtros y Selects:**
   - Usar `option_select()` con Select2 para selects dinámicos
   - Cargar subcuentas dinámicamente según cuenta seleccionada
   - Habilitar/deshabilitar método de pago según tipo de compra

### ❌ PROHIBIDO

1. **NO** usar `try-catch` en ningún archivo
2. **NO** usar `??` o `isset()` con variables `$_POST`
3. **NO** modificar la estructura del pivote
4. **NO** cambiar nombres de métodos establecidos
5. **NO** omitir validaciones de datos

---

## 📊 Flujo de Operaciones

### Flujo de Listado
```
Usuario → filterBar → lsPurchases() → AJAX → ctrl::ls() → mdl::listPurchases() → SQL → Respuesta → Tabla
```

### Flujo de Agregar Compra
```
Usuario → addPurchase() → Modal Form → Validar → AJAX → ctrl::addPurchase() → 
mdl::createPurchase() → SQL → Respuesta → Actualizar tabla y KPIs
```

### Flujo de Editar Compra
```
Usuario → editPurchase(id) → AJAX → ctrl::getPurchase() → Modal Form con autofill → 
Validar → AJAX → ctrl::editPurchase() → mdl::updatePurchase() → Actualizar tabla y KPIs
```

### Flujo de Eliminar Compra
```
Usuario → deletePurchase(id) → SweetAlert → Confirmar → AJAX → ctrl::deletePurchase() → 
mdl::deletePurchaseById() → Soft delete (active=0) → Actualizar tabla y KPIs
```

### Flujo de Concentrado
```
Usuario → toggleView() → consolidated.render() → lsConsolidated() → AJAX → 
ctrl::lsConsolidated() → Generar matriz por cuenta/fecha → createCoffeTable2()
```

---

## 🎨 Componentes Visuales

### KPI Cards
- Estilo: `style: "file"`
- Tema: `theme: "light"`
- Grid: `grid-cols-4`
- Colores por tipo:
  - Total: Gris (`border-gray-300`, `text-gray-700`)
  - Fondo Fijo: Verde (`bg-green-100`, `text-green-700`)
  - Crédito: Rojo (`bg-red-100`, `text-red-700`)
  - Corporativo: Verde (`bg-green-100`, `text-green-700`)

### Tabla de Compras
- Tema: `theme: 'corporativo'`
- Paginación: 25 registros por página
- DataTables: Habilitado
- Columnas centradas: [1, 5]
- Columnas alineadas a derecha: [7]
- Striped: Habilitado

### Tabla Concentrado
- Tema: `theme: 'light'`
- Componente: `createCoffeTable2()`
- Collapsed: Habilitado
- Folding: Habilitado
- Color grupo: `bg-blue-50`
- Columna fija: [1]

### Badges de Tipo de Compra
- Fondo Fijo: `bg-green-100 text-green-700`
- Crédito: `bg-red-100 text-red-700`
- Corporativo: `bg-blue-100 text-blue-700`

### Modal de Detalle
- Secciones: Información de Cuenta, Información de Facturación, Descripción, Resumen Financiero
- Grid: `grid-cols-2` para campos
- Separadores: `border-t` entre secciones

---

## 📝 Notas Importantes

1. **Tipos de Pago:**
   - Fondo Fijo: Pago en efectivo del fondo de caja
   - Crédito: Compra a crédito con proveedor
   - Corporativo: Pago con tarjeta corporativa (requiere método de pago)

2. **Método de Pago:**
   - Solo se habilita cuando el tipo de compra es "Corporativo"
   - Se deshabilita automáticamente para otros tipos

3. **Cuentas y Subcuentas:**
   - Las subcuentas se filtran dinámicamente según la cuenta seleccionada
   - Usar `gl_account_id` para relacionar subcuentas

4. **Daily Closure:**
   - Cada compra se asocia a un cierre diario (`daily_closure_id`)
   - Si no existe cierre para la fecha, se crea automáticamente

5. **Concentrado:**
   - Agrupa compras por cuenta contable y fecha
   - Muestra subcuentas colapsables bajo cada cuenta
   - Calcula totales por columna (fecha) y por fila (cuenta)

6. **Subida de Archivos:**
   - Soporta múltiples archivos
   - Extensiones permitidas: PDF, Excel, Word, imágenes
   - Se asocian al `daily_closure_id` y `section_id`

---

## 🔄 Mantenimiento

Este pivote es **INMUTABLE**. Cualquier cambio debe:
1. Ser discutido con el equipo
2. Actualizarse en TODOS los proyectos que lo usen
3. Documentarse en este archivo

---

**Versión:** 1.0  
**Última actualización:** 2025-01-11  
**Autor:** CoffeeIA ☕  
**Estado:** Aprobado para producción  
**Proyecto Base:** finanzas/consulta_respaldo/compras

---

## 📚 Referencias

- **CoffeeSoft Framework:** `src/js/coffeSoft.js`
- **Plugins jQuery:** `src/js/plugins.js`
- **CRUD Base:** `conf/_CRUD.php`
- **Utilidades:** `conf/_Utileria.php`
- **Documentación:** `DOC-COFFEESOFT.md`
- **Pivote File Manager:** `PIVOTE-FILE-MANAGER.md`

---

**Fin del Pivote Compras** 🛒☕
