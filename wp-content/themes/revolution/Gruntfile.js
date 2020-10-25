'use strict';
module.exports = function(grunt) {

	grunt.initConfig({
		theme_slugname: 'revolution',
		// let us know if our JS is sound
		jshint: {
			options: {
				"bitwise": true,
				"browser": true,
				"curly": true,
				"eqeqeq": true,
				"eqnull": true,
				"immed": true,
				"jquery": true,
				"latedef": true,
				"newcap": true,
				"noarg": true,
				"node": true,
				"strict": false,
				"undef": false,
				"esversion": '8'
			},
			all: [
				'Gruntfile.js',
				'assets/js/plugins/app.js',
				'assets/js/plugins/admin-meta.js',
				'assets/js/plugins/admin-vc.js'
			]
		},

		// concatenation and minification all in one
		uglify: {
			dist: {
				files: {
					'assets/js/admin-vc.min.js': [
						'assets/js/plugins/admin-vc.js'
					],
					'assets/js/admin-meta.min.js': [
						'assets/js/plugins/admin-meta.js'
					],
					'assets/js/vendor.min.js': [
						'assets/js/vendor/*.js'
					],
					'assets/js/app.min.js': [
						'assets/js/plugins/app.js'
					]
				}
			}
		},

		concat: {
			options: {
				stripBanners: true
			},
			dist: {
				src: 'assets/js/vendor/*.js',
				dest: 'assets/js/vendor.min.js',
			},
		},

		// style (Sass) compilation via Compass
		compass: {
			dist: {
				options: {
					sassDir: 'assets/sass',
					cssDir: 'assets/css',
					noLineComments: true,
					outputStyle: 'compressed'
				}
			},
			dev: {
				options: {
					sassDir: 'assets/sass',
					cssDir: 'assets/css',
					noLineComments: true
				}
			}
		},

		filepath: '',
		// watch our project for changes
		watch: {
			compass: {
				files: [
					'assets/sass/*',
					'assets/sass/*/*'
				],
				tasks: ['compass']
			},
			js: {
				files: [
					'assets/js/*/*'
				],
				tasks: ['uglify']
			},
			docs: {
				files: '**/*.php',
				options: {
					nospawn: true,
					livereload: true
				}
			}
		},

		// copy folder
		copy: {
			main: {
				expand: true,
				src: ['**', '!**/node_modules/**'],
				dest: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>',
			},
		},

		// clean folder
		clean: {
			options: {
				'force': true
			},
			dest: [
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/**/*',
			],
			build: [
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/**/.git',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/**/.gitignore',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/**/.sass-cache',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/<%= theme_slugname %>-wp.esproj',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/**/.DS_Store',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/node_modules',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/assets/demo',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/admin/assets/theme-mode',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/inc/plugins/<%= theme_slugname %>-plugin',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/inc/admin/imports/one-click-demo-import/docs',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/inc/admin/imports/one-click-demo-import/tests',
				'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/thb_phpcs.js',
			],
		},

		// Strip Code
		strip_code: {
			strip_theme_switcher: {
				options: {
					blocks: [{
						start_block: "<!-- start theme switcher -->",
						end_block: "<!-- end theme switcher -->"
					}]
				},
				src: ['/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/footer.php', '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/footer.php']
			}
		},

		// Check textdomain errors.
		checktextdomain: {
			options:{
				text_domain: 'revolution',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			theme: {
				src: [
					'**/*.php',
					'!node_modules/**',
					'!inc/admin/plugins/class-tgm-plugin-activation.php',
					'!woocommerce/**',
					'!vendor/**'
				],
				expand: true
			}
		},

		// Compress
		compress: {
			plugin: {
				options: {
					archive: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/inc/plugins/<%= theme_slugname %>-plugin.zip'
				},
				files: [
					{
						expand: true,
						cwd: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>/inc/plugins/',
						src: ['<%= theme_slugname %>-plugin/**/*']
					}
				]
			},
			theme: {
				options: {
					archive: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>.zip'
				},
				files: [
					{
						expand: true,
						cwd: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/',
						src: ['<%= theme_slugname %>/**/*']
					}
				]
			},
			all_files: {
				options: {
					archive: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-all.zip'
				},
				files: [
					{
						expand: true,
						cwd: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/',
						src: [
							'<%= theme_slugname %>.zip',
							'<%= theme_slugname %>-child.zip',
							'PSD.zip',
							'Plugins.zip',
							'Documentation.zip',
							'icon-reference.zip'
						]
					},
				]
			}
		}
	});

	// load tasks
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-strip-code');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-checktextdomain');

	// register task
	grunt.registerTask('default', [
		'jshint',
		'compass:dev',
		'concat',
		'watch'
	]);

	grunt.registerTask('release', [
		'jshint',
		'compass:dist',
		'uglify',
		'watch'
	]);

	grunt.registerTask('pack', [
		'checktextdomain:theme',
		'clean:dest',
  	'copy',
		'strip_code',
  	'compress:plugin',
  	'clean:build',
		'compress:theme',
		'compress:all_files'
	]);

	// THB PHPCS
	const fs = require('graceful-fs');

	async function thb_phpcs(filepath) {
		const exec = require('child_process').exec;
		let res = '';

		return new Promise(resolve => {
			exec('phpcbf --standard=WordPress ' + filepath, {maxBuffer: 1024 * 10000}, (err, stdout) => {
				if (stdout.indexOf('No fixable errors were found') === -1) {
					res += stdout;
					res += 'thb_phpcs first basics completed, your turn\n';
					res += '----------------------------------------------------------------------\n\n';
				}
				exec('phpcs --standard=WordPress ' + filepath, {maxBuffer: 1024 * 10000}, (err, stdout) => {
					res += '';
					res += stdout;
					res += 'thb_phpcs scan completed, good luck\n';
					res += '----------------------------------------------------------------------\n';

					if (res.indexOf('LINES') !== -1) { // is there any err
						var prefix_val = 'LINES\n----------------------------------------------------------------------\n';
						var prefix = res.split(prefix_val);
						var list = prefix[1].split('\n');
						var lines = [];

						if (res.indexOf('Found precision alignment of') !== -1) {
							list.forEach((val, key) => {
								if (val.indexOf('Found precision alignment of') !== -1) {
									lines.push(parseInt(val.split('|')[0]));
									list[key] = val.split('').map(char => char + '\u0336').join('') + ' > fixed by thb_phpcs';
								}
							});

							fs.readFile(filepath, 'utf8', (err, resx) => {
								if (err) { throw err; }
								var file = resx.split('\n');

								var result = file.map((val, key) => {
									if (lines.indexOf(key + 1) !== -1) {
										val = val.split(val.split('\t').slice(-1)[0])[0] + val.split('\t').slice(-1)[0].replace(/^\s+/, '\t');
									}
									return val;
								});

								fs.writeFile(filepath, result.join('\n'), err => {
									if (err) { throw err; }
									resolve(prefix[0] + prefix_val + list.join('\n'));
								});
							});
						} else {
							resolve(res);
						}
					} else {
						resolve(res);
					}
				});
			});
		});
	}
	async function thb_phpcs_go(filepath) {
		return await thb_phpcs(filepath);
	}

	grunt.event.on('watch', function(action, filepath) {
		if (filepath.substr(filepath.length - 4) === '.php') {
			var thb_phpcs_in_process = (fs.existsSync('thb_phpcs.in_process') ? fs.readFileSync('thb_phpcs.in_process', 'utf8') : 'false_first');
			if (thb_phpcs_in_process !== 'true') {
				fs.writeFileSync('thb_phpcs.in_process', 'true');
				thb_phpcs_go(filepath).then(res => {
					if (res.indexOf('fixed by thb_phpcs') !== -1) {
						setTimeout(function() {
							fs.writeFileSync('thb_phpcs.in_process', 'false');
						}, 1000);
					} else {
						fs.writeFileSync('thb_phpcs.in_process', 'false');
					}
					console.log(res);
				});
			}
		}

		grunt.config.set('filepath', grunt.config.escape(filepath));
	});

};