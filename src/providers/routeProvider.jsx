import React from 'react'
import { RouteWithoutLayout } from 'react-admin'
import { PageCheckout, PageDev } from '@pages/Pages'

export default [
  <RouteWithoutLayout exact path="/checkout" component={PageCheckout} />,
  <RouteWithoutLayout exact path="/dev" component={PageDev} />
]
