/*!
 * ETD Solutions's Gruntfile
 * http://etd-solutions.com
 * Copyright 2016 ETD Solutions
 * Licensed under Apache-2.0
 */

module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        babel: {
            options: {
                comments: true,
                sourceMap: false,
                presets: [
                    "es2015"
                ],
                plugins: [
                    "transform-class-properties",
                    "babel-plugin-add-module-exports",
                    "transform-es2015-modules-amd"
                ]
            },
            build: {
                files: {
                    'js/dist/avatar.js': 'js/src/avatar.es6'
                }
            }
        },
        uglify: {
            options: {
                mangle: true,
                compress: true,
                beautify: false,
                sourceMap: false,
                preserveComments: 'some',
                banner: '/**!\n\t* @package     <%= pkg.name %>\n\t*\n\t* @version     <%= pkg.version %>\n\t* @copyright   Copyright (C) <%= grunt.template.today("yyyy") %> ETD Solutions. Tous droits réservés.\n\t* @license     Apache-2.0 \n\t* @author      ETD Solutions http://etd-solutions.com\n*/\n',
                screwIE8: true,
                quoteStyle: 0
            },
            build: {
                files: [
                    {
                        expand: true,
                        cwd: 'js/dist',
                        src: ['*.js', '!*.min.js'],
                        dest: 'js/dist',
                        ext: '.min.js'
                    }
                ]
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Load the plugin that provides the "babel" task.
    grunt.loadNpmTasks('grunt-babel');

    // Build JS
    grunt.registerTask('js', ['babel', 'uglify']);

    // Default tasks.
    grunt.registerTask('default', ['js']);

};