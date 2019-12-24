var gulp = require('gulp');
gulp.task('default', function () {
    return gulp.src([
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/bootstrap/dist/js/bootstrap.js.map',
        'node_modules/select2/dist/css/select2.min.css',
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/animate.css/animate.min.css',
        'node_modules/hamburgers/dist/hamburgers.min.css',
        'node_modules/clipboard/dist/clipboard.min.js'
        ])
        .pipe(gulp.dest('./public/dist/assets/'));
});
