import React from 'react'
import { Admin, Resource } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'
import authProvider from './authProvider'

const httpClient = (url, options = {}) => {
	if (!options.headers) {
			options.headers = new Headers({ Accept: 'application/json' })
	}
	const { token } = JSON.parse(localStorage.getItem('auth'))
	options.headers.set('Authorization', `Bearer ${token}`)
	return fetchUtils.fetchJson(url, options)
}

export default () => <Admin authProvider={authProvider} dataProvider={jsonServerProvider('https://jsonplaceholder.typicode.com', httpClient)} >
	<Resource name="index" />
</Admin>