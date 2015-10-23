module.exports = function(grunt) {
  require('load-grunt-tasks')(grunt);
  require('time-grunt')(grunt);
  
  grunt.initConfig({
    sass: {
    	dist: {
			files: {
				"build/main.css" : "wp-content/themes/vfwp/sass/main.sass"
			}    		
    	}
    },
    babel: {
        options: {
            sourceMap: false
        },
        dist: {
          files : [{
            expand : true,
            cwd : 'wp-content/themes/vfwp/es6/',
            src : [ "**/*.js", "*.js" ],
            dest : '.grunttemp/'
          }]
        }
    },
    concat : {
      dist : {
        src : [
          ".grunttemp/interfaces/*.js", ".grunttemp/classes/*.js", ".grunttemp/events/*.js", ".grunttemp/*.js"
        ],
        dest : 'build/main.js'
      }
    },
    clean : {
      js : [".grunttemp/interfaces/*.js", ".grunttemp/classes/*.js", ".grunttemp/*.js"],
    },
    watch: {
      files: ["wp-content/themes/vfwp/sass/*.sass", "wp-content/themes/vfwp/sass/**/*.sass", "wp-content/themes/vfwp/es6/*.js", "wp-content/themes/vfwp/es6/**/*.js"],
      tasks: ['sass', 'babel', 'concat', 'clean'],
      options: {
        livereload : true
      }
    }
  });

  grunt.registerTask('dev', ['sass']);
};
