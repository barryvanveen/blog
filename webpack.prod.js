const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

const common = require('./webpack.common.js');
const { merge } = require('webpack-merge');

module.exports = [
    merge(
        common.baseConfig,
        {
            mode: 'production',
            plugins: [],
            optimization: {
                minimize: true,
                minimizer: [
                    `...`,
                    new CssMinimizerPlugin({
                        minimizerOptions: {
                            preset: [
                                "default",
                                {
                                    discardComments: { removeAll: true },
                                },
                            ],
                        },
                    }),

                ],
            },
        }
    )
];
