
var app = new Vue({
    el: 'main',
    data: {
        comments: [],
        username: "",
        email: "",
        title: "",
        comment: "",
        error: "",
        errors: {}
    },
    methods: {
        async fnPostComment(form) {
            return fetch('/?controller=Index&method=fnPostCommentJSON', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(form)
            })
            .then(response => response.json())
            .catch(error => console.error(error))
        },
        async fnGetAllComments(form) {
            return fetch('/?controller=Index&method=fnGetCommentsJSON', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .catch(error => console.error(error))
        },
        async fnSubmitComment() {
            this.errors = {};

            if (!this.username) {
                this.errors.username = 'Пожалуйста, введите имя';
            }

            if (!this.email) {
                this.errors.email = 'Пожалуйста, введите email';
            } else if (!this.fnValidEmail(this.email)) {
                this.errors.email = 'Пожалуйста, введите корректный email';
            }

            if (!this.title) {
                this.errors.title = 'Пожалуйста, введите заголовок';
            }

            if (!this.comment) {
                this.errors.comment = 'Пожалуйста, введите сообщение';
            }

            if (Object.keys(this.errors).length != 0) {
                return;
            }
            
            this.error = ""
            var form = {
                username: this.username,
                email: this.email,
                title: this.title,
                comment: this.comment,
            }
            var resp = await this.fnPostComment(form)
            if (resp.code=="error") {
                this.error = resp.message
            } else {
                this.comments.push(resp.fields)
                this.username = ""
                this.email = ""
                this.title = ""
                this.comment = ""
            }
        },
        fnValidEmail(sEmail) {
            return /\S+@\S+\.\S+/.test(sEmail);
        }
    },
    async mounted() {
        this.comments = await this.fnGetAllComments()
    }
})