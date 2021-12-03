import { PATH_API } from '@/config'
export default {
	login ({ username, password }) {
		const request = new Request(PATH_API + 'authenticate', {
			method: 'POST',
			body: JSON.stringify({ username, password }),
			headers: new Headers({ 'Content-Type': 'application/json' }),
		})
		return fetch(request)
			.then(response => {
				console.log('response', response)
				if (response.status < 200 || response.status >= 300) {
					throw new Error(response.statusText)
				}
				return response.json()
			})
			.then(auth => {
				localStorage.setItem('auth', JSON.stringify(auth))
			})
			.catch(() => {
				throw new Error('Network error')
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