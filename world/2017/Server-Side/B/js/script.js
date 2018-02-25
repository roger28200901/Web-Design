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
                fromPlace: 'Danube Delta',
                toPlace: 'Corniche Beach',
                departureTime: '00:00:00'
            }
        },
        places: []
    },
    mounted: function () {
        this.getPlacesList();
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
        searchRoutes: function () {
            var self = this;

            if (!self.user.token) {
                $('#navbarDropdownMenuLink').dropdown('toggle');
                return;
            }

            var fromPlaceId = '',
                toPlaceId = '';

            self.places.forEach(function (place) {
                if (place.name == self.forms.search.fromPlace) {
                    fromPlaceId = place.place_id;
                }
                if (place.name == self.forms.search.toPlace) {
                    toPlaceId = place.place_id;
                }
            });

            /* Sending Ajax To Search Routes */
            $.ajax({
                url: self.baseAPIUrl + `/route/search/${fromPlaceId}/${toPlaceId}/${self.forms.search.departureTime}`,
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

            /* Sending Ajax To Get Places List */
            $.ajax({
                url: self.baseAPIUrl + '/place',
                type: 'get',
                contentType: 'application/json',
                data: {
                    token: self.user.token
                },
                success: function (response) {
                    self.places = response;
                    self.drawPlaces();
                },
                statusCode: {
                    401: function (response) {
                        self.launchMessage('danger', response.responseJSON.message);
                    }
                }
            });
        },
        drawPlaces: function () {
            var html = '';

            this.places.forEach(function (place, i) {
                html += `<circle class="point-${i}" cx="${place.x}" cy="${place.y}" r="8" data-toggle="popover" title="${place.name + ' ' + place.place_id}"></circle>`;
                html += `<text class="point-${i}" x="${place.x - 30}" y="${place.y + 25}" data-toggle="popover" title="${place.name}">${place.name}</text>`;
                html += `<span id="point-${i}"><img src="../A/public/img/${place.image_path}">Description: <br>${place.description}</span>`;
            });

            $('#Layer_places').html(html);
        }
    }
});
