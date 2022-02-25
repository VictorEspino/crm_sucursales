<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Socios - Diario') }}
    </x-slot>
    <div class="flex flex-col w-full  bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">SOCIOS COMERCIALES</div>
            <div class="w-full text-lg font-semibold">Medicion diaria - {{$periodo}}</div>
            <div class="px-4 text-sm font-semibold">Dias transcurridos: {{$transcurridos}}</div>
            <div class="px-4 text-sm font-semibold">Dias totales mes: {{$dias_total}}</div>
            <div class="w-full text-sm font-semibold text-red-400">Ultimo dia de información : {{$ultimo_dia}}</div>
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full bg-white rounded-b-lg flex flex-wrap space-y-3">
            <div class="w-full flex justify-center pt-5">
                <table class="table-auto">
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td rowspan=2 class="px-1 py-1">U Negocio</td>
                        <td colspan=2 class="px-1 py-1"><center>Activaciones</td>
                        <td colspan=2 class="px-1 py-1"><center>Renovaciones</td>
                        <td colspan=2 class="px-1 py-1"><center>Arpu</td>
                        <td class="px-1 py-1 bg-gray-500 text-gray-500"><center>.</td>
                        <td colspan=2 class="px-1 py-1"><center>Proy Activaciones</td>
                        <td colspan=2 class="px-1 py-1"><center>Proy Renovaciones</td>
                    </tr>
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1"><center>Lineas</td>
                        <td class="px-1 py-1"><center>Monto</td>
                        <td class="px-1 py-1"><center>Lineas</td>
                        <td class="px-1 py-1"><center>Monto</td>
                        <td class="px-1 py-1"><center>Activaciones</td>
                        <td class="px-1 py-1"><center>Renovaciones</td>
                        <td class="px-1 py-1 bg-gray-500 text-gray-500"><center>.</td>
                        <td class="px-1 py-1"><center>Lineas</td>
                        <td class="px-1 py-1"><center>Monto</td>
                        <td class="px-1 py-1"><center>Lineas</td>
                        <td class="px-1 py-1"><center>Monto</td>
                    </tr>
                    <tr class="bg-green-200 text-base font-semibold">
                        <td class="px-1">Masivo</td>
                        <td class=""><center>{{number_format($act_masivo_l+$aep_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($act_masivo_m+$aep_masivo_m,0)}}</td>
                        <td class=""><center>{{number_format($ren_masivo_l+$rep_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($ren_masivo_m+$rep_masivo_m,0)}}</td>
                        <td class=""><center>${{$act_masivo_l+$aep_masivo_l==0?'0.00':number_format(($act_masivo_m+$aep_masivo_m)/($act_masivo_l+$aep_masivo_l),2)}}</td>
                        <td class=""><center>${{$ren_masivo_l+$rep_masivo_l==0?'0.00':number_format(($ren_masivo_m+$rep_masivo_m)/($ren_masivo_l+$rep_masivo_l),2)}}</td>
                        <td class="px-1 py-1 bg-gray-500 text-gray-500"><center>.</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($act_masivo_l+$aep_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($act_masivo_m+$aep_masivo_m)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($ren_masivo_l+$rep_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($ren_masivo_m+$rep_masivo_m)/$transcurridos,0)}}</td>
                    </tr> 
                    <tr class="text-sm">
                        <td class="px-4">CON Equipo</td>
                        <td class=""><center>{{number_format($act_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($act_masivo_m,0)}}</td>
                        <td class=""><center>{{number_format($ren_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($ren_masivo_m,0)}}</td>
                        <td class=""><center>${{$act_masivo_l==0?'0.00':number_format(($act_masivo_m)/($act_masivo_l),2)}}</td>
                        <td class=""><center>${{$ren_masivo_l==0?'0.00':number_format(($ren_masivo_m)/($ren_masivo_l),2)}}</td>
                        <td class="px-1 py-1 bg-gray-500 text-gray-500"><center>.</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($act_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($act_masivo_m)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($ren_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($ren_masivo_m)/$transcurridos,0)}}</td>
                    </tr> 
                    <tr class="bg-gray-100 text-sm">
                        <td class="px-4">SIN Equipo</td>
                        <td class=""><center>{{number_format($aep_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($aep_masivo_m,0)}}</td>
                        <td class=""><center>{{number_format($rep_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($rep_masivo_m,0)}}</td>
                        <td class=""><center>${{$aep_masivo_l==0?'0.00':number_format(($aep_masivo_m)/($aep_masivo_l),2)}}</td>
                        <td class=""><center>${{$rep_masivo_l==0?'0.00':number_format(($rep_masivo_m)/($rep_masivo_l),2)}}</td>
                        <td class="px-1 py-1 bg-gray-500 text-gray-500"><center>.</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($aep_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($aep_masivo_m)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($rep_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($rep_masivo_m)/$transcurridos,0)}}</td>
                    </tr>
                    <tr class="bg-green-200 font-semibold">
                        <td class="px-1">Empresarial</td>
                        <td class=""><center>{{number_format($act_empresarial_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($act_empresarial_m,0)}}</td>
                        <td class=""><center>{{number_format($ren_empresarial_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($ren_empresarial_m,0)}}</td></td>
                        <td class=""><center>${{$act_empresarial_l==0?'0.00':number_format(($act_empresarial_m)/($act_empresarial_l),2)}}</td>
                        <td class=""><center>${{$ren_empresarial_l==0?'0.00':number_format(($ren_empresarial_m)/($ren_empresarial_l),2)}}</td>
                        <td class="px-1 py-1 bg-gray-500 text-gray-500"><center>.</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($act_empresarial_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($act_empresarial_m)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($ren_empresarial_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($ren_empresarial_m)/$transcurridos,0)}}</td>
                    </tr> 
                    <tr class="bg-gray-700 text-gray-100 font-semibold">
                        <td class="px-1">Total</td>
                        <td class=""><center>{{number_format($act_empresarial_l+$act_masivo_l+$aep_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($act_empresarial_m+$act_masivo_m+$aep_masivo_m,0)}}</td>
                        <td class=""><center>{{number_format($ren_empresarial_l+$ren_masivo_l+$rep_masivo_l,0)}}</td>
                        <td class="px-3"><center>${{number_format($ren_empresarial_m+$ren_masivo_m+$rep_masivo_m,0)}}</td></td>
                        <td class=""><center>${{$act_empresarial_l+$act_masivo_l+$aep_masivo_l==0?'0.00':number_format(($act_empresarial_m+$act_masivo_m+$aep_masivo_m)/($act_empresarial_l+$act_masivo_l+$aep_masivo_l),2)}}</td>
                        <td class=""><center>${{$ren_empresarial_l+$ren_masivo_l+$rep_masivo_l==0?'0.00':number_format(($ren_empresarial_m+$ren_masivo_m+$rep_masivo_m)/($ren_empresarial_l+$ren_masivo_l+$rep_masivo_l),2)}}</td>
                        <td class="px-1 py-1 bg-gray-500 text-gray-500"><center>.</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($act_empresarial_l+$act_masivo_l+$aep_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($act_empresarial_m+$act_masivo_m+$aep_masivo_m)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($ren_empresarial_l+$ren_masivo_l+$rep_masivo_l)/$transcurridos,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($ren_empresarial_m+$ren_masivo_m+$rep_masivo_m)/$transcurridos,0)}}</td>
                    </tr> 
                </table>
            </div>
            <div class="w-full flex justify-center pt-4 pb-10">
                <table class="table-auto">
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1">Total Direccion</td>
                        <td class="px-1 py-1"><center>Actual</td>
                        <td class="px-1 py-1"><center>Proyeccion</td>
                    </tr>
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1"><center>Monto</td>
                        <td class="px-3"><center>${{number_format($act_empresarial_m+$act_masivo_m+$aep_masivo_m+$ren_empresarial_m+$ren_masivo_m+$rep_masivo_m,0)}}</td>
                        <td class="px-1 py-1"><center>${{number_format($dias_total*($act_empresarial_m+$act_masivo_m+$aep_masivo_m+$ren_empresarial_m+$ren_masivo_m+$rep_masivo_m)/$transcurridos,0)}}</td>
                    </tr>
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1"><center>Activaciones</td>
                        <td class="px-3"><center>{{number_format($act_empresarial_l+$act_masivo_l+$aep_masivo_l,0)}}</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($act_empresarial_l+$act_masivo_l+$aep_masivo_l)/$transcurridos,0)}}</td>
                    </tr>
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1"><center>Renovaciones</td>
                        <td class="px-3"><center>{{number_format($ren_empresarial_l+$ren_masivo_l+$rep_masivo_l,0)}}</td>
                        <td class="px-1 py-1"><center>{{number_format($dias_total*($ren_empresarial_l+$ren_masivo_l+$rep_masivo_l)/$transcurridos,0)}}</td>
                    </tr>
                </table>
            </div>
        </div>  
    </div>

</x-app-layout>
