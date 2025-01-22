const Encore    = require('@symfony/webpack-encore');
const webpack   = require('webpack');
const path      = require('path');

Encore
    .setOutputPath( 'public/shared_assets/build/my-web-projects-velzon-default/' )
    .setPublicPath( '/build/my-web-projects-velzon-default/' )
  
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
         from: './themes/MyWebProjects_VelzonDefault/assets/img',
         to: 'img/[name].[ext]',
     })
     
     // Velzon Images
    .copyFiles([
        //{from: './themes/MyWebProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/lang', to: 'lang/[path][name].[ext]'},
        {from: './themes/MyWebProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/fonts', to: 'fonts/[path][name].[ext]'},
        {from: './themes/MyWebProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/images/flags', to: 'images/flags/[path][name].[ext]'},
        {from: './themes/MyWebProjects_VelzonDefault/assets/vendor/Velzon_v4.2.0/images/users', to: 'images/users/[path][name].[ext]'},
    ])

    // Global Assets
    .addStyleEntry( 'css/app', './themes/MyWebProjects_VelzonDefault/assets/css/app.scss' )
    .addEntry( 'js/layout', './themes/MyWebProjects_VelzonDefault/assets/layout.js' )
    .addEntry( 'js/app', './themes/MyWebProjects_VelzonDefault/assets/app.js' )
    
    // Pages Assets
    .addEntry( 'js/pages/projects', './themes/MyWebProjects_VelzonDefault/assets/js/pages/projects.js' )
    .addEntry( 'js/pages/virtual_hosts', './themes/MyWebProjects_VelzonDefault/assets/js/pages/virtual_hosts.js' )
    .addEntry( 'js/pages/php_versions', './themes/MyWebProjects_VelzonDefault/assets/js/pages/php_versions.js' )
    .addEntry( 'js/pages/phpbrew_extensions', './themes/MyWebProjects_VelzonDefault/assets/js/pages/phpbrew_extensions.js' )
    .addEntry( 'js/pages/projects_third_party', './themes/MyWebProjects_VelzonDefault/assets/js/pages/projects_third_party.js' )
    
    .addEntry( 'js/pages/test_jquery_terminal', './themes/MyWebProjects_VelzonDefault/assets/js/pages/test_jquery_terminal.js' )
;

Encore.configureDefinePlugin( ( options ) => {
    options.IS_PRODUCTION = JSON.stringify( Encore.isProduction() );
});

const config = Encore.getWebpackConfig();
config.name = 'MyWebProjects_VelzonDefault';

config.resolve.extensions = ['.ts', '.js'];

module.exports = config;
