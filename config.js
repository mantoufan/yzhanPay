const webpack = {
  publicPath: '/douban-pocket/', // 生产环境绑定子目录，默认 /
  getPublicPath() {
    return MODE === 'production' ? webpack.publicPath : '/'
  }
}
const paths = {
  assets: '/assets/images/',
  getAssets() {
    return (MODE === 'production' ? webpack.publicPath : '') + paths.assets
  }
}
module.exports = { paths, webpack }