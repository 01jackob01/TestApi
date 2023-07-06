import sharedScripts from "./shared/sharedScripts.js";

Vue.createApp({
    data() {
        return {
            loginError: '',
            loginSuccess: '',
            login: '',
            password: '',
        };
    },
    methods: {
        async loginToAdminPanel() {
            try {
                this.loadingScreen('true');
                const dataToLogin = this.getDataToLogin();
                const requestOptions = {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: dataToLogin
                };
                let response = await fetch("api/api.php", requestOptions);
                let loginData = await response.json();
                if (loginData.loginError === 'true') {
                    this.loginError = loginData.errorInfo;
                    this.loginSuccess = '';
                } else {
                    this.loginError = '';
                    this.loginSuccess = 'Poprawnie zalogowano do systemu'
                    window.location.replace("/panel.html");
                }
                this.loadingScreen('false');
            } catch (error) {
                console.log(error);
            }
        },
        async checkPage() {
            try {
                this.loadingScreen('true');
                let response = await fetch("api/api.php?c=Panel&f=checkPage");
                let page = await response.json();
                if (page.pageCheck === true) {
                    window.location.replace("/panel.html");
                }
                this.loadingScreen('false');
            } catch (error) {
                console.log(error);
            }
        },
        getDataToLogin() {
            let data = {
                'c': 'Login',
                "f": "loginToPanel",
                "login": this.login,
                "password": this.password,
            };

            return JSON.stringify(data);
        },
        loadingScreen: sharedScripts.loadingScreenShared,
        loadingScreenStart: sharedScripts.loadingScreenStartShared
    },
    mounted() {
        Promise.all([
            this.loadingScreenStart(),
            this.checkPage(),
        ]);
    },
}).mount('#index');
