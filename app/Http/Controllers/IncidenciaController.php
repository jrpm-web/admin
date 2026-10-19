<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\IncidenciaComentario;
use App\Models\IncidenciaHistorial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class IncidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // =====================================================
        // QUERY BASE con relaciones eager-loaded
        // =====================================================
        $query = Incidencia::with([
            'user:id,name,email',
            'asignado:id,name,email',
        ]);

        // =====================================================
        // VISIBILIDAD SEGÚN ROL
        // =====================================================
        if ($user->rol === 'super-admin') {
            // Ve absolutamente todas, sin filtro
        } elseif ($user->rol === 'padre') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)          // las que él creó
                  ->orWhere('asignado_a', $user->id)     // las que le asignaron
                  ->orWhere('asignado_por', $user->id)   // las que él asignó
                  ->orWhere('visibilidad', 'publica')    // públicas
                  ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
            });
        } else {
            // Usuario normal
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('asignado_a', $user->id)
                  ->orWhere('visibilidad', 'publica')
                  ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
            });
        }

        // =====================================================
        // FILTROS OPCIONALES
        // =====================================================

        // Filtro por estado (activas / inactivas / específico)
        if ($request->filled('estado')) {
            if ($request->estado === 'activas') {
                $query->whereIn('estado', ['abierta', 'en_proceso', 'en_revision']);
            } elseif ($request->estado === 'inactivas') {
                $query->whereIn('estado', ['finalizada', 'cerrada']);
            } else {
                $query->where('estado', $request->estado);
            }
        }

        // Filtro por prioridad
        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por rango de fechas
        if ($request->filled('desde')) {
            $query->whereDate('fecha_reporte', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha_reporte', '<=', $request->hasta);
        }

        // Búsqueda libre (título + descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        // Filtro "solo las mías"
        if ($request->boolean('mias')) {
            $query->where('user_id', $user->id);
        }

        // =====================================================
        // ORDEN
        // =====================================================
        $orden = $request->get('orden', 'recientes');
        match ($orden) {
            'antiguas'   => $query->oldest('fecha_reporte'),
            'prioridad'  => $query->orderByRaw("FIELD(prioridad, 'critica', 'alta', 'media', 'baja')"),
            'estado'     => $query->orderBy('estado'),
            default      => $query->latest('fecha_reporte'),
        };

        // =====================================================
        // PAGINACIÓN
        // =====================================================
        $incidencias = $query->paginate(15)->withQueryString();

        // =====================================================
        // CONTADORES PARA LOS CARDS DE RESUMEN
        // =====================================================
        $contadores = [
            'total'       => Incidencia::when($user->rol !== 'super-admin', function ($q) use ($user) {
                                $q->where(function ($sub) use ($user) {
                                    $sub->where('user_id', $user->id)
                                        ->orWhere('asignado_a', $user->id)
                                        ->orWhere('visibilidad', 'publica')
                                        ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
                                });
                            })->count(),
            'abiertas'    => Incidencia::when($user->rol !== 'super-admin', function ($q) use ($user) {
                                $q->where(function ($sub) use ($user) {
                                    $sub->where('user_id', $user->id)
                                        ->orWhere('asignado_a', $user->id)
                                        ->orWhere('visibilidad', 'publica')
                                        ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
                                });
                            })->where('estado', 'abierta')->count(),
            'en_proceso'  => Incidencia::when($user->rol !== 'super-admin', function ($q) use ($user) {
                                $q->where(function ($sub) use ($user) {
                                    $sub->where('user_id', $user->id)
                                        ->orWhere('asignado_a', $user->id)
                                        ->orWhere('visibilidad', 'publica')
                                        ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
                                });
                            })->where('estado', 'en_proceso')->count(),
            'finalizadas' => Incidencia::when($user->rol !== 'super-admin', function ($q) use ($user) {
                                $q->where(function ($sub) use ($user) {
                                    $sub->where('user_id', $user->id)
                                        ->orWhere('asignado_a', $user->id)
                                        ->orWhere('visibilidad', 'publica')
                                        ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
                                });
                            })->whereIn('estado', ['finalizada', 'cerrada'])->count(),
            'criticas'    => Incidencia::when($user->rol !== 'super-admin', function ($q) use ($user) {
                                $q->where(function ($sub) use ($user) {
                                    $sub->where('user_id', $user->id)
                                        ->orWhere('asignado_a', $user->id)
                                        ->orWhere('visibilidad', 'publica')
                                        ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
                                });
                            })->where('prioridad', 'critica')
                              ->whereIn('estado', ['abierta', 'en_proceso', 'en_revision'])
                              ->count(),
        ];

        // =====================================================
        // RETURN A LA VISTA BLADE
        // =====================================================
        return view('incidencias.index', compact('incidencias', 'contadores'));
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        // Incidencias disponibles (puedes filtrar por activas si lo deseas)
        $usuarios = User::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        // Categorías disponibles (ajusta según tu catálogo real)
        $categorias = [
            'hardware'   => 'Hardware',
            'software'   => 'Software',
            'red'        => 'Red / Conectividad',
            'seguridad'  => 'Seguridad',
            'mantenimiento' => 'Mantenimiento',
            'otro'       => 'Otro',
        ];
        return view('incidencias.create', compact('usuarios', 'categorias'));
    }

    /**
     * Guarda una nueva actividad en la pizarra.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'categoria'     => 'required|string|max:100',
            'prioridad'     => 'required|in:critica,alta,media,baja',
            'estado'        => 'required|in:abierta,en_proceso,en_revision,finalizada,cerrada',
            'progreso'      => 'required|integer|min:0|max:100',
            'asignado_a'    => 'required|exists:users,id',
            'visibilidad'   => 'required|in:publica,privada,compartida',
            'fecha_limite'  => 'nullable|date|after_or_equal:today',
        ]);

        // Metadatos que no vienen del formulario
        $data['user_id']          = Auth::id();          // quién reporta
        $data['asignado_por']     = Auth::id();          // quién asigna
        $data['fecha_reporte']    = now();
        $data['fecha_asignacion'] = now();
        $data['vistas']           = 0;

        // Si ya se marca como finalizada o cerrada, registrar cierre
        if (in_array($data['estado'], ['finalizada', 'cerrada'])) {
            $data['fecha_cierre'] = now();
            $data['progreso']     = 100;
        }

        $incidencia = Incidencia::create($data);

        return redirect()
            ->route('incidencias.index')
            ->with('success', "Actividad #{$incidencia->id} publicada en la pizarra ITS-VENRUNEFT.");
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
