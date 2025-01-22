var Encore = require('@symfony/webpack-encore');

/**
 *  AdminPanel Default Theme
 */
const themePath         = './vendor/vankosoft/application/src/Vankosoft/ApplicationBundle/Resources/themes/default';
const adminPanelConfig  = require( themePath + '/webpack.config' );

//=================================================================================================

/**
 *  MyProjects Velzon Theme
 */
Encore.reset();
const myWebProjectsVelzonConfig = require( './themes/MyWebProjects_VelzonDefault/webpack.config' );

//=================================================================================================

module.exports = [
    adminPanelConfig,
    myWebProjectsVelzonConfig
];
