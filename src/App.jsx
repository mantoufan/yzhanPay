import React from 'react'
import { BrowserRouter as Router } from 'react-router-dom'
import PageRouter from '@pages/Router'
import '@assets/css/common.scss'

export default () => <div className="app"><Router><PageRouter /></Router></div>