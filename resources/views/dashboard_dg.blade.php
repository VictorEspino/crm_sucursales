<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard General') }}
    </x-slot>
    <div class="flex flex-col w-full text-gray-700 shadow-lg rounded-lg px-3">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Medicion ejecutiva - {{$periodo}}</div>
            <div class="w-full text-lg font-semibold">{{$titulo}}</div>
            <div class="w-full text-lg font-semibold text-blue-900"><a href="/comentarios" target="_blank">Notas de seguimiento</a></div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
            <div class="w-full text-sm font-semibold text-red-400">Ultimo dia de información : {{$ultimo_dia}}</div>
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full  bg-white rounded-b-lg flex flex-wrap space-y-3">
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700 flex">Activaciones - Masivo</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones - Masivo</span></div>
                        <div class="w-full">
                        <canvas id="chartActivaciones" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-4 flex flex-col">
                    <div class="w-full flex justify-center pb-3">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Total</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_mas_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_mas_array[$index]['act']+$año_suc_mas_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_mas_array[$index]['act']+$año_gab_mas_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_mas_array[$index]['act']+$año_anterior_gab_mas_array[$index]['aep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico2+$historico['act']+$historico['aep'];
                                $ytd_act=$ytd_act+$dato_mes+$dato_mes2;
                            }

                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2+$historico['act']+$historico['aep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2+$dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2+$historico['act']+$historico['aep']>0)?number_format(100*($dato_mes+$dato_mes2)/($historico2+$historico['act']+$historico['aep'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-full flex justify-center text-sm font-bold">
                            Division por canal
                    </div>
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3"><center>Sucursales</td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Socios-Emp</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_mas_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_mas_array[$index]['act']+$año_suc_mas_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_mas_array[$index]['act']+$año_gab_mas_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_mas_array[$index]['act']+$año_anterior_gab_mas_array[$index]['aep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico['act']+$historico['aep'];
                                $ytd_act=$ytd_act+$dato_mes;
                                $ytd_ant_2=$ytd_ant_2+$historico2;
                                $ytd_act_2=$ytd_act_2+$dato_mes2;
                            }

                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico['act']+$historico['aep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico['act']+$historico['aep']>0)?number_format(100*$dato_mes/($historico['act']+$historico['aep'])-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2>0)?number_format(100*$dato_mes2/($historico2)-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant_2>0)?number_format(100*$ytd_act_2/($ytd_ant_2)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700 flex">Renovaciones - Masivo</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones - Masivo</span></div>
                        <div class="w-full">
                        <canvas id="chartRenovaciones" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>                
                <div class="w-full md:w-1/2 p-4 flex flex-col">
                    <div class="w-full flex justify-center pb-3">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Total</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_mas_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_mas_array[$index]['ren']+$año_suc_mas_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_mas_array[$index]['ren']+$año_gab_mas_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_mas_array[$index]['ren']+$año_anterior_gab_mas_array[$index]['rep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico2+$historico['ren']+$historico['rep'];
                                $ytd_act=$ytd_act+$dato_mes+$dato_mes2;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2+$historico['ren']+$historico['rep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2+$dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2+$historico['ren']+$historico['rep']>0)?number_format(100*($dato_mes+$dato_mes2)/($historico2+$historico['ren']+$historico['rep'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-full flex justify-center text-sm font-bold">
                            Division por canal
                    </div>
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3"><center>Sucursales</td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Socios-Emp</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_mas_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_mas_array[$index]['ren']+$año_suc_mas_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_mas_array[$index]['ren']+$año_gab_mas_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_mas_array[$index]['ren']+$año_anterior_gab_mas_array[$index]['rep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico['ren']+$historico['rep'];
                                $ytd_act=$ytd_act+$dato_mes;
                                $ytd_ant_2=$ytd_ant_2+$historico2;
                                $ytd_act_2=$ytd_act_2+$dato_mes2;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico['ren']+$historico['rep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico['ren']+$historico['rep']>0)?number_format(100*$dato_mes/($historico['ren']+$historico['rep'])-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2>0)?number_format(100*$dato_mes2/($historico2)-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant_2>0)?number_format(100*$ytd_act_2/($ytd_ant_2)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700 flex">Activaciones - Empresarial</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones - Empresarial</span></div>
                        <div class="w-full">
                        <canvas id="chartActivaciones_neg" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-4 flex flex-col">
                    <div class="w-full flex justify-center pb-3">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Total</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_neg_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_neg_array[$index]['act']+$año_suc_neg_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_neg_array[$index]['act']+$año_gab_neg_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_neg_array[$index]['act']+$año_anterior_gab_neg_array[$index]['aep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico2+$historico['act']+$historico['aep'];
                                $ytd_act=$ytd_act+$dato_mes+$dato_mes2;
                            }

                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2+$historico['act']+$historico['aep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2+$dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2+$historico['act']+$historico['aep']>0)?number_format(100*($dato_mes+$dato_mes2)/($historico2+$historico['act']+$historico['aep'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-full flex justify-center text-sm font-bold">
                            Division por canal
                    </div>
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3"><center>Sucursales</td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Socios-Emp</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_neg_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_neg_array[$index]['act']+$año_suc_neg_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_neg_array[$index]['act']+$año_gab_neg_array[$index]['aep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_neg_array[$index]['act']+$año_anterior_gab_neg_array[$index]['aep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico['act']+$historico['aep'];
                                $ytd_act=$ytd_act+$dato_mes;
                                $ytd_ant_2=$ytd_ant_2+$historico2;
                                $ytd_act_2=$ytd_act_2+$dato_mes2;
                            }

                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico['act']+$historico['aep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico['act']+$historico['aep']>0)?number_format(100*$dato_mes/($historico['act']+$historico['aep'])-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2>0)?number_format(100*$dato_mes2/($historico2)-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant_2>0)?number_format(100*$ytd_act_2/($ytd_ant_2)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700 flex">Renovaciones - Empresarial</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones - Empresarial</span></div>
                        <div class="w-full">
                        <canvas id="chartRenovaciones_neg" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>                
                <div class="w-full md:w-1/2 p-4 flex flex-col">
                    <div class="w-full flex justify-center pb-3">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Total</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_neg_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_neg_array[$index]['ren']+$año_suc_neg_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_neg_array[$index]['ren']+$año_gab_neg_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_neg_array[$index]['ren']+$año_anterior_gab_neg_array[$index]['rep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico2+$historico['ren']+$historico['rep'];
                                $ytd_act=$ytd_act+$dato_mes+$dato_mes2;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2+$historico['ren']+$historico['rep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2+$dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2+$historico['ren']+$historico['rep']>0)?number_format(100*($dato_mes+$dato_mes2)/($historico2+$historico['ren']+$historico['rep'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-full flex justify-center text-sm font-bold">
                            Division por canal
                    </div>
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="text-xs bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td colspan=3 class="py-2 px-3"><center>Sucursales</td>
                                <td colspan=3 class="py-2 px-3 rounded-tr-xl"><center>Socios-Emp</td>
                            </tr>
                            <tr class="text-xs bg-blue-700 text-white">
                                <td class=""></td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                                <td class="py-2 px-2"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-2"><center>{{$año}}</td>
                                <td class="py-2 px-2"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                                $ytd_ant_2=0;
                                $ytd_act_2=0;
                                $fallo=0;
                            @endphp
                            @foreach($año_anterior_suc_neg_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$año_suc_neg_array[$index]['ren']+$año_suc_neg_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                                $fallo=1;
                            }
                            try{
                                $dato_mes2=$año_gab_neg_array[$index]['ren']+$año_gab_neg_array[$index]['rep'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes2=0;
                                $fallo=1;
                            }
                            $historico2=$año_anterior_gab_neg_array[$index]['ren']+$año_anterior_gab_neg_array[$index]['rep'];
                            if($fallo==0)
                            {
                                $ytd_ant=$ytd_ant+$historico['ren']+$historico['rep'];
                                $ytd_act=$ytd_act+$dato_mes;
                                $ytd_ant_2=$ytd_ant_2+$historico2;
                                $ytd_act_2=$ytd_act_2+$dato_mes2;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-2 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico['ren']+$historico['rep'],0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico['ren']+$historico['rep']>0)?number_format(100*$dato_mes/($historico['ren']+$historico['rep'])-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($historico2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($dato_mes2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($historico2>0)?number_format(100*$dato_mes2/($historico2)-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-2 border">YTD</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_ant_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{number_format($ytd_act_2,0)}}</td>
                                <td class="text-xs px-1 border"><center>{{($ytd_ant_2>0)?number_format(100*$ytd_act_2/($ytd_ant_2)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.js"></script>   
<script>
var activaciones = document.getElementById("chartActivaciones").getContext('2d');
var renovaciones = document.getElementById("chartRenovaciones").getContext('2d');
var activaciones_neg = document.getElementById("chartActivaciones_neg").getContext('2d');
var renovaciones_neg = document.getElementById("chartRenovaciones_neg").getContext('2d');

new Chart(activaciones, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_mas_array[$index]['act']+$año_suc_mas_array[$index]['aep']+$año_gab_mas_array[$index]['act']+$año_gab_mas_array[$index]['aep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Sucursales - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_mas_array[$index]['act']+$año_suc_mas_array[$index]['aep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#00FF00', // Add custom color border (Line)
            backgroundColor: '#00FF00', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Socios-Emp - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_gab_mas_array[$index]['act']+$año_gab_mas_array[$index]['aep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#0000FF', // Add custom color border (Line)
            backgroundColor: '#0000FF', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_anterior_suc_mas_array as $index=>$actual)
                {{$año_anterior_suc_mas_array[$index]['act']+$año_anterior_suc_mas_array[$index]['aep']+$año_anterior_gab_mas_array[$index]['act']+$año_anterior_gab_mas_array[$index]['aep']}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ddf8ff', // Add custom color border (Line)
            backgroundColor: '#ddf8ff', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});

new Chart(renovaciones, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_mas_array[$index]['ren']+$año_suc_mas_array[$index]['rep']+$año_gab_mas_array[$index]['ren']+$año_gab_mas_array[$index]['rep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Sucursales - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_mas_array[$index]['ren']+$año_suc_mas_array[$index]['rep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#00FF00', // Add custom color border (Line)
            backgroundColor: '#00FF00', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Socios-Emp - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_gab_mas_array[$index]['ren']+$año_gab_mas_array[$index]['rep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#0000FF', // Add custom color border (Line)
            backgroundColor: '#0000FF', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_anterior_suc_mas_array as $index=>$actual)
                {{$año_anterior_suc_mas_array[$index]['ren']+$año_anterior_suc_mas_array[$index]['rep']+$año_anterior_gab_mas_array[$index]['ren']+$año_anterior_gab_mas_array[$index]['rep']}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#e3fff0', // Add custom color border (Line)
            backgroundColor: '#e3fff0', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(activaciones_neg, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_neg_array[$index]['act']+$año_suc_neg_array[$index]['aep']+$año_gab_neg_array[$index]['act']+$año_gab_neg_array[$index]['aep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Sucursales - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_neg_array[$index]['act']+$año_suc_neg_array[$index]['aep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#00FF00', // Add custom color border (Line)
            backgroundColor: '#00FF00', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Socios-Emp - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_gab_neg_array[$index]['act']+$año_gab_neg_array[$index]['aep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#0000FF', // Add custom color border (Line)
            backgroundColor: '#0000FF', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_anterior_suc_mas_array as $index=>$actual)
                {{$año_anterior_suc_neg_array[$index]['act']+$año_anterior_suc_neg_array[$index]['aep']+$año_anterior_gab_neg_array[$index]['act']+$año_anterior_gab_neg_array[$index]['aep']}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ddf8ff', // Add custom color border (Line)
            backgroundColor: '#ddf8ff', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});

new Chart(renovaciones_neg, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_neg_array[$index]['ren']+$año_suc_neg_array[$index]['rep']+$año_gab_neg_array[$index]['ren']+$año_gab_neg_array[$index]['rep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Sucursales - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_suc_neg_array[$index]['ren']+$año_suc_neg_array[$index]['rep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#00FF00', // Add custom color border (Line)
            backgroundColor: '#00FF00', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
        {
            label: 'Socios-Emp - {{$año}}', // Name the series
            data: [ // Specify the data values array
            @foreach($año_suc_mas_array as $index=>$actual)
                {{$año_gab_neg_array[$index]['ren']+$año_gab_neg_array[$index]['rep']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#0000FF', // Add custom color border (Line)
            backgroundColor: '#0000FF', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($año_anterior_suc_mas_array as $index=>$actual)
                {{$año_anterior_suc_neg_array[$index]['ren']+$año_anterior_suc_neg_array[$index]['rep']+$año_anterior_gab_neg_array[$index]['ren']+$año_anterior_gab_neg_array[$index]['rep']}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#e3fff0', // Add custom color border (Line)
            backgroundColor: '#e3fff0', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});

</script>  
</x-app-layout>
