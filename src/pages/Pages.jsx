import AsyncComponent from '@components/AsyncComponent/AsyncComponent'
import User from '@pages/User/User'

export const PageCheckout = AsyncComponent(() => import('@pages/Checkout/Checkout'))
export const PageUser = User
