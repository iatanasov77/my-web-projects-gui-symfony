const Encore    = require('@symfony/webpack-encore');
const webpack   = require('webpack');
const path      = require('path');

Encore
    .setOutputPath( 'public/build/my-projects-velzon-default/' )
    .setPublicPath( '/build/my-projects-velzon-default/' )
  
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    
    .addAliases({
        '@': path.resolve( __dirname, '../../vendor/vankosoft/application/src/Vankosoft/ApplicationBundle/Resources/themes/default/assets' )
    })
    
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: true
    })

    /**
     * Add Entries
     */
    .autoProvidejQuery()
    
    .configureFilenames({
        js: '[name].js?[contenthash]',
        css: '[name].css?[contenthash]',
        assets: '[name].[ext]?[hash:8]'
    })

    // Application Images
    .copyFiles({
         from: './themes/MyProjects_VelzonDefault/assets/img',
         to: 'img/[name].[ext]',
     })
     
     // Velzon Images
    .copyFiles([
        //{from: './themes/MyProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/lang', to: 'lang/[path][name].[ext]'},
        {from: './themes/MyProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/fonts', to: 'fonts/[path][name].[ext]'},
        {from: './themes/MyProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/images/flags', to: 'images/flags/[path][name].[ext]'},
        {from: './themes/MyProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/images/users', to: 'images/users/[path][name].[ext]'},
    ])

    // Global Assets
    .addStyleEntry( 'css/app', './themes/MyProjects_VelzonDefault/assets/css/app.scss' )
    .addEntry( 'js/layout', './themes/MyProjects_VelzonDefault/assets/layout.js' )
    .addEntry( 'js/app', './themes/MyProjects_VelzonDefault/assets/app.js' )
    
    // Pages Assets
    .addEntry( 'js/pages/projects', './assets/default/js/pages/projects.js' )
    .addEntry( 'js/pages/virtual_hosts', './assets/default/js/pages/virtual_hosts.js' )
    .addEntry( 'js/pages/php_versions', './assets/default/js/pages/php_versions.js' )
    .addEntry( 'js/pages/phpbrew_extensions', './assets/default/js/pages/phpbrew_extensions.js' )
    .addEntry( 'js/pages/projects_third_party', './assets/default/js/pages/projects_third_party.js' )
    
    .addEntry( 'js/pages/test_jquery_terminal', './assets/default/js/pages/test_jquery_terminal.js' )
;

Encore.configureDefinePlugin( ( options ) => {
    options.IS_PRODUCTION = JSON.stringify( Encore.isProduction() );
});

const config = Encore.getWebpackConfig();
config.name = 'MyProjects_VelzonDefault';

config.resolve.extensions = ['.ts', '.js'];

module.exports = config;
