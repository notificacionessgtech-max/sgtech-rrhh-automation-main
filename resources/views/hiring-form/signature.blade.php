<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Firma del Contrato</h2>

            <p class="text-gray-600 mb-4 text-center">
                Por favor, dibuje su firma en el recuadro a continuación para finalizar el proceso de contratación.
            </p>

            <form action="{{ route('hiring.signature.save', $employee->personal_data_id) }}" method="POST"
                id="signatureForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Su Firma:</label>
                    <div class="border-2 border-dashed border-gray-400 rounded-lg bg-gray-50 flex justify-center">
                        <canvas id="signature-pad" class="w-full h-40 touch-none"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="signature-input">
                    <p class="text-xs text-red-500 mt-1 hidden" id="signature-error">Por favor firme antes de continuar.
                    </p>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <button type="button" id="clear-signature"
                        class="text-sm text-gray-500 hover:text-gray-700 underline">
                        Borrar y firmar de nuevo
                    </button>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                        Guardar y Finalizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para Signature Pad --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)' // necesario para JPEG
            });

            // Ajustar canvas al tamaño del contenedor
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear();
            }

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            // Botón Limpiar
            document.getElementById('clear-signature').addEventListener('click', function () {
                signaturePad.clear();
            });

            // Al enviar formulario
            document.getElementById('signatureForm').addEventListener('submit', function (e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    document.getElementById('signature-error').classList.remove('hidden');
                } else {
                    // Guardar firma en input oculto (base64)
                    document.getElementById('signature-input').value = signaturePad.toDataURL('image/png');
                }
            });
        });
    </script>
</x-guest-layout>