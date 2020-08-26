const path = require('path');
const gulp = require('gulp');
const webpack = require('webpack-stream');
const named = require('vinyl-named');
let plugins = require('gulp-load-plugins')({
	pattern: ['gulp-*', 'gulp.*', '@*/gulp{-,.}*', 'fs', 'postcss-*', 'autoprefixer', 'merge-stream', 'del', 'webpack-stream', 'vinyl-named'],
});
const fs = require('fs');
const configPath = './src/config.js';
let isLive = false;
let config = require(configPath);

function err(err) {
	console.log(err);
	this.emit('end');
}

function groupTasks(name) {
	var tasks = gulp.tasks ? Object.keys(gulp.tasks)
		.sort() : gulp.tree()
		.nodes.sort();
	return tasks.filter(function (task) {
		return task.indexOf(name) != -1;
	});
}

function scssTask(name) {
	var options = {};
	var dest = config.scss[name].dest || config.scss.defaultDest;
	var source = gulp.src(config.scss[name].files)
		.pipe(plugins.sourcemaps.init());
	source = source.pipe(plugins.sass())
		.on('error', err)
		.pipe(plugins.postcss([plugins.autoprefixer()]));
	if (config.scss[name].header) {
		source = source.pipe(plugins.header(config.scss[name].header, {
			pkg: config.theme,
		}));
	}
	source = source.pipe(plugins.cleanCss({
		format: 'beautify',
		level: {
			2: {
				mergeMedia: true,
				mergeSemantically: true,
			},
		},
	}));
	var mergeStream = plugins.mergeStream();
	if (!isLive) {
		var pipe1 = source.pipe(plugins.clone())
			.pipe(plugins.sourcemaps.write('.'))
			.pipe(gulp.dest(dest));
		if (config.scss[name].noMinify === true) {
			return pipe1.pipe(plugins.livereload());
		}
		mergeStream.add(pipe1);
	}
	var pipe2 = source.pipe(plugins.clone())
		.pipe(plugins.rename({
			suffix: '.min',
		}))
		.pipe(plugins.cleanCss());
	pipe2 = pipe2 = isLive ? pipe2 : pipe2.pipe(plugins.sourcemaps.write('.'));
	pipe2 = pipe2.pipe(gulp.dest(dest));
	mergeStream.add(pipe2);
	return mergeStream.pipe(plugins.livereload());
}

function jsTask(name) {
	var options = {};
	var dest = config.js[name].dest || config.js.defaultDest;
	var source = gulp.src(config.js[name].files, {
			//read: false,
		})
		.pipe(plugins.vinylNamed())
		.pipe(plugins.webpackStream({
			mode: isLive ? "production" : "development",
			devtool: false,
			optimization: {
				namedModules: false,
				moduleIds: "hashed",
				minimize: isLive,
			},
			module: {
				rules: [{
					test: /\.js$/,
					loader: 'babel-loader',
					exclude: /node_modules/,
					query: {
						presets: [
		["@babel/preset-env", {
								"useBuiltIns": "usage",
								corejs: 3,
		}]
	],
						plugins: ["@babel/plugin-transform-modules-commonjs", "@babel/plugin-proposal-class-properties", ],
					}
}]
			}
		}));
	var mergeStream = plugins.mergeStream();
	if (!isLive) {
		var pipe1 = source.pipe(plugins.clone())
			.pipe(plugins.sourcemaps.write('.'))
			.pipe(gulp.dest(dest));
		if (config.js[name].noMinify === true) {
			return pipe1.pipe(plugins.livereload());
		}
		mergeStream.add(pipe1);
	}
	var pipe2 = source.pipe(plugins.clone())
		.pipe(plugins.rename({
			suffix: '.min',
		}))
	pipe2 = isLive ? pipe2 : pipe2.pipe(plugins.sourcemaps.write('.'));
	pipe2 = pipe2.pipe(gulp.dest(dest));
	mergeStream.add(pipe2);
	return mergeStream.pipe(plugins.livereload());
}
Object.keys(config.scss)
	.forEach(function (group) {
		if (group != 'defaultDest') {
			gulp.task('scss:' + group, function () {
				return scssTask(group);
			});
		}
	});
Object.keys(config.js)
	.forEach(function (group) {
		if (group != 'defaultDest') {
			gulp.task('js:' + group, function () {
				return jsTask(group);
			});
		}
	});
Object.keys(config.assets)
	.forEach(function (group) {
		if (group != 'defaultDest') {
			gulp.task('copy:' + group, function () {
				var dest = config.assets[group].dest || config.copy.defaultDest;
				return gulp.src(config.assets[group].files, {
						allowEmpty: true,
					})
					.pipe(gulp.dest(dest))
					.pipe(plugins.livereload());
			});
		}
	});
gulp.task('scss', gulp.series(...groupTasks('scss:')), function (cb) {
	return cb();
});
gulp.task('copy', gulp.series(...groupTasks('copy:')), function (cb) {
	return cb();
});
gulp.task('js', gulp.series(...groupTasks('js:')), function (cb) {
	return cb();
});
gulp.task('watch', function () {
	plugins.livereload.listen();
	Object.keys(config.assets)
		.forEach(function (group) {
			if (group != 'defaultDest') {
				gulp.watch(config.assets[group].files, gulp.series('copy:' + group));
			}
		});
	Object.keys(config.scss)
		.forEach(function (group) {
			if (group != 'defaultDest') {
				var watchGlob = typeof config.scss[group].watch != 'undefined' ? config.scss[group].watch : config.scss[group].files;
				gulp.watch(watchGlob, gulp.series('scss:' + group));
			}
		});
	Object.keys(config.js)
		.forEach(function (group) {
			if (group != 'defaultDest') {
				var watchGlob = typeof config.js[group].watch != 'undefined' ? config.js[group].watch : config.js[group].files;
				gulp.watch(watchGlob, gulp.series('js:' + group));
			}
		});
});
gulp.task('default', gulp.series(gulp.parallel('scss', 'js', 'copy'), 'watch'));
gulp.task('live', gulp.series(function (cb) {
	isLive = true;
	return plugins.del(['assets', 'build']);
}, gulp.parallel('scss', 'js', 'copy')));
/* Build and Zip */
let distFiles = ['**', '!node_modules/', '!node_modules/**', '!build/', '!build/**', '!src/', '!src/**', '!.gitignore', '!Gulpfile.js', '!package.json', '!package-lock.json', '!composer.json', '!composer.lock', '!.git/', '!.git/**', '!_config/*.php','!cache/*/**' ];
gulp.task('deploy', gulp.series('live', function () {
	return gulp.src(distFiles, {
			allowEmpty: true,
		})
		.pipe(gulp.dest('build/'));
}));
gulp.task('build', gulp.series('live', function () {
	return gulp.src(distFiles, {
			allowEmpty: true,
		})
		.pipe(gulp.dest('build/tiktok-downloader'));
}));
