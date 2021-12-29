const ESLintPlugin = require('eslint-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { WebpackManifestPlugin }  = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const path = require('path');
const settings = require('./webpack.settings.js');

const baseConfig = {
    context: settings.paths.base,
    entry: {
        "app": path.join(settings.paths.resources, '/js/app.js'),
        "admin": path.join(settings.paths.resources, '/js/admin.js'),
    },
    output: {
        path: settings.paths.output,
        filename: settings.output.filename + '.js',
        publicPath: settings.paths.public,
        assetModuleFilename: settings.paths.publicImagesPostfix + '[hash][ext][query]',
    },
    target: settings.target,
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: "babel-loader",
                        options: {
                            presets: ['@babel/preset-env']
                        },
                    },
                ],
            },
            {
                test: /\.p?css$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: 'css-loader',
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [
                                    require('postcss-import'),
                                    require('tailwindcss/nesting'),
                                    require('tailwindcss'),
                                    require('autoprefixer'),
                                ],
                            },
                        },
                    },
                ],
            },
            {
                test: /\.(png|svg|jpg|gif)$/,
                type: 'asset/resource',
            },
        ],
    },
    plugins: [
        new ESLintPlugin({
            fix: true,
        }),
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                '**/*',
                '!.gitignore',
                '!manifest.json'
            ],
            verbose: false,
            dry: false
        }),
        new WebpackManifestPlugin({
            publicPath: './dist/',
        }),
        new MiniCssExtractPlugin({
            filename: settings.output.filename + '.css',
        }),
    ],
};

module.exports = {
    'baseConfig': baseConfig,
};
