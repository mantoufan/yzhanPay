import English from 'ra-language-english'
import Chinese from 'ra-language-chinese'

export default {
  en: Object.assign(English, {
    notification: {
      login: {
        wrong: 'Wrong account and password'
      }
    },
    company: {
      name: 'Jungle Scout'
    },
    footer: {
      copyright: 'Copyright'
    },
    checkout: {
      checkout: 'Checkout',
      orderInfo: 'Order Info',
      appName: 'App Name',
      outTradeNo: 'Out Trade No',
      subject: 'Subject',
      description: 'Description',
      price: 'Price',
      totalAmount: 'Total Amount',
      paymentChannel:'Payment Channel'
    }
  }),
  zh: Object.assign(Chinese, {
    notification: {
      login: {
        wrong: '错误用户名和密码'
      }
    },
    company: {
      name: '桨歌(深圳)科技有限公司'
    },
    footer: {
      copyright: '版权所有'
    },
    checkout: {
      checkout: '结账',
      orderInfo: '订单信息',
      appName: '应用名称',
      orderInfo: '订单信息',
      outTradeNo: '订单编号',
      subject: '商品名称',
      description: '商品描述',
      price: '价格',
      totalAmount: '总金额',
      paymentChannel:'支付渠道'
    }
  })
}