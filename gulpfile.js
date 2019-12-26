var gulp = require('gulp');
gulp.task('default', function () {
    return gulp.src([
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/bootstrap/dist/js/bootstrap.js.map',
        'node_modules/select2/dist/css/select2.min.css',
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/bootstrap/dist/css/bootstrap.min.css.map',
        'node_modules/animate.css/animate.min.css',
        'node_modules/hamburgers/dist/hamburgers.min.css',
        'node_modules/clipboard/dist/clipboard.min.js',
        'node_modules/popper.js/dist/umd/popper.js',
        'node_modules/popper.js/dist/umd/popper.js.map',
        'node_modules/popper.js/dist/umd/popper-utils.js',
        'node_modules/popper.js/dist/umd/popper-utils.js.map'
        ])
        .pipe(gulp.dest('./public/dist/assets/'));
});
