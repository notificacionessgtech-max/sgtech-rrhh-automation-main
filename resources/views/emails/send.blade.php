<x-app-layout>

    <div class="page-wrapper">
        <div class="form-container">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="form-title">Enviar invitación</h2>
            <p class="form-subtitle">
                Ingresa el correo del colaborador para enviarle la invitación
            </p>

            <form
                class="form"
                method="POST"
                action="{{ route('send.welcome.email') }}"
                id="emailForm"
            >
                @csrf

                <label for="email">Correo electrónico</label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    class="input"
                    placeholder="correo@empresa.com"
                    required
                >

                <button class="button-confirm" type="submit" id="submitBtn">
                    Enviar formulario
                    <span id="spinner" class="spinner hidden"></span>
                </button>

            </form>

        </div>
    </div>

    {{-- Script simple para UX --}}
    <script>
        document.getElementById('emailForm').addEventListener('submit', function () {
            document.getElementById('spinner').classList.remove('hidden');
            document.getElementById('submitBtn').disabled = true;
        });
    </script>

</x-app-layout>


<style>
    .page-wrapper {
        min-height: calc(100vh - 80px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px 20px;
        background-color: #f4f6f8;
    }

    .form-container {
        width: 100%;
        max-width: 480px;
        background: #ffffff;
        padding: 32px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .form-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .form-subtitle {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 24px;
    }

    .form {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #374151;
    }

    .input {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    .button-confirm {
        margin-top: 8px;
        padding: 12px;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .button-confirm:hover {
        background-color: #1e40af;
    }

    .button-confirm:disabled {

</style>
