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
            },
            search: {
                fromPlaceId: '',
                toPlaceId: '',
                departureTime: ''
            }
        }
    },
    methods: {
        userLogin: function () {
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
        userLogout: function () {
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
                        //
                    }
                }
            });
        },
        routeSearch: function () {
            var self = this;

            if (!self.user.token) {
                $('#navbarDropdownMenuLink').dropdown('toggle');
                return;
            }

            if (self.forms.search.departureTime) {
                self.forms.search.departureTime += ':00';
            }

            /* Sending Ajax To Search Routes */
            $.ajax({
                url: self.baseAPIUrl + `/route/search/${self.forms.search.fromPlaceId}/${self.forms.search.toPlaceId}/${self.forms.search.departureTime}`,
                type: 'get',
                dataType: 'text',
                data: {
                    token: self.user.token
                },
                contentType: 'application/json',
                success: function (response) {
                    console.log(JSON.parse(response));
                },
                statusCode: {
                    401: function (response) {
                        //
                    }
                }
            });
        }
    }
});
