import React from 'react'
import Container from '@material-ui/core/Container'
import AppBar from '@material-ui/core/AppBar'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import theme from '@providers/themeProvider'
import { ThemeProvider } from '@material-ui/core/styles'
import Accordion from '@material-ui/core/Accordion'
import AccordionSummary from '@material-ui/core/AccordionSummary'
import AccordionDetails from '@material-ui/core/AccordionDetails'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import Link from '@material-ui/core/Link'
import Box from '@material-ui/core/Box'
import { styled } from '@material-ui/core/styles'
import Card from '@material-ui/core/Card'
import CardContent from '@material-ui/core/CardContent'

const MyCard = styled(Card)({
  width: '49%',
  marginRight: '5px',
  color: '#666',
  '& p': {
    marginTop: '5px',
    marginBottom: '5px',
    fontSize: '12px'
  }
})
const BasicCard = (props) => (
  <MyCard>
    <CardContent>{props.children}</CardContent>
  </MyCard>
)

const MyContainer = styled(Container)({
  marginTop: '10px',
  position: 'relative',
  zIndex: 1
})

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
  <ThemeProvider theme={theme}>
    <AppBar position="static">
      <Toolbar>
        <Typography variant="h6" component="div">
          Feature Team
        </Typography>
      </Toolbar>
    </AppBar>
    <MyContainer maxWidth="sm">
      <Accordion>
        <AccordionSummary
          expandIcon={<ExpandMoreIcon />}
          aria-controls="panel1a-content"
          id="panel1a-header">
          <Typography>Milestone</Typography>
        </AccordionSummary>
        <AccordionDetails>
          <Box
            sx={{
              '&': {
                fontSize: '12px',
                color: '#666'
              }
            }}>
            2021.12.10 Complete the process of registration, payment and membership opening
          </Box>
        </AccordionDetails>
      </Accordion>
      <Accordion>
        <AccordionSummary
          expandIcon={<ExpandMoreIcon />}
          aria-controls="panel1a-content"
          id="panel1a-header">
          <Typography>Doc</Typography>
        </AccordionSummary>
        <AccordionDetails>
          <Box
            sx={{
              '& > a': {
                marginRight: '16px'
              }
            }}>
            <Link
              href="https://www.notion.so/China-Pay-Project-f94b7a4d319544b8be911d320deda18c"
              target="_blank">
              Tech Doc
            </Link>
            <Link
              href="https://app.zenhub.com/workspaces/jungle-scout-apps-5bbe59ca4b5806bc2bec6884/issues/junglescout/extension-pro/2617"
              target="_blank">
              Zenhub Epic
            </Link>
            <Link
              href="https://app.diagrams.net/#G1UrMl4mFO5tgbtHbIWWWlMXwFpO2Tb_hd"
              target="_blank">
              Diagrams
            </Link>
          </Box>
        </AccordionDetails>
      </Accordion>
      <Accordion>
        <AccordionSummary
          expandIcon={<ExpandMoreIcon />}
          aria-controls="panel2a-content"
          id="panel2a-header">
          <Typography>Demo</Typography>
        </AccordionSummary>
        <AccordionDetails>
          <Box
            sx={{
              '& > a': {
                marginRight: '16px'
              }
            }}>
            <Link href="https://demo-registration.junglescout.cn" target="_blank">
              Register
            </Link>
            <Link href="https://pay.os120.com" target="_blank">
              Pay Gateway
            </Link>
          </Box>
        </AccordionDetails>
      </Accordion>
      <Accordion>
        <AccordionSummary
          expandIcon={<ExpandMoreIcon />}
          aria-controls="panel2a-content"
          id="panel2a-header">
          <Typography>Frp</Typography>
        </AccordionSummary>
        <AccordionDetails>
          <BasicCard>
            William
            <p>
              https://w.f.os120.com
              <br />
              ip:42.193.185.220 port:7000
            </p>
          </BasicCard>
          <BasicCard>
            Linker
            <p>
              https://l.f.os120.com
              <br />
              ip:42.193.185.220 port:7000
            </p>
          </BasicCard>
        </AccordionDetails>
      </Accordion>
      <Accordion>
        <AccordionSummary
          expandIcon={<ExpandMoreIcon />}
          aria-controls="panel2a-content"
          id="panel2a-header">
          <Typography>Mail</Typography>
        </AccordionSummary>
        <AccordionDetails>
          <BasicCard>
            post@os120.com
            <p>
              psd: VqHrqCKMtvGykE7E
              <br />
              smtp: smtp.feishu.cn
              <br />
              port(SSL only): 465
            </p>
          </BasicCard>
        </AccordionDetails>
      </Accordion>
      <Accordion>
        <AccordionSummary
          expandIcon={<ExpandMoreIcon />}
          aria-controls="panel2a-content"
          id="panel2a-header">
          <Typography>Support</Typography>
        </AccordionSummary>
        <AccordionDetails>
          <Box
            sx={{
              '&': {
                fontSize: '12px',
                color: '#666'
              }
            }}>
            @Wei Gan @Andy @Julian @Shengzhen @Jianyu
          </Box>
        </AccordionDetails>
      </Accordion>
    </MyContainer>
    <Bottom>Copyright © 2018-2021 Jungle Scout 桨歌(深圳)科技有限公司</Bottom>
  </ThemeProvider>
)
