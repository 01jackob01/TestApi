import sharedScripts from "./shared/sharedScripts.js";

Vue.createApp({
    data() {
        return {
            loginError: '',
            userLogin: ''
        };
    },
    methods: {
        async getUserLogin() {
            try {
                this.loadingScreen('true');
                let response = await fetch("api/api.php?c=Panel&f=getUserLogin");
                let user = await response.json();
                if (user.login === false) {
                    window.location.replace("/index.html");
                }
                this.userLogin = user.login;
                this.loadingScreen('false');
            } catch (error) {
                console.log(error);
            }
        },
        async getOrders() {
            try {
                this.loadingScreen('true');
                let response = await fetch("api/api.php?c=Orders&f=getAllOrders");
                let user = await response.json();
                if (user.login === false) {
                    window.location.replace("/index.html");
                }
                this.userLogin = user.login;
                this.loadingScreen('false');
            } catch (error) {
                console.log(error);
            }
        },
        async showSegment() {
            if (document.getElementById(event.currentTarget.id + 'ToShow').style.display  === 'none') {
                document.getElementById(event.currentTarget.id + 'ToShow').style.display = 'table';
            } else {
                document.getElementById(event.currentTarget.id + 'ToShow').style.display = 'none';
            }
        },
        loadingScreen: sharedScripts.loadingScreenShared,
        loadingScreenStart: sharedScripts.loadingScreenStartShared,
        checkPage: sharedScripts.checkPageShared,
        logoutSystem: sharedScripts.logoutSystemShared
    },
    mounted() {
        Promise.all([
            this.loadingScreenStart(),
            this.checkPage(),
            this.getUserLogin(),
        ]);
    },
}).mount('#panel');