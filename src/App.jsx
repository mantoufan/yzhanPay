import React from 'react'
import { Admin, Resource, fetchUtils, EditGuesser } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'
import authProvider from '@providers/authProvider'
import i18nProvider from '@providers/i18nProvider'
import routeProvider from '@providers/routeProvider'
import { createBrowserHistory as createHistory } from 'history'
import '@assets/css/common.scss'
import { PageUser } from '@pages/Pages'

const history = createHistory()
const { UserIcon, UserList, UserEdit, UserCreate } = PageUser
const httpClient = (url, options = {}) => {
  if (!options.headers) {
    options.headers = new Headers({ Accept: 'application/json' })
  }
  options.headers.set('Authorization', `Bearer ${localStorage.getItem('auth')}`)
  return fetchUtils.fetchJson(url, options)
}

export default () => {
  return (
    <Admin
      history={history}
      authProvider={authProvider}
      dataProvider={jsonServerProvider('https://pay.os120.com/api', httpClient)}
      i18nProvider={i18nProvider}
      customRoutes={routeProvider}>
      <Resource name="user" list={UserList} edit={UserEdit} create={UserCreate} icon={UserIcon} />
    </Admin>
  )
}
