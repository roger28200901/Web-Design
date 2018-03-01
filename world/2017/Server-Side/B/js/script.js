const app = new Vue({
    el: '#app',
    data: {
        baseAPIUrl: '../A/api/v1',
        message: {
            type: null,
            content: null
        },
        user: {
            token: null,
            role: null
        },
        forms: {
            auth: {
                username: null,
                password: null
            },
            search: {
                fromPlace: null,
                toPlace: null,
                departureTime: null
            },
            place: {
                mode: null,
                id: null,
                placeId: null,
                name: null,
                latitude: null,
                longitude: null,
                x: null,
                y: null,
                image: null,
                description: null
            }
        },
        places: []
    },
    mounted: function () {
        this.refresh();
        this.initialDatas();
    },
    methods: {
        refresh: function () {
            this.getPlacesList();
            this.popoverPlaces();
        },
        initialDatas: function () {
            this.initialUserData();
            this.initialAuthData();
            this.initialSearchData();
            this.initialPlaceData();
        },
        initialUserData: function () {
            this.user = {
                token: '',
                role: '',
            };
        },
        initialAuthData: function () {
            this.forms.auth = {
                username: 'admin',
                password: '1234'
            }
        },
        initialSearchData: function () {
            this.forms.search = {
                fromPlace: 'Danube Delta',
                toPlace: 'Corniche Beach',
                departureTime: '00:00:00'
            };
        },
        initialPlaceData: function () {
            this.forms.place = {
                mode: '',
                id: '',
                placeId: '011',
                name: 'Place name',
                latitude: '24.59895',
                longitude: '54.2522',
                x: '0',
                y: '0',
                image: '',
                description: 'Description'
            };
        },
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
                    self.user.role = response.role;
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
                    self.initialUserData();
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
                dataType: 'json',
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
        showPlacesList: function () {
            $('#placeListPanel').modal();
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
                dataType: 'json',
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
        createPlace: function () {
            this.initialPlaceData();
            this.forms.place.mode = 'CREATE';
            $('#placeForm').modal();
        },
        storePlace: function () {
            var self = this;

            $('#placeForm').modal('hide');

            var formData = new FormData();
            formData.append('place_id', self.forms.place.placeId);
            formData.append('name', self.forms.place.name);
            formData.append('latitude', self.forms.place.latitude);
            formData.append('longitude', self.forms.place.longitude);
            if (document.getElementById('placeImage').files[0]) {
                formData.append('image', document.getElementById('placeImage').files[0]);
            }
            formData.append('description', self.forms.place.description);

            /* Sending Ajax To Store Place */
            $.ajax({
                url: self.baseAPIUrl + `/place?token=${self.user.token}`,
                type: 'post',
                contentType: false,
                data: formData,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    self.launchMessage('success', response.message);
                    self.refresh();
                },
                error: function (response) {
                    self.launchMessage('danger', response.responseJSON.message);
                }
            });
        },
        editPlace: function (index) {
            var place = this.places[index];
            this.forms.place.mode = 'EDIT';
            this.forms.place.id = place.id;
            this.forms.place.placeId = place.place_id;
            this.forms.place.name = place.name;
            this.forms.place.latitude = place.latitude;
            this.forms.place.longitude = place.longitude;
            this.forms.place.description = place.description;
            $('#placeForm').modal();
            $('#placeListPanel').modal('hide');
        },
        updatePlace: function () {
            var self = this;

            $('#placeForm').modal('hide');

            var formData = new FormData();
            formData.append('place_id', self.forms.place.placeId);
            formData.append('name', self.forms.place.name);
            formData.append('latitude', self.forms.place.latitude);
            formData.append('longitude', self.forms.place.longitude);
            if (document.getElementById('placeImage').files[0]) {
                formData.append('image', document.getElementById('placeImage').files[0]);
            }
            formData.append('description', self.forms.place.description);

            /* Sending Ajax To Update Place */
            $.ajax({
                url: self.baseAPIUrl + `/place/${self.forms.place.id}?token=${self.user.token}`,
                type: 'post',
                contentType: false,
                data: formData,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    self.launchMessage('success', response.message);
                    self.refresh();
                },
                error: function (response) {
                    self.launchMessage('danger', response.responseJSON.message);
                }
            });
        },
        drawPlaces: function () {
            var places = [];

            this.places.forEach(function (place, i) {
                places.push(`
                    <circle cx="${place.x}" cy="${place.y}" r="8" title="${place.name + ' ' + place.place_id}" data-place-id="${place.place_id}" data-toggle="popover"></circle>
                    <text x="${place.x - 30}" y="${place.y + 30}" title="${place.name + ' ' + place.place_id}" data-place-id="${place.place_id}">${place.name}</text>
                    <div id="placeData${place.place_id}">
                        <img src="../A/public/img/${place.image_path}" width="200px">
                        <br>Description:</br>
                        <span>${place.description}</span>
                    </div>
                `);
            });

            $('#Layer_places').html(places.join(''));
        },
        popoverPlaces: function () {
            $('svg').popover({
                selector: '#Layer_places circle, #Layer_places text',
                trigger: 'click',
                placement: 'bottom',
                html: true,
                content: function () {
                    var placeId = this.dataset.placeId;
                    return $('#placeData' + placeId).html();
                }
            }).click(function (event) {
                $('#Layer_places *').each(function () {
                    if (!$(this).is(event.target)) {
                        $(this).popover('hide');
                    }
                });
            });
        }
    }
});
