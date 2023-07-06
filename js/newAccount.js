import sharedScripts from "./shared/sharedScripts.js";

Vue.createApp({
    data() {
        return {
            createError: '',
            createSuccess: '',
            login: '',
            password: '',
        };
    },
    methods: {
        async createNewAccount() {
            try {
                this.loadingScreen('true');
                const dataToLogin = this.getDataToCreate();
                const requestOptions = {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: dataToLogin
                };
                let response = await fetch("api/api.php", requestOptions);
                let loginData = await response.json();
                if (loginData.addNewUser === false) {
                    this.createError = loginData.message;
                    this.createSuccess = '';
                } else {
                    this.createError = '';
                    this.createSuccess = 'Poprawnie utworzono konto'
                    this.login = '';
                    this.password = '';
                }
                this.loadingScreen('false');
            } catch (error) {
                console.log(error);
            }
        },
        getDataToCreate() {
            let data = {
                "c": "NewAccount",
                "f": "createNewAccount",
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
            this.loadingScreenStart,
        ]);
    },
}).mount('#newAccount');
