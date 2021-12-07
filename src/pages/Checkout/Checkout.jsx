import React, { useState } from 'react'
import { useLocation } from 'react-router-dom'
import qs from 'query-string'
import ContainerMedium from '@components/ContainerMedium/ContainerMedium'
import CopyrightFooter from '@components/CopyrightFooter/CopyrightFooter'
import theme from '@providers/themeProvider'
import { ThemeProvider, styled } from '@material-ui/core/styles'
import Box from '@material-ui/core/Box'
import Paper from '@material-ui/core/Paper'
import Grid from '@material-ui/core/Grid'
import Button from '@material-ui/core/Button'
import Radio from '@material-ui/core/Radio'
import RadioGroup from '@material-ui/core/RadioGroup'
import FormControlLabel from '@material-ui/core/FormControlLabel'
import FormControl from '@material-ui/core/FormControl'
import FormLabel from '@material-ui/core/FormLabel'
import AppBar from '@material-ui/core/AppBar'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import TextField from '@material-ui/core/TextField'
import InputAdornment from '@material-ui/core/InputAdornment'
import i18nProvider from '@providers/i18nProvider'
const { translate } = i18nProvider

const useQuery = () => {
  const { search } = useLocation()
  return qs.parse(search)
}

const PadPaper = styled(Paper)({
  padding: '12px',
  '& fieldset': { width: '100%' },
  '& fieldset > div': { marginBottom: '10px' }
})
const TextFieldHidden = styled(TextField)({
  display: 'none'
})
const TextFieldNoLine = styled(TextField)({
  '& > ::before': {
    display: 'none'
  },
  '& > ::after': {
    display: 'none'
  }
})
const TextFieldPrice = styled(TextFieldNoLine)({
  '& input': {
    fontSize: '30px'
  }
})

export default () => {
  const query = useQuery()
  const [value, setValue] = useState('alipay')
  const handleChange = (event) => setValue(event.target.value)

  return (
    <ThemeProvider theme={theme}>
      <AppBar position="static">
        <Toolbar>
          <Typography variant="h6" component="div">
            {translate('checkout.checkout')}
          </Typography>
        </Toolbar>
      </AppBar>
      <ContainerMedium>
        <form action="./api/checkout" method="POST">
          <Box sx={{ flexGrow: 1 }}>
            <Grid container spacing={2}>
              <Grid item xs={6}>
                <PadPaper>
                  <FormControl component="fieldset">
                    <FormLabel component="legend">{translate('checkout.orderInfo')}</FormLabel>
                    <TextFieldNoLine
                      InputProps={{
                        readOnly: true
                      }}
                      label={translate('checkout.appName')}
                      defaultValue="JS Web App"
                    />
                    <TextFieldHidden name="app_id" label="App ID" defaultValue={query.app_id} />
                    <TextFieldNoLine
                      InputProps={{
                        readOnly: true
                      }}
                      name="out_trade_no"
                      label={translate('checkout.outTradeNo')}
                      defaultValue={query.out_trade_no}
                    />
                    <TextFieldNoLine
                      InputProps={{
                        readOnly: true
                      }}
                      name="subject"
                      label={translate('checkout.subject')}
                      defaultValue={query.subject}
                    />
                    <TextFieldHidden
                      name="return_url"
                      label="Return Url"
                      defaultValue={query.return_url}
                    />
                    <TextFieldHidden
                      name="notify_url"
                      label="Notify Url"
                      defaultValue={query.return_url}
                    />
                    <TextFieldHidden
                      name="timestamp"
                      label="Timestamp"
                      defaultValue={query.timestamp}
                    />
                    <TextFieldHidden name="sign" label="Sign" defaultValue={query.sign} />
                    {query.body ? (
                      <TextFieldNoLine
                        InputProps={{
                          readOnly: true
                        }}
                        name="body"
                        label={translate('checkout.description')}
                        multiline
                        defaultValue={query.body}
                      />
                    ) : null}
                  </FormControl>
                </PadPaper>
              </Grid>
              <Grid item xs={4}>
                <PadPaper>
                  <FormControl component="fieldset">
                    <FormLabel component="legend">{translate('checkout.price')}</FormLabel>
                    <TextFieldPrice
                      InputProps={{
                        readOnly: true,
                        startAdornment: <InputAdornment position="start">￥</InputAdornment>
                      }}
                      name="total_amount"
                      label={translate('checkout.totalAmount')}
                      defaultValue={query.total_amount}
                    />
                  </FormControl>
                  <FormControl component="fieldset">
                    <FormLabel component="legend">{translate('checkout.paymentChannel')}</FormLabel>
                    <RadioGroup
                      row
                      aria-label="position"
                      name="channel"
                      defaultValue="end"
                      value={value}
                      onChange={handleChange}>
                      <FormControlLabel
                        value="alipay"
                        control={<Radio color="primary" />}
                        label="Alipay"
                      />
                      <FormControlLabel
                        value="paypal"
                        control={<Radio color="primary" />}
                        label="Paypal"
                      />
                    </RadioGroup>
                  </FormControl>
                  <Button
                    variant="contained"
                    color="primary"
                    type="submit"
                    style={{ width: '100%' }}>
                    {translate('checkout.checkout')}
                  </Button>
                </PadPaper>
              </Grid>
            </Grid>
          </Box>
        </form>
      </ContainerMedium>
      <CopyrightFooter />
    </ThemeProvider>
  )
}
