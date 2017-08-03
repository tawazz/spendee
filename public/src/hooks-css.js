require("../node_modules/bootstrap/dist/css/bootstrap.min.css");
require("../node_modules/bootstrap-material-design/dist/css/bootstrap-material-design.min.css");
require("../node_modules/bootstrap-material-design/dist/css/ripples.min.css");
require("../node_modules/tether/dist/css/tether.min.css");
require("../node_modules/tether/dist/css/tether-theme-basic.min.css");
require('../node_modules/morris.js/morris.css')
require('../static/card.css');
require('../static/pills.css');
require("../node_modules/mdi/css/materialdesignicons.css")

if( process.env.NODE_ENV == "development") {
  require('../../css/main.css');
}
