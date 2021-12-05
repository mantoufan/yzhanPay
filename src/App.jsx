import React from 'react'
import { Admin, Resource, fetchUtils } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'
import authProvider from '@providers/authProvider'
import i18nProvider from '@providers/i18nProvider'
import routeProvider from '@providers/routeProvider'
import { createBrowserHistory as createHistory } from 'history'
import '@assets/css/common.scss'
import { PageRoutes } from '@pages/Pages'

const history = createHistory()
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
      {PageRoutes.map(({ path, page }) => {
        const { PageList, PageEdit, PageCreate, PageIcon } = page
        return (
          <Resource
            name={path}
            list={PageList}
            edit={PageEdit}
            create={PageCreate}
            icon={PageIcon}
          />
        )
      })}
    </Admin>
  )
}
