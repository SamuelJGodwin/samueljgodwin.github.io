module.exports = {
	name: 'ignition',
	paths: {
		src: {
			styles: [
				'inc/assets/css/**/*.scss',
				'inc/customizer/**/*.scss',
				'inc/custom-fields/**/*.scss',
			],
			scripts: [
				'inc/assets/js/**/*.js',
				'!inc/assets/js/**/*[-.]min[-.]*js',
				'inc/custom-fields/controls/**/*.js',
				'!inc/custom-fields/controls/**/*[-.]min[-.]*js',
				'inc/customizer/**/*.js',
				'!inc/customizer/**/*[-.]min[-.]*js',
			],
		},
	},
};
