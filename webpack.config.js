const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = (env) => {

  const prod = env === 'production';
  const dev = env === 'development';
  const test = env === 'testing';

  let config = {
    mode: prod ? 'production' : 'development',
    entry: {
      app: './src/Assets/js/src/index.js'
    },
    output: {
      path: path.resolve(__dirname, './dist'),
      publicPath: '/',
      filename: '[name]' + (prod ? '.min' : '') + '.js'
    },
    performance: {
      hints: false
    },
    module: {
      rules: [
        {
          test: /\.vue$/,
          loader: 'vue-loader',
          options: {
            hotReload: false
          }
        },
        // this will apply to both plain `.js` files
        // AND `<script>` blocks in `.vue` files
        {
          test: /\.js$/,
          loader: 'buble-loader',
          exclude: /node_modules/,
          options: {
            objectAssign: 'Object.assign'
          }
        },
        // this will apply to both plain `.scss` files
        // AND `<style lang="scss">` blocks in `.vue` files
        {
          test: /\.scss$/,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: {
                url: false,
                importLoaders: 2,
                minimize: prod
              }
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: !prod
              }
            },
            {
              loader: 'sass-loader',
              options: {
                sourceMap: !prod
              }
            }
          ]
        }
      ]
    },
    plugins: [
      new VueLoaderPlugin(),
      new MiniCssExtractPlugin({
        filename: '[name]' + (prod ? '.min' : '') + '.css'
      })
    ]
  };

  if (dev) {
    config.devtool = 'source-map';
  }

  if (test) {
    config.devtool = 'inline-cheap-module-source-map';
    config.output.devtoolModuleFilenameTemplate = '[absolute-resource-path]';
    config.output.devtoolFallbackModuleFilenameTemplate = '[absolute-resource-path]?[hash]';
  }

  return config;
};
