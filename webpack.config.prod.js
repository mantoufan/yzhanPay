const { merge } = require('webpack-merge')
const common = require('./webpack.config.js')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const ImageminPlugin = require('imagemin-webpack-plugin').default
const TerserPlugin = require('terser-webpack-plugin')
module.exports = (env, argv) => {
  return merge(common(env, argv), {
    mode: 'production',
    optimization: {
      minimizer: [
        new TerserPlugin({
          terserOptions: {
            toplevel: true,
          }
        }),
        new CssMinimizerPlugin({
          parallel: true,
          minimizerOptions: {
              preset: 'advanced',
          },
        })
      ]
    },
    plugins: [
      new CleanWebpackPlugin(),
      new ImageminPlugin({
        test: /\.png$/i,
        pngquant: {
            quality: '70-75'
        }
      }),
    ],
    mode: 'production'
  })
}