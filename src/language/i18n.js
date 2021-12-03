import English from 'ra-language-english'
import Chinese from 'ra-language-chinese'

export default {
  en: Object.assign(English, {
    notification: {
      login: {
        wrong: 'Wrong account and password'
      }
    }
  }),
  zh: Object.assign(Chinese, {
    notification: {
      login: {
        wrong: '错误用户名和密码'
      }
    }
  })
}