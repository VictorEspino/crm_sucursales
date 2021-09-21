<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Ordenes') }}
    </x-slot>
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Indicadores de Ordenes - {{$periodo}}</div>
            <div class="w-full text-2xl text-green-600 font-bold flex justify-end"><a href="/export_orden/{{$periodo}}"><i class="far fa-file-excel"></i></a></div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
            
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col"> <!--DIV GRAFICO-->
                    <div class="w-full px-10">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="w-full px-10 pt-20">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div> <!--FIN DIV GRAFICO-->
                <div class="w-1/2 flex flex-col p-3"> <!--DIV MEDICIONES-->
                    <div class="flex justify-center text-xl font-semibold pb-3">Estatus General</div>
                    <div class="w-full flex justify-center text-xs">
                        <table>
                            <?php
                            $total_tabla=0;
                            foreach($general as $registro)
                            {
                                $total_tabla=$total_tabla+$registro->ordenes;
                            }
                            foreach($general as $registro)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$registro->estatus_final}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($registro->ordenes,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$registro->ordenes/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-full text-xl flex justify-center font-bold pt-6">
                        <span class=""><!--PIE DE GRAFICO--></span>
                    </div>
                    <div class="flex justify-center text-xl font-semibold pb-3">Activacion CON equipo</div>
                    <div class="w-full flex justify-center text-xs">
                        <table>
                            <?php
                            $total_tabla=0;
                            $ac_f=0;
                            $ac_p=0;
                            $as_f=0;
                            $as_p=0;
                            $rc_f=0;
                            $rc_p=0;
                            $rs_f=0;
                            $rs_p=0;
                            foreach($ac as $registro)
                            {
                                $total_tabla=$total_tabla+$registro->ordenes;
                                if($registro->estatus_final=="ACEPTADA - Facturada")
                                {
                                    $ac_f=$registro->ordenes;
                                }
                                if($registro->estatus_final=="ACEPTADA - Pendiente por facturar")
                                {
                                    $ac_p=$registro->ordenes;
                                }
                            }
                            foreach($ac as $registro)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$registro->estatus_final}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($registro->ordenes,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$registro->ordenes/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="flex justify-center text-xl font-semibold pb-3 pt-4">Activacion SIN equipo</div>
                    <div class="w-full flex justify-center text-xs">
                        <table>
                            <?php
                            $total_tabla=0;
                            foreach($as as $registro)
                            {
                                $total_tabla=$total_tabla+$registro->ordenes;
                                if($registro->estatus_final=="ACEPTADA - Facturada")
                                {
                                    $as_f=$registro->ordenes;
                                }
                                if($registro->estatus_final=="ACEPTADA - Pendiente por facturar")
                                {
                                    $as_p=$registro->ordenes;
                                }
                            }
                            foreach($as as $registro)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$registro->estatus_final}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($registro->ordenes,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$registro->ordenes/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="flex justify-center text-xl font-semibold pb-3 pt-4">Renovacion CON equipo</div>
                    <div class="w-full flex justify-center text-xs">
                        <table>
                            <?php
                            $total_tabla=0;
                            foreach($rc as $registro)
                            {
                                $total_tabla=$total_tabla+$registro->ordenes;
                                if($registro->estatus_final=="ACEPTADA - Facturada")
                                {
                                    $rc_f=$registro->ordenes;
                                }
                                if($registro->estatus_final=="ACEPTADA - Pendiente por facturar")
                                {
                                    $rc_p=$registro->ordenes;
                                }
                            }
                            foreach($rc as $registro)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$registro->estatus_final}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($registro->ordenes,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$registro->ordenes/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="flex justify-center text-xl font-semibold pb-3 pt-4">Renovacion SIN equipo</div>
                    <div class="w-full flex justify-center text-xs">
                        <table>
                            <?php
                            $total_tabla=0;
                            foreach($rs as $registro)
                            {
                                $total_tabla=$total_tabla+$registro->ordenes;
                                if($registro->estatus_final=="ACEPTADA - Facturada")
                                {
                                    $rs_f=$registro->ordenes;
                                }
                                if($registro->estatus_final=="ACEPTADA - Pendiente por facturar")
                                {
                                    $rs_p=$registro->ordenes;
                                }
                            }
                            try{
                                $ac_f_p=100*$ac_f/($ac_f+$ac_p);
                            }
                            catch(Exception $e)
                            {
                                $ac_f_p=0;
                            }
                            try{
                                $ac_p_p=100*$ac_p/($ac_f+$ac_p);
                            }
                            catch(Exception $e)
                            {
                                $ac_p_p=0;
                            }
                            try{
                                $as_f_p=100*$as_f/($as_f+$as_p);
                            }
                            catch(Exception $e)
                            {
                                $as_f_p=0;
                            }
                            try{
                                $as_p_p=100*$as_p/($as_f+$as_p);
                            }
                            catch(Exception $e)
                            {
                                $as_p_p=0;
                            }
                            try{
                                $rc_f_p=100*$rc_f/($rc_f+$rc_p);
                            }
                            catch(Exception $e)
                            {
                                $rc_f_p=0;
                            }
                            try{
                                $rc_p_p=100*$rc_p/($rc_f+$rc_p);
                            }
                            catch(Exception $e)
                            {
                                $rc_p_p=0;
                            }
                            try{
                                $rs_f_p=100*$rs_f/($rs_f+$rs_p);
                            }
                            catch(Exception $e)
                            {
                                $rs_f_p=0;
                            }
                            try{
                                $rs_p_p=100*$rs_p/($rs_f+$rs_p);
                            }
                            catch(Exception $e)
                            {
                                $rs_p_p=0;
                            }
                            foreach($rs as $registro)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$registro->estatus_final}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($registro->ordenes,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$registro->ordenes/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-full text-xl flex justify-center font-bold pt-6">
                        <span class=""><!--PIE DE GRAFICO--></span>
                    </div>
                </div> <!--FIN DIV MEDICIONES-->
                
            </div>

        </div>
        
        <?php
            if($origen=="G" || $origen=="R")
            //if(false)
            {
        ?>
        <div class="w-full bg-gray-200 p-3 flex flex-col"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Detalles</div>
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full bg-white p-3 flex justify-center text-xs">
            <table class="">
                <tr>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3"></td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3"></td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Aceptadas</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Facturadas</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Pendietes</td>
                    <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-3">%pendiente</td>
                </tr>
                <?php
                $color=true;
                foreach($details as $detalle)
                {
                ?>
                <tr>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><a href="/dashboard_orden/{{$periodo}}/{{$origen=='G'?'E':'G'}}/{{$detalle->llave}}/{{$detalle->value}}">{{$detalle->llave}}</a></td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3">{{$detalle->value}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->aceptadas,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->facturadas,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->pendientes,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format(100*$detalle->pendientes/$detalle->aceptadas,0)}}%</td>
                </tr>
                <?php
                    $color=!$color;
                }
                ?>
            </table>
        </div>
        <?php
            }
        ?>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.js"></script>
        
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var ctx2 = document.getElementById('myChart2').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [
                <?php
                foreach($general as $registro)
                {
                ?>
                    '{{$registro->estatus_final}}',
                <?php
                }
                ?>
                ],
        datasets: [{
            label: '# of Votes',
            data: [
                <?php
                foreach($general as $registro)
                {
                ?>
                    {{$registro->ordenes}},
                <?php
                }
                ?>
                ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
            plugins: {
                title: {
                    display: true,
                    text: '{{$titulo}}'
                },
                legend: {
                    display:true,
                    align:'start',
                    position:'bottom'
                },
                labels:{
                    display:true
                }

            }
        }
   
});
var myChart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Activacion CON equipo',
        'Activacion SIN equipo',
        'Renovacion CON equipo',
        'Renovacion SIN equipo',
                ],
        datasets: [{
            label: 'Facturadas',
            data: [{{$ac_f_p}},{{$as_f_p}},{{$rc_f_p}},{{$rs_f_p}} ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        },
        {
            label: 'Pendientes',
            data: [{{$ac_p_p}},{{$as_p_p}},{{$rc_p_p}},{{$rs_p_p}} ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [

                'rgba(255, 99, 132, 1)',


            ],
            borderWidth: 1
        }
        ]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            title: {
                display: true,
                text: 'Pendientes - Datos en Porcentaje'
                },
            
        },
        responsive: true,
        scales: {
        x: {
            stacked: true,
        },
        y: {
            stacked: true
          }
        }
    }   
});


</script>
</x-app-layout>
