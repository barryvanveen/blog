const path = require('path');

module.exports = {
    target: "web",
    paths: {
        base: path.resolve(__dirname),
        resources: path.resolve(__dirname, "./resources/"),
        templates: path.resolve(__dirname, "./resources/views/"),
        output: path.resolve(__dirname, "./public/dist/"),
        public: "",
        publicImagesPostfix: "images/",
    },
    output: {
        filename: "[name].[contenthash]",
    },
};
