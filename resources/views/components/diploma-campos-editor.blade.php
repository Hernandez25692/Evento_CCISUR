@props([
    'imageUrl',
    'fondoWidth' => null,
    'fondoHeight' => null,
    'campos',
    'etiquetas',
    'saveUrl',
    'backUrl' => null,
])

<div
    x-data="{
        campos: @js($campos),
        etiquetas: @js($etiquetas),
        dragging: null,

        iniciarArrastre(clave, event) {
            this.dragging = clave;
            event.preventDefault();
        },

        posicionDesdeEvento(event) {
            const rect = this.$refs.lienzo.getBoundingClientRect();
            const punto = event.touches ? event.touches[0] : event;
            let x = ((punto.clientX - rect.left) / rect.width) * 100;
            let y = ((punto.clientY - rect.top) / rect.height) * 100;
            return {
                x: Math.max(0, Math.min(100, Math.round(x * 10) / 10)),
                y: Math.max(0, Math.min(100, Math.round(y * 10) / 10)),
            };
        },

        mover(event) {
            if (!this.dragging) return;
            const punto = this.posicionDesdeEvento(event);
            this.campos[this.dragging].x = punto.x;
            this.campos[this.dragging].y = punto.y;
        },

        soltar() {
            this.dragging = null;
        },

        guardarPosiciones() {
            this.$refs.inputCampos.value = JSON.stringify(this.campos);
            this.$refs.formulario.requestSubmit();
        },
    }"
    @pointermove.window="mover($event)"
    @pointerup.window="soltar()"
    @touchmove.window="mover($event)"
    @touchend.window="soltar()"
>
    <style>
        /* Nota: Alpine reemplaza (no fusiona) el atributo style cuando se usa
           :style con una cadena, por eso lo fijo va en clase y solo left/top
           se enlazan dinámicamente vía :style en el elemento. */
        .campo-handle {
            position: absolute;
            transform: translate(-50%, -50%);
            cursor: move;
            background: rgba(37, 99, 235, .88);
            color: #fff;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .4);
            touch-action: none;
        }
    </style>
    @unless($fondoWidth && $fondoHeight)
        <div class="alert alert-warning py-2 px-3" style="font-size: 13px;">
            Esta plantilla no tiene registradas las dimensiones reales de la imagen de fondo (es una plantilla
            antigua). Las posiciones se guardarán igual, pero para que coincidan exactamente con el diploma final
            te recomendamos volver a guardar la plantilla (re-subir el mismo fondo) antes de ajustar posiciones.
        </div>
    @endunless

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <p class="text-muted mb-0" style="font-size: 13px;">
            Arrastra cada etiqueta sobre la imagen para indicar dónde va cada dato, o ajusta los valores manualmente
            abajo.
        </p>
        <div class="d-flex gap-2">
            @if ($backUrl)
                <a href="{{ $backUrl }}" class="btn btn-outline-secondary btn-sm">Volver</a>
            @endif
            <button type="button" class="btn btn-primary btn-sm" @click="guardarPosiciones()">Guardar
                posiciones</button>
        </div>
    </div>

    <div x-ref="lienzo"
        style="position:relative; width:100%; max-width:900px; margin:0 auto; aspect-ratio:{{ $fondoWidth && $fondoHeight ? "{$fondoWidth}/{$fondoHeight}" : '16/11' }}; background:#e9ecef; border:1px solid #ccc; overflow:hidden; user-select:none;">
        <img src="{{ $imageUrl }}"
            style="width:100%; height:100%; object-fit:contain; display:block; pointer-events:none;"
            alt="Fondo de la plantilla">

        <template x-for="clave in Object.keys(campos)" :key="clave">
            <div class="campo-handle" @pointerdown="iniciarArrastre(clave, $event)"
                @touchstart="iniciarArrastre(clave, $event)"
                :style="`left:${campos[clave].x}%; top:${campos[clave].y}%;`" x-text="etiquetas[clave] ?? clave">
            </div>
        </template>
    </div>

    <div class="row mt-4 g-3">
        <template x-for="clave in Object.keys(campos)" :key="'panel-' + clave">
            <div class="col-md-4">
                <div class="border rounded p-2">
                    <p class="fw-bold mb-2" style="font-size:13px;" x-text="etiquetas[clave] ?? clave"></p>
                    <div class="d-flex gap-2 align-items-center mb-1">
                        <label style="font-size:11px; width:55px;">X %</label>
                        <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm"
                            x-model.number="campos[clave].x">
                    </div>
                    <div class="d-flex gap-2 align-items-center mb-1">
                        <label style="font-size:11px; width:55px;">Y %</label>
                        <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm"
                            x-model.number="campos[clave].y">
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <label style="font-size:11px; width:55px;">Tamaño</label>
                        <input type="number" min="8" max="80" step="1" class="form-control form-control-sm"
                            x-model.number="campos[clave].font_size">
                    </div>
                </div>
            </div>
        </template>
    </div>

    <form x-ref="formulario" method="POST" action="{{ $saveUrl }}" class="d-none">
        @csrf
        <input type="hidden" name="campos" x-ref="inputCampos">
    </form>
</div>
