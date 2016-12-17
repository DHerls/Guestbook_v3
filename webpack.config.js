var path = require("path");

module.exports = {
    context: path.resolve('resources'),
    entry: {
        app: ['./assets/js/bootstrap.js', './assets/js/jquery.notifyBar.js'],
        index: ['./assets/js/sortsearchtable.js', './assets/js/scrollToTop.js'],
        new: ['./assets/js/newmember.js'],
        edit: './assets/js/editmember.js'
    },
    output: {
        path: path.resolve('public/js'),
        publicPath: '/js/',
        filename: "[name].js"
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                loader: 'buble',
                exclude: /node_modules/
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                loader: 'file-loader',
                query: {
                    limit: 10000,
                    name: '../img/[name].[hash:7].[ext]'
                }
            },
            {
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: '../fonts/[name].[hash:7].[ext]'
                }
            }
        ]
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        }
    },
    vue: {
        loaders: {
            js: 'buble-loader'
        }
    },
}
