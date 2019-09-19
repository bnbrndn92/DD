const { src, dest, parallel, watch } = require('gulp');
const sass = require('gulp-sass');
const minifyCSS = require('gulp-csso');
const concat = require('gulp-concat');
const autoPrefixer = require('gulp-autoprefixer');
const uglify = require('gulp-uglify');

function css() {
    return src([
            './node_modules/bootstrap/dist/css/bootstrap.min.css',
            './node_modules/bootstrap/dist/css/bootstrap-reboot.min.css',
            './assets/scss/style.scss'
        ])
        .pipe(sass())
        .on("error", sass.logError)
        .pipe(autoPrefixer())
        .pipe(minifyCSS())
        .pipe(concat('style.min.css'))
        .pipe(dest('./web/css'))
    ;
}

function js() {
    return src([
            './node_modules/jquery/dist/jquery.min.js',
            './node_modules/bootstrap/dist/js/bootstrap.min.js',
            './assets/js/app.js',
            './assets/js/app/*.js'
        ])
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(dest('./web/js'))
}

function compileWatch() {
    watch('./assets/js/*/*.js', js);
    watch('./assets/scss/*/*.scss', css);
}

exports.js = js;
exports.css = css;
exports.watch = compileWatch;
exports.default = parallel(css, js);