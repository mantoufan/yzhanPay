import { PATH_API } from '@/config'
import i18nProvider from '@/language/i18nProvider'

export default {
	login ({ username, password }) {
		const request = new Request(PATH_API + 'authenticate', {
			method: 'POST',
			body: JSON.stringify({ username, password }),
			headers: new Headers({ 'Content-Type': 'application/json' }),
		})
		return fetch(request)
			.then(response => {
				if (response.status < 200 || response.status >= 300) {
					throw new Error(translate('notification.login.wrong'))
				}
				return response.json()
			})
			.then(auth => {
				localStorage.setItem('auth', JSON.stringify(auth))
			})
			.catch(() => {
				throw new Error(i18nProvider.translate('notification.login.wrong'))
			})
	},
	checkAuth () {
		return localStorage.getItem('auth') ? Promise.resolve() : Promise.reject()
	},
	logout () {
		localStorage.removeItem('auth')
		return Promise.resolve()
	}
}