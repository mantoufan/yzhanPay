<?php
class TplService
{
    public function parse($tpl, $params)
    {
        $tplParams = array();
        foreach ($params as $key => $v) {
            $tplParams['{'+$key+'}'] = $v;
        }
        $tplParams['{date.now}'] = date('Y-m-d H:i:s', time());
        return strtr($tpl, $tplParams);
    }
}
