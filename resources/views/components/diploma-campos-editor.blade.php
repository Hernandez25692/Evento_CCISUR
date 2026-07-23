@props([
    'imageUrl',
    'fondoWidth' => null,
    'fondoHeight' => null,
    'campos',
    'etiquetas',
    'contenidos' => [],
    'firmas' => [],
    'fuentes' => [],
    'participantes' => [],
    'participanteInicial' => null,
    'saveUrl',
    'backUrl' => null,
])

<div
    x-data="{
        campos: @js($campos),
        etiquetas: @js($etiquetas),
        contenidos: @js($contenidos),
        firmas: @js($firmas),
        fuentes: @js($fuentes),
        participantes: @js($participantes),
        participanteId: @js($participanteInicial),
        fondoWidth: {{ $fondoWidth ?? 0 }},
        dragging: null,
        escala: 1,

        init() {
            this.actualizarEscala();
        },

        actualizarEscala() {
            if (!this.fondoWidth || !this.$refs.lienzo) {
                this.escala = 1;
                return;
            }
            const rect = this.$refs.lienzo.getBoundingClientRect();
            this.escala = rect.width / this.fondoWidth;
        },

        esFirma(clave) {
            return clave === 'firma_1' || clave === 'firma_2';
        },

        contenidoDe(clave) {
            if (clave === 'nombre' && Object.keys(this.participantes).length) {
                return this.participantes[this.participanteId] ?? Object.values(this.participantes)[0];
            }
            return this.contenidos[clave] ?? this.etiquetas[clave] ?? clave;
        },

        fuenteWebDe(clave) {
            const f = this.fuentes[this.campos[clave].font_family] ?? this.fuentes['visby-light'];
            return f ? f.web : 'inherit';
        },

        estiloBadge(clave) {
            const c = this.campos[clave];
            const tam = Math.max(7, Math.round((c.font_size || 16) * this.escala));
            return `left:${c.x}%; top:${c.y}%; font-size:${tam}px; font-family:${this.fuenteWebDe(clave)}; font-weight:${c.bold ? 'bold' : 'normal'}; text-decoration:${c.underline ? 'underline' : 'none'};`;
        },

        estiloFirmaImg() {
            return `height:${Math.max(20, Math.round(155 * this.escala))}px; object-fit:contain; display:block; margin:0 auto;`;
        },

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
    @resize.window="actualizarEscala()"
>
    <style>
        /* Alpine reemplaza (no fusiona) el atributo style cuando se usa
           :style con una cadena, por eso lo puramente estético/fijo va en
           clase y solo posición+tipografía se enlazan vía :style. */
        .campo-handle {
            position: absolute;
            transform: translate(-50%, -50%);
            cursor: move;
            touch-action: none;
            white-space: nowrap;
            text-align: center;
            border-radius: 4px;
            padding: 2px 6px;
            transition: background-color .1s;
        }

        .campo-handle.es-texto {
            background: rgba(255, 255, 255, .6);
            outline: 1px dashed rgba(37, 99, 235, .65);
        }

        .campo-handle.es-texto:hover {
            background: rgba(191, 219, 254, .85);
        }

        .campo-handle.oculto {
            opacity: .35;
        }

        .campo-handle .firma-nombre-preview {
            font-size: .85em;
        }

        .campo-handle .firma-sin-imagen {
            font-size: 11px;
            color: #666;
            background: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            outline: 1px dashed rgba(37, 99, 235, .65);
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
        <p class="text-muted mb-0" style="font-size: 13px; max-width: 520px;">
            Arrastra cada elemento sobre la imagen para ubicarlo. El texto y las firmas se muestran con su
            tipografía y tamaño reales.
        </p>
        <div class="d-flex gap-2">
            @if ($backUrl)
                <a href="{{ $backUrl }}" class="btn btn-outline-secondary btn-sm">Volver</a>
            @endif
            <button type="button" class="btn btn-primary btn-sm" @click="guardarPosiciones()">Guardar
                posiciones</button>
        </div>
    </div>

    @if (count($participantes))
        <div class="mb-3">
            <label class="form-label mb-1" style="font-size: 13px;">Previsualizar con el participante:</label>
            <select class="form-select form-select-sm" style="max-width: 340px;" x-model="participanteId">
                <template x-for="id in Object.keys(participantes)" :key="id">
                    <option :value="id" x-text="participantes[id]"></option>
                </template>
            </select>
        </div>
    @endif

    <div x-ref="lienzo" x-init="$nextTick(() => actualizarEscala())"
        style="position:relative; width:100%; max-width:900px; margin:0 auto; aspect-ratio:{{ $fondoWidth && $fondoHeight ? "{$fondoWidth}/{$fondoHeight}" : '16/11' }}; background:#e9ecef; border:1px solid #ccc; overflow:hidden; user-select:none;">
        <img src="{{ $imageUrl }}" draggable="false"
            style="width:100%; height:100%; object-fit:contain; display:block; pointer-events:none;"
            alt="Fondo de la plantilla">

        <template x-for="clave in Object.keys(campos)" :key="clave">
            <div class="campo-handle" :class="{ 'oculto': !campos[clave].visible, 'es-texto': !esFirma(clave) }"
                @pointerdown="iniciarArrastre(clave, $event)" @touchstart="iniciarArrastre(clave, $event)"
                :style="estiloBadge(clave)">

                <template x-if="!esFirma(clave)">
                    <span x-text="contenidoDe(clave)"></span>
                </template>

                <template x-if="esFirma(clave)">
                    <div>
                        <template x-if="firmas[clave] && firmas[clave].url">
                            <img :src="firmas[clave].url" draggable="false" :style="estiloFirmaImg()">
                        </template>
                        <template x-if="!firmas[clave] || !firmas[clave].url">
                            <div class="firma-sin-imagen" x-text="'(' + etiquetas[clave] + ')'"></div>
                        </template>
                        <div class="firma-nombre-preview"
                            x-text="(firmas[clave] && firmas[clave].nombre) || 'Nombre del firmante'"></div>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <div class="row mt-4 g-3">
        <template x-for="clave in Object.keys(campos)" :key="'panel-' + clave">
            <div class="col-md-4">
                <div class="border rounded p-2" :class="{ 'bg-light': !campos[clave].visible }">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="fw-bold mb-0" style="font-size:13px;" x-text="etiquetas[clave] ?? clave"></p>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                x-model="campos[clave].visible" :id="'visible-' + clave">
                            <label class="form-check-label" style="font-size:11px;" :for="'visible-' + clave">
                                Mostrar
                            </label>
                        </div>
                    </div>

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
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <label style="font-size:11px; width:55px;">Tamaño</label>
                        <input type="number" min="8" max="80" step="1" class="form-control form-control-sm"
                            x-model.number="campos[clave].font_size">
                    </div>

                    <div class="mb-2">
                        <label style="font-size:11px;">Fuente</label>
                        <select class="form-select form-select-sm" x-model="campos[clave].font_family"
                            x-init="$nextTick(() => { $el.value = campos[clave].font_family })">
                            <template x-for="fclave in Object.keys(fuentes)" :key="fclave">
                                <option :value="fclave" x-text="fuentes[fclave].label"></option>
                            </template>
                        </select>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" x-model="campos[clave].bold"
                                :id="'bold-' + clave">
                            <label class="form-check-label" style="font-size:12px;" :for="'bold-' + clave">
                                Negrita
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" x-model="campos[clave].underline"
                                :id="'underline-' + clave">
                            <label class="form-check-label" style="font-size:12px;" :for="'underline-' + clave">
                                Subrayado
                            </label>
                        </div>
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
