import { createTheme } from '@material-ui/core/styles'
import blue from '@material-ui/core/colors/blue'
import lightBlue from '@material-ui/core/colors/lightBlue'
export default createTheme({
  palette: {
    primary: {
      main: lightBlue[700],
      contrastText: '#fff'
    },
    secondary: {
      main: blue[700],
      contrastText: '#fff'
    }
  }
})