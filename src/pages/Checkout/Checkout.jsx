import React, { useState, useEffect, Fragment } from 'react'
import { useLocation } from 'react-router-dom'
import qs from 'query-string'
import ContainerMedium from '@components/ContainerMedium/ContainerMedium'
import CopyrightFooter from '@components/CopyrightFooter/CopyrightFooter'
import theme from '@providers/themeProvider'
import { ThemeProvider, styled } from '@material-ui/core/styles'
import Box from '@material-ui/core/Box'
import Paper from '@material-ui/core/Paper'
import Grid from '@material-ui/core/Grid'
import Fab from '@material-ui/core/Fab'
import Divider from '@material-ui/core/Divider'
import Radio from '@material-ui/core/Radio'
import RadioGroup from '@material-ui/core/RadioGroup'
import FormControlLabel from '@material-ui/core/FormControlLabel'
import FormControl from '@material-ui/core/FormControl'
import AppBar from '@material-ui/core/AppBar'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import TextField from '@material-ui/core/TextField'
import InputAdornment from '@material-ui/core/InputAdornment'
import IconButton from '@material-ui/core/IconButton'
import Popover from '@material-ui/core/Popover'
import Rating from '@material-ui/lab/Rating'
import AccountBalanceWalletIcon from '@material-ui/icons/AccountBalanceWallet'
import SentimentVeryDissatisfiedIcon from '@material-ui/icons/SentimentVeryDissatisfied'
import SentimentDissatisfiedIcon from '@material-ui/icons/SentimentDissatisfied'
import SentimentSatisfiedIcon from '@material-ui/icons/SentimentSatisfied'
import SentimentSatisfiedAltIcon from '@material-ui/icons/SentimentSatisfiedAltOutlined'
import SentimentVerySatisfiedIcon from '@material-ui/icons/SentimentVerySatisfied'
import CheckIcon from '@material-ui/icons/Check'
import AccountCircleIcon from '@material-ui/icons/AccountCircle'
import ContactSupportIcon from '@material-ui/icons/ContactSupport'
import PhoneIcon from '@material-ui/icons/Phone'

import i18nProvider from '@providers/i18nProvider'
import { GATEWAY_SUMBIT_API, CHECKOUT_API } from '@common/config'
import { fetchUtils } from 'react-admin'

const { translate } = i18nProvider

const useQuery = () => {
  const { search } = useLocation()
  return qs.parse(search)
}

const PadPaper = styled(Paper)({
  padding: '12px',
  '& fieldset': { width: '100%' }
})
const TextFieldHidden = styled(TextField)({
  display: 'none'
})
const CustomToolbar = styled(Toolbar)(({ theme }) => ({
  minHeight: '30vh',
  alignItems: 'flex-start',
  paddingTop: theme.spacing(1),
  paddingBottom: theme.spacing(2)
}))
const CustomToolbarTitle = styled(Typography)(({ theme }) => ({
  flexGrow: 1,
  marginTop: theme.spacing(1)
}))
const CustomContainerMedium = styled(ContainerMedium)(({ theme }) => ({
  maxWidth: 850,
  width: '45vw',
  minWidth: 600,
  marginTop: '-28vh'
}))
const CustomMenuButton = styled(IconButton)(({ theme }) => ({
  marginRight: theme.spacing(2)
}))
const CustomMiddleBar = styled(PadPaper)(({ theme }) => ({
  minHeight: 56,
  background: theme.palette.primary.main,
  padding: theme.spacing(2),
  paddingTop: theme.spacing(4),
  paddingBottom: theme.spacing(4),
  marginTop: theme.spacing(1),
  marginBottom: theme.spacing(1),
  color: 'white',
  display: 'flex',
  flexDirection: 'row',
  alignItems: 'center',
  justifyContent: 'space-between'
}))
const CustomBottomPadPaper = styled(PadPaper)(({ theme }) => ({
  position: 'relative',
  minHeight: 200,
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  justifyContent: 'center',
  '& > legend': {
    marginBottom: theme.spacing(2),
    width: '60%',
    textAlign: 'center'
  }
}))
const CustomTopPadPaper = styled(PadPaper)(({ theme }) => ({
  position: 'relative',
  minHeight: 168,
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  justifyContent: 'center',
  padding: theme.spacing(2),
  paddingTop: theme.spacing(4),
  paddingBottom: theme.spacing(4)
}))
const CustomFab = styled(Fab)(({ theme }) => ({
  position: 'absolute',
  bottom: 0,
  right: theme.spacing(4),
  transform: 'translateY(50%)'
}))
const RowBox = styled(Box)(({ theme }) => ({
  display: 'flex',
  flexDirection: 'row',
  alignItems: 'center',
  justifyContent: 'space-between',
  padding: theme.spacing(1)
}))

const CustomRatingIcons = {
  1: {
    icon: <SentimentVeryDissatisfiedIcon fontSize="large" />,
    label: 'Very Dissatisfied'
  },
  2: {
    icon: <SentimentDissatisfiedIcon fontSize="large" />,
    label: 'Dissatisfied'
  },
  3: {
    icon: <SentimentSatisfiedIcon fontSize="large" />,
    label: 'Neutral'
  },
  4: {
    icon: <SentimentSatisfiedAltIcon fontSize="large" />,
    label: 'Satisfied'
  },
  5: {
    icon: <SentimentVerySatisfiedIcon fontSize="large" />,
    label: 'Very Satisfied'
  }
}

function HelperPopper() {
  const [anchorEl, setAnchorEl] = useState(null)
  const handleClick = (event) => {
    setAnchorEl(event.currentTarget)
  }

  const handleClose = () => {
    setAnchorEl(null)
  }

  const open = Boolean(anchorEl)
  const id = open ? 'transitions-popover' : undefined

  return (
    <Fragment>
      <IconButton aria-describedby={id} onClick={handleClick} aria-label="search" color="inherit">
        <ContactSupportIcon />
      </IconButton>
      <Popover
        id={id}
        open={open}
        anchorEl={anchorEl}
        onClose={handleClose}
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'right'
        }}
        transformOrigin={{
          vertical: 'top',
          horizontal: 'right'
        }}>
        <Paper>
          <RowBox>
            <PhoneIcon />
            <Typography style={{ marginLeft: 8 }} variant="subtitle1">
              400-000-000
            </Typography>
          </RowBox>
        </Paper>
      </Popover>
    </Fragment>
  )
}

function RatingIconContainer(props) {
  const { value, ...other } = props
  return <span {...other}>{CustomRatingIcons[value].icon}</span>
}

export default () => {
  const query = useQuery()
  const [abilities, setAbilities] = useState([])
  const [channels, setChannels] = useState([])
  const [product, setProduct] = useState(null)

  const handleChange = (event) => updateAbilities(channels, event.target.value)
  const updateAbilities = (channels, id) =>
    setAbilities(channels.find((channel) => channel.id === id).ability.split(','))

  useEffect(() => {
    fetchUtils
      .fetchJson(
        qs.stringifyUrl({
          url: CHECKOUT_API + '/app-info',
          query: Object.assign({ app_id: query.app_id, product: query.product  }, JSON.parse(query.channel || null))
        })
      )
      .then(
        (response) => (
          setChannels(response?.json?.channel_list || []),
          updateAbilities(response?.json?.channel_list || [], response.json?.channel_list[0].id),
          setProduct(response?.json?.product || []),
          (document.title = translate('checkout.title') + '_' + response?.json?.app?.display_name)
        )
      )
  }, [])

  return (
    <ThemeProvider theme={theme}>
      <AppBar position="static">
        <CustomToolbar>
          <CustomMenuButton edge="start" color="inherit">
            <AccountBalanceWalletIcon />
          </CustomMenuButton>
          <CustomToolbarTitle variant="h5">{translate('checkout.title')}</CustomToolbarTitle>
          <HelperPopper />
          <IconButton aria-label="user" color="inherit">
            <AccountCircleIcon />
          </IconButton>
        </CustomToolbar>
      </AppBar>
      <CustomContainerMedium>
        <form action={GATEWAY_SUMBIT_API} method="POST">
          <Box>
            <CustomTopPadPaper>
              <FormControl component="fieldset">
                <TextFieldHidden
                  InputProps={{
                    readOnly: true
                  }}
                  label={translate('checkout.appName')}
                  defaultValue="JS Web App"
                />
                <TextFieldHidden name="app_id" label="App ID" defaultValue={query.app_id} />
                <TextFieldHidden
                  InputProps={{
                    readOnly: true
                  }}
                  name="out_trade_no"
                  label={translate('checkout.outTradeNo')}
                  defaultValue={query.out_trade_no}
                />
                <TextFieldHidden
                  InputProps={{
                    readOnly: true
                  }}
                  name="product"
                  label={translate('checkout.product')}
                  defaultValue={query.product}
                />
                <TextFieldHidden
                  InputProps={{
                    readOnly: true
                  }}
                  name="plan"
                  defaultValue={query.plan}
                />
                <TextFieldHidden
                  InputProps={{
                    readOnly: true
                  }}
                  name="customer"
                  defaultValue={query.customer}
                />
                <TextFieldHidden
                  name="return_url"
                  label="Return Url"
                  defaultValue={query.return_url}
                />
                <TextFieldHidden
                  name="notify_url"
                  label="Notify Url"
                  defaultValue={query.notify_url}
                />
                <TextFieldHidden
                  name="cancel_url"
                  label="Cancel Url"
                  defaultValue={query.cancel_url}
                />
                <TextFieldHidden
                  name="request_time"
                  label="Request Time"
                  defaultValue={query.request_time}
                />
                <TextFieldHidden
                  name="currency"
                  label="Currency"
                  defaultValue={query.currency}
                />
                <TextFieldHidden
                  name="total_amount"
                  label="Total Amount"
                  defaultValue={query.total_amount}
                />
                <TextFieldHidden name="sign" label="Sign" defaultValue={query.sign} />
                {query.body ? (
                  <TextFieldHidden
                    InputProps={{
                      readOnly: true
                    }}
                    name="body"
                    label={translate('checkout.description')}
                    multiline
                    defaultValue={query.body}
                  />
                ) : null}
                <RowBox component="div" m={1}>
                  <Typography variant="h4">{translate('checkout.totalTitle')}</Typography>
                </RowBox>
                <RowBox component="div" m={1}>
                  <div>
                    <Typography variant="h4">{product?.name}</Typography>
                    {product?.description && <Typography variant="subtitle1">{product.description}</Typography>}
                  </div>
                </RowBox>
                {product?.list?.map(({name, description})=>(<RowBox component="div" m={1}>
                  <div>
                    <Typography variant="h6">{name}</Typography>
                    {description && <Typography variant="subtitle1">{description}</Typography>}
                  </div>
                </RowBox>))}
                <Divider />
              </FormControl>
            </CustomTopPadPaper>
            <CustomMiddleBar>
              <Typography variant="h4">{translate('checkout.totalAmount')}</Typography>
              <Typography variant="h4">
              <RowBox component="div" m={1}>
                  <div>
                     {query.plan ? 
                     JSON.parse(query.plan).billing_cycles.map(({frequency, pricing_scheme: {fixed_price}}) => fixed_price.value + ' ' + translate('currency.' + fixed_price.currency_code) + ' / ' + frequency.interval_count + ' ' + translate('time.' + frequency.interval_unit)) : 
                     query.total_amount + ' ' + translate('currency.' + query.currency)}
                  </div>
                </RowBox>
                </Typography>
            </CustomMiddleBar>
            <Grid container spacing={2}>
              <Grid item xs={6}>
                <CustomBottomPadPaper>
                  <Typography component="legend">{translate('checkout.mindRate')}</Typography>
                  <Rating
                    name="rating"
                    defaultValue={0}
                    getLabelText={(value) => CustomRatingIcons[value].label}
                    IconContainerComponent={RatingIconContainer}
                  />
                </CustomBottomPadPaper>
              </Grid>
              <Grid item xs={6}>
                <CustomBottomPadPaper>
                  <FormControl component="fieldset">
                    {channels.length ? (
                      <>
                        <Typography style={{ marginBottom: theme.spacing(1) }} component="legend">
                          {translate('checkout.channel')}
                        </Typography>
                        <RadioGroup
                          aria-label="position"
                          name="channel_id"
                          onChange={handleChange}
                          defaultValue={channels[0]?.id}>
                          {channels.map(({ id, display_name }) => (
                            <FormControlLabel
                              value={id}
                              control={<Radio color="primary" />}
                              label={display_name}
                            />
                          ))}
                        </RadioGroup>
                      </>
                    ) : null}
                    {abilities.length ? (
                      <>
                        <Typography style={{ marginBottom: theme.spacing(1) }} component="legend">
                          {translate('checkout.ability.question')}
                        </Typography>
                        <RadioGroup
                          aria-label="position"
                          name="ability"
                          defaultValue={abilities[0]}>
                          {abilities.map((ability) => (
                            <FormControlLabel
                              value={ability}
                              control={<Radio color="primary" />}
                              label={translate('checkout.ability.' + ability)}
                            />
                          ))}
                        </RadioGroup>
                      </>
                    ) : null}
                  </FormControl>
                  <CustomFab type="submit" color="primary" aria-label="submit">
                    <CheckIcon />
                  </CustomFab>
                </CustomBottomPadPaper>
              </Grid>
            </Grid>
          </Box>
        </form>
      </CustomContainerMedium>
      <CopyrightFooter />
    </ThemeProvider>
  )
}
