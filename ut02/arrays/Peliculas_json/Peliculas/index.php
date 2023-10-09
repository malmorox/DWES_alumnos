<?php

const ARCHIVO_DESTINOS = 'data/peliculas.json';

$vistas = [];
$pendientes = [];

if (file_exists(ARCHIVO_DESTINOS) && filesize(ARCHIVO_DESTINOS) > 0) {
    $contenidoArchivo = file_get_contents(ARCHIVO_DESTINOS);
    $peliculasAnteriores = json_decode($contenidoArchivo, true);

    foreach ($peliculasAnteriores as $pelicula) {
        if ($pelicula['rating'] == '' || $pelicula['rating'] == null) {
            $pendientes[] = $pelicula;
        } else {
            $vistas[] = $pelicula;
        }
    }
} else {
    $peliculasAnteriores = [];
    $vistas = [];
    $pendientes = [];
}

function guardarPeliculas(array $peliculas)
{
    $contenidoArchivo = json_encode($peliculas);
    file_put_contents(ARCHIVO_DESTINOS, $contenidoArchivo);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['eliminar_vista'])) {
        $idEliminar = $_POST['eliminar_vista'];

        foreach ($vistas as $key => $pelicula) {
            if ($pelicula['id'] === $idEliminar) {
                array_splice($vistas, $key, 1);
                break;
            }
        }

        $peliculasAnteriores = array_merge($vistas, $pendientes);
        guardarPeliculas($peliculasAnteriores);
    } elseif (isset($_POST['eliminar_pendiente'])) {
        $idEliminar = $_POST['eliminar_pendiente'];

        foreach ($pendientes as $key => $pelicula) {
            if ($pelicula['id'] === $idEliminar) {
                array_splice($pendientes, $key, 1);
                break;
            }
        }

        $peliculasAnteriores = array_merge($vistas, $pendientes);
        guardarPeliculas($peliculasAnteriores);

        header("Location: index.php");
        exit();
    } elseif (isset($_POST['nombre']) || isset($_POST['rating'])) {
        $nuevaPelicula = [
            'id' => uniqid(),
            'nombre' => $_POST['nombre'],
            'rating' => ($_POST['rating'] != '0') ? $_POST['rating'] : null,
        ];


        // Agregar la nueva película al array correspondiente
        if ($nuevaPelicula['rating'] === null) {
            $pendientes[] = $nuevaPelicula;
        } else {
            $vistas[] = $nuevaPelicula;
        }

        $peliculasAnteriores = array_merge($vistas, $pendientes);
        guardarPeliculas($peliculasAnteriores);
    }
    header("Location: index.php");
    exit();
}

$ratings = array_column($vistas, 'rating');
$nombres = array_column($vistas, 'nombre');

// Ordena primero por rating de forma descendente, y luego por nombre de forma ascendente en vistas
array_multisort($ratings, SORT_DESC, $nombres, SORT_DESC, $vistas);

//Ordenar por nombre en pendientes
usort($pendientes, function ($a, $b) {
    return strcasecmp($a['nombre'], $b['nombre']);
});

$peliculas = file_exists(ARCHIVO_DESTINOS) ? json_decode(file_get_contents(ARCHIVO_DESTINOS), true) : [];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- CDN Icons Bottstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <title>MiCineTracker</title>
</head>

<body>
    <div class="container-fluid">
        <h1 class="text-center mt-2 ">Mi Cine Tracker</h1>


        <div class="container mb-3 ">
            <form method="post" action="" class="form1 p-3 rounded col-md-6 mx-auto">
                <div class=" d-flex  justify-content-center align-items-center">
                    <div class=" mx-auto mb-3 me-3">
                        <label for="destinoInput" class="form-label">Nueva película</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nombre" id="destinoInput" placeholder="Ingrese el nombre" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="mx-auto mb-3 text-center">
                        <label for="raitingInput" class="form-label">Calificación</label>
                        <div class="star">
                            <i class="bi bi-star-fill star1"></i>
                            <i class="bi bi-star-fill star1"></i>
                            <i class="bi bi-star-fill star1"></i>
                            <input type="hidden" name="rating" id="raitingInput" value="0">
                        </div>
                    </div>
                    <div class=" mx-auto text-center mt-3">
                        <button class="btn btn-lg btn-form" type="submit"><i class="bi bi-plus-square"></i></button>
                    </div>
                </div>
            </form>
        </div>



        <div class="row">
            <div class="col-md-6">
                <!-- Tabla para películas vistas -->
                <h2 class="text-center">Películas Vistas</h2>
                <div class="table-responsive">
                    <?php if (!empty($vistas)) : ?>
                        <table class="table text-center table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Título</th>
                                    <th scope="col">Valoración</th>
                                    <th scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($vistas as $key => $value) : ?>
                                    <tr>
                                        <td><?= $value['nombre'] ?></td>
                                        <td><?= $value['rating'] ?></td>
                                        <td>
                                            <form method="post" action="" id="form-vistas-<?= $key ?>" class="form-eliminar">
                                                <input type="hidden" name="eliminar_vista" value="<?= $value['id'] ?>">
                                                <button class="btn btn-warning eliminar-pelicula" data-nombre="<?= $value['nombre'] ?>" data-form-id="form-vistas-<?= $key ?>" type="submit"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="mensaje-vacio">No hay películas vistas.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Tabla para películas pendientes -->
                <h2 class="text-center">Películas Pendientes</h2>
                <div class="table-responsive">
                    <?php if (!empty($pendientes)) : ?>
                        <table class="table text-center table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Título</th>
                                    <th scope="col">Valoración</th>
                                    <th scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($pendientes as $key => $value) : ?>
                                    <tr>
                                        <td><?= $value['nombre'] ?></td>
                                        <td> Pendiente</td>
                                        <td>
                                            <form method="post" action="" id="form-pendientes-<?= $key ?>" class="form-eliminar">
                                                <input type="hidden" name="eliminar_pendiente" value="<?= $value['id'] ?>">
                                                <button class="btn btn-warning eliminar-pelicula" data-nombre="<?= $value['nombre'] ?>" data-form-id="form-pendientes-<?= $key ?>" type="submit"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="mensaje-vacio">No hay películas pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="https://kit.fontawesome.com/10244c556d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>