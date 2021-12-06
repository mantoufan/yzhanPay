import AsyncComponent from '@components/AsyncComponent/AsyncComponent'
import Channel from '@pages/Channel/Channel'
import Client from '@pages/Client/Client'
import Menu from '@pages/Menu/Menu'
import Payment from '@pages/Payment/Payment'
import Plugin from '@pages/Plugin/Plugin'
import User from '@pages/User/User'

export const PageCheckout = AsyncComponent(() => import('@pages/Checkout/Checkout'))
export const PageDev = AsyncComponent(() => import('@pages/Dev/Dev'))
export const PageRoutes = [
  { path: 'channel', page: Channel },
  { path: 'client', page: Client },
  { path: 'menu', page: Menu },
  { path: 'payment', page: Payment },
  { path: 'plugin', page: Plugin },
  { path: 'user', page: User }
]
