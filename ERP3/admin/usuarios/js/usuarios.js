let api = 'ctrl/ctrl-usuarios.php';
let usuarios;

$(function () {
    usuarios = new Usuarios(api, 'root');
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
        this.renderHeader();
        this.renderInfoCards();
        this.lsUsuarios();
    }

    layout() {
        this.createLayout({
            parent: 'root',
            design: false,
            data: {
                id: this.PROJECT_NAME,
                class: 'au-wrapper p-4',
                container: [
                    {
                        type: 'div',
                        id: `header${this.PROJECT_NAME}`,
                        class: 'w-full mb-4'
                    },
                    {
                        type: 'div',
                        id: `cards${this.PROJECT_NAME}`,
                        class: 'w-full mb-4'
                    },
                    {
                        type: 'div',
                        id: `filterBar${this.PROJECT_NAME}`,
                        class: 'w-full mb-3 p-3 border rounded-lg'
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
        $(`#header${this.PROJECT_NAME}`).html(
            `<div class="au-eyebrow">Administracion</div>
             <div class="au-title">Usuarios del sistema</div>`
        );
    }

    filterBar() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            data: [
                {
                    opc: "btn",
                    class: "col-sm-3",
                    color_btn: "primary",
                    id: "btnNuevoUsuario",
                    text: "Nuevo usuario",
                    icono: "icon-plus",
                    fn: `${this.PROJECT_NAME.toLowerCase()}.addUsuario()`
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnRefreshUsuarios",
                    text: "Actualizar",
                    icono: "icon-arrows-cw",
                    fn: `${this.PROJECT_NAME.toLowerCase()}.refresh()`
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnThemeToggleUsuarios",
                    text: "Tema",
                    icono: "icon-adjust",
                    fn: `${this.PROJECT_NAME.toLowerCase()}.toggleTheme()`
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
}

class Usuarios extends App {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Usuarios";
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
                title: 'Usuarios registrados',
                subtitle: 'Lista completa de usuarios del sistema',
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

        // Mapear nivel_id a rol del select
        const nivelToRol = { 1: 'admin', 5: 'editor', 3: 'viewer' };
        if (autofill.nivel_id) {
            autofill.rol = nivelToRol[autofill.nivel_id] || autofill.rol;
        }

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
        $pwd.wrap('<div class="input-group"></div>');
        const $btn = $('<button>', {
            type: 'button',
            class: 'btn btn-outline-secondary',
            html: '<i class="icon-eye"></i>',
            tabindex: -1
        });
        $btn.on('click', function () {
            const isPassword = $pwd.attr('type') === 'password';
            $pwd.attr('type', isPassword ? 'text' : 'password');
            $(this).find('i').attr('class', isPassword ? 'icon-eye-off' : 'icon-eye');
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
