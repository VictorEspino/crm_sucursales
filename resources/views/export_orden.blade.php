<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ordenes.xls");
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
<td style="background-color:#0000FF;color:#FFFFFF"><b>Edad</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>F_Nacimiento</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Genero</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Origen</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Cliente</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Orden</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Telefono</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Correo</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Producto</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Flujo</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Plan</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Renta</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Equipo</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Porcentaje Requerido</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Monto Total</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Estatus Final</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Generada En</td>
<td style="background-color:#0000FF;color:#FFFFFF"><b>Riesgo</td>
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
<td>{{$registro->edad}}</td>
<td>{{$registro->f_nacimiento}}</td>
<td>{{$registro->genero}}</td>
<td>{{$registro->origen}}</td>
<td>{{$registro->cliente}}</td>
<td>{{$registro->numero_orden}}</td>
<td>{{$registro->telefono}}</td>
<td>{{$registro->correo}}</td>
<td>{{$registro->producto}}</td>
<td>{{$registro->flujo}}</td>
<td>{{$registro->plan}}</td>
<td>{{$registro->renta}}</td>
<td>{{$registro->equipo}}</td>
<td>{{$registro->porcentaje_requerido}}</td>
<td>{{$registro->monto_total}}</td>
<td>{{$registro->estatus_final}}</td>
<td>{{$registro->generada_en}}</td>
<td>{{$registro->riesgo}}</td>
<td>{{$registro->observaciones}}</td>
<td>{{$registro->created_at}}</td>
</tr>
<?php
}
?>
</table>