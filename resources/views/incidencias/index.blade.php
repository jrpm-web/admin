<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Incidencias') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- ENCABEZADO + BOTÓN NUEVA --}}
            {{-- ============================================================ --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Actividades</h1>
                    <p class="text-sm text-gray-500">
                        Gestiona todas las actividades
                    </p>
                </div>
                <a href="{{ route('incidencias.create') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium transition shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    Nueva Actividad
                </a>
            </div>

            {{-- ============================================================ --}}
            {{-- ALERTAS --}}
            {{-- ============================================================ --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg flex items-center gap-3">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- ============================================================ --}}
            {{-- CARDS DE RESUMEN --}}
            {{-- ============================================================ --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="text-xs uppercase text-gray-400 font-semibold">Total</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $contadores['total'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-500">
                    <div class="text-xs uppercase text-gray-400 font-semibold">Abiertas</div>
                    <div class="text-2xl font-bold text-red-600">{{ $contadores['abiertas'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-amber-500">
                    <div class="text-xs uppercase text-gray-400 font-semibold">En proceso</div>
                    <div class="text-2xl font-bold text-amber-600">{{ $contadores['en_proceso'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                    <div class="text-xs uppercase text-gray-400 font-semibold">Finalizadas</div>
                    <div class="text-2xl font-bold text-green-600">{{ $contadores['finalizadas'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-700">
                    <div class="text-xs uppercase text-gray-400 font-semibold">Críticas activas</div>
                    <div class="text-2xl font-bold text-red-700">{{ $contadores['criticas'] }}</div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- FILTROS --}}
            {{-- ============================================================ --}}
            <form method="GET" action="{{ route('incidencias.index') }}"
                  class="bg-white rounded-xl shadow-sm p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    {{-- Búsqueda --}}
                    <div class="relative md:col-span-2">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar por título o descripción..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                    </div>

                    {{-- Estado --}}
                    <select name="estado"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        <option value="">Todos los estados</option>
                        <option value="activas"    {{ request('estado') == 'activas' ? 'selected' : '' }}>🟢 Solo activas</option>
                        <option value="inactivas"  {{ request('estado') == 'inactivas' ? 'selected' : '' }}>⚪ Solo inactivas</option>
                        <option value="abierta"    {{ request('estado') == 'abierta' ? 'selected' : '' }}>Abierta</option>
                        <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                        <option value="en_revision"{{ request('estado') == 'en_revision' ? 'selected' : '' }}>En revisión</option>
                        <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                        <option value="cerrada"    {{ request('estado') == 'cerrada' ? 'selected' : '' }}>Cerrada</option>
                    </select>

                    {{-- Prioridad --}}
                    <select name="prioridad"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        <option value="">Todas las prioridades</option>
                        <option value="critica" {{ request('prioridad') == 'critica' ? 'selected' : '' }}>🔴 Crítica</option>
                        <option value="alta"    {{ request('prioridad') == 'alta' ? 'selected' : '' }}>🟠 Alta</option>
                        <option value="media"   {{ request('prioridad') == 'media' ? 'selected' : '' }}>🔵 Media</option>
                        <option value="baja"    {{ request('prioridad') == 'baja' ? 'selected' : '' }}>⚪ Baja</option>
                    </select>
                </div>

                <div class="flex flex-wrap items-center gap-3 mt-3">
                    <select name="orden"
                            class="px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">
                        <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                        <option value="antiguas"  {{ request('orden') == 'antiguas' ? 'selected' : '' }}>Más antiguas</option>
                        <option value="prioridad" {{ request('orden') == 'prioridad' ? 'selected' : '' }}>Por prioridad</option>
                        <option value="estado"    {{ request('orden') == 'estado' ? 'selected' : '' }}>Por estado</option>
                    </select>

                    <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="mias" value="1" {{ request('mias') ? 'checked' : '' }}
                               class="rounded text-blue-600">
                        Solo las mías
                    </label>

                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>

                    @if(request()->anyFilled(['search','estado','prioridad','orden','mias','desde','hasta']))
                        <a href="{{ route('incidencias.index') }}"
                           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times-circle"></i> Limpiar filtros
                        </a>
                    @endif
                </div>
            </form>

            {{-- ============================================================ --}}
            {{-- TABLA --}}
            {{-- ============================================================ --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if($incidencias->count() === 0)
                    <div class="text-center py-16 text-gray-400">
                        <i class="fas fa-inbox text-5xl mb-3"></i>
                        <p class="text-sm">No hay incidencias que coincidan con los filtros</p>
                        <a href="{{ route('incidencias.create') }}"
                           class="inline-block mt-4 text-blue-600 hover:underline text-sm">
                            Crear la primera incidencia →
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr class="text-left text-xs font-semibold text-gray-600 uppercase">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Título</th>
                                    <th class="px-4 py-3">Prioridad</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3">Reportado por</th>
                                    <th class="px-4 py-3">Asignado a</th>
                                    <th class="px-4 py-3">Fecha</th>
                                    <th class="px-4 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($incidencias as $inc)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 font-mono text-gray-500">#{{ $inc->id }}</td>

                                        <td class="px-4 py-3">
                                            <a href="{{ route('incidencias.show', $inc) }}"
                                               class="font-medium text-gray-800 hover:text-blue-600 transition">
                                                {{ $inc->titulo }}
                                            </a>
                                            <div class="text-xs text-gray-400 truncate max-w-xs">
                                                {{ Str::limit($inc->descripcion, 70) }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $inc->badge_prioridad }}">
                                                {{ ucfirst($inc->prioridad) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $inc->badge_estado }}">
                                                {{ ucfirst(str_replace('_', ' ', $inc->estado)) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $inc->user->name ?? '—' }}
                                        </td>

                                        <td class="px-4 py-3">
                                            @if($inc->asignado)
                                                <span class="inline-flex items-center gap-1.5 text-gray-600">
                                                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-semibold">
                                                        {{ strtoupper(substr($inc->asignado->name, 0, 1)) }}
                                                    </span>
                                                    {{ $inc->asignado->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">Sin asignar</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-xs text-gray-500">
                                            {{ $inc->fecha_reporte?->format('d/m/Y') }}
                                            <div class="text-gray-400">
                                                {{ $inc->fecha_reporte?->format('H:i') }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <div class="inline-flex gap-1">
                                                <a href="{{ route('incidencias.show', $inc) }}"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                   title="Ver">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                <a href="{{ route('incidencias.edit', $inc) }}"
                                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                                   title="Editar">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                                <form action="{{ route('incidencias.destroy', $inc) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Eliminar esta incidencia?');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($incidencias->hasPages())
                        <div class="px-4 py-3 border-t bg-gray-50">
                            {{ $incidencias->links() }}
                        </div>
                    @endif
                @endif
            </div>

            {{-- Info de resultados --}}
            @if($incidencias->count() > 0)
                <div class="text-xs text-gray-500 text-center">
                    Mostrando {{ $incidencias->firstItem() }} a {{ $incidencias->lastItem() }}
                    de {{ $incidencias->total() }} incidencias
                </div>
            @endif

        </div>
    </div>
</x-layout>