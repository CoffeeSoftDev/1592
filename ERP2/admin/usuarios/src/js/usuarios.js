let api = 'ctrl/ctrl-usuarios.php';
let app, usuarios, roles, departamentos, categorias;

$(async () => {
    const data = await useFetch({ url: api, data: { opc: "init" } });

    app = new App(api, 'root');

    usuarios = new Usuarios(api, 'root');
    usuarios.roles         = data.roles         || [];
    usuarios.estados       = data.estados       || [];
    usuarios.departamentos = data.departamentos || [];

    roles         = new Roles(api, 'root');
    departamentos = new Departamentos(api, 'root');
    
    categorias = new Categorias(api, 'root');
    categorias.tiposMovimiento = data.tiposMovimiento || [];

    app.init();
});

// -- Clase principal --

class App extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Admin";
    }

    init() {
        this.render();
    }

    render() {
        this.layout();
        this.renderHeader();
        this.renderTabs();
        this.renderActiveTab();
    }

    layout() {
        this.createLayout({
            parent: 'root',
            design: false,
            data: {
                id: this.PROJECT_NAME,
                class: 'w-full p-3',
                container: [
                    {
                        type: 'div',
                        id: `header${this.PROJECT_NAME}`,
                        class: 'w-full mb-3'
                    },
                    {
                        type: 'div',
                        id: `container${this.PROJECT_NAME}`,
                        class: 'w-full h-full'
                    }
                ]
            }
        });
    }

    renderHeader() {
        $(`#header${this.PROJECT_NAME}`).html('<h2 class="text-2xl font-semibold">Administracion</h2><p class="text-gray-400">Gestiona usuarios, roles y departamentos.</p>');
    }

    renderTabs() {
        const activeModule = localStorage.getItem('admin-module') || 'usuarios';

        this.tabLayout({
            parent: `container${this.PROJECT_NAME}`,
            id: 'tabsAdmin',
            theme: 'light',
            type: 'short',
            showBorder: false,
            json: [
                {
                    id: 'usuarios',
                    tab: 'Usuarios',
                    icon: 'icon-users',
                    iconColor: 'text-blue-600',
                    active: activeModule === 'usuarios',
                    onClick: () => {
                        localStorage.setItem('admin-module', 'usuarios');
                        usuarios.render();
                    }
                },
                {
                    id: 'roles',
                    tab: 'Roles',
                    icon: 'icon-shield',
                    iconColor: 'text-purple-600',
                    active: activeModule === 'roles',
                    onClick: () => {
                        localStorage.setItem('admin-module', 'roles');
                        roles.render();
                    }
                },
                {
                    id: 'departamentos',
                    tab: 'Departamentos',
                    icon: 'icon-building',
                    iconColor: 'text-emerald-600',
                    active: activeModule === 'departamentos',
                    onClick: () => {
                        localStorage.setItem('admin-module', 'departamentos');
                        departamentos.render();
                    }
                },
                {
                    id: 'categorias',
                    tab: 'Categorías Finanzas',
                    icon: 'icon-wallet',
                    iconColor: 'text-amber-600',
                    active: activeModule === 'categorias',
                    onClick: () => {
                        localStorage.setItem('admin-module', 'categorias');
                        categorias.render();
                    }
                }
            ]
        });
    }

    renderActiveTab(module) {
        const activeModule = module || localStorage.getItem('admin-module') || 'usuarios';

        const tabActions = {
            'usuarios':      () => usuarios.render(),
            'roles':         () => roles.render(),
            'departamentos': () => departamentos.render(),
            'categorias':    () => categorias.render()
        };

        if (tabActions[activeModule]) tabActions[activeModule]();
    }
}

// -- Usuarios --

class Usuarios extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Usuarios";
    }

    render() {
        this.layout();
        this.filterBar();
        this.lsUsuarios();
    }

    layout() {
        this.primaryLayout({
            parent: 'container-usuarios',
            id: this.PROJECT_NAME,
            class: 'p-2',
            card: {
                filterBar: {
                    class: 'w-full mb-3',
                    id: `filterBar${this.PROJECT_NAME}`
                },
                container: {
                    class: 'w-full my-3 h-full',
                    id: `container${this.PROJECT_NAME}`
                }
            }
        });
    }

    filterBar() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            coffeesoft: true,
            data: [
                {
                    opc: "select",
                    id: "rol",
                    lbl: "Rol",
                    class: "col-sm-2",
                    data: [
                        { id: "", valor: "Todos" },
                        ...this.roles
                    ],
                    onchange: "usuarios.lsUsuarios()"
                },
                {
                    opc: "select",
                    id: "active",
                    lbl: "Estado",
                    class: "col-sm-2",
                    data: [
                        { id: "1", valor: "Activos" },
                        { id: "0", valor: "Inactivos" },
                        { id: "",  valor: "Todos" }
                    ],
                    onchange: "usuarios.lsUsuarios()"
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "primary",
                    id: "btnNuevoUsuario",
                    text: "Nuevo usuario",
                    icon: "icon-user-add",
                    fn: "usuarios.addUsuario()"
                },
              
            ]
        });
    }

    refresh() {
        this.lsUsuarios();
    }

    lsUsuarios() {
        this.createTable({
            parent: `container${this.PROJECT_NAME}`,
            idFilterBar: `filterBar${this.PROJECT_NAME}`,
            data: { opc: "lsUsuarios" },
            conf: { datatable: true, pag: 10 },
            coffeesoft: true,
            attr: {
                id: `tb${this.PROJECT_NAME}`,
                theme: 'corporativo',
                center: [1, 4],
                right: [8]
            }
        });
    }

    addUsuario() {
        this.createModalForm({
            id: 'frmAddUsuario',
            bootbox: {
                title: 'Nuevo usuario',
                closeButton: true
            },
            data: { opc: "addUsuario" },
            json: this.jsonUsuario(false),
            success: (response) => {
                if (response.status == 200) {
                    alert({
                        icon: "success",
                        title: "Usuario creado",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.refresh();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
        this.setupPasswordToggle('frmAddUsuario');
    }

    async editUsuario(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getUsuario", id } });

        if (request.status != 200 || !request.data || !request.data[0]) {
            alert({
                icon: "error",
                text: "No se pudo cargar el usuario",
                btn1: true,
                btn1Text: "Ok"
            });
            return;
        }

        const autofill = request.data[0];
        autofill.password = '';

        this.createModalForm({
            id: 'frmEditUsuario',
            bootbox: {
                title: `Editar usuario: ${autofill.nombre || ''}`,
                closeButton: true
            },
            data: { opc: "editUsuario", id },
            autofill: autofill,
            json: this.jsonUsuario(true),
            success: (response) => {
                if (response.status == 200) {
                    alert({
                        icon: "success",
                        title: "Usuario actualizado",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.refresh();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
        this.setupPasswordToggle('frmEditUsuario');
    }

    disableUsuario(id) {
        this.swalQuestion({
            opts: {
                title: "¿Desactivar usuario?",
                html: "Esta accion desactivara el acceso del usuario al sistema. Podras reactivarlo despues."
            },
            data: { opc: "disableUsuario", id },
            methods: {
                request: () => {
                    alert({
                        icon: "success",
                        title: "Usuario desactivado",
                        text: "El usuario fue desactivado correctamente.",
                        btn1: true
                    });
                    this.refresh();
                }
            }
        });
    }

    setupPasswordToggle(formId) {
        const $pwd = $(`#${formId} [name="password"]`);
        if (!$pwd.length) return;

        $pwd.attr('type', 'password');
        $pwd.wrap($('<div>', { class: 'input-group' }));

        const $btn = $('<button>', {
            type: 'button',
            class: 'btn btn-outline-secondary',
            tabindex: -1
        });

        $('<i>', { class: 'icon-eye' }).appendTo($btn);

        $btn.on('click', function () {
            const isPassword = $pwd.attr('type') === 'password';
            $pwd.attr('type', isPassword ? 'text' : 'password');
            $(this).find('i').attr('class', isPassword ? 'icon-eye-off' : 'icon-eye');
        });

        $pwd.after($btn);
    }

    jsonUsuario(isEdit = false) {
        return [
            {
                opc: "input",
                lbl: "Nombre completo",
                id: "nombre",
                tipo: "texto",
                class: "col-12 mb-3"
            },
            {
                opc: "input",
                lbl: "Usuario",
                id: "usuario",
                tipo: "texto",
                class: "col-12 col-md-6 mb-3"
            },
            {
                opc: "input",
                lbl: "Email",
                id: "email",
                tipo: "email",
                class: "col-12 col-md-6 mb-3"
            },
            {
                opc: "input",
                lbl: "Telefono",
                id: "telefono",
                tipo: "tel",
                class: "col-12 col-md-6 mb-3",
                required: false
            },
            {
                opc: "select",
                lbl: "Departamento",
                id: "departamento",
                class: "col-12 col-md-6 mb-3",
                data: this.departamentos
            },
            {
                opc: "select",
                lbl: "Rol",
                id: "rol",
                class: "col-12 col-md-6 mb-3",
                data: this.roles
            },
            {
                opc: "select",
                lbl: "Estado",
                id: "estado",
                class: "col-12 col-md-6 mb-3",
                data: this.estados
            },
            {
                opc: "input",
                lbl: isEdit ? "Nueva contrasena (opcional)" : "Contrasena",
                id: "password",
                type: "password",
                tipo: "texto",
                class: "col-12 col-md-6 mb-3",
                required: !isEdit,
                autocomplete: "new-password"
            },
            {
                opc: "input",
                lbl: "2FA habilitado (0/1)",
                id: "two_fa_enabled",
                tipo: "numero",
                class: "col-12 col-md-6 mb-3",
                required: false
            }
        ];
    }
}

// -- Roles --

class Roles extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Roles";
    }

    render() {
        this.layout();
        this.filterBar();
        this.lsRoles();
    }

    layout() {
        this.primaryLayout({
            parent: 'container-roles',
            id: this.PROJECT_NAME,
            class: 'p-2',
            card: {
                filterBar: {
                    class: 'w-full my-3 p-3 border rounded-lg',
                    id: `filterBar${this.PROJECT_NAME}`
                },
                container: {
                    class: 'w-full my-3 h-full',
                    id: `container${this.PROJECT_NAME}`
                }
            }
        });
    }

    filterBar() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            coffeesoft: true,
            data: [
                {
                    opc: "select",
                    id: "active",
                    lbl: "Estado",
                    class: "col-sm-2",
                    data: [
                        { id: "1", valor: "Activos" },
                        { id: "0", valor: "Inactivos" },
                        { id: "",  valor: "Todos" }
                    ],
                    onchange: "roles.lsRoles()"
                },
                {
                    opc: "btn",
                    class: "col-sm-3",
                    color_btn: "primary",
                    id: "btnNuevoRol",
                    text: "Nuevo Rol",
                    icon: "icon-plus",
                    fn: "roles.addRol()"
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnRefreshRoles",
                    text: "Actualizar",
                    icon: "icon-arrows-cw",
                    fn: "roles.lsRoles()"
                }
            ]
        });
    }

    lsRoles() {
        this.createTable({
            parent: `container${this.PROJECT_NAME}`,
            idFilterBar: `filterBar${this.PROJECT_NAME}`,
            data: { opc: "lsRoles" },
            conf: { datatable: true, pag: 10 },
            coffeesoft: true,
            attr: {
                id: `tb${this.PROJECT_NAME}`,
                theme: 'corporativo',
                title: 'Roles del sistema',
                subtitle: 'Lista de roles registrados',
                center: [0]
            }
        });
    }

    addRol() {
        this.createModalForm({
            id: 'frmAddRol',
            data: { opc: 'addRol' },
            bootbox: {
                title: 'Agregar Rol',
                closeButton: true
            },
            json: this.jsonRol(false),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Rol creado",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsRoles();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    async editRol(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getRol", id } });

        if (request.status != 200 || !request.data || !request.data[0]) {
            alert({
                icon: "error",
                text: "No se pudo cargar el rol",
                btn1: true,
                btn1Text: "Ok"
            });
            return;
        }

        const autofill = request.data[0];

        this.createModalForm({
            id: 'frmEditRol',
            data: { opc: 'editRol', id },
            bootbox: {
                title: `Editar Rol: ${autofill.nombre || ''}`,
                closeButton: true
            },
            autofill: autofill,
            json: this.jsonRol(true),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Rol actualizado",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsRoles();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    disableRol(id) {
        this.swalQuestion({
            opts: {
                title: "¿Desactivar rol?",
                html: "Esta accion desactivara el rol. Los usuarios asignados a este rol podrian verse afectados."
            },
            data: { opc: "disableRol", id },
            methods: {
                request: () => {
                    alert({
                        icon: "success",
                        title: "Rol desactivado",
                        text: "El rol fue desactivado correctamente.",
                        btn1: true
                    });
                    this.lsRoles();
                }
            }
        });
    }

    jsonRol(isEdit = false) {
        return [
            {
                opc: "input",
                lbl: "Nombre del Rol",
                id: "nombre",
                tipo: "texto",
                class: "col-12 mb-3"
            }
        ];
    }
}

// -- Departamentos --

class Departamentos extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Departamentos";
    }

    render() {
        this.layout();
        this.filterBar();
        this.lsDepartamentos();
    }

    layout() {
        this.primaryLayout({
            parent: 'container-departamentos',
            id: this.PROJECT_NAME,
            class: 'p-2',
            card: {
                filterBar: {
                    class: 'w-full my-3 p-3 border rounded-lg',
                    id: `filterBar${this.PROJECT_NAME}`
                },
                container: {
                    class: 'w-full my-3 h-full',
                    id: `container${this.PROJECT_NAME}`
                }
            }
        });
    }

    filterBar() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            coffeesoft: true,
            data: [
                {
                    opc: "select",
                    id: "active",
                    lbl: "Estado",
                    class: "col-sm-2",
                    data: [
                        { id: "1", valor: "Activos" },
                        { id: "0", valor: "Inactivos" },
                        { id: "",  valor: "Todos" }
                    ],
                    onchange: "departamentos.lsDepartamentos()"
                },
                {
                    opc: "btn",
                    class: "col-sm-3",
                    color_btn: "primary",
                    id: "btnNuevoDepto",
                    text: "Nuevo Departamento",
                    icon: "icon-plus",
                    fn: "departamentos.addDepartamento()"
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnRefreshDepto",
                    text: "Actualizar",
                    icon: "icon-arrows-cw",
                    fn: "departamentos.lsDepartamentos()"
                }
            ]
        });
    }

    lsDepartamentos() {
        this.createTable({
            parent: `container${this.PROJECT_NAME}`,
            idFilterBar: `filterBar${this.PROJECT_NAME}`,
            data: { opc: "lsDepartamentos" },
            conf: { datatable: true, pag: 10 },
            coffeesoft: true,
            attr: {
                id: `tb${this.PROJECT_NAME}`,
                theme: 'corporativo',
                title: 'Departamentos del sistema',
                subtitle: 'Lista de departamentos registrados',
                center: [0]
            }
        });
    }

    addDepartamento() {
        this.createModalForm({
            id: 'frmAddDepartamento',
            data: { opc: 'addDepartamento' },
            bootbox: {
                title: 'Agregar Departamento',
                closeButton: true
            },
            json: this.jsonDepartamento(false),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Departamento creado",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsDepartamentos();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    async editDepartamento(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getDepartamento", id } });

        if (request.status != 200 || !request.data || !request.data[0]) {
            alert({
                icon: "error",
                text: "No se pudo cargar el departamento",
                btn1: true,
                btn1Text: "Ok"
            });
            return;
        }

        const autofill = request.data[0];

        this.createModalForm({
            id: 'frmEditDepartamento',
            data: { opc: 'editDepartamento', id },
            bootbox: {
                title: `Editar Departamento: ${autofill.nombre || ''}`,
                closeButton: true
            },
            autofill: autofill,
            json: this.jsonDepartamento(true),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Departamento actualizado",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsDepartamentos();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    disableDepartamento(id) {
        this.swalQuestion({
            opts: {
                title: "¿Desactivar departamento?",
                html: "Esta accion desactivara el departamento. Los usuarios asignados a este departamento podrian verse afectados."
            },
            data: { opc: "disableDepartamento", id },
            methods: {
                request: () => {
                    alert({
                        icon: "success",
                        title: "Departamento desactivado",
                        text: "El departamento fue desactivado correctamente.",
                        btn1: true
                    });
                    this.lsDepartamentos();
                }
            }
        });
    }

    jsonDepartamento(isEdit = false) {
        return [
            {
                opc: "input",
                lbl: "Nombre del Departamento",
                id: "nombre",
                tipo: "texto",
                class: "col-12 mb-3"
            }
        ];
    }
}


// -- Categorías Finanzas --

class Categorias extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Categorias";
        this.tiposMovimiento = [];
        this.categoriaSeleccionada = null;
    }

    render() {
        this.layout();
        this.filterBar();
        this.lsCategorias();
    }

    layout() {
        this.createLayout({
            parent: 'container-categorias',
            design: false,
            data: {
                id: this.PROJECT_NAME,
                class: 'p-2 flex gap-4',
                container: [
                    {
                        type: 'div',
                        id: `panelCategorias${this.PROJECT_NAME}`,
                        class: 'w-1/2 rounded-lg p-3 h-full',
                        children: [
                            {
                                id: `filterBar${this.PROJECT_NAME}`,
                                class: 'mb-3'
                            },
                            {
                                id: `container${this.PROJECT_NAME}`
                            }
                        ]
                    },
                    {
                        type: 'div',
                        id: `panelSubcategorias${this.PROJECT_NAME}`,
                        class: 'w-1/2 rounded-lg p-3 h-full',
                        children: [
                            {
                                id: `filterBarSub${this.PROJECT_NAME}`,
                                class: 'mb-3'
                            },
                            {
                                id: `containerSub${this.PROJECT_NAME}`
                            }
                        ]
                    }
                ]
            }
        });
    }

    filterBar() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            coffeesoft: true,
            data: [
                {
                    opc: "select",
                    id: "tipoMovimiento",
                    lbl: "Tipo Movimiento",
                    class: "col-sm-4",
                    data: [
                        { id: "", valor: "Todos" },
                        ...this.tiposMovimiento
                    ],
                    onchange: "categorias.lsCategorias()"
                },
                {
                    opc: "select",
                    id: "activeCat",
                    lbl: "Estado",
                    class: "col-sm-3",
                    data: [
                        { id: "1", valor: "Activos" },
                        { id: "0", valor: "Inactivos" },
                        { id: "",  valor: "Todos" }
                    ],
                    onchange: "categorias.lsCategorias()"
                },
                {
                    opc: "btn",
                    class: "col-sm-4",
                    color_btn: "primary",
                    id: "btnNuevaCategoria",
                    text: "Nueva Categoría",
                    icon: "icon-plus",
                    fn: "categorias.addCategoria()"
                }
            ]
        });
    }

    lsCategorias() {
        this.categoriaSeleccionada = null;
        this.renderEmptySubcategorias();

        const tipoMovimiento = $('#tipoMovimiento').val() || '';
        const activeCat = $('#activeCat').val() || '1';

        this.createTable({
            parent: `container${this.PROJECT_NAME}`,
            idFilterBar: `filterBar${this.PROJECT_NAME}`,
            data: { 
                opc: "lsCategorias",
                tipoMovimiento: tipoMovimiento,
                activeCat: activeCat
            },
            conf: { datatable: true, pag: 10 },
            coffeesoft: true,
            attr: {
                id: `tb${this.PROJECT_NAME}`,
                theme: 'corporativo',
                title: 'Categorías',
                subtitle: 'Conceptos de finanzas',
                center: [1, 3],
                bordered:true
            }
        });
    }

    renderEmptySubcategorias() {
        $(`#filterBarSub${this.PROJECT_NAME}`).html('');
        $(`#containerSub${this.PROJECT_NAME}`).html(`
            <div class="text-center text-gray-400 py-10">
                <i class="icon-folder-open" style="font-size:48px;opacity:0.5;display:block;margin-bottom:0.75rem"></i>
                <p>Selecciona una categoría para ver sus subcategorías</p>
            </div>
        `);
    }

    selectCategoria(id, nombre) {
        this.categoriaSeleccionada = { id, nombre };
        this.filterBarSubcategorias();
        this.lsSubcategorias();
    }

    filterBarSubcategorias() {
        this.createfilterBar({
            parent: `filterBarSub${this.PROJECT_NAME}`,
            coffeesoft: true,
            data: [
                {
                    opc: "label",
                    id: "lblCategoria",
                    text: `📂 ${this.categoriaSeleccionada.nombre}`,
                    class: "col-sm-5 fw-bold"
                },
                {
                    opc: "select",
                    id: "activeSub",
                    lbl: "Estado",
                    class: "col-sm-3",
                    data: [
                        { id: "1", valor: "Activos" },
                        { id: "0", valor: "Inactivos" },
                        { id: "",  valor: "Todos" }
                    ],
                    onchange: "categorias.lsSubcategorias()"
                },
                {
                    opc: "btn",
                    class: "col-sm-4",
                    color_btn: "success",
                    id: "btnNuevaSubcategoria",
                    text: "Nueva Subcategoría",
                    icon: "icon-plus",
                    fn: "categorias.addSubcategoria()"
                }
            ]
        });
    }

    lsSubcategorias() {
        if (!this.categoriaSeleccionada) return;

        const activeSub = $('#activeSub').val() || '1';

        this.createTable({
            parent: `containerSub${this.PROJECT_NAME}`,
            idFilterBar: `filterBarSub${this.PROJECT_NAME}`,
            data: { 
                opc: "lsSubcategorias",
                idCategoria: this.categoriaSeleccionada.id,
                activeSub: activeSub
            },
            conf: { datatable: true, pag: 10 },
            coffeesoft: true,
            attr: {
                id: `tbSub${this.PROJECT_NAME}`,
                theme: 'corporativo',
                title: 'Subcategorías',
                subtitle: `De: ${this.categoriaSeleccionada.nombre}`,
                center: [0, 2],
                right: [3]
            }
        });
    }

    addCategoria() {
        this.createModalForm({
            id: 'frmAddCategoria',
            data: { opc: 'addCategoria' },
            bootbox: {
                title: 'Nueva Categoría',
                closeButton: true
            },
            json: this.jsonCategoria(),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Categoría creada",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsCategorias();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    async editCategoria(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getCategoria", id } });

        if (request.status != 200 || !request.data) {
            alert({
                icon: "error",
                text: "No se pudo cargar la categoría",
                btn1: true,
                btn1Text: "Ok"
            });
            return;
        }

        const autofill = request.data;

        this.createModalForm({
            id: 'frmEditCategoria',
            data: { opc: 'editCategoria', id },
            bootbox: {
                title: `Editar Categoría: ${autofill.nombre || ''}`,
                closeButton: true
            },
            autofill: autofill,
            json: this.jsonCategoria(),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Categoría actualizada",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsCategorias();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    disableCategoria(id) {
        this.swalQuestion({
            opts: {
                title: "¿Desactivar categoría?",
                html: "Esta acción desactivará la categoría y sus subcategorías asociadas."
            },
            data: { opc: "disableCategoria", id },
            methods: {
                request: () => {
                    alert({
                        icon: "success",
                        title: "Categoría desactivada",
                        text: "La categoría fue desactivada correctamente.",
                        btn1: true
                    });
                    this.lsCategorias();
                }
            }
        });
    }

    addSubcategoria() {
        if (!this.categoriaSeleccionada) {
            alert({
                icon: "warning",
                text: "Selecciona una categoría primero",
                btn1: true,
                btn1Text: "Ok"
            });
            return;
        }

        this.createModalForm({
            id: 'frmAddSubcategoria',
            data: { 
                opc: 'addSubcategoria',
                idCategoria: this.categoriaSeleccionada.id
            },
            bootbox: {
                title: `Nueva Subcategoría en: ${this.categoriaSeleccionada.nombre}`,
                closeButton: true
            },
            json: this.jsonSubcategoria(),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Subcategoría creada",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsSubcategorias();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    async editSubcategoria(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getSubcategoria", id } });

        if (request.status != 200 || !request.data) {
            alert({
                icon: "error",
                text: "No se pudo cargar la subcategoría",
                btn1: true,
                btn1Text: "Ok"
            });
            return;
        }

        const autofill = request.data;

        this.createModalForm({
            id: 'frmEditSubcategoria',
            data: { opc: 'editSubcategoria', id },
            bootbox: {
                title: `Editar Subcategoría: ${autofill.nombre || ''}`,
                closeButton: true
            },
            autofill: autofill,
            json: this.jsonSubcategoria(),
            success: (response) => {
                if (response.status === 200) {
                    alert({
                        icon: "success",
                        title: "Subcategoría actualizada",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.lsSubcategorias();
                } else {
                    alert({
                        icon: "error",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Ok"
                    });
                }
            }
        });
    }

    disableSubcategoria(id) {
        this.swalQuestion({
            opts: {
                title: "¿Desactivar subcategoría?",
                html: "Esta acción desactivará la subcategoría seleccionada."
            },
            data: { opc: "disableSubcategoria", id },
            methods: {
                request: () => {
                    alert({
                        icon: "success",
                        title: "Subcategoría desactivada",
                        text: "La subcategoría fue desactivada correctamente.",
                        btn1: true
                    });
                    this.lsSubcategorias();
                }
            }
        });
    }

    jsonCategoria() {
        return [
            {
                opc: "input",
                lbl: "Nombre de la Categoría",
                id: "nombre",
                tipo: "texto",
                class: "col-12 mb-3"
            },
            {
                opc: "select",
                lbl: "Tipo de Movimiento",
                id: "tipoMovimiento",
                class: "col-12 mb-3",
                data: this.tiposMovimiento
            }
        ];
    }

    jsonSubcategoria() {
        return [
            {
                opc: "input",
                lbl: "Nombre de la Subcategoría",
                id: "nombre",
                tipo: "texto",
                class: "col-12 mb-3"
            },
            {
                opc: "input",
                lbl: "Tarifa (opcional)",
                id: "tarifa",
                tipo: "cifra",
                class: "col-12 col-md-6 mb-3",
                required: false
            },
            {
                opc: "select",
                lbl: "Estado",
                id: "activo",
                class: "col-12 col-md-6 mb-3",
                data: [
                    { id: "1", valor: "Activo" },
                    { id: "0", valor: "Inactivo" }
                ]
            }
        ];
    }
}
