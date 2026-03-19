let api = 'ctrl/ctrl-tareas.php';
let app;

$(function () {
    app = new App(api, 'root');
    app.init();
});

class App extends Templates {

    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "tareas";
    }

    init() {
        this.render();
    }

    render() {
        this.layout();
        this.createFilterBar();
        this.ls();
    }

    layout() {
        this.primaryLayout({
            parent: 'root',
            id: this.PROJECT_NAME,
            class: 'flex p-2',
        });
    }

    createFilterBar() {
        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            data: [
                {
                    opc: "btn",
                    class: "col-sm-3",
                    color_btn: "primary",
                    id: "btnAdd",
                    text: "Nueva Tarea",
                    fn: `app.addTarea()`,
                },
                {
                    opc: "btn",
                    class: "col-sm-2",
                    color_btn: "secondary",
                    id: "btnRefresh",
                    text: "Actualizar",
                    fn: `app.ls()`,
                },
            ],
        });
    }

    ls() {
        this.createTable({
            parent: `container${this.PROJECT_NAME}`,
            idFilterBar: `filterBar${this.PROJECT_NAME}`,
            data: { opc: "ls" },
            conf: { datatable: true, pag: 10 },
            coffeesoft: true,
            attr: {
                id: `tb${this.PROJECT_NAME}`,
                theme: 'shadcdn',
                title: 'Lista de Tareas',
                subtitle: 'Tareas registradas en el sistema',
                center: [3, 4, 5],
                right: [6],
                extends: true,
            },
        });
    }

    addTarea() {
        this.createModalForm({
            id: "formTarea",
            bootbox: {
                title: "Nueva Tarea",
                closeButton: true
            },
            data: { opc: "addTarea" },
            autofill: false,
            json: this.jsonTarea(),
            success: (response) => {
                if (response.status == 200) {
                    alert({
                        icon: "success",
                        title: "Tarea creada",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.ls();
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

    async editTarea(id) {
        const request = await useFetch({ url: this._link, data: { opc: "getTarea", id } });
        const tarea = request.data[0];

        this.createModalForm({
            id: "formEditTarea",
            bootbox: {
                title: "Editar Tarea",
                closeButton: true
            },
            data: { opc: "editTarea", id },
            autofill: tarea,
            json: this.jsonTarea(),
            success: (response) => {
                if (response.status == 200) {
                    alert({
                        icon: "success",
                        title: "Tarea actualizada",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Aceptar"
                    });
                    this.ls();
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

    deleteTarea(id) {
        const row = event.target.closest('tr');
        const titulo = row.querySelectorAll('td')[1]?.innerText || '';

        this.swalQuestion({
            opts: {
                title: '¿Esta seguro?',
                html: `¿Deseas eliminar la tarea <strong>${titulo}</strong>?`,
            },
            data: { opc: "deleteTarea", id: id },
            methods: {
                request: (data) => {
                    alert({
                        icon: "success",
                        title: "Eliminada",
                        text: "La tarea fue eliminada exitosamente.",
                        btn1: true
                    });
                    this.ls();
                },
            },
        });
    }

    jsonTarea() {
        return [
            {
                opc: "input",
                lbl: "Titulo de la tarea",
                id: "title",
                tipo: "texto",
                class: "col-12 mb-3"
            },
            {
                opc: "textarea",
                id: "description",
                lbl: "Descripcion",
                rows: 3,
                class: "col-12 mb-3"
            },
            {
                opc: "select",
                id: "priority",
                lbl: "Prioridad",
                class: "col-12 col-sm-6 mb-3",
                data: [
                    { id: 1, valor: "Baja" },
                    { id: 2, valor: "Media" },
                    { id: 3, valor: "Alta" }
                ]
            },
            {
                opc: "select",
                id: "status",
                lbl: "Estado",
                class: "col-12 col-sm-6 mb-3",
                data: [
                    { id: 1, valor: "Pendiente" },
                    { id: 2, valor: "En progreso" },
                    { id: 3, valor: "Completada" }
                ]
            },
            {
                opc: "btn-submit",
                id: "btnGuardarTarea",
                text: "Guardar Tarea",
                class: "col-12"
            }
        ];
    }
}
