const { resolve } = require('path')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const chalk = require('chalk')
const ProgressBarPlugin = require('progress-bar-webpack-plugin')
const Dotenv = require('dotenv-webpack')

module.exports = (_, argv) => {
  const MODE = argv.config && argv.config.includes('webpack.config.prod.js') ? 'production' : 'development'
  return {
    entry: resolve('src/index.jsx'),
    output: {
      filename: `assets/js/[name]${MODE === 'production' ? '.[contenthash]' : ''}.js`,
      chunkFilename: 'assets/js/[chunkhash].js',
      path: resolve('dist')
    },
    resolve: {
      extensions: ['.jsx', '.js'],
      alias: {
        '@': resolve('src'),
        '@assets': resolve('src/assets'),
        '@components': resolve('src/components'),
        '@pages': resolve('src/pages'),
        '@common': resolve('src/common'),
        '@providers': resolve('src/providers')
      }
    },
    module: {
      rules: [
        {
          test: /\.html$/,
          use: ['html-loader']
        },
        {
          test: /\.css$/,
          use: [MiniCssExtractPlugin.loader, 'css-loader']
        },
        {
          test: /\.scss$/,
          use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader']
        },
        {
          test: /\.jsx?$/,
          exclude: /node_modules/,
          use: ['swc-loader'],
        }
      ]
    },
    devtool: 'source-map',
    plugins: [
      new Dotenv(),
      new HtmlWebpackPlugin({
        template: 'src/index.html',
        filename: 'index.html'
        // favicon: resolve('src/favicon.ico'),
      }),
      new MiniCssExtractPlugin({
        filename: `assets/css/[name]${MODE === 'production' ? '.[contenthash]' : ''}.css`
      }),
      new ProgressBarPlugin({
        format: `  :msg [:bar] ${chalk.green.bold(':percent')} (:elapsed s)`
      })
    ],
    devServer: {
      port: 8080,
      historyApiFallback: {
        disableDotRule: true
      }
    },
    mode: 'development',
    cache: true
  }
}
