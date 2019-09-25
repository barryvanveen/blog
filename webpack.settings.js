const path = require('path');

module.exports = {
    target: "web",
    paths: {
        base: path.resolve(__dirname),
        resources: path.resolve(__dirname, "./resources/"),
        templates: path.resolve(__dirname, "./resources/views/"),
        public: path.resolve(__dirname, "./public/"),
        output: path.resolve(__dirname, "./public/dist/"),
    },
    output: {
        filename: "[name].[hash]",
    },
};
