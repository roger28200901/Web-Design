const app = new Vue({
    el: '#app',
    components: {
        'place-component': {
            props: [
                'x',
                'y',
                'name',
                'place_id',
                'image_path',
                'description'
            ],
            template: `
                <g>
                    <circle :cx="x" :cy="y" r="8" :title="name + ' ' + place_id" :data-place-id="place_id" data-toggle="popover" v-on:mouseover="scalePoint($event)" v-on:mouseleave="unscalePoint($event)"></circle>
                    <text :x="parseInt(x) - 30" :y="parseInt(y) + 30" :title="name + ' ' + place_id" :data-place-id="place_id">{{ name }}</text>
                    <div :id="'placeData' + place_id">
                        <img :src="'../A/public/img/' + image_path" width="200px">
                        <br>Description:</br>
                        <span>{{ description }}</span>
                    </div>
                </g>
            `,
            methods: {
                scalePoint: function (event) {
                    event.target.setAttribute('r', 12);
                },
                unscalePoint: function (event) {
                    event.target.setAttribute('r', 8);
                }
            }
        }
    },
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
        places: [],
        routes: [],
        daySchedules: [],
        selectedSchedules: null,
        schedulesInformationVisible: null
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
                token: sessionStorage.getItem('userToken') || '',
                role: sessionStorage.getItem('userRole') || '',
            };
        },
        initialAuthData: function () {
            this.forms.auth = {
                username: 'admin',
                password: 'adminpass'
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
                    sessionStorage.setItem('userToken', response.token);
                    sessionStorage.setItem('userRole', response.role);
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
                    sessionStorage.clear();
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
                    self.routes = response;
                    self.drawSchedule(self.routes[0]);
                    if (self.routes.length) {
                        self.getDaySchedulesBySchedules(self.routes[0].schedules);
                        self.storeHistory(fromPlaceId, toPlaceId, self.routes[0].schedules);
                    }
                },
                statusCode: {
                    401: function (response) {
                        self.launchMessage('danger', response.responseJSON.message);
                    }
                }
            });
        },
        drawSchedule: function (route) {
            var schedules = [];

            if (route) {
                route.schedules.forEach(function (schedule, i) {
                    schedules.push(`<line class="type-${schedule.type}" x1="${schedule.from_place.x}" y1="${schedule.from_place.y}" x2="${schedule.to_place.x}" y2="${schedule.to_place.y}" marker-end="url(#scheduleArrow)"></line>`);
                });
            }
            $('#Layer_schedules').html(schedules.join(''));
        },
        showSchedulesInfomation: function (schedules) {
            this.selectedSchedules = schedules;
            this.schedulesInformationVisible = true;
        },
        getDaySchedulesBySchedules: function (schedules) {
            var self = this;

            var scheduleId = [];
            schedules.forEach(function (schedule) {
                scheduleId.push(schedule.id);
            });

            /* Sending Ajax To Get Day Schedules Via Schedules */
            $.ajax({
                url: self.baseAPIUrl + `/schedule/day/${JSON.stringify(scheduleId)}/${self.forms.search.departureTime}`,
                type: 'get',
                contentType: 'application/json',
                data: {
                    token: self.user.token
                },
                dataType: 'json',
                success: function (response) {
                    self.daySchedules = response;
                },
                statusCode: {
                    401: function (response) {
                        self.launchMessage('danger', response.responseJSON.message);
                    }
                }
            });
        },
        storeHistory: function (fromPlaceId, toPlaceId, schedules) {
            var self = this;

            var scheduleId = [];
            schedules.forEach(function (schedule) {
                scheduleId.push(schedule.id);
            });

            /* Sending Ajax To Store Search History */
            $.ajax({
                url: self.baseAPIUrl + `/route/selection?token=${self.user.token}`,
                type: 'post',
                contentType: 'application/json',
                data: JSON.stringify({
                    from_place_id: fromPlaceId,
                    to_place_id: toPlaceId,
                    schedule_id: scheduleId
                }),
                dataType: 'json',
                statusCode: {
                    422: function (response) {
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
        destroyPlace: function (index) {
            var self = this;

            $('#placeListPanel').modal('hide');

            /* Sending Ajax To Delete Place */
            $.ajax({
                url: self.baseAPIUrl + `/place/${self.places[index].id}`,
                type: 'delete',
                data: {
                    token: self.user.token
                },
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
