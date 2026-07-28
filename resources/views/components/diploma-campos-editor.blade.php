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
    'qrPreview' => null,
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
        qrPreview: @js($qrPreview),
        fondoWidth: {{ $fondoWidth ?? 0 }},
        dragging: null,
        seleccionado: null,
        escala: 1,
        panelPos: null,
        arrastrandoPanel: false,
        offsetArrastrePanel: { x: 0, y: 0 },

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

        esQr(clave) {
            return clave === 'qr_verificacion';
        },

        esEditable(clave) {
            return clave !== 'nombre' && !this.esFirma(clave) && !this.esQr(clave);
        },

        // Distinto de esEditable: el nombre no tiene texto libre (siempre es
        // el del participante), pero sí es un campo de texto al que le
        // aplican interlineado, ancho máximo, espaciado, cursiva, rotación y
        // salto de línea manual igual que a cualquier otro.
        // Excluye firma y QR: son los únicos 7 campos donde interlineado,
        // ancho máximo (%) y salto de línea manual tienen sentido (el ancho
        // de la firma es fijo en px, no en %, y el QR es una imagen).
        esTexto(clave) {
            return !this.esFirma(clave) && !this.esQr(clave);
        },

        // Espejo de DiplomaCamposService::conSaltoLinea() en PHP: inserta un
        // salto de línea real después de la palabra N para que el editor
        // muestre exactamente el mismo partido de línea que el PDF final.
        conSaltoLinea(texto, indice) {
            if (!indice || indice <= 0) return texto;
            const palabras = String(texto).trim().split(/\s+/);
            if (indice >= palabras.length) return texto;
            return palabras.slice(0, indice).join(' ') + '\n' + palabras.slice(indice).join(' ');
        },

        contenidoDe(clave) {
            let contenido;
            if (clave === 'nombre' && Object.keys(this.participantes).length) {
                contenido = this.participantes[this.participanteId] ?? Object.values(this.participantes)[0];
            } else if (this.esEditable(clave) && this.campos[clave].texto) {
                contenido = this.campos[clave].texto;
            } else {
                contenido = this.contenidos[clave] ?? this.etiquetas[clave] ?? clave;
            }
            return this.esTexto(clave)
                ? this.conSaltoLinea(contenido, this.campos[clave].salto_linea_palabra)
                : contenido;
        },

        fuenteWebDe(clave) {
            const f = this.fuentes[this.campos[clave].font_family] ?? this.fuentes['visby-light'];
            return f ? f.web : 'inherit';
        },

        estiloBadge(clave) {
            const c = this.campos[clave];
            const tam = Math.max(7, Math.round((c.font_size || 16) * this.escala));
            let base = `left:${c.x}%; top:${c.y}%; font-size:${tam}px; font-family:${this.fuenteWebDe(clave)}; font-weight:${c.bold ? 'bold' : 'normal'}; text-decoration:${c.underline ? 'underline' : 'none'}; color:${c.color}; text-align:${c.align || 'center'};`;
            // Mismo max-width/interlineado/espaciado/rotación que usa el PDF
            // (pdf/diplomas.blade.php $estiloCampo) para que el editor
            // muestre el texto igual que el resultado final.
            if (this.esFirma(clave)) {
                base += `width:${Math.round(220 * this.escala)}px;`;
            } else if (this.esQr(clave)) {
                const lado = Math.max(16, Math.round((c.font_size || 90) * this.escala));
                base += `width:${lado}px; height:${lado}px;`;
            } else {
                // letter-spacing es un valor absoluto en px (a diferencia de
                // line-height/max-width, que son relativos), así que hay que
                // escalarlo igual que font-size para que se vea proporcional
                // en el lienzo reducido del editor.
                const espaciado = Math.round(c.letter_spacing * this.escala * 10) / 10;
                base += `max-width:${c.max_width}%; white-space:pre-line; line-height:${c.line_height}; letter-spacing:${espaciado}px; font-style:${c.italic ? 'italic' : 'normal'}; transform:translate(-50%,-50%) rotate(${c.rotacion}deg);`;
            }
            return base;
        },

        estiloFirmaImg() {
            return `height:${Math.max(20, Math.round(155 * this.escala))}px; object-fit:contain; display:block; margin:0 auto;`;
        },

        // Mismos límites que la CSS de .panel-flotante (min(270px, 88vw) /
        // min(480px, 80vh)), para que el panel nunca se calcule fuera del
        // lienzo ni se salga de la pantalla en móvil.
        dimensionesPopover() {
            return {
                ancho: Math.min(270, window.innerWidth * 0.88),
                alto: Math.min(480, window.innerHeight * 0.8),
            };
        },

        posicionAutoPopover() {
            const rect = this.$refs.lienzo.getBoundingClientRect();
            const c = this.campos[this.seleccionado];
            const { ancho: anchoPop, alto: altoPop } = this.dimensionesPopover();
            let x = (c.x / 100) * rect.width + 18;
            let y = (c.y / 100) * rect.height - 20;
            if (x + anchoPop > rect.width) x = (c.x / 100) * rect.width - anchoPop - 18;
            if (x < 4) x = 4;
            if (y + altoPop > rect.height) y = rect.height - altoPop - 4;
            if (y < 4) y = 4;
            return { x, y };
        },

        estiloPopover() {
            if (!this.seleccionado || !this.$refs.lienzo) return '';
            const pos = this.panelPos ?? this.posicionAutoPopover();
            return `left:${Math.round(pos.x)}px; top:${Math.round(pos.y)}px;`;
        },

        activarSeleccion(clave) {
            if (this.seleccionado !== clave) {
                this.panelPos = null;
            }
            this.seleccionado = clave;
        },

        seleccionar(clave) {
            this.activarSeleccion(clave);
        },

        iniciarArrastrePanel(event) {
            event.preventDefault();
            if (!this.$refs.lienzo) return;
            const rect = this.$refs.lienzo.getBoundingClientRect();
            const actual = this.panelPos ?? this.posicionAutoPopover();
            const punto = event.touches ? event.touches[0] : event;
            this.offsetArrastrePanel = {
                x: punto.clientX - rect.left - actual.x,
                y: punto.clientY - rect.top - actual.y,
            };
            this.panelPos = actual;
            this.arrastrandoPanel = true;
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
            this.activarSeleccion(clave);
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
            if (this.arrastrandoPanel) {
                const rect = this.$refs.lienzo.getBoundingClientRect();
                const punto = event.touches ? event.touches[0] : event;
                const { ancho: anchoPop, alto: altoPop } = this.dimensionesPopover();
                let x = punto.clientX - rect.left - this.offsetArrastrePanel.x;
                let y = punto.clientY - rect.top - this.offsetArrastrePanel.y;
                x = Math.max(0, Math.min(Math.max(0, rect.width - anchoPop), x));
                y = Math.max(0, Math.min(Math.max(0, rect.height - altoPop), y));
                this.panelPos = { x, y };
                return;
            }
            if (!this.dragging) return;
            const punto = this.posicionDesdeEvento(event);
            this.campos[this.dragging].x = punto.x;
            this.campos[this.dragging].y = punto.y;
        },

        soltar() {
            this.dragging = null;
            this.arrastrandoPanel = false;
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
            border-radius: 3px;
            padding: 0 2px;
            transition: background-color .1s, outline-color .1s;
        }

        /* Sin relleno ni fondo permanentes: a tamaños de fuente grandes, un
           fondo blanco semitransparente constante tapaba buena parte del
           diseño de la plantilla. El contorno punteado, fino y discreto,
           basta para ubicar el campo sin ocultar lo que hay debajo. */
        .campo-handle.es-texto {
            outline: 1px dashed rgba(37, 99, 235, .45);
        }

        .campo-handle.es-texto:hover {
            outline-color: rgba(37, 99, 235, .9);
            background: rgba(191, 219, 254, .3);
        }

        .campo-handle.oculto {
            opacity: .35;
        }

        .campo-handle.es-qr {
            outline: 1px dashed rgba(37, 99, 235, .65);
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
            width: min(270px, 88vw);
            max-height: min(480px, 80vh);
            overflow-y: auto;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .2);
            padding: 12px;
            z-index: 50;
            font-size: 12px;
        }

        .panel-flotante .form-label-sm {
            display: block;
            font-size: 10px;
            color: #6c757d;
            margin-bottom: 2px;
        }

        @media (max-width: 480px) {
            .panel-flotante {
                font-size: 13px;
            }
        }

        .panel-header {
            cursor: move;
            touch-action: none;
            user-select: none;
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
                :class="{ 'oculto': !campos[clave].visible, 'es-texto': !esFirma(clave) && !esQr(clave), 'es-qr': esQr(clave), 'seleccionado': seleccionado === clave }"
                @pointerdown="iniciarArrastre(clave, $event)" @touchstart="iniciarArrastre(clave, $event)"
                @click.stop :style="estiloBadge(clave)">

                <template x-if="!esFirma(clave) && !esQr(clave)">
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

                <template x-if="esQr(clave)">
                    <img :src="qrPreview" draggable="false" style="width:100%; height:100%; display:block;"
                        alt="Vista previa del QR de verificación">
                </template>
            </div>
        </template>

        <template x-if="seleccionado">
            <div class="panel-flotante" :style="estiloPopover()" @click.stop>
                <div class="d-flex justify-content-between align-items-center mb-2 panel-header"
                    @pointerdown="iniciarArrastrePanel($event)" @touchstart="iniciarArrastrePanel($event)">
                    <span>
                        <i class="fas fa-grip-lines text-muted me-1" style="font-size:11px;"></i>
                        <strong x-text="etiquetas[seleccionado]"></strong>
                    </span>
                    <button type="button" class="btn-close" style="font-size:10px;" @pointerdown.stop
                        @click="seleccionado = null" aria-label="Cerrar"></button>
                </div>

                <template x-if="esEditable(seleccionado)">
                    <div class="mb-2">
                        <label class="d-block" style="font-size:10px;">Texto (vacío = automático)</label>
                        <textarea class="form-control form-control-sm" rows="2"
                            :placeholder="contenidos[seleccionado] ?? ''"
                            x-model="campos[seleccionado].texto"></textarea>
                    </div>
                </template>

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
                        <input type="number" min="8" max="200" step="1" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].font_size">
                    </div>
                </div>

                <div class="mb-2" x-show="!esQr(seleccionado)">
                    <label class="d-block" style="font-size:10px;">Fuente</label>
                    <select class="form-select form-select-sm" x-model="campos[seleccionado].font_family"
                        x-init="$nextTick(() => { if (seleccionado) $el.value = campos[seleccionado].font_family })">
                        <template x-for="fclave in Object.keys(fuentes)" :key="fclave">
                            <option :value="fclave" x-text="fuentes[fclave].label"></option>
                        </template>
                    </select>
                </div>

                <div class="d-flex gap-3 align-items-end mb-2" x-show="!esQr(seleccionado)">
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

                <div class="d-flex gap-3 mb-2 flex-wrap" x-show="!esQr(seleccionado)">
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
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" x-model="campos[seleccionado].italic"
                            id="pop-italic">
                        <label class="form-check-label" for="pop-italic">Cursiva</label>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-2" x-show="!esQr(seleccionado)">
                    <div class="flex-fill">
                        <label class="form-label-sm">Espaciado letras (px)</label>
                        <input type="number" min="-5" max="30" step="0.5" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].letter_spacing">
                    </div>
                    <div class="flex-fill">
                        <label class="form-label-sm">Rotación (°)</label>
                        <input type="number" min="-180" max="180" step="1" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].rotacion">
                    </div>
                </div>

                <div class="d-flex gap-2 mb-2" x-show="esTexto(seleccionado)">
                    <div class="flex-fill">
                        <label class="form-label-sm">Interlineado</label>
                        <input type="number" min="0.8" max="3" step="0.1" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].line_height">
                    </div>
                    <div class="flex-fill">
                        <label class="form-label-sm">Ancho máx. (%)</label>
                        <input type="number" min="10" max="100" step="1" class="form-control form-control-sm"
                            x-model.number="campos[seleccionado].max_width">
                    </div>
                </div>

                <div class="mb-3" x-show="esTexto(seleccionado)">
                    <label class="form-label-sm">
                        Forzar salto de línea después de la palabra # (0 = automático por ancho)
                    </label>
                    <input type="number" min="0" max="20" step="1" class="form-control form-control-sm"
                        x-model.number="campos[seleccionado].salto_linea_palabra">
                    <div class="form-text" style="font-size:9.5px;" x-show="seleccionado === 'nombre'">
                        Útil para partir el nombre en una línea y los apellidos en otra: pon 1 si el nombre es
                        de una sola palabra, 2 si es de dos, etc.
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
