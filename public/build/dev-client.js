/* eslint-disable */
require('eventsource-polyfill')
var hotClient = require("webpack-hot-middleware/client?path=http://10.0.0.3:3001/__webpack_hmr&noInfo=true&reload=true")

hotClient.subscribe(function (event) {
  if (event.action === 'reload') {
    window.location.reload()
  }
})
