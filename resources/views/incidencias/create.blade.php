<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pizarra de Actividades ITS-VENRUNEFT') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-blue-600"></i>
                        Pizarra de Actividades ITS-VENRUNEFT
                    </h1>
                    <p class="text-sm text-gray-500">
                        Publica una nueva actividad o incidencia
                    </p>
                </div>
                <a href="{{ route('incidencias.index') }}"
                   class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i> Volver a la pizarra
                </a>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg">
                    <div class="flex items-center gap-2 font-semibold mb-1">
                        <i class="fas fa-exclamation-circle"></i> Revisa los errores:
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('incidencias.store') }}"
                  class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                @csrf

                {{-- Título --}}
                <div>
                    <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Título de la actividad <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}"
                           placeholder="Ej. Reemplazo de switch en piso 3"
                           required
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                              placeholder="Detalla la actividad, hallazgos y pasos a seguir..."
                              class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm resize-none">{{ old('descripcion') }}</textarea>
                </div>

                {{-- Categoría + Prioridad --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="categoria" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Categoría <span class="text-red-500">*</span>
                        </label>
                        <select name="categoria" id="categoria" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <option value="">— Selecciona —</option>
                            @foreach($categorias as $key => $label)
                                <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="prioridad" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Prioridad <span class="text-red-500">*</span>
                        </label>
                        <select name="prioridad" id="prioridad" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <option value="">— Selecciona —</option>
                            <option value="critica" {{ old('prioridad') == 'critica' ? 'selected' : '' }}>🔴 Crítica</option>
                            <option value="alta"    {{ old('prioridad') == 'alta'    ? 'selected' : '' }}>🟠 Alta</option>
                            <option value="media"   {{ old('prioridad') == 'media'   ? 'selected' : '' }}>🔵 Media</option>
                            <option value="baja"    {{ old('prioridad') == 'baja'    ? 'selected' : '' }}>⚪ Baja</option>
                        </select>
                    </div>
                </div>

                {{-- Estado + Asignado --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="estado" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select name="estado" id="estado" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <option value="">— Selecciona —</option>
                            <option value="abierta"     {{ old('estado') == 'abierta'     ? 'selected' : '' }}>🟢 Abierta</option>
                            <option value="en_proceso"  {{ old('estado') == 'en_proceso'  ? 'selected' : '' }}>🔄 En proceso</option>
                            <option value="en_revision" {{ old('estado') == 'en_revision' ? 'selected' : '' }}>🔍 En revisión</option>
                            <option value="finalizada"  {{ old('estado') == 'finalizada'  ? 'selected' : '' }}>✅ Finalizada</option>
                            <option value="cerrada"     {{ old('estado') == 'cerrada'     ? 'selected' : '' }}>🔒 Cerrada</option>
                        </select>
                    </div>

                    <div>
                        <label for="asignado_a" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Asignado a <span class="text-red-500">*</span>
                        </label>
                        <select name="asignado_a" id="asignado_a" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <option value="">— Selecciona un responsable —</option>
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id }}" {{ old('asignado_a') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Visibilidad + Fecha límite --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="visibilidad" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Visibilidad <span class="text-red-500">*</span>
                        </label>
                        <select name="visibilidad" id="visibilidad" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <option value="publica"   {{ old('visibilidad') == 'publica'   ? 'selected' : '' }}>🌐 Pública</option>
                            <option value="privada"   {{ old('visibilidad') == 'privada'   ? 'selected' : '' }}>🔒 Privada</option>
                            <option value="compartida"{{ old('visibilidad') == 'compartida'? 'selected' : '' }}>👥 Compartida</option>
                        </select>
                    </div>

                    <div>
                        <label for="fecha_limite" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Fecha límite
                        </label>
                        <input type="date" name="fecha_limite" id="fecha_limite"
                               value="{{ old('fecha_limite') }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                </div>

                {{-- BARRA DE PROGRESO --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="progreso" class="block text-sm font-semibold text-gray-700">
                            Progreso <span class="text-red-500">*</span>
                        </label>
                        <span id="progresoValor"
                              class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                            {{ old('progreso', 0) }}%
                        </span>
                    </div>

                    <input type="range" name="progreso" id="progreso"
                           min="0" max="100" step="5"
                           value="{{ old('progreso', 0) }}"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">

                    <div class="mt-3 w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div id="progresoBarra"
                             class="h-3 rounded-full transition-all duration-300 bg-blue-500"
                             style="width: {{ old('progreso', 0) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>0%</span><span>50%</span><span>100%</span>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('incidencias.index') }}"
                       class="w-full sm:w-auto text-center px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-sm">
                        <i class="fas fa-save"></i> Publicar en la Pizarra
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('progreso');
            const valor  = document.getElementById('progresoValor');
            const barra  = document.getElementById('progresoBarra');

            const pintar = (v) => {
                valor.textContent = v + '%';
                barra.style.width = v + '%';

                let color = 'bg-blue-500';
                let chip  = 'bg-blue-100 text-blue-700';

                if (v >= 100)      { color = 'bg-green-500';  chip = 'bg-green-100 text-green-700'; }
                else if (v >= 75)  { color = 'bg-teal-500';   chip = 'bg-teal-100 text-teal-700'; }
                else if (v >= 50)  { color = 'bg-amber-500';  chip = 'bg-amber-100 text-amber-700'; }
                else if (v >= 25)  { color = 'bg-orange-500'; chip = 'bg-orange-100 text-orange-700'; }
                else if (v > 0)    { color = 'bg-red-500';    chip = 'bg-red-100 text-red-700'; }

                barra.className = 'h-3 rounded-full transition-all duration-300 ' + color;
                valor.className = 'inline-flex px-2.5 py-1 rounded-full text-xs font-bold ' + chip;
            };

            slider.addEventListener('input', (e) => pintar(e.target.value));
            pintar(slider.value);
        });
    </script>
</x-layout>