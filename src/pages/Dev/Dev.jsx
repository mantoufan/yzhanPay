import React, { useEffect } from 'react'
import ContainerMedium from '@components/ContainerMedium/ContainerMedium'
import CopyrightFooter from '@components/CopyrightFooter/CopyrightFooter'
import theme from '@providers/themeProvider'
import { ThemeProvider } from '@material-ui/core/styles'
import Typography from '@material-ui/core/Typography'
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
const VideoBg = styled(Box)({
  position: 'fixed',
  zIndex: -1,
  width: '100vw',
  height: '100vh',
  '& video': {
    width: '100%',
    height: '100%',
    objectFit: 'cover'
  }
})
const BasicCard = (props) => (
  <MyCard>
    <CardContent>{props.children}</CardContent>
  </MyCard>
)
const ContainerCenter = styled(ContainerMedium)({
  position: 'absolute',
  top: 0,
  right: 0,
  bottom: 0,
  left: 0,
  height: 'fit-content',
  opacity: '0.8',
  marginTop: 'auto',
  marginBottom: 'auto'
})

export default () => {
  useEffect(() => {
    global.mtfKey = {}
    const tag = document.createElement('script')
    tag.async = true
    tag.src = 'https://www.mantoufan.com/api/mtfkey/a=js&fid=fteam'
    document.getElementsByTagName('body')[0].appendChild(tag)
    tag.onload = () => {
      const videoBg = document.getElementById('js_video_bg')
      const video = document.createElement('video')
      video.src = 'https://cdn.mantoufan.com/yun/20210214.mp4?key=' + mtfKey.key
      video.muted = true
      video.autoplay = true
      video.loop = 'loop'
      videoBg.appendChild(video)
    }
  }, [])
  return (
    <ThemeProvider theme={theme}>
      <VideoBg id="js_video_bg" />
      <ContainerCenter>
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
                href="https://www.notion.so/China-Pay-Project-f94b7a4d319544b8be911d320deda18c#f765dc83b0cf4e3184d957005d021dcf"
                target="_blank">
                API Doc
              </Link>
              <Link
                href="https://www.notion.so/China-Pay-Project-f94b7a4d319544b8be911d320deda18c#55131a10e66a4e31989dbcdf61315f67"
                target="_blank">
                System Design
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
              <Link
                href="https://pay.os120.com/checkout?app_id=1&out_trade_no=2015032001016669&subject=title&total_amount=99.00&return_url=http://www.baidu.com&notify_url=http://www.google.com&timestamp=2021-12-26 22:03:60&sign=dddd&body=description"
                target="_blank">
                Pay Checkout
              </Link>
              <Link href="https://pay.os120.com/admin" target="_blank">
                Pay Admin
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
      </ContainerCenter>
      <CopyrightFooter />
    </ThemeProvider>
  )
}
