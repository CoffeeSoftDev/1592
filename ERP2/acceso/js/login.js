let api = 'ctrl/ctrl-login.php';
let app;

$(function () {
    app = new App(api, 'root');
    app.init();
});

class App extends Templates {

    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Login";
    }

    init() {
        this.render();
    }

    render() {
        this.layout();
        this.lsLogin();
    }

    layout() {
        this.primaryLayout({
            parent: 'root',
            id: this.PROJECT_NAME,
            class: 'd-flex justify-content-center align-items-center min-vh-100',
            card: {
                filterBar: {
                    class: 'w-full d-none',
                    id: `filterBar${this.PROJECT_NAME}`
                },
                container: {
                    class: 'col-12 col-md-6 col-lg-4',
                    id: `container${this.PROJECT_NAME}`
                }
            }
        });
    }

    filterBar() {
    }

    lsLogin() {
        $(`#container${this.PROJECT_NAME}`).html(`
            <div class="card shadow-sm p-4 bg-white">
                <div class="text-center mb-4">
                    <img src="../src/img/logos/logo_row.png" alt="15-92" class="img-fluid" style="max-height: 80px;">
                    <h4 class="mt-3 fw-bold">Bienvenido a 15-92</h4>
                </div>
                <div id="loginFormContainer"></div>
            </div>
        `);

        this.createForm({
            parent: "loginFormContainer",
            id: "formLogin",
            data: { opc: "addSession" },
            json: this.jsonLogin(),
            success: (response) => {
                if (response.status == 200) {
                    const path = response.data && response.data.path ? response.data.path : "";
                    const HREF = new URL(window.location.href);
                    const ERP = HREF.pathname.split("/").filter(Boolean)[0];

                    localStorage.clear();
                    sessionStorage.clear();

                    window.location.href = HREF.origin + "/" + ERP + "/" + path;
                } else {
                    alert({
                        icon: "error",
                        title: "Acceso denegado",
                        text: response.message,
                        btn1: true,
                        btn1Text: "Reintentar"
                    });
                }
            }
        });
    }

    jsonLogin() {
        return [
            {
                opc: "label",
                id: "lblLogin",
                text: "Iniciar sesión",
                class: "col-12 text-center fw-bold mb-3"
            },
            {
                opc: "input",
                lbl: "Usuario",
                id: "usuario",
                tipo: "texto",
                class: "col-12 mb-3"
            },
            {
                opc: "input",
                lbl: "Contraseña",
                id: "clave",
                type: "password",
                tipo: "texto",
                class: "col-12 mb-3"
            },
            {
                opc: "btn-submit",
                id: "btnLogin",
                text: "Iniciar sesión",
                class: "col-12"
            }
        ];
    }
}
