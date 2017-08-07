// The following line loads the standalone build of Vue instead of the runtime-only build,
// so you don't have to do: import Vue from 'vue/dist/vue'
// This is done with the browser options. For the config, see package.json
import Vue from 'vue'
import Campgrounds from '../components/campgrounds/campgrounds.vue'
import Campground from '../components/campgrounds/campground.vue'
import AddCampground from '../components/campgrounds/addCampground.vue'
import Campsite from '../components/campsites/campsite.vue'
import page_404 from '../components/utils/404.vue'
import Router from 'vue-router'
import Campsite_type_dash from '../components/campsites-types/campsite-types-dash.vue'
import Campsite_type from '../components/campsites-types/campsite-type.vue'
import $ from '../hooks'
var css = require('../hooks-css.js');
Vue.use(Router);

// Define global variables
global.debounce = function (func, wait, immediate) {
    // Returns a function, that, as long as it continues to be invoked, will not
    // be triggered. The function will be called after it stops being called for
    // N milliseconds. If `immediate` is passed, trigger the function on the
    // leading edge, instead of the trailing.
    'use strict';
    var timeout;
    return function () {
        var context = this;
        var args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    }
};

global.$ = $

const routes = [
    {
        path: '/',
        component: {
            render (c) { return c('router-view') }
        },
        children: [
            {
                path:'dashboard',
                component: {
                    render (c) { return c('router-view') }
                },
                children: [
                    {
                        path:'campsite-types',
                        name:'campsite-types',
                        component: Campsite_type_dash
                    },
                    {
                        path:'campsite-type',
                        component: {
                            render (c) { return c('router-view') }
                        },
                        children: [
                            {
                                path: '/',
                                name: 'campsite-type',
                                component: Campsite_type
                            },
                            { 
                                path:':campsite_type_id',
                                name:'campsite-type-detail',
                                component: Campsite_type,
                            }
                        ]
                    },
                    {
                        path:'campgrounds/addCampground',
                        name:'cg_add',
                        component: AddCampground
                    },
                    {
                        path:'campgrounds',
                        component: {
                            render (c) { return c('router-view') }
                        },
                        children:[
                            {
                                path: '/',
                                name: 'cg_main',
                                component: Campgrounds,
                            },
                            {
                                path:':id',
                                name:'cg_detail',
                                component: Campground,
                            },
                            {
                                path:':id/campsites/add',
                                name:'add_campsite',
                                component:Campsite
                            },
                            {
                                path:':id/campsites/:campsite_id',
                                name:'view_campsite',
                                component:Campsite
                            },
                        ]
                    },
                ]
            }
        ]
    },
    {
        path: '/404',
        name: '404',
        component: page_404
    }
];

const router = new Router({
  'routes' : routes,
  'mode': 'history'
});

new Vue({
  'router':router
}).$mount('#app');
