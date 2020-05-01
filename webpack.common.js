const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const path = require('path');
const settings = require('./webpack.settings.js');

const baseConfig = {
    context: settings.paths.base,
    entry: {
        "app": path.join(settings.paths.resources, '/js/app.js'),
    },
    output: {
        path: settings.paths.output,
        filename: settings.output.filename + '.js',
        publicPath: settings.paths.public,
    },
    target: settings.target,
    module: {
        rules: [
            {
                enforce: 'pre',
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'eslint-loader',
                        options: {
                            fix: true,
                        },
                    },
                ],
            },
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
                test: /\.css$/,
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
                            ident: 'postcss',
                            plugins: [
                                require('postcss-import'),
                                require('tailwindcss'),
                                require('postcss-nested'),
                                require('autoprefixer'),
                            ],
                        },
                    },
                ],
            },
            {
                test: /\.(png|svg|jpg|gif)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            outputPath: settings.paths.publicImagesPostfix,
                        },
                    },
                ],
            },
        ],
    },
    plugins: [
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                '**/*',
                '!.gitignore',
                '!manifest.json'
            ],
            verbose: false,
            dry: false
        }),
        new ManifestPlugin({
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
