"use strict";(self.webpackChunkyzhanpay=self.webpackChunkyzhanpay||[]).push([[408],{46:(r,e,o)=>{o.d(e,{Z:()=>G});var t=o(3433),p=o(7462),n=(o(5697),o(9668));function i(r,e){var o={};return Object.keys(r).forEach((function(t){-1===e.indexOf(t)&&(o[t]=r[t])})),o}function a(r){var e=function(e){var o=r(e);return e.css?(0,p.Z)({},(0,n.Z)(o,r((0,p.Z)({theme:e.theme},e.css))),i(e.css,[r.filterProps])):e.sx?(0,p.Z)({},(0,n.Z)(o,r((0,p.Z)({theme:e.theme},e.sx))),i(e.sx,[r.filterProps])):o};return e.propTypes={},e.filterProps=["css","sx"].concat((0,t.Z)(r.filterProps)),e}const s=a;const c=function(){for(var r=arguments.length,e=new Array(r),o=0;o<r;o++)e[o]=arguments[o];var t=function(r){return e.reduce((function(e,o){var t=o(r);return t?(0,n.Z)(e,t):e}),{})};return t.propTypes={},t.filterProps=e.reduce((function(r,e){return r.concat(e.filterProps)}),[]),t};var u=o(4942),f=o(1410);function d(r,e){return e&&"string"==typeof e?e.split(".").reduce((function(r,e){return r&&r[e]?r[e]:null}),r):null}const l=function(r){var e=r.prop,o=r.cssProperty,t=void 0===o?r.prop:o,p=r.themeKey,n=r.transform,i=function(r){if(null==r[e])return null;var o=r[e],i=d(r.theme,p)||{};return(0,f.k)(r,o,(function(r){var e;return"function"==typeof i?e=i(r):Array.isArray(i)?e=i[r]||r:(e=d(i,r)||r,n&&(e=n(e))),!1===t?e:(0,u.Z)({},t,e)}))};return i.propTypes={},i.filterProps=[e],i};function m(r){return"number"!=typeof r?r:"".concat(r,"px solid")}const h=c(l({prop:"border",themeKey:"borders",transform:m}),l({prop:"borderTop",themeKey:"borders",transform:m}),l({prop:"borderRight",themeKey:"borders",transform:m}),l({prop:"borderBottom",themeKey:"borders",transform:m}),l({prop:"borderLeft",themeKey:"borders",transform:m}),l({prop:"borderColor",themeKey:"palette"}),l({prop:"borderRadius",themeKey:"shape"}));const y=c(l({prop:"displayPrint",cssProperty:!1,transform:function(r){return{"@media print":{display:r}}}}),l({prop:"display"}),l({prop:"overflow"}),l({prop:"textOverflow"}),l({prop:"visibility"}),l({prop:"whiteSpace"}));const g=c(l({prop:"flexBasis"}),l({prop:"flexDirection"}),l({prop:"flexWrap"}),l({prop:"justifyContent"}),l({prop:"alignItems"}),l({prop:"alignContent"}),l({prop:"order"}),l({prop:"flex"}),l({prop:"flexGrow"}),l({prop:"flexShrink"}),l({prop:"alignSelf"}),l({prop:"justifyItems"}),l({prop:"justifySelf"}));const x=c(l({prop:"gridGap"}),l({prop:"gridColumnGap"}),l({prop:"gridRowGap"}),l({prop:"gridColumn"}),l({prop:"gridRow"}),l({prop:"gridAutoFlow"}),l({prop:"gridAutoColumns"}),l({prop:"gridAutoRows"}),l({prop:"gridTemplateColumns"}),l({prop:"gridTemplateRows"}),l({prop:"gridTemplateAreas"}),l({prop:"gridArea"}));const b=c(l({prop:"position"}),l({prop:"zIndex",themeKey:"zIndex"}),l({prop:"top"}),l({prop:"right"}),l({prop:"bottom"}),l({prop:"left"}));const v=c(l({prop:"color",themeKey:"palette"}),l({prop:"bgcolor",cssProperty:"backgroundColor",themeKey:"palette"}));const Z=l({prop:"boxShadow",themeKey:"shadows"});function k(r){return r<=1?"".concat(100*r,"%"):r}var w=l({prop:"width",transform:k}),W=l({prop:"maxWidth",transform:k}),P=l({prop:"minWidth",transform:k}),K=l({prop:"height",transform:k}),C=l({prop:"maxHeight",transform:k}),R=l({prop:"minHeight",transform:k});l({prop:"size",cssProperty:"width",transform:k}),l({prop:"size",cssProperty:"height",transform:k});const S=c(w,W,P,K,C,R,l({prop:"boxSizing"}));const N=c(l({prop:"fontFamily",themeKey:"typography"}),l({prop:"fontSize",themeKey:"typography"}),l({prop:"fontStyle",themeKey:"typography"}),l({prop:"fontWeight",themeKey:"typography"}),l({prop:"letterSpacing"}),l({prop:"lineHeight"}),l({prop:"textAlign"}));var T=o(8681),z=o(1911),A=s(c(h,y,g,x,b,v,Z,S,T.Z,N));const G=(0,z.Z)("div")(A,{name:"MuiBox"})},3832:(r,e,o)=>{o.d(e,{Z:()=>f});var t=o(7462),p=o(5987),n=o(4942),i=o(7294),a=(o(5697),o(6010)),s=o(4670),c=o(3871),u=i.forwardRef((function(r,e){var o=r.classes,n=r.className,s=r.component,u=void 0===s?"div":s,f=r.disableGutters,d=void 0!==f&&f,l=r.fixed,m=void 0!==l&&l,h=r.maxWidth,y=void 0===h?"lg":h,g=(0,p.Z)(r,["classes","className","component","disableGutters","fixed","maxWidth"]);return i.createElement(u,(0,t.Z)({className:(0,a.Z)(o.root,n,m&&o.fixed,d&&o.disableGutters,!1!==y&&o["maxWidth".concat((0,c.Z)(String(y)))]),ref:e},g))}));const f=(0,s.Z)((function(r){return{root:(0,n.Z)({width:"100%",marginLeft:"auto",boxSizing:"border-box",marginRight:"auto",paddingLeft:r.spacing(2),paddingRight:r.spacing(2),display:"block"},r.breakpoints.up("sm"),{paddingLeft:r.spacing(3),paddingRight:r.spacing(3)}),disableGutters:{paddingLeft:0,paddingRight:0},fixed:Object.keys(r.breakpoints.values).reduce((function(e,o){var t=r.breakpoints.values[o];return 0!==t&&(e[r.breakpoints.up(o)]={maxWidth:t}),e}),{}),maxWidthXs:(0,n.Z)({},r.breakpoints.up("xs"),{maxWidth:Math.max(r.breakpoints.values.xs,444)}),maxWidthSm:(0,n.Z)({},r.breakpoints.up("sm"),{maxWidth:r.breakpoints.values.sm}),maxWidthMd:(0,n.Z)({},r.breakpoints.up("md"),{maxWidth:r.breakpoints.values.md}),maxWidthLg:(0,n.Z)({},r.breakpoints.up("lg"),{maxWidth:r.breakpoints.values.lg}),maxWidthXl:(0,n.Z)({},r.breakpoints.up("xl"),{maxWidth:r.breakpoints.values.xl})}}),{name:"MuiContainer"})(u)},1911:(r,e,o)=>{o.d(e,{Z:()=>d});var t=o(7462),p=o(5987),n=o(7294),i=o(6010),a=(o(5697),o(8679)),s=o.n(a),c=o(115);function u(r,e){var o={};return Object.keys(r).forEach((function(t){-1===e.indexOf(t)&&(o[t]=r[t])})),o}var f=o(9700);const d=function(r){var e=function(r){return function(e){var o,a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},f=a.name,d=(0,p.Z)(a,["name"]),l=f,m="function"==typeof e?function(r){return{root:function(o){return e((0,t.Z)({theme:r},o))}}}:{root:e},h=(0,c.Z)(m,(0,t.Z)({Component:r,name:f||r.displayName,classNamePrefix:l},d));e.filterProps&&(o=e.filterProps,delete e.filterProps),e.propTypes&&(e.propTypes,delete e.propTypes);var y=n.forwardRef((function(e,a){var s=e.children,c=e.className,f=e.clone,d=e.component,l=(0,p.Z)(e,["children","className","clone","component"]),m=h(e),y=(0,i.Z)(m.root,c),g=l;if(o&&(g=u(g,o)),f)return n.cloneElement(s,(0,t.Z)({className:(0,i.Z)(s.props.className,y)},g));if("function"==typeof s)return s((0,t.Z)({className:y},g));var x=d||r;return n.createElement(x,(0,t.Z)({ref:a,className:y},g),s)}));return s()(y,r),y}}(r);return function(r,o){return e(r,(0,t.Z)({defaultTheme:f.Z},o))}}}}]);
//# sourceMappingURL=4b04c4b012f731c1dd1b.js.map