import React from 'react'
import { Admin, Resource } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'
import authProvider from '@providers/authProvider'
import i18nProvider from '@providers/i18nProvider'
import routeProvider from '@providers/routeProvider'
import { createBrowserHistory as createHistory } from 'history'
import '@assets/css/common.scss'

const history = createHistory()
const httpClient = (url, options = {}) => {
  if (!options.headers) {
    options.headers = new Headers({ Accept: 'application/json' })
  }
  const { token } = JSON.parse(localStorage.getItem('auth'))
  options.headers.set('Authorization', `Bearer ${token}`)
  return fetchUtils.fetchJson(url, options)
}

export default () => (
  <Admin
    history={history}
    authProvider={authProvider}
    dataProvider={jsonServerProvider('https://jsonplaceholder.typicode.com', httpClient)}
    i18nProvider={i18nProvider}
    customRoutes={routeProvider}>
    <Resource name="index" />
  </Admin>
)
