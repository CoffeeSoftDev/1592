let api = 'ctrl/ctrl-coffee.php';
let app;

$(function () {
    app = new App(api, 'root');
    app.init();
});

class App extends Templates {

    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "coffee";
    }

    init() {
        this.render();
    }

    render() {
        this.layoutMain();
    }

    layoutMain() {
        $("#root").html("");

        this.tabLayout({
            parent: "root",
            id: "showcaseTabs",
            type: "button",
            theme: "light",
            class: "mb-3",
            json: [
                { id: "templates", tab: "Templates (Layouts)", icon: "icon-layout", active: true, onClick: () => this.renderTemplates() },
                { id: "components", tab: "Components", icon: "icon-th-large", onClick: () => this.renderComponents() },
                { id: "complements", tab: "Complements", icon: "icon-wrench", onClick: () => this.renderComplements() },
                { id: "plugins", tab: "Plugins (jQuery)", icon: "icon-plug", onClick: () => this.renderPlugins() },
                { id: "globals", tab: "Funciones Globales", icon: "icon-globe", onClick: () => this.renderGlobals() },
            ]
        });

        $(`<div id="showcaseContent" class="p-2"></div>`).appendTo("#root");
        this.renderTemplates();
    }

    // ===================== TEMPLATES =====================

    renderTemplates() {
        $("#showcaseContent").html("");

        this.tabLayout({
            parent: "showcaseContent",
            id: "templatesTabs",
            type: "large",
            theme: "light",
            class: "mb-2",
            json: [
                { id: "tpl-primary", tab: "primaryLayout", active: true, onClick: () => this.demoPrimaryLayout() },
                { id: "tpl-split", tab: "splitLayout", onClick: () => this.demoSplitLayout() },
                { id: "tpl-vertical", tab: "verticalLinearLayout", onClick: () => this.demoVerticalLinearLayout() },
                { id: "tpl-secondary", tab: "secondaryLayout", onClick: () => this.demoSecondaryLayout() },
                { id: "tpl-createLayout", tab: "createLayout", onClick: () => this.demoCreateLayout() },
                { id: "tpl-tabsLayout", tab: "tabsLayout", onClick: () => this.demoTabsLayout() },
            ]
        });

        $(`<div id="templateDemo" class="mt-3 border rounded-lg p-3 bg-gray-50 min-h-[400px]"></div>`).appendTo("#showcaseContent");
        this.demoPrimaryLayout();
    }

    demoPrimaryLayout() {
        $("#templateDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">primaryLayout(options)</h3>
                <p class="text-sm text-gray-600">Layout principal con filterBar superior y container. Es el layout mas utilizado para modulos con tablas y filtros.</p>
                <code class="text-xs bg-gray-200 px-2 py-1 rounded">Genera: filterBar + container</code>
            </div>
            <div id="demoPrimary" class="border rounded bg-white p-2 min-h-[300px]"></div>
        `);

        this.primaryLayout({
            parent: "demoPrimary",
            id: "examplePrimary",
            class: "flex p-2",
            card: {
                filterBar: { class: 'w-full mb-2', id: 'filterBarexamplePrimary' },
                container: { class: 'w-full h-full', id: 'containerexamplePrimary' }
            }
        });

        $("#filterBarexamplePrimary").html(`<div class="bg-blue-50 border border-blue-200 rounded p-3 text-blue-700 text-sm"><i class="icon-filter"></i> filterBar${this.PROJECT_NAME} - Aqui van los filtros (createfilterBar)</div>`);
        $("#containerexamplePrimary").html(`<div class="bg-green-50 border border-green-200 rounded p-3 text-green-700 text-sm h-[200px] flex items-center justify-center"><i class="icon-th-large"></i> container${this.PROJECT_NAME} - Aqui va la tabla (createTable)</div>`);
    }

    demoSplitLayout() {
        $("#templateDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">splitLayout(options)</h3>
                <p class="text-sm text-gray-600">Layout dividido en 2 columnas con filterBar y footer. Ideal para vistas de detalle con panel lateral.</p>
                <code class="text-xs bg-gray-200 px-2 py-1 rounded">Genera: filterBar + left/right containers + footer</code>
            </div>
            <div id="demoSplit" class="border rounded bg-white p-2 min-h-[300px]"></div>
        `);

        this.splitLayout({
            parent: "demoSplit",
            id: "exSplit",
            filterBar: { id: 'filterBarexSplit', class: 'w-full mb-2' },
            container: {
                id: 'containerexSplit',
                class: 'flex w-full flex-grow gap-2',
                children: [
                    { class: 'w-1/2', id: 'leftexSplit' },
                    { class: 'w-1/2', id: 'rightexSplit' }
                ]
            },
            footer: { id: 'footerexSplit', class: 'w-full mt-2' }
        });

        $("#filterBarexSplit").html(`<div class="bg-blue-50 border border-blue-200 rounded p-2 text-blue-700 text-sm"><i class="icon-filter"></i> filterBar</div>`);
        $("#leftexSplit").html(`<div class="bg-purple-50 border border-purple-200 rounded p-3 text-purple-700 text-sm h-[150px] flex items-center justify-center">Panel Izquierdo</div>`);
        $("#rightexSplit").html(`<div class="bg-orange-50 border border-orange-200 rounded p-3 text-orange-700 text-sm h-[150px] flex items-center justify-center">Panel Derecho</div>`);
        $("#footerexSplit").html(`<div class="bg-gray-100 border border-gray-300 rounded p-2 text-gray-600 text-sm text-center">Footer</div>`);
    }

    demoVerticalLinearLayout() {
        $("#templateDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">verticalLinearLayout(options)</h3>
                <p class="text-sm text-gray-600">Layout vertical con 3 secciones apiladas: filterBar, container y footer. Ideal para reportes o dashboards.</p>
                <code class="text-xs bg-gray-200 px-2 py-1 rounded">Genera: filterBar + container + footer (apilados)</code>
            </div>
            <div id="demoVertical" class="border rounded bg-white p-2 min-h-[300px]"></div>
        `);

        this.verticalLinearLayout({
            parent: "demoVertical",
            id: "exVertical",
            className: "flex m-2",
            card: {
                id: "singleLayoutVert",
                className: "w-full",
                filterBar: { className: 'w-full mb-2', id: 'filterBarVert' },
                container: { className: 'w-full mb-2', id: 'containerVert' },
                footer: { className: 'w-full', id: 'footerVert' },
            }
        });

        $("#filterBarVert").html(`<div class="bg-blue-50 border border-blue-200 rounded p-2 text-blue-700 text-sm"><i class="icon-filter"></i> filterBar</div>`);
        $("#containerVert").html(`<div class="bg-green-50 border border-green-200 rounded p-3 text-green-700 text-sm h-[150px] flex items-center justify-center">Container principal</div>`);
        $("#footerVert").html(`<div class="bg-yellow-50 border border-yellow-200 rounded p-2 text-yellow-700 text-sm text-center"><i class="icon-doc-text"></i> Footer - Totales o resumen</div>`);
    }

    demoSecondaryLayout() {
        $("#templateDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">secondaryLayout(options)</h3>
                <p class="text-sm text-gray-600">Layout con formulario lateral (col-5) y tabla (col-7). Ideal para modulos CRUD con formulario siempre visible.</p>
                <code class="text-xs bg-gray-200 px-2 py-1 rounded">Genera: cardform (col-5) + cardtable con filterBar y container (col-7)</code>
            </div>
            <div id="demoSecondary" class="border rounded bg-white p-2 min-h-[300px]"></div>
        `);

        this.secondaryLayout({
            parent: "demoSecondary",
            id: "exSecondary",
            className: "flex p-2 gap-2",
            cardtable: {
                className: 'col-7',
                id: 'containerTableexSecondary',
                filterBar: { id: 'filterTableSec', className: 'col-12 mb-2' },
                container: { id: 'listTableSec', className: 'col-12' },
            },
            cardform: {
                className: 'col-5',
                id: 'containerFormexSecondary',
            },
        });

        $("#containerFormexSecondary").html(`<div class="bg-purple-50 border border-purple-200 rounded p-3 text-purple-700 text-sm h-[200px] flex items-center justify-center"><i class="icon-doc-text"></i> Formulario (cardform)</div>`);
        $("#filterTableSec").html(`<div class="bg-blue-50 border border-blue-200 rounded p-2 text-blue-700 text-sm"><i class="icon-filter"></i> filterBar tabla</div>`);
        $("#listTableSec").html(`<div class="bg-green-50 border border-green-200 rounded p-3 text-green-700 text-sm h-[150px] flex items-center justify-center"><i class="icon-th"></i> Tabla (container)</div>`);
    }

    demoCreateLayout() {
        $("#templateDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createLayout(options)</h3>
                <p class="text-sm text-gray-600">Layout generico que permite crear estructuras personalizadas con divs anidados. Es la base sobre la que se construyen los demas layouts.</p>
                <code class="text-xs bg-gray-200 px-2 py-1 rounded">Genera: Estructura personalizada de divs</code>
            </div>
            <div id="demoCustomLayout" class="border rounded bg-white p-2 min-h-[300px]"></div>
        `);

        this.createLayout({
            parent: "demoCustomLayout",
            design: false,
            data: {
                id: "customGrid",
                class: "flex gap-2 p-2",
                container: [
                    {
                        type: "div",
                        id: "panelA",
                        class: "w-1/3 bg-indigo-50 border border-indigo-200 rounded p-3",
                        children: [
                            { id: "subA1", class: "mb-2 bg-indigo-100 rounded p-2 text-indigo-700 text-sm" },
                            { id: "subA2", class: "bg-indigo-100 rounded p-2 text-indigo-700 text-sm" }
                        ]
                    },
                    {
                        type: "div",
                        id: "panelB",
                        class: "w-2/3 bg-teal-50 border border-teal-200 rounded p-3"
                    }
                ]
            }
        });

        $("#subA1").html("Sub-panel A1");
        $("#subA2").html("Sub-panel A2");
        $("#panelB").html(`<div class="text-teal-700 text-sm h-[200px] flex items-center justify-center">Panel B - Contenido principal</div>`);
    }

    demoTabsLayout() {
        $("#templateDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">tabsLayout(options)</h3>
                <p class="text-sm text-gray-600">Layout basado en pestanas Bootstrap (nav-tabs). Usa simple_json_tab internamente. Para tabs modernos usa tabLayout (clase Components).</p>
                <code class="text-xs bg-gray-200 px-2 py-1 rounded">Genera: nav-tabs con contenedores tab-pane</code>
            </div>
            <div id="demoTabsLayoutContainer" class="border rounded bg-white p-2 min-h-[200px]"></div>
        `);

        this.tabsLayout({
            parent: "demoTabsLayoutContainer",
            id: "exTabs",
            json: [
                { tab: "Ventas", id: "tab-ventas", active: true },
                { tab: "Compras", id: "tab-compras" },
                { tab: "Inventario", id: "tab-inventario" },
            ]
        });

        $("#tab-ventas").html(`<div class="p-3 text-gray-600 text-sm">Contenido del tab Ventas</div>`);
        $("#tab-compras").html(`<div class="p-3 text-gray-600 text-sm">Contenido del tab Compras</div>`);
        $("#tab-inventario").html(`<div class="p-3 text-gray-600 text-sm">Contenido del tab Inventario</div>`);
    }

    // ===================== COMPONENTS =====================

    renderComponents() {
        $("#showcaseContent").html("");

        this.tabLayout({
            parent: "showcaseContent",
            id: "componentsTabs",
            type: "large",
            theme: "light",
            class: "mb-2",
            json: [
                { id: "cmp-table", tab: "createTable", active: true, onClick: () => this.demoCreateTable() },
                { id: "cmp-coffetable", tab: "createCoffeTable", onClick: () => this.demoCoffeTable() },
                { id: "cmp-form", tab: "createForm", onClick: () => this.demoCreateForm() },
                { id: "cmp-modalform", tab: "createModalForm", onClick: () => this.demoCreateModalForm() },
                { id: "cmp-filterbar", tab: "createfilterBar", onClick: () => this.demoFilterBar() },
                { id: "cmp-tablayout", tab: "tabLayout", onClick: () => this.demoTabLayout() },
                { id: "cmp-detailcard", tab: "detailCard", onClick: () => this.demoDetailCard() },
                { id: "cmp-itemcard", tab: "createItemCard", onClick: () => this.demoItemCard() },
                { id: "cmp-swalq", tab: "swalQuestion", onClick: () => this.demoSwalQuestion() },
                { id: "cmp-tableform", tab: "createTableForm", onClick: () => this.demoTableForm() },
                { id: "cmp-modal", tab: "createModal", onClick: () => this.demoCreateModal() },
                { id: "cmp-form2", tab: "form()", onClick: () => this.demoFormMethod() },
                { id: "cmp-coffeeform", tab: "coffeeForm ☕", onClick: () => this.demoCoffeeForm() },
            ]
        });

        $(`<div id="componentDemo" class="mt-3 border rounded-lg p-3 bg-gray-50 min-h-[400px]"></div>`).appendTo("#showcaseContent");
        this.demoCreateTable();
    }

    demoCreateTable() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createTable(options)</h3>
                <p class="text-sm text-gray-600">Genera tablas dinamicas con datos del backend. Soporta filterBar, datatable, paginacion y temas CoffeeSoft (createCoffeTable3).</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, idFilterBar, data, coffeesoft, conf{datatable, pag}, attr{id, theme, title, subtitle, center, right, extends}</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>this.createTable({
    parent: 'container' + this.PROJECT_NAME,
    idFilterBar: 'filterBar' + this.PROJECT_NAME,
    data: { opc: "ls", fi: rangePicker.fi, ff: rangePicker.ff },
    conf: { datatable: true, pag: 15 },
    coffeesoft: true,
    attr: {
        id: 'tbProducts',
        theme: 'shadcdn',        // 'light' | 'dark' | 'corporativo' | 'shadcdn'
        title: 'Lista de Productos',
        subtitle: 'Productos registrados',
        center: [1, 2],
        right: [4, 5],
        extends: true,
    },
});</code></pre>
                <p class="text-xs text-gray-500 mt-2">Cuando <strong>coffeesoft: true</strong>, usa internamente createCoffeTable3 para renderizar. Si es false, usa rpt_json_table2.</p>
            </div>
        `);
    }

    demoCoffeTable() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createCoffeTable(options) / createCoffeTable2 / createCoffeTable3</h3>
                <p class="text-sm text-gray-600">Tablas estilizadas con TailwindCSS. Soportan temas, colgroup, striped, hover, fixed columns, folding y selectable.</p>
                <p class="text-xs text-gray-500 mt-1">Temas: light, dark, corporativo, shadcdn</p>
            </div>
            <div id="coffeTableDemo" class="bg-white rounded p-3 border"></div>
        `);

        this.createCoffeTable({
            parent: "coffeTableDemo",
            id: "tbShowcase",
            theme: "corporativo",
            title: "Ejemplo CoffeTable",
            subtitle: "Datos de ejemplo con tema corporativo",
            center: [1, 3],
            right: [2],
            f_size: 13,
            hover: true,
            striped: true,
            data: {
                row: [
                    { id: 1, Producto: "Cafe Colombiano", Precio: "$150.00", Stock: "200 pzas", Estado: '<span class="badge bg-success">Activo</span>' },
                    { id: 2, Producto: "Cafe Mexicano", Precio: "$120.00", Stock: "150 pzas", Estado: '<span class="badge bg-success">Activo</span>' },
                    { id: 3, Producto: "Cafe Brasileno", Precio: "$180.00", Stock: "80 pzas", Estado: '<span class="badge bg-warning text-dark">Bajo</span>' },
                    { id: 4, Producto: "Te Verde", Precio: "$90.00", Stock: "300 pzas", Estado: '<span class="badge bg-success">Activo</span>' },
                    { id: 5, Producto: "Chocolate Caliente", Precio: "$110.00", Stock: "0 pzas", Estado: '<span class="badge bg-danger">Agotado</span>' },
                ]
            }
        });
    }

    demoCreateForm() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createForm(options)</h3>
                <p class="text-sm text-gray-600">Genera formularios dinamicos con validacion automatica. Usa type:'div' por defecto (sin boton submit). Soporta autofill para edicion.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, id, data{opc}, autofill, json[], success(response)</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Formulario generado:</h4>
                    <div id="formShowcase" class="bg-white rounded p-3 border"></div>
                </div>
                <div class="w-1/2">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Codigo:</h4>
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto h-[350px]"><code>this.createForm({
    parent: "formShowcase",
    id: "frmExample",
    data: { opc: "addShowcase" },
    json: [
        { opc: "label", id: "lblInfo", text: "Datos generales",
          class: "col-12 fw-bold text-lg mb-2 p-1" },
        { opc: "input", lbl: "Nombre", id: "name",
          tipo: "texto", class: "col-12 col-sm-6 mb-3" },
        { opc: "input", lbl: "Correo", id: "email",
          tipo: "email", class: "col-12 col-sm-6 mb-3" },
        { opc: "input", lbl: "Precio", id: "price",
          tipo: "cifra", class: "col-12 col-sm-6 mb-3" },
        { opc: "input", lbl: "Fecha", id: "date",
          type: "date", class: "col-12 col-sm-6 mb-3" },
        { opc: "select", lbl: "Categoria", id: "category",
          class: "col-12 col-sm-6 mb-3",
          data: [{id:1, valor:"Bebidas"}, {id:2, valor:"Alimentos"}] },
        { opc: "textarea", id: "notes", lbl: "Notas",
          rows: 3, class: "col-12 mb-3" },
    ],
    success: (response) => {
        if (response.status == 200) {
            alert({ icon: "success", text: response.message });
        }
    }
});</code></pre>
                </div>
            </div>
        `);

        this.createForm({
            parent: "formShowcase",
            id: "frmExample",
            data: { opc: "addShowcase" },
            json: [
                { opc: "label", id: "lblInfo", text: "Datos generales", class: "col-12 fw-bold text-lg mb-2 p-1" },
                { opc: "input", lbl: "Nombre", id: "name", tipo: "texto", class: "col-12 col-sm-6 mb-3" },
                { opc: "input", lbl: "Correo", id: "email", tipo: "email", class: "col-12 col-sm-6 mb-3" },
                { opc: "input", lbl: "Precio", id: "price", tipo: "cifra", class: "col-12 col-sm-6 mb-3" },
                { opc: "input", lbl: "Fecha", id: "date_example", type: "date", class: "col-12 col-sm-6 mb-3" },
                { opc: "select", lbl: "Categoria", id: "category", class: "col-12 col-sm-6 mb-3", data: [{ id: 1, valor: "Bebidas" }, { id: 2, valor: "Alimentos" }] },
                { opc: "textarea", id: "notes", lbl: "Notas", rows: 3, class: "col-12 mb-3" },
            ],
            success: (response) => {
                if (response.status == 200) {
                    alert({ icon: "success", text: response.message, btn1: true, btn1Text: "Ok" });
                }
            }
        });
    }

    demoCreateModalForm() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createModalForm(options)</h3>
                <p class="text-sm text-gray-600">Crea un formulario dentro de un modal bootbox. Agrega automaticamente botones Aceptar/Cancelar. Valida y envia datos al backend via useFetch.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: id, bootbox{title, closeButton}, data{opc}, autofill, json[], dynamicValues, success(response)</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <button class="btn btn-primary btn-sm" id="btnOpenModal"><i class="icon-plus"></i> Abrir Modal Form</button>
                <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto mt-3"><code>this.createModalForm({
    id: 'frmModal',
    bootbox: { title: 'Agregar Registro', closeButton: true },
    data: { opc: 'addShowcase' },
    autofill: false,
    json: [
        { opc: "input", id: "name", lbl: "Nombre", class: "col-12", tipo: "texto" },
        { opc: "input", id: "price", lbl: "Precio", class: "col-6", tipo: "cifra" },
        { opc: "select", id: "category", lbl: "Categoria", class: "col-6",
          data: [{id:1, valor:"A"}, {id:2, valor:"B"}] },
        { opc: "textarea", id: "description", lbl: "Descripcion", class: "col-12" },
    ],
    success: (response) => { console.log(response); }
});</code></pre>
            </div>
        `);

        $("#btnOpenModal").on("click", () => {
            this.createModalForm({
                id: 'frmModalShowcase',
                bootbox: { title: 'Agregar Registro (Ejemplo)', closeButton: true },
                data: { opc: 'addShowcase' },
                autofill: false,
                json: [
                    { opc: "input", id: "name", lbl: "Nombre", class: "col-12", tipo: "texto" },
                    { opc: "input", id: "price", lbl: "Precio", class: "col-6", tipo: "cifra" },
                    { opc: "select", id: "category", lbl: "Categoria", class: "col-6", data: [{ id: 1, valor: "Bebidas" }, { id: 2, valor: "Alimentos" }] },
                    { opc: "textarea", id: "description", lbl: "Descripcion", class: "col-12" },
                ],
                success: (response) => {
                    alert({ icon: "info", title: "Respuesta del servidor", text: JSON.stringify(response), btn1: true, btn1Text: "Ok" });
                }
            });
        });
    }

    demoFilterBar() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createfilterBar(options)</h3>
                <p class="text-sm text-gray-600">Barra de filtros reutilizable. Genera inputs, selects, calendarios y botones usando content_json_form internamente.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, id, data[] (usa mismos tipos que formularios: input, select, input-calendar, btn, etc.)</p>
            </div>
            <div id="filterBarDemo" class="bg-white rounded p-3 border mb-3"></div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>this.createfilterBar({
    parent: 'filterBar' + this.PROJECT_NAME,
    data: [
        { opc: "input-calendar", class: "col-sm-4", id: "calendar" + this.PROJECT_NAME, lbl: "Consultar fecha:" },
        { opc: "select", id: "filterStatus", lbl: "Estado", class: "col-sm-3",
          data: [{id:1, valor:"Activo"}, {id:2, valor:"Inactivo"}],
          onchange: "app.ls()" },
        { opc: "btn", class: "col-sm-2", color_btn: "primary", id: "btnSearch", text: "Buscar",
          fn: "app.ls()" },
    ],
});

dataPicker({ parent: "calendar" + this.PROJECT_NAME, onSelect: () => this.ls() });</code></pre>
        `);

        this.createfilterBar({
            parent: 'filterBarDemo',
            data: [
                { opc: "input-calendar", class: "col-sm-4", id: "calendarShowcase", lbl: "Consultar fecha:" },
                { opc: "select", id: "filterStatusDemo", lbl: "Estado", class: "col-sm-3", data: [{ id: 1, valor: "Activo" }, { id: 2, valor: "Inactivo" }] },
                { opc: "btn", class: "col-sm-2", color_btn: "primary", id: "btnSearchDemo", text: "Buscar", fn: "console.log('Buscar')" },
            ],
        });

        dataPicker({ parent: "calendarShowcase" });
    }

    demoTabLayout() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">tabLayout(options)</h3>
                <p class="text-sm text-gray-600">Componente de tabs moderno con TailwindCSS. Soporta 3 tipos (large, short, button) y 2 temas (light, dark). Cada tab ejecuta onClick al ser seleccionado.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, id, type('large'|'short'|'button'), theme('light'|'dark'), json[{id, tab, icon, active, onClick}]</p>
            </div>
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">type: "large" + theme: "light"</h4>
                    <div id="tabLargeDemo"></div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">type: "short" + theme: "dark"</h4>
                    <div id="tabShortDemo"></div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">type: "button"</h4>
                    <div id="tabButtonDemo"></div>
                </div>
            </div>
        `);

        const tabsJson = [
            { id: "demo1", tab: "Dashboard", icon: "icon-home", active: true, onClick: () => {} },
            { id: "demo2", tab: "Reportes", icon: "icon-chart-bar", onClick: () => {} },
            { id: "demo3", tab: "Configuracion", icon: "icon-cog", onClick: () => {} },
        ];

        this.tabLayout({ parent: "tabLargeDemo", id: "tabLarge", type: "large", theme: "light", json: tabsJson });
        this.tabLayout({ parent: "tabShortDemo", id: "tabShort", type: "short", theme: "dark", json: [
            { id: "d1", tab: "Dashboard", icon: "icon-home", active: true, onClick: () => {} },
            { id: "d2", tab: "Reportes", icon: "icon-chart-bar", onClick: () => {} },
            { id: "d3", tab: "Config", icon: "icon-cog", onClick: () => {} },
        ]});
        this.tabLayout({ parent: "tabButtonDemo", id: "tabButton", type: "button", json: [
            { id: "b1", tab: "Efectivo", active: true, onClick: () => {} },
            { id: "b2", tab: "Tarjeta", onClick: () => {} },
            { id: "b3", tab: "Transferencia", onClick: () => {} },
        ]});
    }

    demoDetailCard() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">detailCard(options)</h3>
                <p class="text-sm text-gray-600">Muestra informacion en formato tarjeta con campos clave-valor. Soporta tipos: default, status (badges), observacion (textarea) y div (HTML custom).</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, title, class('cols-2' para 2 columnas), data[{text, value, icon, type, color}]</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2 bg-[#1F2A37] rounded-lg" id="detailCardDemo"></div>
                <div class="w-1/2">
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto h-[300px]"><code>this.detailCard({
    parent: "detailCardDemo",
    class: "cols-2 gap-2",
    data: [
        { text: "Cliente", value: "Juan Perez", icon: "icon-user" },
        { text: "Telefono", value: "555-1234", icon: "icon-phone" },
        { text: "Email", value: "juan@mail.com", icon: "icon-mail" },
        { text: "Total", value: "$15,000.00", icon: "icon-dollar" },
        { type: "status", text: "Estado", value: "Activo",
          color: "bg-green-500 text-white" },
        { type: "observacion", text: "Notas",
          value: "Cliente frecuente desde 2023" },
    ]
});</code></pre>
                </div>
            </div>
        `);

        this.detailCard({
            parent: "detailCardDemo",
            class: "cols-2 gap-2",
            data: [
                { text: "Cliente", value: "Juan Perez", icon: "icon-user" },
                { text: "Telefono", value: "555-1234", icon: "icon-phone" },
                { text: "Email", value: "juan@mail.com", icon: "icon-mail" },
                { text: "Total", value: "$15,000.00", icon: "icon-dollar" },
                { type: "status", text: "Estado", value: "Activo", color: "bg-green-500 text-white" },
                { type: "observacion", text: "Notas", value: "Cliente frecuente desde 2023. Preferencia por cafe colombiano." },
            ]
        });
    }

    demoItemCard() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createItemCard(options)</h3>
                <p class="text-sm text-gray-600">Crea tarjetas de navegacion tipo grid. Ideal para menus de modulos o dashboards. Soporta imagenes, click y enlaces.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, title, json[{titulo, descripcion, imagen, enlace, onClick}]</p>
            </div>
            <div id="itemCardDemo" class="bg-[#1E293B] rounded-lg p-2"></div>
        `);

        this.createItemCard({
            parent: 'itemCardDemo',
            title: 'Modulos del Sistema',
            json: [
                { titulo: "Ventas", descripcion: "Gestion de ventas y POS", onClick: () => alert({ icon: "info", text: "Modulo Ventas", btn1: true, btn1Text: "Ok" }) },
                { titulo: "Compras", descripcion: "Ordenes de compra", onClick: () => alert({ icon: "info", text: "Modulo Compras", btn1: true, btn1Text: "Ok" }) },
                { titulo: "Almacen", descripcion: "Control de inventario", onClick: () => alert({ icon: "info", text: "Modulo Almacen", btn1: true, btn1Text: "Ok" }) },
                { titulo: "Finanzas", descripcion: "Reportes financieros", onClick: () => alert({ icon: "info", text: "Modulo Finanzas", btn1: true, btn1Text: "Ok" }) },
            ]
        });
    }

    demoSwalQuestion() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">swalQuestion(options)</h3>
                <p class="text-sm text-gray-600">Dialogo de confirmacion con SweetAlert2. Al confirmar, envia datos al backend via fn_ajax y ejecuta callbacks.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: opts{title, text, html, icon}, data{opc, ...params}, methods{send(data), request(data)}, extends(bool), fn(string)</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <button class="btn btn-danger btn-sm" id="btnSwalDemo"><i class="icon-trash"></i> Eliminar Registro (Ejemplo)</button>
                <button class="btn btn-warning btn-sm ms-2" id="btnSwalCancel"><i class="icon-cancel"></i> Cancelar Pedido (Ejemplo)</button>
                <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto mt-3"><code>this.swalQuestion({
    opts: {
        title: "Confirmar eliminacion?",
        html: 'Deseas eliminar el registro &lt;strong&gt;#123&lt;/strong&gt;?',
        icon: "warning"
    },
    data: { opc: "deleteShowcase", id: 123 },
    methods: {
        request: (data) => {
            alert({ icon: "success", title: "Eliminado", text: "Registro eliminado.", btn1: true });
            this.ls();
        },
    },
});</code></pre>
            </div>
        `);

        $("#btnSwalDemo").on("click", () => {
            this.swalQuestion({
                opts: {
                    title: "Confirmar eliminacion?",
                    html: 'Deseas eliminar el registro <strong>#123</strong>? (Ejemplo - no se ejecuta)',
                    icon: "warning"
                },
                extends: true,
            });
        });

        $("#btnSwalCancel").on("click", () => {
            this.swalQuestion({
                opts: {
                    title: "Cancelar pedido?",
                    html: 'Deseas cancelar el pedido <strong>FOL-001</strong>?',
                    icon: "question"
                },
                extends: true,
            });
        });
    }

    demoTableForm() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createTableForm(options)</h3>
                <p class="text-sm text-gray-600">Componente combinado: formulario lateral + tabla. El formulario se puede abrir/cerrar. Al enviar el form, la tabla se refresca automaticamente.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, id, title, classForm, table{data, conf}, form{json, success}</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>this.createTableForm({
    parent: 'container',
    id: 'recetas',
    title: 'Agregar Ingrediente',
    table: {
        data: { opc: "lsIngredientes" },
        conf: { datatable: false, pag: 10 },
    },
    form: {
        json: [
            { opc: "input", lbl: "Ingrediente", id: "nombre", class: "col-12", tipo: "texto" },
            { opc: "input", lbl: "Cantidad", id: "cantidad", class: "col-12", tipo: "numero" },
            { opc: "btn-submit", id: "btnAdd", text: "Agregar", class: "col-12" }
        ],
    },
    success: (data) => { console.log("Tabla refrescada"); }
});</code></pre>
        `);
    }

    demoCreateModal() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createModal(options)</h3>
                <p class="text-sm text-gray-600">Modal generico con bootbox. Primero consulta datos al backend y luego abre el modal con el contenido. Ideal para vistas de detalle.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: id, bootbox{title, closeButton, message}, data{opc}, success(data)</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>this.createModal({
    bootbox: {
        title: 'Detalle del Registro',
        closeButton: true,
        message: '&lt;div id="modalContent"&gt;&lt;/div&gt;',
    },
    data: { opc: 'getShowcase', id: 123 },
    success: (data) => {
        this.detailCard({
            parent: "modalContent",
            data: [
                { text: "Nombre", value: data.name },
                { text: "Precio", value: data.price },
            ]
        });
    }
});</code></pre>
        `);
    }

    demoFormMethod() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">form(options)</h3>
                <p class="text-sm text-gray-600">Genera formularios sin validacion automatica ni contenedor predefinido. Crea elementos HTML directamente en el DOM. Ideal para formularios de solo lectura o filtros avanzados.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, id, class, json[{opc, id, lbl, tipo, data}]</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Formulario generado:</h4>
                    <div id="formMethodDemo" class="bg-white rounded p-3 border"></div>
                </div>
                <div class="w-1/2">
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>this.form({
    parent: "formMethodDemo",
    id: "simpleForm",
    class: "row",
    json: [
        { opc: "input", id: "search", lbl: "Buscar",
          class: "col-6" },
        { opc: "select", id: "type", lbl: "Tipo",
          class: "col-6",
          data: [{id:1, valor:"Producto"},
                 {id:2, valor:"Servicio"}] },
        { opc: "input-calendar", id: "dateRange",
          lbl: "Periodo", class: "col-6" },
        { opc: "textarea", id: "obs", lbl: "Observaciones",
          class: "col-12" },
    ]
});</code></pre>
                </div>
            </div>
        `);

        this.form({
            parent: "formMethodDemo",
            id: "simpleFormDemo",
            class: "row",
            json: [
                { opc: "input", id: "searchDemo", lbl: "Buscar", class: "col-6" },
                { opc: "select", id: "typeDemo", lbl: "Tipo", class: "col-6", data: [{ id: 1, valor: "Producto" }, { id: 2, valor: "Servicio" }] },
                { opc: "input-calendar", id: "dateRangeDemo", lbl: "Periodo", class: "col-6" },
                { opc: "textarea", id: "obsDemo", lbl: "Observaciones", class: "col-12" },
            ]
        });
    }

    demoCoffeeForm() {
        $("#componentDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">coffeeForm(options) <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Tailwind Native</span></h3>
                <p class="text-sm text-gray-600">Genera formularios usando exclusivamente TailwindCSS. Vive dentro de la clase Components (no es plugin jQuery). Light theme por defecto. No depende de Bootstrap ni de content_json_form.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, id, class, prefijo, data[], Element, required, theme ('light'|'dark')</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Formulario Tailwind (light - default):</h4>
                    <div id="coffeeFormDemo" class="bg-white rounded-lg p-4 border border-gray-300"></div>
                    <h4 class="text-sm font-semibold text-gray-700 mt-4 mb-2">Formulario Tailwind (dark):</h4>
                    <div id="coffeeFormDemoDark" class="bg-gray-900 rounded-lg p-4 border border-gray-700"></div>
                    <h4 class="text-sm font-semibold text-gray-700 mt-4 mb-2">VS createForm (Bootstrap):</h4>
                    <div id="bootstrapFormCompare" class="bg-white rounded p-3 border"></div>
                </div>
                <div class="w-1/2">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Codigo coffeeForm:</h4>
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto h-[280px]"><code>this.coffeeForm({
    parent: "coffeeFormDemo",
    id: "frmCoffee",
    theme: "light", // 'light' (default) | 'dark'
    data: [
        { opc: "label", id: "lblSection",
          text: "Datos del Componente",
          class: "w-full px-2" },

        { opc: "input", id: "cf_name",
          lbl: "Nombre", tipo: "texto",
          class: "w-full sm:w-1/2 px-2 mt-1" },

        { opc: "input", id: "cf_version",
          lbl: "Version", tipo: "textoNum",
          class: "w-full sm:w-1/2 px-2 mt-1" },

        { opc: "select", id: "cf_category",
          lbl: "Categoria",
          class: "w-full sm:w-1/3 px-2 mt-1",
          data: [
            { id: "layout", valor: "Layout" },
            { id: "component", valor: "Componente" },
            { id: "utility", valor: "Utilidad" }
          ] },

        { opc: "input-group", id: "cf_price",
          lbl: "Precio", tipo: "cifra",
          icon: "icon-dollar",
          class: "w-full sm:w-1/3 px-2 mt-1" },

        { opc: "input", id: "cf_date",
          lbl: "Fecha", type: "date",
          class: "w-full sm:w-1/3 px-2 mt-1" },

        { opc: "checkbox", id: "cf_active",
          text: "Componente activo",
          class: "w-full sm:w-1/3 px-2 mt-2" },

        { opc: "radio", id: "cf_type_1",
          name: "cf_type", value: "public",
          text: "Publico", checked: true,
          class: "w-full sm:w-1/3 px-2 mt-2" },

        { opc: "radio", id: "cf_type_2",
          name: "cf_type", value: "private",
          text: "Privado",
          class: "w-full sm:w-1/3 px-2 mt-2" },

        { opc: "textarea", id: "cf_notes",
          lbl: "Observaciones", rows: 2,
          class: "w-full px-2 mt-1",
          required: false },

        { opc: "input-calendar", id: "cf_range",
          lbl: "Rango de fechas",
          class: "w-full sm:w-1/2 px-2 mt-1" },

        { opc: "input-file-btn", id: "cf_file",
          lbl: "Archivo",
          class: "w-full sm:w-1/2 px-2 mt-1" },
    ]
});</code></pre>
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <h5 class="text-sm font-bold text-blue-800 mb-1">Clases Tailwind vs Bootstrap</h5>
                        <table class="text-xs w-full">
                            <tr class="border-b"><td class="py-1 text-gray-600 font-mono">w-full</td><td class="py-1">=</td><td class="py-1 text-gray-600 font-mono">col-12</td></tr>
                            <tr class="border-b"><td class="py-1 text-gray-600 font-mono">sm:w-1/2</td><td class="py-1">=</td><td class="py-1 text-gray-600 font-mono">col-sm-6</td></tr>
                            <tr class="border-b"><td class="py-1 text-gray-600 font-mono">sm:w-1/3</td><td class="py-1">=</td><td class="py-1 text-gray-600 font-mono">col-sm-4</td></tr>
                            <tr class="border-b"><td class="py-1 text-gray-600 font-mono">sm:w-1/4</td><td class="py-1">=</td><td class="py-1 text-gray-600 font-mono">col-sm-3</td></tr>
                            <tr><td class="py-1 text-gray-600 font-mono">lg:w-1/4</td><td class="py-1">=</td><td class="py-1 text-gray-600 font-mono">col-lg-3</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        `);

        const formData = [
            { opc: "label", id: "lblSection", text: "Datos del Componente", class: "w-full px-2" },
            { opc: "input", id: "cf_name", lbl: "Nombre", tipo: "texto", class: "w-full sm:w-1/2 px-2 mt-1" },
            { opc: "input", id: "cf_version", lbl: "Version", tipo: "textoNum", class: "w-full sm:w-1/2 px-2 mt-1" },
            { opc: "select", id: "cf_category", lbl: "Categoria", class: "w-full sm:w-1/3 px-2 mt-1", data: [{ id: "layout", valor: "Layout" }, { id: "component", valor: "Componente" }, { id: "utility", valor: "Utilidad" }] },
            { opc: "input-group", id: "cf_price", lbl: "Precio", tipo: "cifra", icon: "icon-dollar", class: "w-full sm:w-1/3 px-2 mt-1" },
            { opc: "input", id: "cf_date", lbl: "Fecha", type: "date", class: "w-full sm:w-1/3 px-2 mt-1" },
            { opc: "checkbox", id: "cf_active", text: "Componente activo", class: "w-full sm:w-1/3 px-2 mt-2" },
            { opc: "radio", id: "cf_type_1", name: "cf_type", value: "public", text: "Publico", checked: true, class: "w-full sm:w-1/3 px-2 mt-2" },
            { opc: "radio", id: "cf_type_2", name: "cf_type", value: "private", text: "Privado", class: "w-full sm:w-1/3 px-2 mt-2" },
            { opc: "textarea", id: "cf_notes", lbl: "Observaciones", rows: 2, class: "w-full px-2 mt-1", required: false },
            { opc: "input-calendar", id: "cf_range", lbl: "Rango de fechas", class: "w-full sm:w-1/2 px-2 mt-1" },
            { opc: "input-file-btn", id: "cf_file", lbl: "Archivo", class: "w-full sm:w-1/2 px-2 mt-1" },
        ];

        // Light (default)
        this.coffeeForm({
            parent: "coffeeFormDemo",
            id: "frmCoffee",
            data: formData,
        });

        // Dark
        this.coffeeForm({
            parent: "coffeeFormDemoDark",
            id: "frmCoffeeDark",
            theme: "dark",
            data: formData,
        });

        this.createForm({
            parent: "bootstrapFormCompare",
            id: "frmBootstrap",
            data: { opc: "addShowcase" },
            json: [
                { opc: "label", id: "lblCompare", text: "Mismos campos (Bootstrap)", class: "col-12 fw-bold text-lg mb-2 p-1" },
                { opc: "input", lbl: "Nombre", id: "bs_name", tipo: "texto", class: "col-12 col-sm-6 mb-3" },
                { opc: "input", lbl: "Version", id: "bs_version", tipo: "textoNum", class: "col-12 col-sm-6 mb-3" },
                { opc: "select", lbl: "Categoria", id: "bs_category", class: "col-12 col-sm-4 mb-3", data: [{ id: "layout", valor: "Layout" }, { id: "component", valor: "Componente" }, { id: "utility", valor: "Utilidad" }] },
                { opc: "textarea", id: "bs_notes", lbl: "Observaciones", rows: 2, class: "col-12 mb-3", required: false },
            ],
            success: (r) => {}
        });
    }

    // ===================== COMPLEMENTS =====================

    renderComplements() {
        $("#showcaseContent").html("");

        this.tabLayout({
            parent: "showcaseContent",
            id: "complementsTabs",
            type: "large",
            theme: "light",
            class: "mb-2",
            json: [
                { id: "comp-usefetch", tab: "useFetch", active: true, onClick: () => this.demoUseFetch() },
                { id: "comp-dropdown", tab: "dropdown", onClick: () => this.demoDropdown() },
                { id: "comp-loader", tab: "loader", onClick: () => this.demoLoader() },
                { id: "comp-objectmerge", tab: "ObjectMerge", onClick: () => this.demoObjectMerge() },
                { id: "comp-closedmodal", tab: "closedModal", onClick: () => this.demoClosedModal() },
                { id: "comp-excel", tab: "createExcel", onClick: () => this.demoCreateExcel() },
            ]
        });

        $(`<div id="complementDemo" class="mt-3 border rounded-lg p-3 bg-gray-50 min-h-[300px]"></div>`).appendTo("#showcaseContent");
        this.demoUseFetch();
    }

    demoUseFetch() {
        $("#complementDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">useFetch(options) - Metodo de instancia</h3>
                <p class="text-sm text-gray-600">Metodo de la clase Complements. Realiza peticiones AJAX usando Fetch API. Usa this._link como URL por defecto. Envia datos como URLSearchParams (POST).</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: url, method('POST'), data{opc}, success(data)</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Como metodo de la clase (usa this._link por defecto)
this.useFetch({
    data: { opc: 'ls', fi: '2024-01-01', ff: '2024-12-31' },
    success: (data) => {
        console.log(data);
    }
});

// Con URL personalizada
this.useFetch({
    url: 'ctrl/ctrl-otro.php',
    data: { opc: 'init' },
    success: (data) => {
        console.log(data);
    }
});</code></pre>
        `);
    }

    demoDropdown() {
        $("#complementDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">dropdown(options)</h3>
                <p class="text-sm text-gray-600">Genera un menu dropdown HTML como string. Se usa tipicamente dentro de las filas de tablas para las opciones de accion (editar, eliminar, etc.).</p>
                <p class="text-xs text-gray-500 mt-1">Parametro: array de objetos [{icon, text, onClick}]. Retorna: string HTML</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2 bg-white rounded p-3 border">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Resultado:</h4>
                    <div id="dropdownDemoResult"></div>
                </div>
                <div class="w-1/2">
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// En PHP (controlador) para las tablas:
'dropdown' => [
    ['icon' => 'icon-pencil', 'text' => 'Editar',
     'onclick' => "app.editItem(\$id)"],
    ['icon' => 'icon-trash', 'text' => 'Eliminar',
     'onclick' => "app.deleteItem(\$id)"],
    ['icon' => 'icon-eye', 'text' => 'Ver detalle',
     'onclick' => "app.viewItem(\$id)"],
]

// En JS (metodo de Complements):
let html = this.dropdown([
    { icon: "icon-pencil", text: "Editar",
      onClick: "app.editItem(1)" },
    { icon: "icon-trash", text: "Eliminar",
      onClick: "app.deleteItem(1)" },
]);</code></pre>
                </div>
            </div>
        `);

        let dropdownHtml = this.dropdown([
            { icon: "icon-pencil", text: " Editar", onClick: "alert('Editar')" },
            { icon: "icon-trash", text: " Eliminar", onClick: "alert('Eliminar')" },
            { icon: "icon-eye", text: " Ver detalle", onClick: "alert('Ver')" },
        ]);
        $("#dropdownDemoResult").html(dropdownHtml);
    }

    demoLoader() {
        $("#complementDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">loader(options)</h3>
                <p class="text-sm text-gray-600">Genera un loader animado con multiples tipos y tamanos. Se puede insertar en cualquier contenedor o usar como retorno HTML.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, text, size('xs'|'sm'|'md'|'lg'), type('quantum'|'aurora'|'nebula'|'crystal')</p>
            </div>
            <div class="bg-white rounded p-4 border space-y-4">
                <div class="flex items-center gap-8">
                    <div><span class="text-xs text-gray-500 block mb-1">quantum (xs)</span><div id="loaderQ"></div></div>
                    <div><span class="text-xs text-gray-500 block mb-1">aurora (sm)</span><div id="loaderA"></div></div>
                    <div><span class="text-xs text-gray-500 block mb-1">nebula (md)</span><div id="loaderN"></div></div>
                    <div><span class="text-xs text-gray-500 block mb-1">crystal (lg)</span><div id="loaderC"></div></div>
                </div>
                <div class="flex items-center gap-8 mt-3">
                    <div id="loaderText1"></div>
                    <div id="loaderText2"></div>
                </div>
            </div>
        `);

        this.loader({ parent: "loaderQ", size: "xs", type: "quantum" });
        this.loader({ parent: "loaderA", size: "sm", type: "aurora" });
        this.loader({ parent: "loaderN", size: "md", type: "nebula" });
        this.loader({ parent: "loaderC", size: "lg", type: "crystal" });
        this.loader({ parent: "loaderText1", size: "sm", type: "aurora", text: "Cargando datos..." });
        this.loader({ parent: "loaderText2", size: "sm", type: "quantum", text: "Procesando..." });
    }

    demoObjectMerge() {
        $("#complementDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">ObjectMerge(target, source)</h3>
                <p class="text-sm text-gray-600">Combina dos objetos recursivamente (deep merge). Se usa internamente en los componentes para fusionar defaults con options del usuario.</p>
                <p class="text-xs text-gray-500 mt-1">A diferencia de Object.assign (shallow), ObjectMerge preserva sub-objetos anidados.</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>let defaults = {
    theme: 'light',
    card: {
        filterBar: { class: 'w-full', id: 'filterBar' },
        container: { class: 'w-full', id: 'container' }
    }
};

let options = {
    card: {
        filterBar: { class: 'w-1/2' }  // Solo cambia class
    }
};

let result = this.ObjectMerge(defaults, options);
// result.card.filterBar = { class: 'w-1/2', id: 'filterBar' }  // id se preserva
// result.card.container = { class: 'w-full', id: 'container' }  // intacto</code></pre>
        `);
    }

    demoClosedModal() {
        $("#complementDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">closedModal(data)</h3>
                <p class="text-sm text-gray-600">Cierra un modal de bootbox si la operacion fue exitosa. Si data es true o data.success es true, cierra el modal via .bootbox-close-button.</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Uso tipico despues de una operacion exitosa en un modal
fn_ajax(datos, this._link, '').then((data) => {
    this.closedModal(data);
    // o simplemente:
    this.closedModal(true);
});</code></pre>
        `);
    }

    demoCreateExcel() {
        $("#complementDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">createExcel(options)</h3>
                <p class="text-sm text-gray-600">Exporta una tabla HTML a archivo Excel (.xlsx) usando ExcelJS. Preserva colores de fondo, texto, alineacion y negrita de la tabla original.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent, tableId, fileName, onSuccess(), onError(). Requiere: ExcelJS CDN</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>this.createExcel({
    tableId: "tbProducts",           // ID de la tabla HTML a exportar
    fileName: "reporte-productos",   // Nombre del archivo sin extension
    onSuccess: (fileName) => {
        alert({ icon: "success", text: "Archivo exportado: " + fileName });
    },
    onError: (err) => {
        console.error("Error:", err);
    }
});</code></pre>
        `);
    }

    // ===================== PLUGINS =====================

    renderPlugins() {
        $("#showcaseContent").html("");

        this.tabLayout({
            parent: "showcaseContent",
            id: "pluginsTabs",
            type: "large",
            theme: "light",
            class: "mb-2",
            json: [
                { id: "plg-jsonform", tab: "content_json_form", active: true, onClick: () => this.demoJsonForm() },
                { id: "plg-validation", tab: "validation_form", onClick: () => this.demoValidationForm() },
                { id: "plg-validarcontainer", tab: "validar_contenedor", onClick: () => this.demoValidarContenedor() },
                { id: "plg-optionselect", tab: "option_select", onClick: () => this.demoOptionSelect() },
                { id: "plg-jsontable", tab: "rpt_json_table2", onClick: () => this.demoJsonTable() },
                { id: "plg-jsontab", tab: "simple_json_tab", onClick: () => this.demoJsonTab() },
                { id: "plg-loading", tab: "Loading", onClick: () => this.demoLoading() },
            ]
        });

        $(`<div id="pluginDemo" class="mt-3 border rounded-lg p-3 bg-gray-50 min-h-[300px]"></div>`).appendTo("#showcaseContent");
        this.demoJsonForm();
    }

    demoJsonForm() {
        $("#pluginDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">$.fn.content_json_form(options)</h3>
                <p class="text-sm text-gray-600">Plugin jQuery que genera formularios desde JSON. Es la base interna de createForm y createfilterBar. Soporta todos los tipos de input del framework.</p>
                <p class="text-xs text-gray-500 mt-1">Tipos soportados: input, select, textarea, radio, checkbox, input-calendar, input-file, btn, btn-submit, button, dropdown, label</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>$('#container').content_json_form({
    data: [
        { opc: "label", text: "Seccion", class: "col-12 fw-bold" },
        { opc: "input", id: "name", lbl: "Nombre", tipo: "texto", class: "col-6" },
        { opc: "input", id: "amount", lbl: "Monto", tipo: "cifra", class: "col-6" },
        { opc: "input", id: "qty", lbl: "Cantidad", tipo: "numero", class: "col-6" },
        { opc: "input", id: "email", lbl: "Email", tipo: "email", class: "col-6" },
        { opc: "input", id: "date", lbl: "Fecha", type: "date", class: "col-6" },
        { opc: "input", id: "time", lbl: "Hora", type: "time", class: "col-6" },
        { opc: "select", id: "cat", lbl: "Categoria", class: "col-6",
          data: [{id:1, valor:"A"}, {id:2, valor:"B"}] },
        { opc: "textarea", id: "obs", lbl: "Observaciones", class: "col-12", rows: 3 },
        { opc: "input-calendar", id: "range", lbl: "Rango", class: "col-6" },
        { opc: "input-file", id: "file", lbl: "Archivo", class: "col-6" },
        { opc: "btn", text: "Buscar", fn: "search()", class: "col-3" },
        { opc: "btn-submit", text: "Enviar", class: "col-3" },
        { opc: "button", text: "Cancelar", color_btn: "danger", class: "col-3",
          onClick: () => {} },
    ]
});</code></pre>
        `);
    }

    demoValidationForm() {
        $("#pluginDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">$.fn.validation_form(options, callback)</h3>
                <p class="text-sm text-gray-600">Plugin que valida automaticamente todos los campos required de un formulario. Captura el evento submit y ejecuta el callback con FormData si es valido.</p>
                <p class="text-xs text-gray-500 mt-1">Se usa internamente en createForm. El callback recibe los datos del formulario listos para enviar al backend.</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>$('#myForm').validation_form(
    { opc: 'add', tipo: 'text' },
    (formData) => {
        // formData contiene: opc, tipo + todos los campos del formulario
        fn_ajax(formData, 'ctrl/ctrl-products.php', '').then((data) => {
            console.log(data);
        });
    }
);</code></pre>
        `);
    }

    demoValidarContenedor() {
        $("#pluginDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">$.fn.validar_contenedor(options, callback)</h3>
                <p class="text-sm text-gray-600">Valida campos dentro de un contenedor (no necesariamente un form). Se usa con createTable + idFilterBar para recolectar los filtros antes de la consulta.</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Se usa internamente en createTable cuando tiene idFilterBar
$('#filterBar').validar_contenedor(
    { tipo: 'text', opc: 'ls' },
    (datos) => {
        // datos contiene: opc, tipo + valores de todos los inputs del contenedor
        console.log(datos);
    }
);</code></pre>
        `);
    }

    demoOptionSelect() {
        $("#pluginDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">$.fn.option_select(options)</h3>
                <p class="text-sm text-gray-600">Llena un elemento select con datos JSON. Soporta placeholder, Select2, y filtrado con onchange.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: data[{id, valor}], placeholder, select2(bool), fn(string onchange)</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/3">
                    <label class="fw-semibold text-sm">Ejemplo:</label>
                    <select id="selectDemoPlugin" class="form-select"></select>
                </div>
                <div class="w-2/3">
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>$('#mySelect').option_select({
    data: [
        { id: 1, valor: "Cafe Colombiano" },
        { id: 2, valor: "Cafe Mexicano" },
        { id: 3, valor: "Te Verde" },
    ],
    placeholder: "Seleccionar producto",
    select2: true,
    fn: "app.filterByProduct()"
});</code></pre>
                </div>
            </div>
        `);

        $("#selectDemoPlugin").option_select({
            data: [
                { id: 1, valor: "Cafe Colombiano" },
                { id: 2, valor: "Cafe Mexicano" },
                { id: 3, valor: "Te Verde" },
                { id: 4, valor: "Chocolate Caliente" },
            ],
            placeholder: "Seleccionar producto",
        });
    }

    demoJsonTable() {
        $("#pluginDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">$.fn.rpt_json_table2(options)</h3>
                <p class="text-sm text-gray-600">Plugin jQuery que genera tablas HTML desde JSON. Es la alternativa no-CoffeeSoft (cuando coffeesoft:false en createTable). Usa Bootstrap para el estilo.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: data{thead, row}, id, center[], right[], f_size</p>
            </div>
            <div id="jsonTableDemoPlugin" class="bg-white rounded p-3 border"></div>
        `);

        $("#jsonTableDemoPlugin").rpt_json_table2({
            data: {
                row: [
                    { id: 1, Producto: "Cafe", Precio: "$150.00", Stock: "200", opc: 0 },
                    { id: 2, Producto: "Te", Precio: "$90.00", Stock: "300", opc: 0 },
                    { id: 3, Producto: "Chocolate", Precio: "$110.00", Stock: "150", opc: 0 },
                ]
            },
            id: "tbPluginDemo",
            center: [0],
            right: [2]
        });
    }

    demoJsonTab() {
        $("#pluginDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">$.fn.simple_json_tab(options)</h3>
                <p class="text-sm text-gray-600">Plugin jQuery que genera tabs Bootstrap (nav-tabs) desde JSON. Se usa internamente en tabsLayout. Para tabs modernos usar tabLayout de la clase Components.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: data[{id, tab, active, onClick, contenedor[{class, id}]}], id</p>
            </div>
            <div id="jsonTabDemoPlugin" class="bg-white rounded p-3 border"></div>
        `);

        $("#jsonTabDemoPlugin").simple_json_tab({
            data: [
                { tab: "Tab A", id: "tabA", active: true },
                { tab: "Tab B", id: "tabB" },
                { tab: "Tab C", id: "tabC" },
            ],
            id: "pluginTabs"
        });

        $("#tabA").html(`<div class="p-3 text-gray-600 text-sm">Contenido Tab A</div>`);
        $("#tabB").html(`<div class="p-3 text-gray-600 text-sm">Contenido Tab B</div>`);
        $("#tabC").html(`<div class="p-3 text-gray-600 text-sm">Contenido Tab C</div>`);
    }

    demoLoading() {
        $("#pluginDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">$.fn.Loading()</h3>
                <p class="text-sm text-gray-600">Plugin que muestra un spinner de carga dentro de un contenedor. Se usa internamente en createTable mientras se obtienen los datos del backend.</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <button class="btn btn-primary btn-sm mb-2" id="btnLoadingDemo">Activar Loading</button>
                    <div id="loadingDemoContainer" class="bg-white rounded border h-[150px]"></div>
                </div>
                <div class="w-1/2">
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Mostrar loading en un contenedor
$('#container').Loading();

// Se usa internamente en createTable:
// if (dataConfig.beforeSend) {
//     $('#' + options.parent).Loading();
// }</code></pre>
                </div>
            </div>
        `);

        $("#btnLoadingDemo").on("click", () => {
            $("#loadingDemoContainer").Loading();
            setTimeout(() => {
                $("#loadingDemoContainer").html(`<div class="p-3 text-green-600 text-sm text-center">Datos cargados!</div>`);
            }, 2000);
        });
    }

    // ===================== FUNCIONES GLOBALES =====================

    renderGlobals() {
        $("#showcaseContent").html("");

        this.tabLayout({
            parent: "showcaseContent",
            id: "globalsTabs",
            type: "large",
            theme: "light",
            class: "mb-2",
            json: [
                { id: "glb-usefetch", tab: "useFetch (global)", active: true, onClick: () => this.demoGlobalUseFetch() },
                { id: "glb-fnajax", tab: "fn_ajax", onClick: () => this.demoFnAjax() },
                { id: "glb-datapicker", tab: "dataPicker", onClick: () => this.demoDataPicker() },
                { id: "glb-getrange", tab: "getDataRangePicker", onClick: () => this.demoGetRange() },
                { id: "glb-datatable", tab: "simple_data_table", onClick: () => this.demoSimpleDataTable() },
                { id: "glb-formatprice", tab: "formatPrice", onClick: () => this.demoFormatPrice() },
                { id: "glb-formdatatojson", tab: "formDataToJson", onClick: () => this.demoFormDataToJson() },
            ]
        });

        $(`<div id="globalDemo" class="mt-3 border rounded-lg p-3 bg-gray-50 min-h-[300px]"></div>`).appendTo("#showcaseContent");
        this.demoGlobalUseFetch();
    }

    demoGlobalUseFetch() {
        $("#globalDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">useFetch(options) - Funcion global async</h3>
                <p class="text-sm text-gray-600">Funcion global async/await para peticiones AJAX. A diferencia del metodo de clase, esta retorna una Promise y puede usarse con await.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: url(obligatorio), method('POST'), data{opc}, success(data). Retorna: Promise&lt;data&gt;</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Con await (recomendado)
const data = await useFetch({
    url: 'ctrl/ctrl-products.php',
    data: { opc: 'ls', fi: '2024-01-01', ff: '2024-12-31' }
});
console.log(data);

// Con .then()
useFetch({
    url: 'ctrl/ctrl-products.php',
    data: { opc: 'init' },
    success: (data) => {
        console.log(data);
    }
});

// Uso tipico en edit() con await
async editProduct(id) {
    const request = await useFetch({
        url: this._link,
        data: { opc: "getProduct", id }
    });
    // request.data contiene los datos del producto
}</code></pre>
        `);
    }

    demoFnAjax() {
        $("#globalDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">fn_ajax(data, url, div)</h3>
                <p class="text-sm text-gray-600">Funcion global de peticion AJAX con jQuery. Retorna una Promise. Se usa internamente en createForm y swalQuestion. Compatible con .then().</p>
                <p class="text-xs text-gray-500 mt-1">Parametros: data(object), url(string), div(string - contenedor para loading, '' si no se necesita)</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Uso basico
fn_ajax({ opc: 'ls' }, 'ctrl/ctrl-products.php', '#container')
    .then(data => {
        console.log(data);
    });

// Uso interno en createForm:
extends_ajax = fn_ajax(datos, this._link, '');
extends_ajax.then((data) => {
    if (opts.success) opts.success(data);
});

// Uso interno en swalQuestion:
fn_ajax(opts.data, this._link, "").then((data) => {
    opts.methods.send(data);
});</code></pre>
        `);
    }

    demoDataPicker() {
        $("#globalDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">dataPicker(options)</h3>
                <p class="text-sm text-gray-600">Inicializa un selector de fechas (DateRangePicker). Se usa despues de createfilterBar para activar los campos de tipo input-calendar.</p>
                <p class="text-xs text-gray-500 mt-1">Propiedades: parent(id del input), type('all'|'simple'), onSelect(start, end)</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/3">
                    <label class="fw-semibold text-sm">Ejemplo:</label>
                    <div class="input-group date calendariopicker">
                        <input class="form-control input-sm" id="datePickerGlobalDemo" name="datePickerGlobalDemo">
                        <span class="input-group-text"><i class="icon-calendar-2"></i></span>
                    </div>
                </div>
                <div class="w-2/3">
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Inicializar despues de renderizar el filterBar
dataPicker({
    parent: "calendar" + this.PROJECT_NAME,
    type: "all",
    onSelect: (start, end) => {
        console.log(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        this.ls();
    }
});</code></pre>
                </div>
            </div>
        `);

        dataPicker({ parent: "datePickerGlobalDemo" });
    }

    demoGetRange() {
        $("#globalDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">getDataRangePicker(idInput)</h3>
                <p class="text-sm text-gray-600">Obtiene el rango de fechas seleccionado en un DateRangePicker. Retorna un objeto con fi (fecha inicial) y ff (fecha final).</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Uso tipico en ls()
ls() {
    let rangePicker = getDataRangePicker("calendar" + this.PROJECT_NAME);

    this.createTable({
        parent: 'container' + this.PROJECT_NAME,
        idFilterBar: 'filterBar' + this.PROJECT_NAME,
        data: {
            opc: "ls",
            fi: rangePicker.fi,   // "2024-01-01"
            ff: rangePicker.ff    // "2024-12-31"
        },
        // ...
    });
}</code></pre>
        `);
    }

    demoSimpleDataTable() {
        $("#globalDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">simple_data_table(table, rows)</h3>
                <p class="text-sm text-gray-600">Inicializa un DataTable de jQuery en una tabla HTML existente. Se ejecuta automaticamente cuando conf.datatable es true en createTable.</p>
                <p class="text-xs text-gray-500 mt-1">Parametros: table(selector CSS), rows(numero de filas por pagina)</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>// Uso manual
simple_data_table('#myTable', 15);

// Se ejecuta automaticamente en createTable cuando:
// conf: { datatable: true, pag: 15 }
// Internamente hace:
// window[dataConfig.fn_datatable]('#' + attr_table_filter.id, dataConfig.pag);</code></pre>
        `);
    }

    demoFormatPrice() {
        $("#globalDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">formatPrice(amount, locale, currency)</h3>
                <p class="text-sm text-gray-600">Formatea numeros como moneda usando Intl.NumberFormat. Por defecto usa formato mexicano (es-MX, MXN).</p>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2 bg-white rounded p-3 border">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Resultados:</h4>
                    <ul class="text-sm space-y-1">
                        <li><code>formatPrice(1500)</code> = <strong id="fp1"></strong></li>
                        <li><code>formatPrice(25000.50)</code> = <strong id="fp2"></strong></li>
                        <li><code>formatPrice(1500, 'en-US', 'USD')</code> = <strong id="fp3"></strong></li>
                    </ul>
                </div>
                <div class="w-1/2">
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>formatPrice(1500);                         // "$1,500.00"
formatPrice(25000.50);                     // "$25,000.50"
formatPrice(1500, 'en-US', 'USD');         // "$1,500.00"

// Uso en controlador PHP con evaluar():
'Total' => evaluar($key['total'])</code></pre>
                </div>
            </div>
        `);

        $("#fp1").text(formatPrice(1500));
        $("#fp2").text(formatPrice(25000.50));
        $("#fp3").text(formatPrice(1500, 'en-US', 'USD'));
    }

    demoFormDataToJson() {
        $("#globalDemo").html(`
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">formDataToJson(formData)</h3>
                <p class="text-sm text-gray-600">Convierte un objeto FormData a un objeto JSON plano. Maneja campos duplicados convirtiendolos en arrays.</p>
            </div>
            <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto"><code>let formData = new FormData(document.getElementById('myForm'));
let json = formDataToJson(formData);
// { name: "Juan", email: "juan@mail.com", tags: ["js", "php"] }

// Util para depuración o para enviar datos como JSON en lugar de FormData</code></pre>
        `);
    }
}
