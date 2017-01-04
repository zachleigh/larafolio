const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir((mix) => {
    mix.sass('./src/resources/assets/sass/larafolio.scss', './dist/css/')
        .styles([
            './src/resources/assets/vendor/normalize.css',
            '/../../../node_modules/dropzone/dist/min/dropzone.min.css',
            './dist/css/larafolio.css'
        ], './dist/css/larafolio-final.css')
        .webpack('./src/resources/assets/js/larafolio.js', './dist/js/')
        .version([
            './dist/css/larafolio-final.css',
            './dist/js/larafolio.js'
        ], './dist/')
        .browserSync({open: false, proxy: 'http://localhost:8000/', notify: false})
        .copy('./dist/css', 'vendor/laravel/laravel/public/vendor/larafolio/css')
        .copy('./dist/images', 'vendor/laravel/laravel/public/vendor/larafolio/images')
        .copy('./dist/zondicons', 'vendor/laravel/laravel/public/vendor/larafolio/zondicons')
        .copy('./dist/js', 'vendor/laravel/laravel/public/vendor/larafolio/js')
        .copy('./dist/rev-manifest.json', 'vendor/laravel/laravel/public/vendor/larafolio/rev-manifest.json');
});
