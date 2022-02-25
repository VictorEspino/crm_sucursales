<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rentabilidad_sucursales.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border=1>
<tr>
    <td style="background-color:#0000FF;color:#FFFFFF"><b>Sucursales</td>
    <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Fijos</td>
    <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Indirectos</td>
    <td style="background-color:#0000FF;color:#FFFFFF"><b>Costo de Venta</td>
    <td style="background-color:#FF0000;color:#FFFFFF"><b>Total Gastos</td>
    <td style="background-color:#00FF00;color:#FFFFFF"><b>Ingresos</td>
    <td style="background-color:#0000FF;color:#FFFFFF"><b>Rentabilidad</td>
</tr>
<tr>
    <td style="background-color:#FFFFFF;color:#000000">SUCURSALES</td>
    <td style="background-color:#FFFFFF;color:#000000">${{number_format($gastos_fijos)}}</td>
    <td style="background-color:#FFFFFF;color:#000000">${{number_format($gastos_indirectos)}}</td>
    <td style="background-color:#FFFFFF;color:#000000">${{number_format($costos_venta)}}</td>
    <td style="background-color:#FFFFFF;color:#000000">${{number_format($gastos)}}</td>
    <td style="background-color:#FFFFFF;color:#000000">${{number_format($ingresos)}}</td>
    <td style="background-color:#FFFFFF;color:#000000">{{number_format($porc_rentabilidad,2)}}%</td>
    </tr>
</table>
<br>
REGIONES
<br>
<br>
<table border=1>
    <tr>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Region</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Fijos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Indirectos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Costo de Venta</td>
        <td style="background-color:#FF0000;color:#FFFFFF"><b>Total Gastos</td>
        <td style="background-color:#00FF00;color:#FFFFFF"><b>Ingresos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Rentabilidad</td>
    </tr>
<?php

foreach ($detalles_regiones as $registro) {
	?>
<tr>
<td>{{$registro->llave}}</td>
<td>${{number_format($registro->gastos_fijos,0)}}</td>
<td>${{number_format($registro->gastos_indirectos,0)}}</td>
<td>${{number_format($registro->c_v,0)}}</td>
<td>${{number_format($registro->gastos_fijos+$registro->gastos_indirectos+$registro->c_v,0)}}</td>
<td>${{number_format($registro->ingresos,0)}}</td>
<td>{{number_format($registro->rentabilidad,2)}}%</td>
</tr>
<?php
}
?>
</table>
<br>
CENTRO
<br>
<br>
<table border=1>
    <tr>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Surcursal</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Fijos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Indirectos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Costo de Venta</td>
        <td style="background-color:#FF0000;color:#FFFFFF"><b>Total Gastos</td>
        <td style="background-color:#00FF00;color:#FFFFFF"><b>Ingresos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Rentabilidad</td>
    </tr>
<?php

foreach ($detalles_centro as $registro) {
	?>
<tr>
<td>{{$sucursales[$registro->llave]}}</td>
<td>${{number_format($registro->gastos_fijos,0)}}</td>
<td>${{number_format($registro->gastos_indirectos,0)}}</td>
<td>${{number_format($registro->c_v,0)}}</td>
<td>${{number_format($registro->gastos_fijos+$registro->gastos_indirectos+$registro->c_v,0)}}</td>
<td>${{number_format($registro->ingresos,0)}}</td>
<td>{{number_format($registro->rentabilidad,2)}}%</td>
</tr>
<?php
}
?>
</table>
<br>
NORTE
<br>
<br>
<table border=1>
    <tr>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Surcursal</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Fijos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Indirectos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Costo de Venta</td>
        <td style="background-color:#FF0000;color:#FFFFFF"><b>Total Gastos</td>
        <td style="background-color:#00FF00;color:#FFFFFF"><b>Ingresos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Rentabilidad</td>
    </tr>
<?php

foreach ($detalles_norte as $registro) {
	?>
<tr>
<td>{{$sucursales[$registro->llave]}}</td>
<td>${{number_format($registro->gastos_fijos,0)}}</td>
<td>${{number_format($registro->gastos_indirectos,0)}}</td>
<td>${{number_format($registro->c_v,0)}}</td>
<td>${{number_format($registro->gastos_fijos+$registro->gastos_indirectos+$registro->c_v,0)}}</td>
<td>${{number_format($registro->ingresos,0)}}</td>
<td>{{number_format($registro->rentabilidad,2)}}%</td>
</tr>
<?php
}
?>
</table>
<br>
SUR
<br>
<br>
<table border=1>
    <tr>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Surcursal</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Fijos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Gastos Indirectos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Costo de Venta</td>
        <td style="background-color:#FF0000;color:#FFFFFF"><b>Total Gastos</td>
        <td style="background-color:#00FF00;color:#FFFFFF"><b>Ingresos</td>
        <td style="background-color:#0000FF;color:#FFFFFF"><b>Rentabilidad</td>
    </tr>
<?php

foreach ($detalles_sur as $registro) {
	?>
<tr>
<td>{{$sucursales[$registro->llave]}}</td>
<td>${{number_format($registro->gastos_fijos,0)}}</td>
<td>${{number_format($registro->gastos_indirectos,0)}}</td>
<td>${{number_format($registro->c_v,0)}}</td>
<td>${{number_format($registro->gastos_fijos+$registro->gastos_indirectos+$registro->c_v,0)}}</td>
<td>${{number_format($registro->ingresos,0)}}</td>
<td>{{number_format($registro->rentabilidad,2)}}%</td>
</tr>
<?php
}
?>
</table>