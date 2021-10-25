<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcesaFormasController;
use App\Http\Controllers\ProcesaSeguimientoController;
use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ExcelController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('dashboard');
//})->middleware('auth');

//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return view('dashboard');
//})->name('dashboard');

Route::get('/',[DashboardsController::class,'dashboard_central'])->middleware('auth');
Route::get('/dashboard',[DashboardsController::class,'dashboard_central'])->middleware('auth')->name('dashboard');

Route::get('/plantilla_nuevo', function () {return view('plantilla_nuevo');})->middleware('auth')->name('plantilla_nuevo');
Route::post('/plantilla_nuevo', [ProcesaFormasController::class,'plantilla_nuevo'])->middleware('auth')->name('plantilla_nuevo');

Route::get('/plantilla_update', function () {return view('plantilla_update');})->middleware('auth')->name('plantilla_update');
Route::post('/plantilla_update', [ProcesaFormasController::class,'plantilla_update'])->middleware('auth')->name('plantilla_update');

Route::get('/plantilla_consulta/{empleado}', [ProcesaFormasController::class,'plantilla_consulta'])->middleware('auth')->name('plantilla_consulta');

Route::get('/interacion_nuevo', function () {return view('interaccion_nuevo');})->middleware('auth')->name('interaccion_nuevo');
Route::post('/interacion_nuevo', [ProcesaFormasController::class,'interaccion_nuevo'])->middleware('auth')->name('interaccion_nuevo');

Route::get('/funnel_nuevo', function () {return view('funnel_nuevo');})->middleware('auth')->name('funnel_nuevo');
Route::post('/funnel_nuevo', [ProcesaFormasController::class,'funnel_nuevo'])->middleware('auth')->name('funnel_nuevo');

Route::get('/orden_nuevo', function () {return view('orden_nuevo');})->middleware('auth')->name('orden_nuevo');
Route::post('/orden_nuevo', [ProcesaFormasController::class,'orden_nuevo'])->middleware('auth')->name('orden_nuevo');

Route::get('/seguimiento_funnel', [ProcesaSeguimientoController::class,'seguimiento_funnel'])->middleware('auth')->name('seguimiento_funnel');
Route::get('/seguimiento_funnel_calendario', [ProcesaSeguimientoController::class,'seguimiento_funnel_calendario'])->middleware('auth')->name('seguimiento_funnel_calendario');
Route::get('/seguimiento_orden', [ProcesaSeguimientoController::class,'seguimiento_orden'])->middleware('auth')->name('seguimiento_orden');
Route::get('/seguimiento_incidencias', [ProcesaSeguimientoController::class,'seguimiento_incidencias'])->middleware('auth')->name('seguimiento_incidencias');

Route::get('/funnel_detalles/{id}', [ProcesaFormasController::class,'funnel_detalles'])->middleware('auth')->name('funnel_detalles');

Route::post('/funnel_update', [ProcesaFormasController::class,'funnel_update'])->middleware('auth')->name('funnel_update');

Route::get('/orden_detalles/{id}', [ProcesaFormasController::class,'orden_detalles'])->middleware('auth')->name('orden_detalles');

Route::post('/orden_update', [ProcesaFormasController::class,'orden_update'])->middleware('auth')->name('orden_update');

Route::get('/dashboard_efectividad/{periodo}', [DashboardsController::class,'dashboard_efectividad'])->middleware('auth')->name('dashboard_efectividad');
Route::get('/dashboard_efectividad/{periodo}/{tipo}/{key}/{value}', [DashboardsController::class,'dashboard_efectividad'])->middleware('auth')->name('dashboard_efectividad');

Route::get('/dashboard_interaccion/{periodo}', [DashboardsController::class,'dashboard_interaccion'])->middleware('auth')->name('dashboard_interaccion');
Route::get('/dashboard_interaccion/{periodo}/{tipo}/{key}/{value}', [DashboardsController::class,'dashboard_interaccion'])->middleware('auth')->name('dasboard_interaccion');

Route::get('/dashboard_orden/{periodo}', [DashboardsController::class,'dashboard_orden'])->middleware('auth')->name('dashboard_orden');
Route::get('/dashboard_orden/{periodo}/{tipo}/{key}/{value}', [DashboardsController::class,'dashboard_orden'])->middleware('auth')->name('dashboard_orden');

Route::get('/demanda_nuevo', function () {return view('demanda_nuevo');})->middleware('auth')->name('demanda_nuevo');
Route::post('/demanda_nuevo', [ProcesaFormasController::class,'demanda_nuevo'])->middleware('auth')->name('demanda_nuevo');

Route::get('/dashboard_productividad/{periodo}', [DashboardsController::class,'dashboard_productividad'])->middleware('auth')->name('dashboard_productividad');
Route::get('/dashboard_productividad/{periodo}/{tipo}/{key}/{value}', [DashboardsController::class,'dashboard_productividad'])->middleware('auth')->name('dashboard_productividad');

Route::get('/dashboard_resumen_periodo/{periodo}', [DashboardsController::class,'dashboard_resumen_periodo'])->middleware('auth')->name('dashboard_resumen_periodo');
Route::get('/dashboard_resumen_periodo/{periodo}/{tipo}/{key}/{value}', [DashboardsController::class,'dashboard_resumen_periodo'])->middleware('auth')->name('dashboard_resumen_periodo');

Route::get('/dashboard_resumen_efectividad/{periodo}', [DashboardsController::class,'dashboard_resumen_efectividad'])->middleware('auth')->name('dashboard_resumen_efectividad');
Route::get('/dashboard_resumen_efectividad/{periodo}/{tipo}/{key}/{value}', [DashboardsController::class,'dashboard_resumen_efectividad'])->middleware('auth')->name('dashboard_resumen_efectividad');

Route::get('/dashboard_rentabilidad/{periodo}', [DashboardsController::class,'dashboard_rentabilidad'])->middleware('auth')->name('dashboard_rentabilidad');
Route::get('/dashboard_rentabilidad/{periodo}/{tipo}/{key}/{value}', [DashboardsController::class,'dashboard_rentabilidad'])->middleware('auth')->name('dashboard_rentabilidad');

Route::get('/export_interaccion/{periodo}', [ExportController::class,'export_interaccion'])->middleware('auth');
Route::get('/export_orden/{periodo}', [ExportController::class,'export_orden'])->middleware('auth');

Route::get('/incidencia_nuevo', function () {return view('incidencia_nuevo');})->middleware('auth')->name('incidencia_nuevo');
Route::post('/incidencia_nuevo', [ProcesaFormasController::class,'incidencia_nuevo'])->middleware('auth')->name('incidencia_nuevo');
Route::post('/incidencia_borrar', [ProcesaFormasController::class,'incidencia_borrar'])->middleware('auth');

Route::get('/actividad_extra_nuevo', function () {return view('actividad_extra_nuevo');})->middleware('auth')->name('actividad_extra_nuevo');
Route::post('/actividad_extra_nuevo', [ProcesaFormasController::class,'actividad_extra_nuevo'])->middleware('auth')->name('actividad_extra_nuevo');

Route::get('/objetivo_update', [DashboardsController::class,'objetivo_form'])->middleware('auth')->name('objetivo_update');
Route::post('/objetivo_update', [ProcesaFormasController::class,'objetivo_update'])->middleware('auth')->name('objetivo_update');

Route::get('/objetivo_consulta/{periodo}/{udn}', [ProcesaFormasController::class,'objetivo_consulta'])->middleware('auth')->name('objetivo_consulta');

Route::get('/plantilla_sucursal', function () {return view('plantilla_sucursal');})->middleware('auth')->name('plantilla_sucursal');

Route::get('/erp_import', function () {return view('erp_import');})->middleware('auth')->name('erp_import');
Route::post('/erp_import', [ExcelController::class,'erp_import'])->middleware('auth')->name('erp_import');
Route::get('/rentabilidad_gastos',[DashboardsController::class,'forma_carga_gastos'])->middleware('auth')->name('rentabilidad_gastos');
Route::post('/rentabilidad_gastos',[ExcelController::class,'gastos_import'])->middleware('auth')->name('rentabilidad_gastos');