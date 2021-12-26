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
      title: 'Checkout',
      orderInfo: 'Order Info',
      appName: 'App Name',
      outTradeNo: 'Out Trade No',
      subject: 'Subject',
      description: 'Description',
      price: 'Price',
      totalAmount: 'Total',
      channel:'Please select your payment channel',
      mindRate:'Mind giving a rate to your experience on our site',
      othercost:'Exchange Rate Fee',
      totalTitle:"Here's your Total",
      ability: {
        question: 'Please select your payment method',
        checkout: 'Checkout',
        subscribe: 'Subscribe',
      }
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
      title: '结账',
      orderInfo: '订单信息',
      appName: '应用名称',
      orderInfo: '订单信息',
      outTradeNo: '订单编号',
      subject: '商品名称',
      description: '商品描述',
      price: '价格',
      totalAmount: '总金额',
      channel:'请选择您的支付渠道',
      mindRate:'能麻烦您对网站的使用感受给予评价吗？',
      othercost:'汇率费用',
      totalTitle:"账单总结",
      ability: {
        question: '请选择你的付款方式',
        checkout: '单次付款',
        subscribe: '订阅',
      }
    }
  })
}