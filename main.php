<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['open_shop'])) {
        try {
            $stmt_product = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, 
                                to_char(p.date, \'DD Month YYYY год\') AS formatted_date, 
                                p.cost, m.name, m.surname, m.thirdname, m.exp, m.grade 
                            FROM product p
                            LEFT JOIN master m ON p.master_id = m.id
                            LEFT JOIN product_type t ON p.type_id = t.id');

            $products = $stmt_product->fetchAll(PDO::FETCH_ASSOC);

            $stmt_count_products = $pdo->query('SELECT t.name AS product_type, COUNT(*) AS count
                                                FROM product p
                                                LEFT JOIN product_type t ON p.type_id = t.id
                                                GROUP BY t.name');
            $product_counts = $stmt_count_products->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }

        // Вывод результатов запроса
        $title = "Все данные магазина:";
        $headers = array("Вид изделия", "Вес", "Проба", "Дата поступления", "Стоимость", "Имя мастера", "Фамилия мастера", "Отчество мастера", "Стаж мастера", "Разряд мастера");
        $data = $products;
    } elseif (isset($_POST['list_masters'])) {
        // Запрос на отображение списка мастеров
        try {
            $stmt_master = $pdo->query('SELECT name, surname, thirdname, exp, grade FROM master');
            $masters = $stmt_master->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }

        // Вывод кнопки для добавления мастера и таблицы с мастерами
        $title = "Список мастеров:";
        $headers = array("Имя", "Фамилия", "Отчество", "Стаж", "Разряд");
        $data = $masters;
        $add_button = "<button class=\"button\" onclick=\"window.location.href='add_master.php'\">Добавить мастера</button>";
    } elseif (isset($_POST['list_types'])) {
        // Запрос на отображение списка видов изделий
        try {
            $stmt_type = $pdo->query('SELECT name FROM product_type');
            $types = $stmt_type->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }

        // Вывод кнопки для добавления типа изделия и таблицы с типами изделий
        $title = "Список видов изделий:";
        $headers = array("Название");
        $data = $types;
        $add_button = "<button class=\"button\" onclick=\"window.location.href='add_product_type.php'\">Добавить тип изделия</button>";
    } elseif (isset($_POST['list_products'])) {
        // Запрос на отображение списка изделий
        try {
            $stmt_product = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, 
                                to_char(p.date, \'DD Month YYYY г.\') AS formatted_date, 
                                p.cost, m.surname 
                            FROM product p
                            LEFT JOIN master m ON p.master_id = m.id
                            LEFT JOIN product_type t ON p.type_id = t.id');

            $products = $stmt_product->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }


        // Вывод кнопки для добавления изделия и таблицы с изделиями
        $title = "Список изделий:";
        $headers = array("Вид изделия", "Вес", "Проба", "Дата поступления", "Стоимость", "Фамилия мастера");
        $data = $products;
        $add_button = "<button class=\"button\" onclick=\"window.location.href='add_product.php'\">Добавить изделие</button>";
        $sort_button = "<form method=\"post\"><button class=\"button\" type=\"submit\" name=\"sort_cost\">Отсортировать по стоимости (убыванию)</button></form>";
    } elseif (isset($_POST['sort_cost'])) {
            // Выполнить запрос на сортировку по стоимости в порядке убывания
            try {
                $stmt_product = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, 
                            to_char(p.date, \'DD Month YYYY г.\') AS formatted_date, 
                            p.cost, m.surname 
                        FROM product p
                        LEFT JOIN master m ON p.master_id = m.id
                        LEFT JOIN product_type t ON p.type_id = t.id
                        ORDER BY p.cost DESC'); // Сортировка по стоимости в порядке убывания
                $products = $stmt_product->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Ошибка выполнения запроса: " . $e->getMessage());
            }
        $title = "Список изделий:";
        $headers = array("Вид изделия", "Вес", "Проба", "Дата поступления", "Стоимость", "Фамилия мастера");
        $data = $products;
        $add_button = "<button class=\"button\" onclick=\"window.location.href='add_product.php'\">Добавить изделие</button>";
        $sort_button = "<form method=\"post\"><button class=\"button\" type=\"submit\" name=\"sort_cost\">Отсортировать по стоимости (убыванию)</button></form>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практическая работа Коробко Д.С.</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: white;
            background-image: url('background_secondary.png');
            background-size: cover;
        }

        .toolbar {
            background-color: dimgrey;
            padding: 20px;
            text-align: center;
        }

        .toolbar h1 {
            margin: 0;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #f0f0f0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #ddd;
        }

        .container {
            margin-top: 20px;
            text-align: center;
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
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            color: black;
        }

        th {
            background-color: #f2f2f2;
        }

        .add-button-container {
            margin-top: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
    </style>
</head>

<body>
<div class="toolbar">
    <h1 style="color: #E2CFC0;">Золотой PHP</h1>
    <form method="post">
        <button class="button" type="submit" name="open_shop">Открыть учёт магазина</button>
        <button class="button" type="submit" name="list_masters">Список мастеров</button>
        <button class="button" type="submit" name="list_types">Список видов изделий</button>
        <button class="button" type="submit" name="list_products">Список изделий</button>
    </form>
</div>

<div class="container">
    <?php if(isset($title) && isset($headers) && isset($data)): ?>
        <h2><?= $title ?></h2>
        <?php if (isset($product_counts)): ?>
            <div style="margin-bottom: 10px;">
                <?php foreach ($product_counts as $product_count): ?>
                    <p><?= $product_count['product_type'] ?>: <?= $product_count['count'] ?> шт.</p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <table>
            <tr>
                <?php foreach ($headers as $header): ?>
                    <th><?= $header ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <td><?= $cell ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
</body>

</html>