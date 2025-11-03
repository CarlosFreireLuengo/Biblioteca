<?php
$sql_db = "CREATE DATABASE biblioteca";
if ($conexion->query($sql_db) === TRUE) {
    $conexion->select_db('biblioteca');
}
$sql = "
    -- Tabla libros
    CREATE TABLE libros(
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    anio_publicacion INT(4),
    ISBN VARCHAR(13) UNIQUE,
    sinopsis TEXT,
    n_disponibles INT(4) NOT NULL,
    n_totales INT(4) NOT NULL
);


    -- Tabla lectores
    CREATE TABLE lectores(
    id_lector INT(6) AUTO_INCREMENT PRIMARY KEY,
    lector_nombre VARCHAR(100) NOT NULL,
    DNI VARCHAR(9) UNIQUE NOT NULL,
    estado VARCHAR(20) NOT NULL,
    n_prestados INT(4) NOT NULL
);
    


    -- Tabla prestamos
    CREATE TABLE prestamos (
    id_lector INT(6),
    id_libro INT(6),
    FOREIGN KEY (id_lector) REFERENCES lectores(id_lector),
    FOREIGN KEY (id_libro) REFERENCES libros(id)
);

    -- Insertar datos iniciales en la tabla libros
    INSERT INTO libros (titulo, autor, anio_publicacion, ISBN, sinopsis, n_disponibles, n_totales) VALUES
    ('Cien Años de Soledad', 'Gabriel García Márquez', 1967, '1234567890123', 'Novela que narra la historia de la familia Buendía en el pueblo ficticio de Macondo.', 4, 5),
    ('Don Quijote de la Mancha', 'Miguel de Cervantes', 1605, '2345678901234', 'La aventura del ingenioso hidalgo Don Quijote y su fiel escudero Sancho Panza.', 2, 3);

    -- Insertar datos iniciales en la tabla lectores
    INSERT INTO lectores (lector_nombre, DNI, estado, n_prestados) VALUES   
    ('Juan Pérez', '12345678A', 'activo', 1),
    ('María Gómez', '87654321B', 'activo', 1);

    -- Insertar datos iniciales en la tabla prestamos
    INSERT INTO prestamos (id_lector, id_libro) VALUES
    (1, 1),
    (2, 2);

    ";



if ($conexion->multi_query($sql)) {
    while ($conexion->next_result()) {;
    }
    echo "Tabla creada y actualizada correctamente.";
} else {
    echo "Error: {$conexion->error}";
}
