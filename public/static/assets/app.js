
var app = new Vue({
    el: 'main',
    data: {
        comments: [],
        username: "",
        email: "",
        title: "",
        comment: "",
        // comment_form: {
        //     username: "",
        //     email: "",
        //     title: "",
        //     comment: "",
        // }
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
        fnSubmitComment() {

        },
        fnUpdate() {

        }
    },
    async mounted() {
        this.comments = await this.fnGetAllComments()
    }
})