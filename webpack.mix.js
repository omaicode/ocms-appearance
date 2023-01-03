const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/partial-editor.js', 'modules/appearance/js/partial-editor.js').vue()
    .sass( __dirname + '/Resources/assets/sass/partial-editor.scss', 'modules/appearance/css/partial-editor.css');

if (mix.inProduction()) {
    mix.version();
}
