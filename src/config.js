module.exports = {
	scss: {
		defaultDest: 'assets/css',
		style: {
			files: ['src/scss/*.scss'],
			dest: 'assets/css',
			watch: 'src/scss/**/*.scss',
		},
	},
	js: {
		admin: {
			files: ['src/js/*.js'],
			dest: 'js',
			dest: 'assets/js',
			watch: 'src/js/**/*.js',
		},
	},
	assets: {
		defaultDest: 'assets',
		fonts: {
			files: ['src/fonts/**/*'],
			dest: 'assets/fonts',
		},
		img: {
			files: ['src/img/**/*'],
			dest: 'assets/img',
		},
		jquery: {
			files: ['node_modules/jquery/dist/jquery.min.*'],
			dest: 'assets/vendor',
		},
		bootstrap: {
			files: ['node_modules/bootstrap/dist/js/bootstrap.bundle.min.*','node_modules/bootstrap/dist/css/bootstrap.min.*'],
			dest: 'assets/vendor',
		},
		bootswatchTheme:{
			files: ['node_modules/bootswatch/dist/yeti/bootstrap.min.css'],
			dest: 'assets/vendor',
		},
		moment: {
			files: ['node_modules/moment/min/moment.min.*'],
			dest: 'assets/vendor',
		},
		underscore: {
			files: ['node_modules/underscore/underscore-min.*'],
			dest: 'assets/vendor',
		},
	},
};
