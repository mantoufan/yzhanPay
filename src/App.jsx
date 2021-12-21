import { Admin, Resource, fetchUtils, defaultTheme } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'
import authProvider from '@providers/authProvider'
import i18nProvider from '@providers/i18nProvider'
import routeProvider from '@providers/routeProvider'
import { createBrowserHistory as createHistory } from 'history'
import '@assets/css/common.scss'
import { PageRoutes } from '@pages/Pages'
import theme from '@providers/themeProvider'
import { ADMIN_API } from '@common/config'
import DocumentTitle from '@components/DocumentTitle/DocumentTitle'


const history = createHistory()
const httpClient = (url, options = {}) => {
  if (!options.headers) {
    options.headers = new Headers({ Accept: 'application/json' })
  }
  options.headers.set('Authorization', `Bearer ${localStorage.getItem('auth')}`)
  return fetchUtils.fetchJson(url, options)
}

const AdminTheme = Object.assign({}, defaultTheme, theme)

export default () => {
  return (
    <Admin
      theme={AdminTheme}
      history={history}
      authProvider={authProvider}
      dataProvider={jsonServerProvider(ADMIN_API, httpClient)}
      i18nProvider={i18nProvider}
      customRoutes={routeProvider}
      disableTelemetry={false}>
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
      <DocumentTitle />
    </Admin>
  )
}
