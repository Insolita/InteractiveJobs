require('./bootstrap');
import Vue from 'vue';
import VueEcho from 'vue-echo';
import axios from 'axios';
import Toasted from 'vue-toasted';

import ActiveJob from './components/ActiveJob';
import WatchActive from './components/WatchActive';

Vue.use(Toasted, { iconPack: 'fontawesome', theme:'bubble', icon:'fa-info-circle', className: 'app-toasted'});

Vue.use(VueEcho, {
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

Object.defineProperty(
    Vue.prototype,
    '$user', {value: window.appConfig.auth},
    '$http', {
        value: axios.create({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        })
    });

const app = new Vue({
    el: '#app',
    components:{ActiveJob, WatchActive},
    data(){
        return {
            isAuthorized: false
        }
    },
    mounted(){
        if(this.$user!== null){
            this.isAuthorized = true;
            this.subscribeUser();
        }
    },
    methods:{
        subscribeUser(){
            this.$echo.private(`User.${this.$user.id}`).notification((notification) => {
                this.$toasted.show(notification.message, {
                    type: notification.message_type
                }).goAway(5000);
            });
        }
    }
});
