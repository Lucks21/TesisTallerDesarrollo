<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotecas UBB</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #003b5c;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav {
            background-color: #b0bec5;
            padding: 10px;
            width: 20%;
            float: left;
            height: 100vh;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            margin-bottom: 10px;
        }
        nav ul li a {
            color: #003b5c;
            text-decoration: none;
            font-weight: bold;
        }
        section {
            padding: 20px;
            width: 75%;
            float: right;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            padding: 10px;
            width: 80%;
        }
        .search-box button {
            padding: 10px;
            background-color: #ff5700;
            color: white;
            border: none;
            cursor: pointer;
        }
        footer {
            clear: both;
            background-color: #003b5c;
            color: white;
            text-align: center;
            padding: 10px;
            position: absolute;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Red de Bibliotecas UBB</h1>
</header>

<nav>
    <ul>
        <li><a href="formulario_busqueda.php">Búsqueda</a></li>
        <li><a href="#">Cuenta Personal</a></li>
    </ul>
</nav>

<section>
    <div class="search-box">
        <input type="text" placeholder="Buscar en la Biblioteca">
        <button>Buscar</button>
    </div>
    <h2>Bienvenido a la Red de Bibliotecas UBB</h2>
    <p>El sistema de bibliotecas de la Universidad del Bío-Bío pone a disposición de su comunidad de usuarios una amplia variedad de recursos electrónicos, libros, bases de datos y otros servicios para apoyar la investigación y el aprendizaje.</p>
</section>

<footer>
    <p>© 2024 Universidad del Bío-Bío</p>
</footer>

</body>
</html>
