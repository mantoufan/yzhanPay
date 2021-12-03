import React from 'react'
import { Admin, Resource, resolveBrowserLocale } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'
import authProvider from './authProvider'
import polyglotI18nProvider from 'ra-i18n-polyglot'
import i18n from '@/language/i18n'

const httpClient = (url, options = {}) => {
	if (!options.headers) {
			options.headers = new Headers({ Accept: 'application/json' })
	}
	const { token } = JSON.parse(localStorage.getItem('auth'))
	options.headers.set('Authorization', `Bearer ${token}`)
	return fetchUtils.fetchJson(url, options)
}

const i18nProvider = polyglotI18nProvider(locale => i18n[locale] || i18n.en, resolveBrowserLocale())

export default () => <Admin authProvider={authProvider} dataProvider={jsonServerProvider('https://jsonplaceholder.typicode.com', httpClient)} i18nProvider={i18nProvider} >
	<Resource name="index" />
</Admin>