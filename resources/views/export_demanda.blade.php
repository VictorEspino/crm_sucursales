<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=g_demanda.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border=1>
<tr>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Empleado</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Nombre</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>UDN</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>PDV</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Region</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Dia Trabajo</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>SMS</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>SMS Individual</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Llamadas</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Redes Sociales</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Created_at</td>


</tr>
<?php

foreach ($resultados as $registro) {
	?>
<tr>
<td>{{$registro->empleado}}</td>
<td>{{$registro->nombre}}</td>
<td>{{$registro->udn}}</td>
<td>{{$registro->pdv}}</td>
<td>{{$registro->region}}</td>
<td>{{$registro->dia_trabajo}}</td>
<td>{{$registro->sms}}</td>
<td>{{$registro->sms_individual}}</td>
<td>{{$registro->llamadas}}</td>
<td>{{$registro->rs}}</td>
<td>{{$registro->created_at}}</td>
</tr>
<?php
}
?>
</table>