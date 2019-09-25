const PurgecssPlugin = require('purgecss-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');

const common = require('./webpack.common.js');
const glob = require('glob');
const merge = require('webpack-merge');
const settings = require('./webpack.settings.js');

module.exports = [
    merge(
        common.baseConfig,
        {
            mode: 'production',
            devtool: 'inline-source-map',
            plugins: [
                new PurgecssPlugin({
                    paths: glob.sync(`${settings.paths.templates}/**/*`, { nodir: true }),
                    defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
                }),
                new OptimizeCSSAssetsPlugin({
                    cssProcessorOptions: {
                        map: {
                            inline: false,
                            annotation: true,
                        },
                        safe: true,
                        discardComments: true
                    },
                }),
            ],
        }
    )
];
