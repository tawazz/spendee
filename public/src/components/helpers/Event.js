/*
 * Name : Vue Event dispatcher
 * Author : Tawanda Nyakudjga
 * Date : December 2016
 * Description : A Vue event dispatcher
 **/
import Vue from 'vue'

var Dispachter = module.exports = {
    v : new Vue(),

    fire:(event, data = null ) => {
        Dispachter.v.$emit(event,data);
    },

    listen:(event,callback) => {
        Dispachter.v.$on(event,callback);
    }
}

exports.Event = Dispachter;
