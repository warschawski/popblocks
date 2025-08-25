// let fs             = require('fs');
let browserSync    = require('browser-sync').create();
let esbuild        = require('gulp-esbuild')
let gulp           = require('gulp');
let autoprefixer   = require('gulp-autoprefixer');
let cleanCSS       = require('gulp-clean-css');
let exec           = require('child_process').exec;
let filter         = require('gulp-filter');
let foreach        = require('gulp-foreach');
let jshint         = require('gulp-jshint');
let less           = require('gulp-less');
let replace        = require('gulp-replace');
let path           = require('path');
let plumber        = require('gulp-plumber');

var lessImportNPM  = require('less-plugin-npm-import');

var fs = require('fs');
var postcss = require('gulp-postcss');

var { globalExternals } = require('@fal-works/esbuild-plugin-global-externals');

var plumberOptions = {
  errorHandler: function (err) {
    console.log(err);
    this.emit('end');
  }
};

// 

// Less
function compileStyles(src, opts, dest, vars) {
  return gulp.src(src, opts)
  .pipe(plumber(plumberOptions))
  .pipe(less({
    globalVars: vars || {},
    plugins: [ new lessImportNPM() ],
  }))
  .pipe(replace('--fs-', '--popblocks-'))
  .pipe(replace('fs-', 'popblocks-'))
  .pipe(autoprefixer({
    // browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1', 'ie >= 10']
  }))
  .pipe(cleanCSS({
    // format: 'beautify',
    level: {
      2: {
        mergeMedia: false,
      },
    },
    inline: false,
    // compatibility: 'ie10'
  }))
  .pipe(gulp.dest(dest))
  .pipe(browserSync.stream());
}

gulp.task('styles', function() {
  return compileStyles([
    './assets/src/less/*.less',
  ], {
    base: './assets/src/less'
  }, './assets/css');
});

// JS
function compileScripts(src, opts, dest) {
  return gulp.src(src, opts)
  .pipe(plumber(plumberOptions))
  .pipe(jshint())
  .pipe(esbuild({
    outdir: './',
      bundle: true,
      // minify: false,
      minify: true,
      format: 'iife',
      // drop: ['console'],
      sourcemap: 'linked',
      platform: 'browser',
      legalComments: 'none',
      plugins: [globalExternals({
        'jquery': {
          varName: 'jQuery',
          type: 'cjs'
        }
      })],
  }))
  .pipe(replace('--fs-', '--popblocks-'))
  .pipe(replace('fs-', 'popblocks-'))
  .pipe(gulp.dest(dest))
  .pipe(browserSync.stream());
}

gulp.task('scripts', function() {
  return compileScripts([
    './assets/src/js/*.js',
  ], {
    base: './assets/src/js'
  }, './assets/js');
});


// var fs             = require('fs');
// var gulp           = require('gulp');
// var autoprefixer   = require('gulp-autoprefixer');
// var cleanCSS       = require('gulp-clean-css');
// var filter         = require('gulp-filter');
// var include        = require('gulp-include');
// var jshint         = require('gulp-jshint');
// var less           = require('gulp-less');
// var plumber        = require('gulp-plumber');
// var shell          = require('gulp-shell')
// var sourcemaps     = require('gulp-sourcemaps');
// var uglify         = require('gulp-uglify');
// var lessImportNPM  = require('less-plugin-npm-import');

// var plumberOptions = {
//   errorHandler: function (err) {
//     console.log(err);
//     this.emit('end');
//   }
// };

// // Less
// gulp.task('styles', function() {
//   return gulp.src('./assets/src/less/admin*.less')
//     .pipe(plumber(plumberOptions))
//     // .pipe(sourcemaps.init({ loadMaps: true }))
//     .pipe(less({
//       plugins: [ new lessImportNPM() ]
//     }))
//     .pipe(autoprefixer({
//       // browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1', 'ie >= 10']
//     }))
//     .pipe(cleanCSS({
//       compatibility: 'ie10'
//     }))
//     // .pipe(sourcemaps.write('./'))
//     .pipe(gulp.dest('./assets/css'));
// });

// // JS
// gulp.task('scripts', function() {
//   return gulp.src('./assets/src/js/admin*.js')
//     .pipe(plumber(plumberOptions))
//     // .pipe(sourcemaps.init({ loadMaps: true }))
//     .pipe(include({
//       includePaths: [
//         __dirname + '/assets/src/js',
//         __dirname + '/node_modules'
//       ]
//     }))
//     .pipe(jshint())
//     .pipe(uglify())
//     // .pipe(sourcemaps.write('./'))
//     .pipe(gulp.dest('./assets/js'));
// });

// gulp.task('wpsync', function() {
//   return gulp.src('wp-sync.sh', { read: false })
//     .pipe(shell([
// './wp-sync.sh \
// --plugin-name="popblocks" \
// --git-repo="git@bitbucket.org:benplum/popblocks.git" \
// --svn-user=presswell \
// --assets-dir="wp-assets"'
//     ]));
// });

gulp.task('default', gulp.series([
  gulp.parallel([ 'styles', 'scripts' ]),
]));

gulp.task('watch', gulp.series('default', function(done) {
  gulp.watch('assets/src/**/*.less', { cwd: './' }, gulp.parallel('styles'));
  gulp.watch('assets/src/**/*.js', { cwd: './' }, gulp.parallel('scripts'));
}));
