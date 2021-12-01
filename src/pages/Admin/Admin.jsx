import React from 'react'
import { Admin } from 'react-admin'
import jsonServerProvider from 'ra-data-json-server'

export default () => <Admin dataProvider={jsonServerProvider('https://jsonplaceholder.typicode.com')} />