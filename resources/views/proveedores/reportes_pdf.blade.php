<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Proveedores</title>
    <style>
        @page {
            size: 330mm 216mm;   
            margin: 12mm 10mm;
        }

        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 10px; color: #1e293b; margin: 0; padding: 0; }

        .header { border-bottom: 3px solid #667eea; padding-bottom: 8px; margin-bottom: 10px; }
        .header h1 { margin: 0 0 2px 0; font-size: 20px; color: #667eea; }
        .header small { color: #64748b; font-size: 9px; }

        .filtros { background: #f1f5f9; border-radius: 6px; padding: 6px 12px; margin-bottom: 10px; font-size: 9px; }
        .filtros span { margin-right: 16px; }
        .filtros strong { color: #334155; }

        .section-title { font-size: 12px; font-weight: bold; color: #334155;
                         border-left: 4px solid #667eea; padding-left: 8px; margin: 12px 0 8px 0; }

        /* ============ KPIs ============ */
        .kpis { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 12px; }
        .kpi { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; }
        .kpi-label { color: #64748b; font-size: 8px; text-transform: uppercase; letter-spacing: 0.4px; }
        .kpi-value { font-size: 16px; font-weight: bold; color: #0f172a; margin-top: 2px; }
        .kpi-value.success { color: #11998e; }
        .kpi-value.warning { color: #d97706; }
        .kpi-value.danger  { color: #dc2626; }

        /* ============ GRÁFICAS ============ */
        .chart-box { margin-bottom: 8px; }
        .chart-title { font-size: 10px; font-weight: bold; color: #334155; margin-bottom: 4px;
                       background: #f1f5f9; padding: 5px 10px; border-radius: 4px; }
        .chart-img       { width: 100%; max-height: 200px; }
        .chart-img-full  { width: 100%; max-height: 230px; }

        /* ============ TABLA PRINCIPAL ============ */
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            table-layout: fixed;
        }
        table.data thead { background: #667eea; color: #fff; }
        table.data th, table.data td {
            border: 1px solid #cbd5e1;
            padding: 4px 4px;
            word-wrap: break-word;
        }
        table.data th {
            font-weight: bold;
            text-align: center;
            font-size: 8px;
            line-height: 1.15;
        }
        table.data th:first-child,
        table.data td:first-child {
            text-align: left;
            width: 130px;
            font-size: 8px;
        }
        table.data td.num { text-align: right; font-variant-numeric: tabular-nums; }
        table.data td.center { text-align: center; }
        table.data tbody tr:nth-child(even) { background: #f8fafc; }
        table.data tfoot th { background: #e2e8f0; color: #0f172a; font-weight: bold; }
        table.data tfoot th.num { text-align: right; }
        table.data tfoot th.center { text-align: center; }

        .proy { color: #2563eb; font-style: italic; }
        .proy-bg { background: #eff6ff !important; }
        .inc-up { color: #16a34a; font-weight: bold; }
        .inc-down { color: #dc2626; font-weight: bold; }
        .inc-proy { color: #2563eb; font-weight: bold; }

        /* ============ CAJAS DE PROYECCIÓN ============ */
        .box { border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 10px; background: #fff; }
        .box h3 { margin: 0 0 6px 0; font-size: 11px; color: #334155; }
        table.small { width: 100%; border-collapse: collapse; font-size: 9px; }
        table.small th, table.small td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; }
        table.small thead th { background: #f1f5f9; text-align: left; }
        table.small td.num { text-align: right; }

        .alert-info { background: #eff6ff; border-left: 3px solid #2563eb; padding: 6px 10px;
                      font-size: 9px; color: #1e40af; margin-bottom: 8px; border-radius: 4px; }
        .alert-warn { background: #fffbeb; border-left: 3px solid #d97706; padding: 6px 10px;
                      font-size: 9px; color: #92400e; margin-bottom: 8px; border-radius: 4px; }
        .alert-danger { background: #fef2f2; border-left: 3px solid #dc2626; padding: 10px;
                        border-radius: 4px; margin-bottom: 8px; }
        .alert-danger .big { font-size: 22px; font-weight: bold; color: #dc2626; }

        .footer { margin-top: 14px; padding-top: 6px; border-top: 1px solid #cbd5e1;
                  text-align: center; color: #94a3b8; font-size: 8px; }

        .legend { font-size: 8px; color: #64748b; margin: 4px 0 6px 0; }
        .legend .box-color { display: inline-block; width: 10px; height: 10px;
                             border: 1px solid #cbd5e1; vertical-align: middle; margin-right: 3px; }

        .page-break { page-break-before: always; }
        .avoid-break { page-break-inside: avoid; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Reporte de Proveedores</h1>
        <small>Sistema de Gestión · Generado el {{ $fechaGeneracion }}</small>
    </div>

    {{-- FILTROS --}}
    <div class="filtros">
        <strong>Filtros:</strong>
        <span><strong>Año:</strong> {{ $anioActual }}</span>
        <span><strong>Proveedor:</strong>
            @if($proveedorId)
                {{ $listaProveedores->firstWhere('id', $proveedorId)->proveedor ?? 'N/A' }}
            @else
                Todos
            @endif
        </span>
        <span><strong>Ciudad:</strong> {{ request('ciudad') ?: 'Todas' }}</span>
        <span><strong>Devaluación $:</strong> {{ $devaluacion }}%</span>
    </div>

    {{-- KPIs --}}
    <div class="section-title">Resumen general</div>
    <table class="kpis">
        <tr>
            <td class="kpi" width="25%">
                <div class="kpi-label">Total Facturas</div>
                <div class="kpi-value">{{ number_format($stats['total_facturas']) }}</div>
            </td>
            <td class="kpi" width="25%">
                <div class="kpi-label">Monto Total USD</div>
                <div class="kpi-value success">${{ number_format($stats['monto_total_usd'], 2, '.', ',') }}</div>
            </td>
            <td class="kpi" width="25%">
                <div class="kpi-label">Pendientes</div>
                <div class="kpi-value warning">{{ number_format($stats['pendientes']) }}</div>
            </td>
            <td class="kpi" width="25%">
                <div class="kpi-label">Vencidas</div>
                <div class="kpi-value danger">{{ number_format($stats['vencidas']) }}</div>
            </td>
        </tr>
    </table>

    {{-- GRÁFICAS --}}
    <div class="section-title">Gráficas</div>

    <table style="width:100%; border-collapse:collapse;" class="avoid-break">
        <tr>
            <td width="50%" style="padding:0 4px 8px 0; vertical-align:top;">
                <div class="chart-box">
                    <div class="chart-title">Top proveedores por monto facturado</div>
                    @if(!empty($chartImages['proveedores']))
                        <img src="{{ $chartImages['proveedores'] }}" class="chart-img">
                    @else
                        <div style="text-align:center;color:#94a3b8;padding:20px;">Sin imagen</div>
                    @endif
                </div>
            </td>
            <td width="50%" style="padding:0 0 8px 4px; vertical-align:top;">
                <div class="chart-box">
                    <div class="chart-title">Evolución mensual</div>
                    @if(!empty($chartImages['meses']))
                        <img src="{{ $chartImages['meses'] }}" class="chart-img">
                    @else
                        <div style="text-align:center;color:#94a3b8;padding:20px;">Sin imagen</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; border-collapse:collapse;" class="avoid-break">
        <tr>
            <td width="50%" style="padding:0 4px 0 0; vertical-align:top;">
                <div class="chart-box">
                    <div class="chart-title">Histórico de pagos por servicio / producto</div>
                    @if(!empty($chartImages['servicios']))
                        <img src="{{ $chartImages['servicios'] }}" class="chart-img-full">
                    @endif
                </div>
            </td>
            <td width="50%" style="padding:0 0 0 4px; vertical-align:top;">
                <div class="chart-box">
                    <div class="chart-title">Incremento mes a mes y proyección anual</div>
                    @if(!empty($chartImages['incremento']))
                        <img src="{{ $chartImages['incremento'] }}" class="chart-img-full">
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- ====================================================== --}}
    {{-- TABLA EN HORIZONTAL (aprovecha todo el ancho del A4 L) --}}
    {{-- ====================================================== --}}
    <div class="page-break"></div>

    <div class="section-title">
        Facturación por proveedor y mes ({{ $anioActual }}) — USD
    </div>

    <div class="legend">
        <span><span class="box-color" style="background:#ffffff;"></span> Monto real facturado</span>
        &nbsp;&nbsp;
        <span><span class="box-color" style="background:#eff6ff;"></span> Monto proyectado (basado en los 2 meses anteriores)</span>
        &nbsp;&nbsp;
        <span><span class="inc-up">▲</span> Incremento</span>
        &nbsp;&nbsp;
        <span><span class="inc-down">▼</span> Decremento</span>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="text-align:left;">Proveedor</th>
                @foreach($tablaProveedores['meses'] as $mes)
                    <th>{{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->format('M') }}</th>
                    <th>Δ%</th>
                @endforeach
                <th>Total</th>
                <th>Prom Δ%</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tablaProveedores['proveedores'] as $fila)
                <tr>
                    <td>{{ $fila['nombre'] }}</td>
                    @foreach($fila['valores'] as $idx => $valor)
                        @php $esProy = $fila['es_proyectado'][$idx] ?? false; @endphp
                        <td class="num {{ $esProy ? 'proy proy-bg' : '' }}">
                            @if($valor > 0)
                                ${{ number_format($valor, 2, '.', ',') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="center">
                            @php $inc = $fila['incrementos'][$idx] ?? null; @endphp
                            @if($inc === null)
                                —
                            @elseif($inc > 0)
                                <span class="{{ $esProy ? 'inc-proy' : 'inc-up' }}">▲ {{ number_format($inc, 1) }}%</span>
                            @elseif($inc < 0)
                                <span class="{{ $esProy ? 'inc-proy' : 'inc-down' }}">▼ {{ number_format(abs($inc), 1) }}%</span>
                            @else
                                <span style="color:#94a3b8;">0%</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="num" style="background:#eff6ff;font-weight:bold;">
                        ${{ number_format($fila['total'], 2, '.', ',') }}
                    </td>
                    <td class="center" style="background:#ecfeff;">{{ $fila['prom_inc'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="27" style="text-align:center;padding:20px;color:#94a3b8;">
                        No hay datos para mostrar en el año {{ $anioActual }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($tablaProveedores['proveedores']) > 0)
            <tfoot>
                <tr>
                    <th style="text-align:left;">TOTAL GENERAL</th>
                    @foreach($tablaProveedores['totales_mes'] as $idx => $valor)
                        @php $algunProy = $tablaProveedores['totales_mes_proyectado'][$idx] ?? false; @endphp
                        <th class="num {{ $algunProy ? 'proy' : '' }}">
                            @if($valor > 0)
                                ${{ number_format($valor, 2, '.', ',') }}
                            @else
                                —
                            @endif
                        </th>
                        <th class="center">
                            @php $inc = $tablaProveedores['totales_inc'][$idx] ?? null; @endphp
                            @if($inc === null)
                                —
                            @elseif($inc > 0)
                                <span class="{{ $algunProy ? 'inc-proy' : 'inc-up' }}">▲ {{ number_format($inc, 1) }}%</span>
                            @elseif($inc < 0)
                                <span class="{{ $algunProy ? 'inc-proy' : 'inc-down' }}">▼ {{ number_format(abs($inc), 1) }}%</span>
                            @else
                                0%
                            @endif
                        </th>
                    @endforeach
                    <th class="num" style="background:#667eea;color:#fff;">
                        ${{ number_format($tablaProveedores['gran_total'], 2, '.', ',') }}
                    </th>
                    <th class="center" style="background:#0ea5e9;color:#fff;">
                        {{ $tablaProveedores['prom_inc_global'] }}%
                    </th>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- PROYECCIONES --}}
    <div class="page-break"></div>

    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td width="50%" style="padding:0 8px 0 0; vertical-align:top;">
                <div class="box avoid-break">
                    <h3>Proyección meses restantes {{ $anioActual }}</h3>

                    @if(($proyeccionRestante['aplica'] ?? false) && count($proyeccionRestante['proyecciones'] ?? []) > 0)
                        <div class="alert-info">
                            Proyectando con incremento promedio del
                            <strong>{{ $proyeccionRestante['prom_inc'] }}%</strong> mensual.
                        </div>

                        <table class="small">
                            <thead>
                                <tr><th>Mes</th><th style="text-align:right;">Monto proyectado</th></tr>
                            </thead>
                            <tbody>
                                @foreach($proyeccionRestante['proyecciones'] as $proy)
                                    <tr>
                                        <td>{{ $proy['nombre'] }}</td>
                                        <td class="num proy">${{ number_format($proy['monto'], 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#e2e8f0;font-weight:bold;">
                                    <td>Total restante</td>
                                    <td class="num">${{ number_format($proyeccionRestante['total'], 2, '.', ',') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div style="color:#94a3b8;font-size:9px;">
                            {{ $proyeccionRestante['nota'] ?? 'No hay meses por proyectar.' }}
                        </div>
                    @endif
                </div>
            </td>

            <td width="50%" style="padding:0 0 0 8px; vertical-align:top;">
                <div class="box avoid-break">
                    <h3>Presupuesto 2027 (con devaluación del $)</h3>

                    <div class="alert-warn">
                        Tasa de devaluación aplicada:
                        <strong>{{ $proyeccion2027['tasa_devaluacion'] }}%</strong> anual
                        (factor ×{{ $proyeccion2027['factor_aplicado'] }})
                    </div>

                    <table class="small">
                        <tr>
                            <td>Promedio mensual (base)</td>
                            <td class="num">${{ number_format($proyeccion2027['promedio_mensual'], 2, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td>Base anual {{ $anioActual }}</td>
                            <td class="num">${{ number_format($proyeccion2027['base_anual_actual'], 2, '.', ',') }}</td>
                        </tr>
                    </table>

                    <div class="alert-danger" style="margin-top:10px;">
                        <div style="font-size:9px;color:#92400e;">Presupuesto 2027 estimado</div>
                        <div class="big">${{ number_format($proyeccion2027['proyeccion_2027'], 2, '.', ',') }}</div>
                        <div style="font-size:9px;color:#92400e;">
                            ≈ ${{ number_format($proyeccion2027['promedio_2027'], 2, '.', ',') }} / mes
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Reporte generado automáticamente por el Sistema de Gestión de Proveedores · {{ $fechaGeneracion }}
    </div>

</body>
</html>