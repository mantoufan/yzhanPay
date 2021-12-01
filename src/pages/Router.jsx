import React, { Component } from 'react'
import { Route, withRouter, Switch } from 'react-router-dom'
import { PageAdmin } from '@pages/Pages'

class PageRouter extends Component {
  constructor(props) {
    super(props)
    this.routes = [
      { path: '/admin', component: PageAdmin }
    ]
  }
	
  render () {
    return (
      <Switch>
        { this.routes.map((route, index) => <Route key={index} path={route.path} exact component={route.component}/>) }
      </Switch>
    )
  }
}
export default withRouter(PageRouter)