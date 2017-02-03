var path = require("path");
var webpack = require("webpack");
var CleanWebpackPlugin = require('clean-webpack-plugin');
var AssetsPlugin = require('assets-webpack-plugin')
var assetsPluginInstance = new AssetsPlugin({path: path.join(__dirname, 'storage','app'),filename: 'stats.json'})

module.exports = {
    context: path.resolve('resources'),
    entry: {
        app: ['./assets/js/bootstrap.js', 'jqnotifybar', './assets/js/confirm.js', './assets/js/errorhandler.js'],
        index: ['./assets/js/sortsearchtable.js', './assets/js/scrollToTop.js'],
        new: ['./assets/js/members/newmember.js'],
        edit: './assets/js/members/editmember.js',
        checkin: './assets/js/guests/checkinguest.js',
        guests: './assets/js/guests/guesttable.js',
        display: './assets/js/members/displaymember.js',
        notes: './assets/js/members/notetable.js',
        balance: './assets/js/members/balancetable.js',
        users: './assets/js/usertable.js',
        reports: './assets/js/reports.js',
        test: ['./assets/js/test.js']
    },
    output: {
        path: path.resolve('public/js'),
        publicPath: '/js/',
        filename: "[name].[hash].js"
    },
    module: {
        loaders: [
            {test: /signature_pad/, loader: 'expose?SignaturePad'},
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
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
            },
            { test: /\.json$/, loader: 'json' },
        ]
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        },
        modulesDirectories: ["web_modules", "node_modules", "bower_components"]

    },
    vue: {
        loaders: {
            js: 'babel-loader'
        }
    },
    plugins: [
        new CleanWebpackPlugin(['public\\js'], {
            root: 'C:\\Users\\Developer\\Documents\\Ridge Top\\guestbook_v3',
            verbose: true,
            dry: false,
            exclude: ['shared.js']
        }),
        new webpack.ResolverPlugin(
            new webpack.ResolverPlugin.DirectoryDescriptionFilePlugin(".bower.json", ["main"])
        ),
        assetsPluginInstance,
    ]
}
