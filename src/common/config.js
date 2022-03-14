export const WEB_URL = process.env.WEB_URL || location.host
export const BASE_API = WEB_URL + '/api'
export const ADMIN_API = BASE_API + '/admin'
export const GATEWAY_SUMBIT_API = BASE_API + '/gateway/sumbit'
export const AUTH_API = BASE_API + '/auth/login'
export const CHECKOUT_API = BASE_API + '/checkout'