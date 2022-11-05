var Encore = require('@symfony/webpack-encore');

Encore
// the project directory where compiled assets will be stored
    .setOutputPath('public/assets/default/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/assets/default/build/')

    // delete old files before creating them
    .cleanupOutputBeforeBuild()
    
    // generate runtime.js that must to be loaded into the page template
    // bootstrap the environement
    .enableSingleRuntimeChunk()

    // add debug data in development
    .enableSourceMaps(!Encore.isProduction())

    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    
    // enable sass/scss parser
    .enableSassLoader()

    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // see https://symfony.com/doc/current/frontend/encore/bootstrap.html
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: true
    })
    
    .copyFiles({
	     from: './assets/default/img',
	     to: 'img/[name].[ext]',
	 })
    .copyFiles({
	     from: './node_modules/bootstrap-sass/assets/fonts/bootstrap',
	     to: 'fonts/bootstrap/[name].[ext]',
	 })
    .copyFiles([
		
		// CkEditor
		{from: './node_modules/ckeditor/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor/skins', to: 'ckeditor/skins/[path][name].[ext]'}
	])
	
    // add hash after file name
    .configureFilenames({
        js: '[name].js?[contenthash]',
        css: '[name].css?[contenthash]',
        assets: '[name].[ext]?[hash:8]'
    })
    
     // for "legacy" applications that require $/jQuery as a global variable
    .autoProvidejQuery()

    
    .addStyleEntry('css/app', './assets/default/scss/app.scss')
    .addStyleEntry('css/custom', './assets/default/css/custom.css')
    
    .addEntry( 'js/app', './assets/default/js/app.js' )
    .addEntry( 'js/pages/projects', './assets/default/js/pages/projects.js' )
    .addEntry( 'js/pages/virtual_hosts', './assets/default/js/pages/virtual_hosts.js' )
    .addEntry( 'js/pages/php_versions', './assets/default/js/pages/php_versions.js' )
    .addEntry( 'js/pages/phpbrew_extensions', './assets/default/js/pages/phpbrew_extensions.js' )
    .addEntry( 'js/pages/projects_third_party', './assets/default/js/pages/projects_third_party.js' )
    .addEntry( 'js/pages/vagrant_machines', './assets/default/js/pages/vagrant_machines.js' )
;
const defaultConfig = Encore.getWebpackConfig();
defaultConfig.name = 'default';

//=================================================================================================

module.exports = [defaultConfig];
