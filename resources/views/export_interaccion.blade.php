<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=interaccion.xls");
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
<td style="background-color:#0000FF;color:#FFFFFF"><b>Tramite</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Intencion</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Fin Interaccion</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Telefono</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Observaciones</td>
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
<td>{{$registro->tramite}}</td>
<td>{{$registro->intencion}}</td>
<td>{{$registro->fin_interaccion}}</td>
<td>{{$registro->telefono}}</td>
<td>{{$registro->observaciones}}</td>
<td>{{$registro->created_at}}</td>

</tr>
<?php
}
?>
</table>