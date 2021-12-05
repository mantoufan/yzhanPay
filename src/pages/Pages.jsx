import AsyncComponent from '@components/AsyncComponent/AsyncComponent'
import Users from '@pages/Users/Users'

export const PageCheckout = AsyncComponent(() => import('@pages/Checkout/Checkout'))
export const PageUsers = Users
