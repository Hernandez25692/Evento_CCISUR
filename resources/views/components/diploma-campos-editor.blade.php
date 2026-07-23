@props([
    'imageUrl',
    'fondoWidth' => null,
    'fondoHeight' => null,
    'campos',
    'etiquetas',
    'contenidos' => [],
    'firmas' => [],
    'fuentes' => [],
    'defaults' => [],
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
        defaults: @js($defaults),
        participantes: @js($participantes),
        participanteId: @js($participanteInicial),
        fondoWidth: {{ $fondoWidth ?? 0 }},
        dragging: null,
        seleccionado: null,
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
            return `left:${c.x}%; top:${c.y}%; font-size:${tam}px; font-family:${this.fuenteWebDe(clave)}; font-weight:${c.bold ? 'bold' : 'normal'}; text-decoration:${c.underline ? 'underline' : 'none'}; color:${c.color};`;
        },

        estiloFirmaImg() {
            return `height:${Math.max(20, Math.round(155 * this.escala))}px; object-fit:contain; display:block; margin:0 auto;`;
        },

        estiloPopover() {
            if (!this.seleccionado || !this.$refs.lienzo) return '';
            const rect = this.$refs.lienzo.getBoundingClientRect();
            const c = this.campos[this.seleccionado];
            const anchoPop = 270;
            const altoPop = 400;
            let x = (c.x / 100) * rect.width + 18;
            let y = (c.y / 100) * rect.height - 20;
            if (x + anchoPop > rect.width) x = (c.x / 100) * rect.width - anchoPop - 18;
            if (x < 4) x = 4;
            if (y + altoPop > rect.height) y = rect.height - altoPop - 4;
            if (y < 4) y = 4;
            return `left:${Math.round(x)}px; top:${Math.round(y)}px;`;
        },

        seleccionar(clave) {
            this.seleccionado = clave;
        },

        restablecerCampo() {
            if (!this.seleccionado) return;
            this.campos[this.seleccionado] = { ...this.defaults[this.seleccionado] };
        },

        moverConTeclado(event) {
            if (!this.seleccionado) return;
            const activo = document.activeElement;
            if (activo && ['INPUT', 'SELECT', 'TEXTAREA'].includes(activo.tagName)) return;

            const paso = event.shiftKey ? 1 : 0.1;
            let dx = 0, dy = 0;
            if (event.key === 'ArrowLeft') dx = -paso;
            else if (event.key === 'ArrowRight') dx = paso;
            else if (event.key === 'ArrowUp') dy = -paso;
            else if (event.key === 'ArrowDown') dy = paso;
            else return;

            event.preventDefault();
            const c = this.campos[this.seleccionado];
            c.x = Math.max(0, Math.min(100, Math.round((c.x + dx) * 10) / 10));
            c.y = Math.max(0, Math.min(100, Math.round((c.y + dy) * 10) / 10));
        },

        iniciarArrastre(clave, event) {
            this.dragging = clave;
            this.seleccionado = clave;
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
    @keydown.window="moverConTeclado($event)"
    @keydown.escape.window="seleccionado = null"
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

        .campo-handle.seleccionado {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
            z-index: 10;
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

        .panel-flotante {
            position: absolute;
            width: 270px;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .2);
            padding: 12px;
            z-index: 50;
            font-size: 12px;
        }

        .campo-chip.oculto-chip {
            opacity: .55;
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
            Haz clic en un elemento para editar sus opciones, o arrástralo para moverlo. Usa las flechas del teclado
            para ajustes finos.
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

    <div x-ref="lienzo" x-init="$nextTick(() => actualizarEscala())" @click="seleccionado = null"
        style="position:relative; width:100%; max-width:900px; margin:0 auto; aspect-ratio:{{ $fondoWidth && $fondoHeight ? "{$fondoWidth}/{$fondoHeight}" : '16/11' }}; background:#e9ecef; border:1px solid #ccc; overflow:hidden; user-select:none;">
        <img src="{{ $imageUrl }}" draggable="false"
            style="width:100%; height:100%; object-fit:contain; display:block; pointer-events:none;"
            alt="Fondo de la plantilla">

        <template x-for="clave in Object.keys(campos)" :key="clave">
            <div class="campo-handle"
                :class="{ 'oculto': !campos[clave].visible, 'es-texto': !esFirma(clave), 'seleccionado': seleccionado === clave }"
                @pointerdown="iniciarArrastre(clave, $event)" @touchstart="iniciarArrastre(clave, $event)"
                @click.stop :style="estiloBadge(clave)">

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

        <template x-if="seleccionado">
            <div class="panel-flotante" :style="estiloPopover()" @click.stop>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong x-text="etiquetas[seleccionado]"></strong>
                    <button type="button" class="btn-close" style="font-size:10px;" @click="seleccionado = null"
                        aria-label="Cerrar"></button>
                </div>

                <div class="d-flex gap-2 mb-2">
                    <div class="flex-fill">
                        <label class="d-block" style="font-size:10px;">X %</label>
                        <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].x">
                    </div>
                    <div class="flex-fill">
                        <label class="d-block" style="font-size:10px;">Y %</label>
                        <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].y">
                    </div>
                    <div class="flex-fill">
                        <label class="d-block" style="font-size:10px;">Tamaño</label>
                        <input type="number" min="8" max="80" step="1" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].font_size">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="d-block" style="font-size:10px;">Fuente</label>
                    <select class="form-select form-select-sm" x-model="campos[seleccionado].font_family"
                        x-init="$nextTick(() => { if (seleccionado) $el.value = campos[seleccionado].font_family })">
                        <template x-for="fclave in Object.keys(fuentes)" :key="fclave">
                            <option :value="fclave" x-text="fuentes[fclave].label"></option>
                        </template>
                    </select>
                </div>

                <div class="d-flex gap-3 align-items-end mb-2">
                    <div>
                        <label class="d-block" style="font-size:10px;">Color</label>
                        <input type="color" class="form-control form-control-color form-control-sm"
                            x-model="campos[seleccionado].color" style="width:44px; height:31px; padding:2px;">
                    </div>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Alineación">
                        <button type="button" class="btn btn-outline-secondary"
                            :class="{ active: campos[seleccionado].align === 'left' }"
                            @click="campos[seleccionado].align = 'left'" title="Izquierda">
                            <i class="fas fa-align-left"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary"
                            :class="{ active: campos[seleccionado].align === 'center' }"
                            @click="campos[seleccionado].align = 'center'" title="Centro">
                            <i class="fas fa-align-center"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary"
                            :class="{ active: campos[seleccionado].align === 'right' }"
                            @click="campos[seleccionado].align = 'right'" title="Derecha">
                            <i class="fas fa-align-right"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" x-model="campos[seleccionado].bold"
                            id="pop-bold">
                        <label class="form-check-label" for="pop-bold">Negrita</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" x-model="campos[seleccionado].underline"
                            id="pop-underline">
                        <label class="form-check-label" for="pop-underline">Subrayado</label>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch"
                        x-model="campos[seleccionado].visible" id="pop-visible">
                    <label class="form-check-label" for="pop-visible">Mostrar</label>
                </div>

                <button type="button" class="btn btn-outline-danger btn-sm w-100" @click="restablecerCampo()">
                    Restablecer este campo
                </button>
            </div>
        </template>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-3">
        <template x-for="clave in Object.keys(campos)" :key="'chip-' + clave">
            <button type="button" class="btn btn-sm campo-chip" :class="[
                seleccionado === clave ? 'btn-primary' : 'btn-outline-secondary',
                !campos[clave].visible ? 'oculto-chip' : '',
            ]" @click="seleccionar(clave)">
                <i class="fas fa-eye-slash me-1" x-show="!campos[clave].visible"></i>
                <span x-text="etiquetas[clave]"></span>
            </button>
        </template>
    </div>

    <form x-ref="formulario" method="POST" action="{{ $saveUrl }}" class="d-none">
        @csrf
        <input type="hidden" name="campos" x-ref="inputCampos">
    </form>
</div>
