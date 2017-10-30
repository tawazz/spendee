var merge = require('webpack-merge')
var prodEnv = require('./prod.env')
var host = "10.0.0.3"
var port = "3001"

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  publicPath: `http://${host}:${port}`,
  webpackHost:`${host}:${port}`,
  host,
  port
})
