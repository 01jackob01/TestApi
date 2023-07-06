export default {
    showHideShared(id) {
        if (document.getElementById(id).style.display  === 'none') {
            document.getElementById(id).style.display = 'table';
            let arrow = document.getElementById(id + "Arrow");
            if (arrow) {
                arrow.classList.remove("down");
                arrow.classList.add("up");
            }
        } else {
            document.getElementById(id).style.display = 'none';
            let arrow = document.getElementById(id + "Arrow");
            if (arrow) {
                arrow.classList.remove("up");
                arrow.classList.add("down");
            }
        }
    },
    async checkPageShared() {
        try {
            this.loadingScreen('true');
            let response = await fetch("api/api.php?c=Panel&f=checkPage");
            let page = await response.json();
            if (page.pageCheck === false) {
                window.location.replace("/errorPage.html");
            }
            this.loadingScreen('false');
        } catch (error) {
            console.log(error);
        }
    },
    async logoutSystemShared() {
        try {
            this.loadingScreen('true');
            let response = await fetch("api/api.php?c=Login&f=logoutFromPanel");
            let logoutSystem = await response.json();
            if (logoutSystem.logout === true) {
                window.location.replace("/index.html");
            } else {
                console.log('Nie wylogowano poprawnie');
            }
            this.loadingScreen('false');
        } catch (error) {
            console.log(error);
        }
    },
    loadingScreenShared(show) {
        let loader = document.getElementById('loader');
        if (typeof loader !== 'undefined' && loader !== null) {
            if (show == 'true') {
                document.getElementById('loader').style.display = 'block';
            } else {
                document.getElementById('loader').style.display = 'none';
            }
        }
    },
    loadingScreenStartShared() {
        this.loadingScreen('false');
    }
}