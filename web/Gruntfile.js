const jsfiles = require('./vendor')
const cssfiles = require('./vendor-css')

module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'src/<%= pkg.name %>.js',
        dest: 'build/<%= pkg.name %>.min.js'
      }
   },
   concat: {
      js: {
         src: jsfiles,
         dest: '../js/dist/spendee.js'
      },
      css: {
         src: cssfiles,
         dest: '../css/dist/spendee.css'
      },
      options:{
         stripBanners:true

      }
   },
   min: {
     js: {
       src: '../js/dist/spendee.js',
       dest: '../js/dist/spendee.min.js'
     }
   },
   cssmin: {
     css:{
       src: '../css/dist/spendee.css',
       dest: '../css/dist/spendee.min.css'
     }
   }
});

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-css');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-min');
  // Default task(s).
  grunt.registerTask('default', ['concat','min', 'cssmin']);

};
