export default {
  login({ username }) {
    localStorage.setItem('username', username)
    return Promise.resolve()
  },
  logout() {
    localStorage.removeItem('username')
    return Promise.resolve()
  },
  checkError({ status }) {
    if (status === 401 || status === 403) {
      localStorage.removeItem('username')
      return Promise.reject()
    }
    return Promise.resolve()
  },
  checkAuth() {
    return localStorage.getItem('username') ? Promise.resolve() : Promise.reject()
  },
  getPermissions () {
		return Promise.resolve()
	}
}
