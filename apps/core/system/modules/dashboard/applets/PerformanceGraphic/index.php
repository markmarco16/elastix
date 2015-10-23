<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Elastix version 0.5                                                  |
  | http://www.elastix.com                                               |
  +----------------------------------------------------------------------+
  | Copyright (c) 2006 Palosanto Solutions S. A.                         |
  +----------------------------------------------------------------------+
  | Cdla. Nueva Kennedy Calle E 222 y 9na. Este                          |
  | Telfs. 2283-268, 2294-440, 2284-356                                  |
  | Guayaquil - Ecuador                                                  |
  | http://www.palosanto.com                                             |
  +----------------------------------------------------------------------+
  | The contents of this file are subject to the General Public License  |
  | (GPL) Version 2 (the "License"); you may not use this file except in |
  | compliance with the License. You may obtain a copy of the License at |
  | http://www.opensource.org/licenses/gpl-license.php                   |
  |                                                                      |
  | Software distributed under the License is distributed on an "AS IS"  |
  | basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See  |
  | the License for the specific language governing rights and           |
  | limitations under the License.                                       |
  +----------------------------------------------------------------------+
  | The Original Code is: Elastix Open Source.                           |
  | The Initial Developer of the Original Code is PaloSanto Solutions    |
  +----------------------------------------------------------------------+
  $Id: index.php,v 1.1 2007/01/09 23:49:36 alex Exp $
*/
global $arrConf;
require_once "{$arrConf['basePath']}/libs/paloSantoSampler.class.php";
require_once "libs/paloSantoGraphImage.lib.php";

class Applet_PerformanceGraphic
{
    function handleJSON_getContent($smarty, $module_name, $appletlist)
    {
        /* Se cierra la sesión para quitar el candado sobre la sesión y permitir
         * que otras operaciones ajax puedan funcionar. */
        session_commit();

        $respuesta = array(
            'status'    =>  'success',
            'message'   =>  '(no message)',
        );
        //CallsMemoryCPU
        $respuesta['html'] = "<div style='width:450px;height:240px;' id='dashboard-applet-performancegraph'></div>
<script>
$.plot('#dashboard-applet-performancegraph', ".
$this->_sampler_CallsMemoryCPU().
", {
    xaxes: [ { mode: 'time' } ],
    yaxes: [ { min: 0 }, {
    // align if we are to the right
    position: 'right'} ],
    legend: {
       position: 'ne',
       labelBoxBorderColor: '#ffffff'
     },
    margin: { top: 20,  left: 20, bottom: 20, right: 20 },
    xaxis: {tickLength:0},
    series: {
       lines: {
          lineWidth: 1,
          show: true ,
          fill: true,
          shadowSize: 0
       }
    },
    grid: {
       hoverable: true,
       clickable: true,
       tickColor: '#f6f6f6',
       borderWidth: 0,
       labelMargin: 22
    },
});
</script>";

        $json = new Services_JSON();
        Header('Content-Type: application/json');
        return $json->encode($respuesta);
    }

    private function _sampler_CallsMemoryCPU()
    {
        $arrayResult = array();
        $oSampler = new paloSampler();

        //retorna
        //Array ( [0] => Array ( [id] => 1 [name] => Sim. calls [color] => #00cc00 [line_type] => 1 )
        $arrLines = $oSampler->getGraphLinesById(1);

        //retorna
        //Array ( [name] => Simultaneous calls, memory and CPU )
        $arrGraph = $oSampler->getGraphById(1);

        $endtime = time();
        $starttime = $endtime - 8*60*60;
        $oSampler->deleteDataBeforeThisTimestamp($starttime);

        //$oSampler->getSamplesByLineId(1)
        //retorna
        //Array ( [0] => Array ( [timestamp] => 1230562202 [value] => 2 ), .......

        $i = 1;
        $arrValues = array();
        $json = new Services_JSON();
        foreach ($arrLines as $line) {
            $arraySample = $oSampler->getSamplesByLineId($line['id']);
            $data = array();
            $obj = array(
                'data'  =>  array(),
                'shadowSize'    =>  0,
                'label'         =>  _tr($line['name']),
                'yaxis'         =>  $i,
                'color'         =>  $line['color'],
            );
            foreach ($arraySample as $time_value) {
                $obj['data'][] = array((int)$time_value['timestamp'], (int)$time_value['value']);
            }
            switch ($i) {
            case 1:
                //$obj['color'] = '#33cc33';
                //$obj['label'] => 'Calls';
                break;
            case 2:
                //$obj['color'] = '#3da8fb';
                //$obj['label'] => 'CPU';
                break;
            case 3:
                //$obj['color'] = '#cb4b4b';
                //$obj['label'] => 'RAM';
                $obj['fill'] = TRUE;
                $obj['fillColor'] = '#eeeeee';
                break;
            }
            $arrValues[] = $obj;
            $i++;
        }
        return $json->encode($arrValues);
    }
}

?>