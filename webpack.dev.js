const common = require('./webpack.common.js');
const { merge } = require('webpack-merge');

module.exports = [
    merge(
        common.baseConfig,
        {
            mode: 'development',
            devtool: 'source-map',
            watchOptions: {
                poll: true,
                ignored: [
                    'app/**',
                    'node_modules/**',
                    'storage/**',
                    'tests/**',
                    'vendor/**',
                ],
            },
        }
    )
];
