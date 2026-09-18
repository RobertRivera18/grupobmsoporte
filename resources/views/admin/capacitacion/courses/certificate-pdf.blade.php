```html
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Certificado - {{ $user->name }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }

        .font-cinzel {
            font-family: 'Cinzel', serif;
        }

        .font-cursive {
            font-family: 'Great Vibes', cursive;
        }

        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }

        .gold-gradient {
            background: linear-gradient(135deg,
                    #b8860b 0%,
                    #f5d77a 25%,
                    #d4af37 50%,
                    #f8df8a 75%,
                    #b8860b 100%);
        }

        .gold-text {
            background: linear-gradient(135deg,
                    #a67c00,
                    #d4af37,
                    #8f6b00);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .certificate-shadow {
            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.25),
                0 5px 15px rgba(0, 0, 0, 0.12);
        }

        @media print {

            html,
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .certificate-wrapper {
                width: 100vw !important;
                height: 100vh !important;
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .certificate {
                width: 100vw !important;
                height: 100vh !important;
                max-width: none !important;
                aspect-ratio: auto !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            @page {
                size: A4 landscape;
                margin: 0;
            }
        }
    </style>
</head>

<body class="bg-slate-900">

    <!-- ============================= -->
    <!-- BOTONES -->
    <!-- ============================= -->

    <div class="no-print fixed top-6 right-6 z-50 flex gap-3">

        <button onclick="window.print()"
            class="flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-xl transition hover:bg-blue-700">

            <i class="fa-solid fa-print"></i>

            Imprimir / Guardar PDF
        </button>

        <button onclick="window.close()"
            class="flex items-center gap-2 rounded-xl bg-slate-700 px-5 py-3 text-sm font-semibold text-white shadow-xl transition hover:bg-slate-600">

            <i class="fa-solid fa-xmark"></i>

            Cerrar
        </button>

    </div>


    <!-- ============================= -->
    <!-- CONTENEDOR -->
    <!-- ============================= -->

    <div class="certificate-wrapper min-h-screen p-6 sm:p-10 flex items-center justify-center">

        <!-- CERTIFICADO -->

        <div
            class="certificate relative w-full max-w-[1200px] aspect-[1.414/1] overflow-hidden bg-white certificate-shadow">

            <!-- ============================= -->
            <!-- MARCO EXTERIOR -->
            <!-- ============================= -->

            <div class="absolute inset-0 border-[10px] border-slate-900 pointer-events-none z-30"></div>

            <div class="absolute inset-[10px] border-[3px] border-[#d4af37] pointer-events-none z-30"></div>

            <div class="absolute inset-[18px] border border-slate-300 pointer-events-none z-30"></div>


            <!-- ============================= -->
            <!-- DECORACIÓN SUPERIOR -->
            <!-- ============================= -->

            <div class="absolute top-0 left-0 right-0 h-3 gold-gradient z-20"></div>


            <!-- ============================= -->
            <!-- DECORACIÓN LATERAL -->
            <!-- ============================= -->

            <div class="absolute left-0 top-0 bottom-0 w-[18px] bg-slate-900"></div>

            <div class="absolute left-[18px] top-0 bottom-0 w-[5px] gold-gradient"></div>


            <!-- ============================= -->
            <!-- FORMAS DECORATIVAS -->
            <!-- ============================= -->

            <div class="absolute -left-32 -top-32 h-[420px] w-[420px] rounded-full border-[60px] border-slate-100">
            </div>

            <div class="absolute -right-40 -bottom-40 h-[500px] w-[500px] rounded-full border-[70px] border-slate-100">
            </div>

            <div class="absolute right-20 top-10 h-20 w-20 rotate-45 border border-[#d4af37]/30"></div>

            <div class="absolute left-24 bottom-10 h-14 w-14 rotate-45 border border-[#d4af37]/30"></div>


            <!-- ============================= -->
            <!-- CONTENIDO -->
            <!-- ============================= -->

            <div class="relative z-10 h-full flex flex-col px-16 py-12">

                <!-- ============================= -->
                <!-- ENCABEZADO -->
                <!-- ============================= -->

                <div class="text-center">

                    <!-- Logo / Marca -->

                    <div class="mb-3 flex items-center justify-center gap-3">

                        <div class="h-px w-20 bg-[#d4af37]"></div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-full gold-gradient shadow-md">

                            <i class="fa-solid fa-certificate text-xl text-slate-900"></i>

                        </div>

                        <div class="h-px w-20 bg-[#d4af37]"></div>

                    </div>


                    <p class="font-cinzel text-sm font-semibold tracking-[0.35em] text-slate-500">
                        RESPALCORP
                    </p>

                    <h1 class="mt-2 font-cinzel text-4xl font-bold tracking-[0.18em] text-slate-900">
                        CERTIFICADO
                    </h1>

                    <div class="mt-2 flex items-center justify-center gap-3">

                        <span class="h-px w-10 bg-[#d4af37]"></span>

                        <span class="text-[11px] font-semibold uppercase tracking-[0.35em] text-[#b8860b]">
                            De capacitación
                        </span>

                        <span class="h-px w-10 bg-[#d4af37]"></span>

                    </div>

                </div>

                <div class="flex-1 flex flex-col items-center justify-center text-center">

                    <p class="text-xs uppercase tracking-[0.35em] text-slate-400">
                        Se otorga el presente certificado a
                    </p>


     

                    <div class="mt-3">

                        <h2 class="font-cursive text-6xl font-bold leading-tight text-slate-900">

                            {{ $user->name }}

                        </h2>

                        <div class="mx-auto mt-2 h-[2px] w-80 gold-gradient"></div>
                    </div>
                    <p class="mt-5 max-w-2xl text-sm leading-relaxed text-slate-600">

                        Por haber completado satisfactoriamente los requisitos establecidos
                        y participado en el proceso de capacitación correspondiente al curso:

                    </p>


                    <div class="mt-5">

                        <h3 class="font-cinzel text-2xl font-bold text-slate-800">

                            {{ $course->name }}

                        </h3>

                        <div class="mx-auto mt-2 h-1 w-16 rounded-full gold-gradient"></div>

                    </div>


                    <!-- MENSAJE -->

                    <p class="mt-4 text-xs italic text-slate-500">

                        Se reconoce su compromiso con el aprendizaje,
                        el desarrollo profesional y la mejora continua.

                    </p>

                </div>


                <!-- ============================= -->
                <!-- PIE -->
                <!-- ============================= -->

                <div class="mt-4">

                    <!-- FECHA -->

                    <div class="mb-5 text-center">

                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">
                            Fecha de emisión
                        </p>

                        <p class="mt-1 text-sm font-semibold text-[#a67c00]">
                            {{ $completedAt }}
                        </p>

                    </div>


                    <!-- FIRMAS -->

                    <div class="grid grid-cols-3 items-end gap-8">

                        <!-- FIRMA 1 -->

                        <div class="text-center">

                            <div class="mx-auto mb-2 w-44 border-b border-slate-400"></div>

                            <p class="font-cinzel text-[10px] font-bold tracking-wide text-slate-700">
                                COORDINACIÓN DE CAPACITACIÓN
                            </p>

                            <p class="mt-1 text-[9px] uppercase tracking-wider text-slate-400">
                                RespalCorp
                            </p>

                        </div>


                        <!-- SELLO -->

                        <div class="flex justify-center">

                            <div
                                class="relative flex h-20 w-20 items-center justify-center rounded-full border-2 border-[#d4af37]">
                                <div class="absolute inset-1 rounded-full border border-[#d4af37]">
                                </div>

                                <div class="text-center">
                                    <i class="fa-solid fa-award text-xl text-[#b8860b]"></i>
                                    <p class="mt-1 text-[7px] font-bold uppercase tracking-wider text-[#8f6b00]">
                                        Certificado
                                    </p>
                                </div>
                            </div>
                        </div>


                        <!-- FIRMA 2 -->

                        <div class="text-center">

                            <div class="mx-auto mb-2 w-44 border-b border-slate-400"></div>

                            <p class="font-cinzel text-[10px] font-bold tracking-wide text-slate-700">
                                DIRECCIÓN GENERAL
                            </p>

                            <p class="mt-1 text-[9px] uppercase tracking-wider text-slate-400">
                                RespalCorp
                            </p>

                        </div>

                    </div>


                    <div class="mt-5 flex items-center justify-center gap-2">

                        <i class="fa-solid fa-shield-halved text-[9px] text-[#b8860b]"></i>

                        <span class="text-[8px] uppercase tracking-[0.25em] text-slate-400">
                            Documento de certificación · Sistema de capacitación
                        </span>

                    </div>

                </div>

            </div>


            <div class="absolute left-7 top-7 h-12 w-12 border-l-2 border-t-2 border-[#d4af37]"></div>
            <div class="absolute right-7 top-7 h-12 w-12 border-r-2 border-t-2 border-[#d4af37]"></div>
            <div class="absolute bottom-7 left-7 h-12 w-12 border-b-2 border-l-2 border-[#d4af37]"></div>
            <div class="absolute bottom-7 right-7 h-12 w-12 border-b-2 border-r-2 border-[#d4af37]"></div>

        </div>

    </div>

</body>

</html>
```
