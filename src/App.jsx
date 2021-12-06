import React from 'react'
import { Admin, Resource, fetchUtils, defaultTheme } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'
import authProvider from '@providers/authProvider'
import i18nProvider from '@providers/i18nProvider'
import routeProvider from '@providers/routeProvider'
import { createBrowserHistory as createHistory } from 'history'
import '@assets/css/common.scss'
import { PageRoutes } from '@pages/Pages'
import orange from '@material-ui/core/colors/orange'
import amber from '@material-ui/core/colors/amber'

const history = createHistory()
const httpClient = (url, options = {}) => {
  if (!options.headers) {
    options.headers = new Headers({ Accept: 'application/json' })
  }
  options.headers.set('Authorization', `Bearer ${localStorage.getItem('auth')}`)
  return fetchUtils.fetchJson(url, options)
}

const myTheme = Object.assign({}, defaultTheme, {
  palette: {
    primary: orange,
    secondary: amber
  }
})

export default () => {
  return (
    <Admin
      theme={myTheme}
      history={history}
      authProvider={authProvider}
      dataProvider={jsonServerProvider('https://pay.os120.com/api', httpClient)}
      i18nProvider={i18nProvider}
      customRoutes={routeProvider}>
      {PageRoutes.map(({ path, page }) => {
        const { PageList, PageEdit, PageCreate, PageIcon } = page
        return (
          <Resource
            key={path}
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
