const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
  devtool: '#source-map',
  entry: {
    app: './src/Assets/js/src/index.js'
  },
  output: {
    path: path.resolve(__dirname, './dist'),
    publicPath: '/',
    filename: '[name].js'
  },
  performance: {
    hints: false
  },
  module: {
    noParse: /es6-promise\.js$/, // avoid webpack shimming process
    rules: [
      {
        enforce: 'pre',
        test: /.vue$/,
        loader: 'eslint-loader',
        exclude: /node_modules/
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          buble: {
            objectAssign: 'Object.assign',
          },
          preserveWhitespace: false,
          loaders: {
            scss: ExtractTextPlugin.extract({
              use: 'css-loader?-url!sass-loader',
              fallback: 'vue-style-loader' // <- this is a dep of vue-loader
            })
          },
          postcss: [require('autoprefixer')()]
        }
      },
      {
        test: /\.js$/,
        loader: 'buble-loader',
        exclude: /node_modules/,
        options: {
          objectAssign: 'Object.assign'
        }
      },
      {
        test: /\.(png|jpg|gif|svg)$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: '[name].[ext]?[hash]'
        }
      }
    ]
  },
  plugins: [
    // extract css
    new ExtractTextPlugin('app.css')
  ]
};
