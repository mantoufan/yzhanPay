import { createTheme } from '@material-ui/core/styles'
import orange from '@material-ui/core/colors/orange'
import amber from '@material-ui/core/colors/amber'
export default createTheme({
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