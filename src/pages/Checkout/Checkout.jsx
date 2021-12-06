import React from 'react'
import { useLocation } from 'react-router-dom'
import qs from 'query-string'
import Button from '@material-ui/core/Button'
import Radio from '@material-ui/core/Radio'
import RadioGroup from '@material-ui/core/RadioGroup'
import FormControlLabel from '@material-ui/core/FormControlLabel'
import FormControl from '@material-ui/core/FormControl'
import FormLabel from '@material-ui/core/FormLabel'
import AppBar from '@material-ui/core/AppBar'
import Toolbar from '@material-ui/core/Toolbar'
import Container from '@material-ui/core/Container'
import Card from '@material-ui/core/Card'
import CardContent from '@material-ui/core/CardContent'
import TextField from '@material-ui/core/TextField'
import { ThemeProvider, createTheme } from '@material-ui/core/styles'
import orange from '@material-ui/core/colors/orange'
import amber from '@material-ui/core/colors/amber'
const theme = createTheme({
  palette: {
    primary: {
      main: orange[500],
      contrastText: '#fff'
    },
    secondary: {
      main: amber[500],
      contrastText: '#fff'
    }
  }
})

function useQuery() {
  const { search } = useLocation()
  return qs.parse(search)
}

export default () => {
  const query = useQuery()
  const [value, setValue] = React.useState('alipay')
  const handleChange = (event) => setValue(event.target.value)
  return (
    <ThemeProvider theme={theme}>
      <AppBar position="static">
        <Toolbar>Checkout</Toolbar>
      </AppBar>
      <Container maxWidth="sm">
        <Card>
          <CardContent>
            <form action="./api/checkout" method="POST">
              <FormControl component="fieldset" style={{ width: '63%' }}>
                <FormLabel component="legend">Order Info</FormLabel>
                <TextField readOnly name="app_id" label="App ID" defaultValue={query.app_id} />
                <TextField
                  readOnly
                  name="out_trade_no"
                  label="Out Trade No"
                  defaultValue={query.out_trade_no}
                />
                <TextField readOnly name="subject" label="Subject" defaultValue={query.subject} />
                <TextField
                  readOnly
                  name="total_amount"
                  label="Total Amount"
                  defaultValue={query.total_amount}
                />
                <TextField
                  readOnly
                  name="return_url"
                  label="Return Url"
                  defaultValue={query.return_url}
                />
                <TextField
                  readOnly
                  name="notify_url"
                  label="Notify Url"
                  defaultValue={query.return_url}
                />
                <TextField
                  readOnly
                  name="timestamp"
                  label="Timestamp"
                  defaultValue={query.timestamp}
                />
                <TextField readOnly name="sign" label="sign" defaultValue={query.sign} />
                <TextField readOnly name="body" label="body" defaultValue={query.body} />
              </FormControl>
              <FormControl component="fieldset">
                <FormLabel component="legend">Payment Channel</FormLabel>
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
                style={{ marginTop: '10px' }}>
                Checkout
              </Button>
            </form>
          </CardContent>
        </Card>
      </Container>
    </ThemeProvider>
  )
}
