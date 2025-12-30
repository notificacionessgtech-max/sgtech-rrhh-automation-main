<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .thank-you-container {
            background-color: white;
            padding: 3rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }
        .success-icon {
            color: #10b981;
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        h1 {
            color: #1f2937;
            margin-bottom: 1rem;
        }
        p {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .home-button {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .home-button:hover {
            background-color: #2563eb;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="thank-you-container">
    <div class="success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <h1>¡Formulario Enviado Exitosamente!</h1>
    <p>
        Gracias por completar la ficha de contratación.
        Tu información ha sido registrada correctamente en nuestro sistema.
        El equipo de Recursos Humanos se pondrá en contacto contigo pronto.
    </p>
</div>
</body>
</html>
