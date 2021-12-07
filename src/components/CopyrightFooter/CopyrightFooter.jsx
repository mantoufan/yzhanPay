import React from 'react'

import Box from '@material-ui/core/Box'
import i18nProvider from '@providers/i18nProvider'
import { styled } from '@material-ui/core/styles'

const { translate } = i18nProvider
const Bottom = styled(Box)({
  position: 'absolute',
  right: 0,
  bottom: '30px',
  left: 0,
  margin: 'auto',
  width: 'fit-content',
  fontSize: '12px',
  color: '#999'
})

export default () => (
  <Bottom>
    {translate('footer.copyright')} © 2018-2021 {translate('company.name')}
  </Bottom>
)
