<?php

const ARCHIVO_DESTINOS = 'destinos.dat';

if (file_exists(ARCHIVO_DESTINOS)) {
    $contenidoArchivo = file_get_contents(ARCHIVO_DESTINOS);
    $destinosAnteriores = unserialize($contenidoArchivo);
} else {
    $destinosAnteriores = [];
}

function guardarDestinos(array $destinos)
{
    $contenidoSerializado = serialize($destinos);
    file_put_contents(ARCHIVO_DESTINOS, $contenidoSerializado);
}


if (isset($_POST['eliminar'])) {
    $idEliminar = $_POST['eliminar'];
    array_splice($destinosAnteriores, $idEliminar, 1);
    guardarDestinos($destinosAnteriores);
} elseif (isset($_POST['destino']) && isset($_POST['fecha']) && isset($_POST['descripcion'])) {

    if (isset($_POST['destino'])) {
        $nuevoDestino = [
            'nombre' => $_POST['destino'],
            'fecha' => $_POST['fecha'],
            'descripcion' => $_POST['descripcion'],
        ];


        $destinosAnteriores[] = $nuevoDestino;
        guardarDestinos($destinosAnteriores);
        header("Location: index.php");
        exit(); 
    }
}

$destinos = file_exists(ARCHIVO_DESTINOS) ? unserialize(file_get_contents(ARCHIVO_DESTINOS)) : [];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Lista de Destinos por Visitar</title>
</head>

<body>
    <div class="container-fluid">
        <h1 class="text-center m-3">Destinos por Visitar</h1>

        <div class="card border-info mb-5 card-hover ">
            <form method="post" action="" class="p-3">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="destino" id="destinoInput" placeholder="Nuevo destino" autocomplete="off" required>
                    <label for="destinoInput">Nuevo destino</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" name="fecha" id="fechaInput" placeholder="Fecha" required>
                    <label for="fechaInput">Fecha de planificación</label>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text ">Descripción</span>
                    <textarea class="form-control " aria-label="Descripción" name="descripcion" autocomplete="off" required></textarea>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary mb-2" type="submit">Agregar</button>
                </div>

            </form>
        </div>


        <div class="table-responsive">
            <?php if (empty($destinos)) : ?>
                <p class="mensaje-vacio">No hay destinos por visitar.</p>
            <?php else : ?>
                <table class="table text-center table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Destino</th>
                            <th scope="col">Fecha de Planificación</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php foreach ($destinos as $key => $value) : ?>
                            <tr>
                                <td><?= $value['nombre'] ?></td>
                                <td><?= $value['fecha'] ?></td>
                                <td><?= $value['descripcion'] ?></td>
                                <td>
                                    <form method="post" action="" id="form-eliminar-<?= $key ?>">
                                        <input type="hidden" name="eliminar" value="<?= $key ?>">
                                        <button class="btn btn-danger eliminar-destino" data-index="<?= $key ?>" data-nombre="<?= $value['nombre'] ?>">Eliminar</button>

                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>