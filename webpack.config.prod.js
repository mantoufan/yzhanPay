const { merge } = require('webpack-merge')
const common = require('./webpack.config.js')
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const ImageminPlugin = require('imagemin-webpack-plugin').default
const TerserPlugin = require('terser-webpack-plugin')

module.exports = (env, argv) => merge(common(env, argv), {
  mode: 'production',
  optimization: {
    splitChunks: {
      chunks: 'all',
    },
    minimizer: [
      new TerserPlugin({
        minify: TerserPlugin.swcMinify,
        terserOptions: {
          toplevel: true
        }
      }),
      new CssMinimizerPlugin({
        minimizerOptions: {
          preset: 'advanced',
        },
      })
    ]
  },
  plugins: [
    new ImageminPlugin({
      test: /\.png$/i,
      pngquant: {
          quality: '70-75'
      }
    }),
  ],
  mode: 'production'
})