export const API_HOST = process.env.API_HOST || location.origin
export const BASE_API = API_HOST + '/api'
export const ADMIN_API = BASE_API + '/admin'
export const GATEWAY_SUMBIT_API = BASE_API + '/gateway/sumbit'
export const AUTH_API = BASE_API + '/auth/login'
export const CHECKOUT_API = BASE_API + '/checkout'