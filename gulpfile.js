const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

const jsDir = __dirname + '/resources/assets/js/';

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
        ], './dist/');
});
