import React, { Component } from 'react'
import { Route, withRouter, Switch } from 'react-router-dom'
import { webpack } from '/config'
class PageRouter extends Component {
  constructor(props) {
    super(props)
    this.publicPath = webpack.getPublicPath()
    this.routes = [
      { }
    ]
  }
	
  render () {
    return (
      <Switch>
        { this.routes.map((route, index) => <Route key={index} path={this.publicPath + route.path} exact component={route.component}/>) }
      </Switch>
    )
  }
}
export default withRouter(PageRouter)