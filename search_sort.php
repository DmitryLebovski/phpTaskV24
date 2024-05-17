<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search_by_type'])) {
        $search_type = $_POST['search_type'];
        try {
            $stmt = $pdo->prepare('SELECT t.name AS product_type, p.weight, p.sample, p.cost 
                                FROM product p
                                LEFT JOIN product_type t ON p.type_id = t.id
                                WHERE t.name = :search_type');
            $stmt->execute(['search_type' => $search_type]);
            $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    } elseif (isset($_POST['search_by_sample'])) {
        $search_sample = $_POST['search_sample'];
        try {
            $stmt = $pdo->prepare('SELECT t.name AS product_type, p.weight, p.sample, p.cost 
                                FROM product p
                                LEFT JOIN product_type t ON p.type_id = t.id
                                WHERE p.sample = :search_sample');
            $stmt->execute(['search_sample' => $search_sample]);
            $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    } elseif (isset($_POST['sort_by_type'])) {
        try {
            $stmt = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, p.cost 
                                FROM product p
                                LEFT JOIN product_type t ON p.type_id = t.id
                                ORDER BY t.name ASC');
            $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    } elseif (isset($_POST['sort_by_cost'])) {
        try {
            $stmt = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, p.cost 
                                FROM product p
                                LEFT JOIN product_type t ON p.type_id = t.id
                                ORDER BY p.cost DESC');
            $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    } elseif (isset($_POST['show_most_expensive'])) {
        try {
            $stmt = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, p.cost 
                                FROM product p
                                LEFT JOIN product_type t ON p.type_id = t.id
                                ORDER BY p.cost DESC
                                LIMIT 1');
            $most_expensive = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск и сортировка изделий</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            background-image: url('background_main.png');
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .input-group input[type="text"],
        .input-group input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .input-group input[type="radio"] {
            display: none;
        }

        .input-group label {
            display: inline-block;
            padding: 8px 15px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .input-group input[type="radio"]:checked + label {
            background-color: #E2CFC0;
            color: black;
        }

        table {
            width: auto; /* Для динамической ширины */
            margin: 0 auto;
            border-collapse: collapse;
            border: none; /* Убираем границы */
            border-radius: 10px; /* Загругляем края */
            overflow: hidden; /* Обрезаем размытый фон */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Тень */
            background-color: rgba(255, 255, 255, 0.7); /* Цвет и прозрачность фона */
            padding: 8px;
        }


        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #E2CFC0;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
<div class="container">
    <h1>Поиск и сортировка изделий</h1>
    <form method="post">
        <div class="input-group">
            <label for="search_type">Поиск по виду изделия:</label>
            <input type="text" id="search_type" name="search_type" placeholder="Введите вид изделия">
            <input class="button" type="submit" name="search_by_type" value="Найти">
        </div>

        <div class="input-group">
            <label for="search_sample">Поиск по пробе:</label>
            <input type="number" id="search_sample" name="search_sample" placeholder="Введите пробу (например, 585)">
            <input class="button" type="submit" name="search_by_sample" value="Найти">
        </div>
    </form>

    <form method="post">
        <button class="button" type="submit" name="sort_by_type">Сортировать по видам изделий</button>
        <button class="button" type="submit" name="sort_by_cost">Сортировать по стоимости изделий</button>
        <button class="button" type="submit" name="show_most_expensive">Вывести самое дорогое изделие</button>
        <a href="main.php" class="button">Вернуться</a>
    </form>

    <?php if (isset($search_results) && !empty($search_results)) : ?>
        <h2>Результаты поиска или сортировки:</h2>
        <table>
            <tr>
                <th>Вид изделия</th>
                <th>Вес</th>
                <th>Проба</th>
                <th>Стоимость</th>
            </tr>
            <?php foreach ($search_results as $row) : ?>
                <tr>
                    <td><?= $row['product_type'] ?></td>
                    <td><?= $row['weight'] ?></td>
                    <td><?= $row['sample'] ?></td>
                    <td><?= $row['cost'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if (isset($most_expensive)) : ?>
        <h2>Самое дорогое изделие:</h2>
        <table>
            <tr>
                <th>Вид изделия</th>
                <th>Вес</th>
                <th>Проба</th>
                <th>Стоимость</th>
            </tr>
            <tr>
                <td><?= $most_expensive['product_type'] ?></td>
                <td><?= $most_expensive['weight'] ?></td>
                <td><?= $most_expensive['sample'] ?></td>
                <td><?= $most_expensive['cost'] ?></td>
            </tr>
        </table>
    <?php endif; ?>
</div>
</body>

</html>
