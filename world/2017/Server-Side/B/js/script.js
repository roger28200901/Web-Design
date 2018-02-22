const app = new Vue({
    el: '#app',
    data: {
        baseAPIUrl: '../A/api/v1',
        user: {
            token: '',
            role: ''
        },
        forms: {
            auth: {
                username: 'admin',
                password: '1234'
            }
        }
    },
    methods: {
        login: function () {
            var self = this;

            /* Sending Ajax To Login Service */
            $.ajax({
                url: self.baseAPIUrl + '/auth/login',
                type: 'post',
                dataType: 'json',
                data: JSON.stringify(self.forms.auth),
                contentType: 'application/json',
                success: function (response) {
                    self.user.token = response.token;
                },
                statusCode: {
                    401: function (response) {
                    }
                }
            });
        },
        logout: function () {
            var self = this;

            /* Sending Ajax To Logout Service */
            $.ajax({
                url: self.baseAPIUrl + '/auth/logout',
                type: 'get',
                dataType: 'text',
                data: {
                    token: self.user.token
                },
                contentType: 'application/json',
                success: function (response) {
                    self.user.token = '';
                },
                statusCode: {
                    401: function (response) {
                    }
                }
            });
        }
    }
});
