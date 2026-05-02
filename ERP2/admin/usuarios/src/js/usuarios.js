let api = 'ctrl/ctrl-usuarios.php';
let usuarios, roles, departamentos;

$(async () => {
    const data = await useFetch({ url: api, data: { opc: "init" } });
    const dataTabs = await useFetch({ url: api, data: { opc: "initTabs" } });

    usuarios = new Usuarios(api, 'root');
    usuarios.roles = data.roles || [];
    usuarios.estados = data.estados || [];
    usuarios.departamentosData = data.departamentos || [];
    usuarios.rolesDB = dataTabs.rolesDB || [];
    usuarios.departamentosTab = dataTabs.departamentos || [];

    roles = new Roles(api, 'root');
    departamentos = new Departamentos(api, 'root');

    usuarios.init();
});

class App extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "App";
    }

    init() {
        this.render();
    }

    render() {
        this.layout();
        this.filterBar();
        // this.renderInfoCards();
        this.lsUsuarios();
        lucide.createIcons();
    }

    layout() {
        this.createLayout({
            parent: 'root',
            design: false,
            data: {
                id: this.PROJECT_NAME,
                class: 'au-wrapper p-3',
                container: [
                    {
                        type: 'div',
                        id: `filterBar${this.PROJECT_NAME}`,
                        class: 'w-full mb-3 py-2'
                    },
                    {
                        type: 'div',
                        id: `cards${this.PROJECT_NAME}`,
                        class: 'w-full mb-4'
                    },
                    {
                        type: 'div',
                        id: `container${this.PROJECT_NAME}`,
                        class: 'w-full h-full'
                    }
                ]
            }
        });

        this.layoutTabs();
        roles.filterBarRoles();
        departamentos.filterBarDepartamentos();
    }

    layoutTabs() {
        this.tabLayout({
            parent: `container${this.PROJECT_NAME}`,
            id: "tabsAdmin",
            content: { class: "" },
            theme: "light",
            type: 'short',
            json: [
                {
                    id: "usuarios",
                    tab: "Usuarios",
                    class: 'mb-1',
                    onClick: () => this.lsUsuarios(),
                    active: true,
                },
                {
                    id: "roles",
                    tab: "Roles",
                    onClick: () => roles.lsRoles()
                },
                {
                    id: "departamentos",
                    tab: "Departamentos",
                    onClick: () => departamentos.lsDepartamentos()
                }
            ]
        });

        $(`#container${this.PROJECT_NAME}`).prepend(`
        <div class="px-4 pt-3 pb-3">
            <h2 class="text-2xl font-semibold">Administracion</h2>
            <p class="text-gray-400">Gestiona usuarios, roles y departamentos.</p>
        </div>`);
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
                    class: "col-sm-3",
                    data: [
                        { id: "", valor: "Todos" },
                        ...this.roles
                    ],
                    onchange: `${this.PROJECT_NAME.toLowerCase()}.lsUsuarios()`
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "primary",
                    id: "btnNuevoUsuario",
                    text: "Nuevo usuario",
                    lucideIcon: "user-plus",
                    fn: `${this.PROJECT_NAME.toLowerCase()}.addUsuario()`
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnRefreshUsuarios",
                    text: "Actualizar",
                    lucideIcon: "refresh-cw",
                    fn: `${this.PROJECT_NAME.toLowerCase()}.refresh()`
                }
            ]
        });
    }

    async renderInfoCards() {
        const response = await useFetch({ url: this._link, data: { opc: "showUsuarios" } });
        const counts = (response && response.counts) ? response.counts : { total: 0, activos: 0, inactivos: 0, administradores: 0 };
        const pctActivos = counts.total > 0 ? Math.round((counts.activos / counts.total) * 100) : 0;
        const pctInactivos = counts.total > 0 ? Math.round((counts.inactivos / counts.total) * 100) : 0;

        this.infoCard({
            parent: `cards${this.PROJECT_NAME}`,
            theme: "light",
            style: "file",
            class: "pt-1 pb-2",
            json: [
                {
                    id: "kpiTotalUsuarios",
                    title: "Total usuarios",
                    subtitle: "Registrados",
                    bgColor: "bg-blue-50",
                    borderColor: "border-blue-200",
                    data: {
                        value: counts.total,
                        color: "text-blue-700"
                    }
                },
                {
                    id: "kpiActivosUsuarios",
                    title: "Activos",
                    subtitle: `${pctActivos}% del total`,
                    bgColor: "bg-green-50",
                    borderColor: "border-green-200",
                    data: {
                        value: counts.activos,
                        color: "text-green-700"
                    }
                },
                {
                    id: "kpiInactivosUsuarios",
                    title: "Inactivos",
                    subtitle: `${pctInactivos}% del total`,
                    bgColor: "bg-red-50",
                    borderColor: "border-red-200",
                    data: {
                        value: counts.inactivos,
                        color: "text-red-700"
                    }
                },
                {
                    id: "kpiAdminUsuarios",
                    title: "Administradores",
                    subtitle: "Acceso total",
                    bgColor: "bg-purple-50",
                    borderColor: "border-purple-200",
                    data: {
                        value: counts.administradores,
                        color: "text-purple-700"
                    }
                }
            ]
        });
    }

    toggleTheme() {
        const current = document.documentElement.getAttribute('data-au-theme') || 'light';
        const next = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-au-theme', next);
        localStorage.setItem('au-theme', next);
    }

    refresh() {
        this.renderInfoCards();
        this.lsUsuarios();
    }

    lsUsuarios() {
        this.createTable({
            parent: `container-usuarios`,
            idFilterBar: `filterBar${this.PROJECT_NAME}`,
            data: { opc: "lsUsuarios" },
            conf: { datatable: true, pag: 10 },
            coffeesoft: true,
            attr: {
                id: `tb${this.PROJECT_NAME}`,
                theme: 'corporativo',
                center: [1, 4],
                right: [8]
            },
            success: () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
    }
}

class Usuarios extends App {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Usuarios";
    }

    // init() {
    //     this.render();
    // }

    // render() {
    //     this.layout();
    //     this.filterBar();
    //     // this.renderInfoCards();
    //     this.lsUsuarios();
    //     lucide.createIcons();
    // }

    render(){
        this.layout();
    }

    layout() {
        this.createLayout({
            parent: 'container-usuarios',
            design: false,
            data: {
                id: 'content'.this.PROJECT_NAME,
                class: 'au-wrapper p-3',
                container: [
                    {
                        type: 'div',
                        id: `filterBar${this.PROJECT_NAME}`,
                        class: 'w-full mb-3 py-2'
                    },
                    {
                        type: 'div',
                        id: `cards${this.PROJECT_NAME}`,
                        class: 'w-full mb-4'
                    },
                    {
                        type: 'div',
                        id: `container${this.PROJECT_NAME}`,
                        class: 'w-full h-full'
                    }
                ]
            }
        });

        this.layoutTabs();
        roles.filterBarRoles();
        departamentos.filterBarDepartamentos();
    }

    addUsuario() {
        this.createModalForm({
            id: 'frmAddUsuario',
            bootbox: {
                title: 'Nuevo usuario',
                closeButton: true,
                size: 'large'
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
                closeButton: true,
                size: 'large'
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

    setupPasswordToggle(formId) {
        const $pwd = $(`#${formId} [name="password"]`);
        if (!$pwd.length) return;
        $pwd.attr('type', 'password');

        $pwd.wrap($("<div>", { class: "input-group" }));
        const $btn = $("<button>", {
            type: 'button',
            class: 'btn btn-outline-secondary',
            tabindex: -1
        });
        $("<i>", { "data-lucide": "eye", class: "w-4 h-4" }).appendTo($btn);
        $btn.on('click', function () {
            const isPassword = $pwd.attr('type') === 'password';
            $pwd.attr('type', isPassword ? 'text' : 'password');
            const $icon = $(this).find('i');
            $icon.attr('data-lucide', isPassword ? 'eye-off' : 'eye');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
        $pwd.after($btn);
    }

    deleteUsuario(id) {
        this.swalQuestion({
            opts: {
                title: "¿Eliminar usuario?",
                html: "Esta accion desactivara el acceso del usuario al sistema."
            },
            data: { opc: "deleteUsuario", id },
            methods: {
                request: () => {
                    alert({
                        icon: "success",
                        title: "Usuario eliminado",
                        text: "El usuario fue eliminado correctamente.",
                        btn1: true
                    });
                    this.refresh();
                }
            }
        });
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
                data: [
                    { id: "Direccion General", valor: "Direccion General" },
                    { id: "Finanzas",          valor: "Finanzas" },
                    { id: "Recursos Humanos",  valor: "Recursos Humanos" },
                    { id: "Operaciones",       valor: "Operaciones" },
                    { id: "Ventas",            valor: "Ventas" }
                ]
            },
            {
                opc: "select",
                lbl: "Rol",
                id: "rol",
                class: "col-12 col-md-6 mb-3",
                data: [
                    { id: "admin",  valor: "Administrador" },
                    { id: "editor", valor: "Editor" },
                    { id: "viewer", valor: "Lector" }
                ]
            },
            {
                opc: "select",
                lbl: "Estado",
                id: "estado",
                class: "col-12 col-md-6 mb-3",
                data: [
                    { id: "activo",    valor: "Activo" },
                    { id: "inactivo",  valor: "Inactivo" },
                    { id: "pendiente", valor: "Pendiente" }
                ]
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

class Roles extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Roles";
    }

    filterBarRoles() {
        const container = $("#container-roles");
        container.html('<div id="filterbar-roles" class="mb-2"></div><div id="table-roles"></div>');

        this.createfilterBar({
            parent: "filterbar-roles",
            coffeesoft: true,
            data: [
                {
                    opc: "btn",
                    class: "col-sm-3",
                    color_btn: "primary",
                    id: "btnNuevoRol",
                    text: "Nuevo Rol",
                    lucideIcon: "plus",
                    fn: 'roles.addRol()'
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnRefreshRoles",
                    text: "Actualizar",
                    lucideIcon: "refresh-cw",
                    fn: 'roles.lsRoles()'
                }
            ]
        });
    }

    lsRoles() {
        this.createTable({
            parent: "table-roles",
            idFilterBar: "filterbar-roles",
            data: { opc: "lsRoles" },
            coffeesoft: true,
            conf: { datatable: true, pag: 10 },
            attr: {
                id: "tbRoles",
                theme: 'corporativo',
                title: 'Roles del sistema',
                subtitle: 'Lista de roles registrados',
                center: [0]
            },
            success: () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
    }

    addRol() {
        this.createModalForm({
            id: 'frmAddRol',
            data: { opc: 'addRol' },
            bootbox: {
                title: 'Agregar Rol',
                closeButton: true,
                size: 'large'
            },
            json: this.jsonRol(false),
            success: (response) => {
                if (response.status === 200) {
                    alert({ icon: "success", title: "Rol creado", text: response.message, btn1: true, btn1Text: "Aceptar" });
                    this.lsRoles();
                } else {
                    alert({ icon: "error", text: response.message, btn1: true, btn1Text: "Ok" });
                }
            }
        });
    }

    async editRol(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getRol", id } });

        if (request.status != 200 || !request.data || !request.data[0]) {
            alert({ icon: "error", text: "No se pudo cargar el rol", btn1: true, btn1Text: "Ok" });
            return;
        }

        const autofill = request.data[0];

        this.createModalForm({
            id: 'frmEditRol',
            data: { opc: 'editRol', id },
            bootbox: {
                title: `Editar Rol: ${autofill.nombre || ''}`,
                closeButton: true,
                size: 'large'
            },
            autofill: autofill,
            json: this.jsonRol(true),
            success: (response) => {
                if (response.status === 200) {
                    alert({ icon: "success", title: "Rol actualizado", text: response.message, btn1: true, btn1Text: "Aceptar" });
                    this.lsRoles();
                } else {
                    alert({ icon: "error", text: response.message, btn1: true, btn1Text: "Ok" });
                }
            }
        });
    }

    deleteRol(id) {
        this.swalQuestion({
            opts: {
                title: "¿Eliminar rol?",
                html: "Esta accion eliminara el rol del sistema. Los usuarios asignados a este rol podrian verse afectados."
            },
            data: { opc: "deleteRol", id },
            methods: {
                request: () => {
                    alert({ icon: "success", title: "Rol eliminado", text: "El rol fue eliminado correctamente.", btn1: true });
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

class Departamentos extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Departamentos";
    }

    filterBarDepartamentos() {
        const container = $("#container-departamentos");
        container.html('<div id="filterbar-departamentos" class="mb-2"></div><div id="table-departamentos"></div>');

        this.createfilterBar({
            parent: "filterbar-departamentos",
            coffeesoft: true,
            data: [
                {
                    opc: "btn",
                    class: "col-sm-3",
                    color_btn: "primary",
                    id: "btnNuevoDepto",
                    text: "Nuevo Departamento",
                    lucideIcon: "plus",
                    fn: 'departamentos.addDepartamento()'
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnRefreshDepto",
                    text: "Actualizar",
                    lucideIcon: "refresh-cw",
                    fn: 'departamentos.lsDepartamentos()'
                }
            ]
        });
    }

    lsDepartamentos() {
        this.createTable({
            parent: "table-departamentos",
            idFilterBar: "filterbar-departamentos",
            data: { opc: "lsDepartamentos" },
            coffeesoft: true,
            conf: { datatable: true, pag: 10 },
            attr: {
                id: "tbDepartamentos",
                theme: 'corporativo',
                title: 'Departamentos del sistema',
                subtitle: 'Lista de departamentos registrados',
                center: [0]
            },
            success: () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
    }

    addDepartamento() {
        this.createModalForm({
            id: 'frmAddDepartamento',
            data: { opc: 'addDepartamento' },
            bootbox: {
                title: 'Agregar Departamento',
                closeButton: true,
                size: 'large'
            },
            json: this.jsonDepartamento(false),
            success: (response) => {
                if (response.status === 200) {
                    alert({ icon: "success", title: "Departamento creado", text: response.message, btn1: true, btn1Text: "Aceptar" });
                    this.lsDepartamentos();
                } else {
                    alert({ icon: "error", text: response.message, btn1: true, btn1Text: "Ok" });
                }
            }
        });
    }

    async editDepartamento(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getDepartamento", id } });

        if (request.status != 200 || !request.data || !request.data[0]) {
            alert({ icon: "error", text: "No se pudo cargar el departamento", btn1: true, btn1Text: "Ok" });
            return;
        }

        const autofill = request.data[0];

        this.createModalForm({
            id: 'frmEditDepartamento',
            data: { opc: 'editDepartamento', id },
            bootbox: {
                title: `Editar Departamento: ${autofill.nombre || ''}`,
                closeButton: true,
                size: 'large'
            },
            autofill: autofill,
            json: this.jsonDepartamento(true),
            success: (response) => {
                if (response.status === 200) {
                    alert({ icon: "success", title: "Departamento actualizado", text: response.message, btn1: true, btn1Text: "Aceptar" });
                    this.lsDepartamentos();
                } else {
                    alert({ icon: "error", text: response.message, btn1: true, btn1Text: "Ok" });
                }
            }
        });
    }

    deleteDepartamento(id) {
        this.swalQuestion({
            opts: {
                title: "¿Eliminar departamento?",
                html: "Esta accion eliminara el departamento del sistema. Los usuarios asignados a este departamento podrian verse afectados."
            },
            data: { opc: "deleteDepartamento", id },
            methods: {
                request: () => {
                    alert({ icon: "success", title: "Departamento eliminado", text: "El departamento fue eliminado correctamente.", btn1: true });
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