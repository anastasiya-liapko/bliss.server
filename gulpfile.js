'use strict';

var gulp = require('gulp'),
  sass = require('gulp-sass'),
  sourcemaps = require('gulp-sourcemaps'),
  del = require('del'),
  postcss = require('gulp-postcss'),
  autoprefixer = require('gulp-autoprefixer'),
  minify = require('gulp-csso'),
  rename = require('gulp-rename'),
  browserSync = require('browser-sync').create(),
  imagemin = require('gulp-imagemin'),
  concat = require('gulp-concat'),
  uglify = require('gulp-uglify'),
  eslint = require('gulp-eslint'),
  pkg = require('./package.json');

gulp.task('styles', function () {
  return gulp.src('src/public/sass/style.sass')
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: pkg.browserslist,
      cascade: false,
    }))
    .pipe(minify())
    .pipe(sourcemaps.write())
    .pipe(rename('main.min.css'))
    .pipe(gulp.dest('public/assets/css'));
});

gulp.task('js', function () {
  return gulp.src(['src/common/js/**/*.js', 'src/public/js/**/*.js'])
    .pipe(eslint())
    .pipe(eslint.failOnError())
    .pipe(sourcemaps.init())
    .pipe(concat('main.min.js'))
    .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('public/assets/js'));
});

gulp.task('styles_admin', function () {
  return gulp.src('src/admin/sass/style.sass')
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: pkg.browserslist,
      cascade: false,
    }))
    .pipe(minify())
    .pipe(sourcemaps.write())
    .pipe(rename('main-admin.min.css'))
    .pipe(gulp.dest('public/assets/css'));
});

gulp.task('js_admin', function () {
  return gulp.src(['src/common/js/**/*.js', 'src/admin/js/**/*.js'])
    .pipe(eslint())
    .pipe(eslint.failOnError())
    .pipe(sourcemaps.init())
    .pipe(concat('main-admin.min.js'))
    .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('public/assets/js'));
});

gulp.task('images', function () {
  return gulp.src(['src/public/img/*.{png,jpg,svg}', 'src/admin/img/*.{png,jpg,svg}'])
    .pipe(imagemin([
      imagemin.optipng({optimizationLevel: 3}),
      imagemin.jpegtran({progressive: true}),
      imagemin.svgo()
    ]))
    .pipe(gulp.dest('public/assets/img'));
});

gulp.task('fonts', function () {
  return gulp.src(['src/public/fonts/**/*', 'src/admin/fonts/**/*'])
    .pipe(gulp.dest('public/assets/fonts'));
});

gulp.task('vendor:jquery', function () {
  return gulp.src('node_modules/jquery/dist/jquery.min.js')
    .pipe(gulp.dest('public/assets/vendor/'));
});

gulp.task('vendor:inputmask', function () {
  return gulp.src('node_modules/inputmask/dist/min/jquery.inputmask.bundle.min.js')
    .pipe(gulp.dest('public/assets/vendor/'));
});

gulp.task('vendor:swiper', function () {
  return gulp.src('node_modules/swiper/dist/**/*')
    .pipe(gulp.dest('public/assets/vendor/swiper/'));
});

gulp.task('vendor:bootstrap', function () {
  return gulp.src('node_modules/bootstrap/dist/**/*')
    .pipe(gulp.dest('public/assets/vendor/bootstrap/'));
});

gulp.task('vendor:jqueryValidation', function () {
  return gulp.src('node_modules/jquery-validation/dist/jquery.validate.min.js')
    .pipe(gulp.dest('public/assets/vendor/'));
});

gulp.task('vendor:jqueryCountdown', function () {
  return gulp.src('node_modules/jquery-countdown/dist/jquery.countdown.min.js')
    .pipe(gulp.dest('public/assets/vendor/'));
});

gulp.task('vendor:jsCookie', function () {
  return gulp.src('node_modules/js-cookie/src/js.cookie.js')
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(rename('js.cookie.min.js'))
    .pipe(gulp.dest('public/assets/vendor/'));
});

gulp.task('vendor:airDatepicker', function () {
  return gulp.src('node_modules/air-datepicker/dist/**/*')
    .pipe(gulp.dest('public/assets/vendor/air-datepicker'));
});

gulp.task('vendor', gulp.series('vendor:inputmask', 'vendor:swiper', 'vendor:bootstrap', 'vendor:jquery', 'vendor:jqueryValidation', 'vendor:jqueryCountdown', 'vendor:jsCookie', 'vendor:airDatepicker'));

gulp.task('clean', function () {
  return del('public/assets/');
});

gulp.task('watch', function () {
  gulp.watch('src/public/sass/**/*', {usePolling: true}, gulp.series('styles'));
  gulp.watch('src/admin/sass/**/*', {usePolling: true}, gulp.series('styles_admin'));
  gulp.watch('src/common/js/**/*.js', {usePolling: true}, gulp.series('js', 'js_admin'));
  gulp.watch('src/public/js/**/*.js', {usePolling: true}, gulp.series('js'));
  gulp.watch('src/admin/js/**/*.js', {usePolling: true}, gulp.series('js_admin'));
});

gulp.task('server', function () {
  browserSync.init({
    server: 'public'
  });
  browserSync.watch('src/!**/!*.*').on('change', browserSync.reload);
});

gulp.task('build', gulp.series('clean', 'images', gulp.parallel('styles', 'js', 'styles_admin', 'js_admin', 'fonts', 'vendor')));

gulp.task('dev', gulp.series('build', gulp.parallel('watch', 'server')));
