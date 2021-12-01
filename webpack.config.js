const { resolve } = require('path')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CopyPlugin = require('copy-webpack-plugin')
const { DefinePlugin } = require('webpack')
const { webpack } = require('./config')

module.exports = (env, argv) => {
  const MODE = argv.config === 'webpack.config.prod.js' ? 'production' : 'development'
  return {
    entry: resolve('src/index.jsx'),
    output: {
      filename: MODE === 'production' ? 'assets/js/bundle.[contenthash].js' : 'assets/js/bundle.js',
      chunkFilename: 'assets/js/[chunkhash].js',
      path: resolve('dist'),
      publicPath: MODE === 'production' ? webpack['publicPath'] : '/'
    },
    resolve: {
      extensions: ['.jsx', '.js'],
      alias: {
        '@': resolve('src'),
        '@assets': resolve('src/assets'),
        '@components': resolve('src/components'),
        '@pages': resolve('src/pages')
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
        use: [
          'thread-loader',
          {
            loader: 'babel-loader?cacheDirectory',
            options: {
              presets: ['@babel/preset-env', '@babel/preset-react'],
              plugins: ['@babel/plugin-transform-runtime']
            }
          }
        ]
      }],
    },
    devtool: 'source-map',
    plugins: [
      new CopyPlugin({
        patterns: [
          // {
          //   from: resolve('src/assets/images'),
          //   to: resolve('dist/assets/images'),
          // },
					{
            from: resolve('src/api'),
            to: resolve('dist/api'),
          }
        ]
      }),
      new HtmlWebpackPlugin({
        template: 'src/index.html',
        filename: 'index.html',
        // favicon: resolve('src/favicon.ico'),
      }),
      new MiniCssExtractPlugin({
        filename: MODE === 'production' ? 'assets/css/style.[contenthash].css' : 'assets/css/style.css',
      }),
      new DefinePlugin({
        MODE: JSON.stringify(MODE)
      })
    ],
    devServer: {
      hot: true,
			host: 'pay.os120.com',
			port: 8080,
      historyApiFallback: {
        disableDotRule: true
      }
		},
		mode: 'development'
  }
}