const webpackConfig = require('./webpack.config.js');

module.exports = function(grunt) {
  "use strict";

  // measures the time each task takes
  require('jit-grunt')(grunt);
  require('time-grunt')(grunt);

  grunt.initConfig({

    //------------------------------------------------- Webpack ------------------------------------------------------//
    webpack: {
      options: {
        stats: true
      },
      dev: webpackConfig('development'),
      prod: webpackConfig('production')
    },

    //-------------------------------------------------- Copy --------------------------------------------------------//
    copy: {
      dev: {
        files: [
          { src: 'dist/app.css', dest: './webroot/css/app.css' },
          { src: 'dist/app.css.map', dest: './webroot/css/app.css.map' },
          { src: 'dist/app.js', dest: './webroot/js/app.js' },
          { src: 'dist/app.js.map', dest: './webroot/js/app.js.map' }
        ]
      },
      prod: {
        files: [
          { src: 'dist/app.min.css', dest: './webroot/css/app.min.css' },
          { src: 'dist/app.min.js', dest: './webroot/js/app.min.js' }
        ]
      },
      fonts: {
        files: [
          {expand: true, cwd: './src/Assets/fonts', src: ['**'], dest: './webroot/fonts/'}
        ]
      }
    },

    //------------------------------------------------ ByteSize ------------------------------------------------------//
    bytesize: {
      prod: {
        src: [
          './webroot/css/app.min.css',
          './webroot/js/app.min.js'
        ]
      }
    },

    //-------------------------------------------------- Karma -------------------------------------------------------//
    karma: {
      unit: {
        configFile: 'test/js/unit/karma.conf.js',
        singleRun: true
      }
    },

    //----------------------------------------------- WATCHERS -------------------------------------------------------//
    watch: {
      vue: {
        files: ['src/**/*.vue', 'src/**/*.js', 'src/**/*.scss'],
        tasks: ['webpack-dev']
      }
    }

  });

  //--------------------------------------------- REGISTERED TASKS ---------------------------------------------------//
  grunt.registerTask('webpack-dev', [
    'webpack:dev',
    'copy:dev'
  ]);

  grunt.registerTask('webpack-prod', [
    'webpack:prod',
    'copy:prod',
    'copy:fonts',
    'bytesize:prod'
  ]);

  grunt.registerTask('watch-vue', [
    'watch:vue'
  ]);

  grunt.registerTask('copy-fonts', [
    'copy:fonts'
  ]);

  grunt.registerTask('test', [
    'karma:unit'
  ]);
};
