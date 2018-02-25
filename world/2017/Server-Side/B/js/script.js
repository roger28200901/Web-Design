const app = new Vue({
    el: '#app',
    data: {
        baseAPIUrl: '../A/api/v1',
        message: {
            type: '',
            content: ''
        },
        user: {
            token: '',
            role: ''
        },
        forms: {
            auth: {
                username: 'admin',
                password: '1234'
            },
            search: {
                fromPlaceId: '000',
                toPlaceId: '010',
                departureTime: '00:00:00'
            }
        },
        places: []
    },
    methods: {
        launchMessage: function (type, content) {
            this.message.type = type;
            this.message.content = content;
            $('#messageBox').modal();
        },
        userLogin: function () {
            var self = this;

            /* Sending Ajax To Login Service */
            $.ajax({
                url: self.baseAPIUrl + '/auth/login',
                type: 'post',
                contentType: 'application/json',
                data: JSON.stringify(self.forms.auth),
                dataType: 'json',
                success: function (response) {
                    self.launchMessage('success', 'Login Success');
                    self.user.token = response.token;
                    self.getPlacesList();
                },
                statusCode: {
                    401: function (response) {
                        self.launchMessage('danger', response.responseJSON.message);
                    }
                }
            });
        },
        userLogout: function () {
            var self = this;

            /* Sending Ajax To Logout Service */
            $.ajax({
                url: self.baseAPIUrl + '/auth/logout',
                type: 'get',
                data: {
                    token: self.user.token
                },
                dataType: 'json',
                success: function (response) {
                    self.launchMessage('success', 'Logout Success');
                    self.user.token = '';
                },
                statusCode: {
                    401: function (response) {
                        self.launchMessage('danger', response.responseJSON.message);
                    }
                }
            });
        },
        routesSearch: function () {
            var self = this;

            if (!self.user.token) {
                $('#navbarDropdownMenuLink').dropdown('toggle');
                return;
            }

            /* Sending Ajax To Search Routes */
            $.ajax({
                url: self.baseAPIUrl + `/route/search/${self.forms.search.fromPlaceId}/${self.forms.search.toPlaceId}/${self.forms.search.departureTime}`,
                type: 'get',
                contentType: 'application/json',
                data: {
                    token: self.user.token
                },
                success: function (response) {
                    //
                },
                statusCode: {
                    401: function (response) {
                        self.launchMessage('danger', response.responseJSON.message);
                    }
                }
            });
        },
        getPlacesList: function () {
            var self = this;

            $.ajax({
                url: self.baseAPIUrl + '/place',
                type: 'get',
                contentType: 'application/json',
                data: {
                    token: self.user.token
                },
                success: function (response) {
                    self.places = response;
                },
                statusCode: {
                    401: function (response) {
                        self.launchMessage('danger', response.responseJSON.message);
                    }
                }
            });
        }
    }
});
