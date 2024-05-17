<?php
include 'db.php';

// Функция для выполнения запроса на получение изделий мастера
function getProductsByMaster($pdo, $masterSurname)
{
    try {
        $stmt = $pdo->prepare('SELECT t.name AS product_type, p.weight, p.sample, p.cost 
                               FROM product p
                               LEFT JOIN master m ON p.master_id = m.id
                               LEFT JOIN product_type t ON p.type_id = t.id
                               WHERE m.surname = :surname');
        $stmt->execute(['surname' => $masterSurname]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Ошибка выполнения запроса: " . $e->getMessage());
    }
}

// Получение списка фамилий мастеров
try {
    $stmt_master_surnames = $pdo->query('SELECT DISTINCT surname FROM master');
    $master_surnames = $stmt_master_surnames->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Ошибка выполнения запроса: " . $e->getMessage());
}

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
            $sort_button = "<button class=\"button\" onclick=\"window.location.href='search_sort.php'\">Сортировка и поиск</button>";
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
                                            m.surname AS master_surname
                                    FROM product p
                                    LEFT JOIN master m ON p.master_id = m.id
                                    LEFT JOIN product_type t ON p.type_id = t.id
                                    ORDER BY m.surname ASC, t.name ASC');

            $products = $stmt_product->fetchAll(PDO::FETCH_ASSOC);

            $stmt_total_count = $pdo->query('SELECT COUNT(*) AS total_count FROM product');
            $total_count = $stmt_total_count->fetchColumn();
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }


        // Вывод кнопки для добавления изделия и таблицы с изделиями
        $title = "Список изделий:";
        $headers = array("Вид изделия", "Вес", "Проба", "Фамилия мастера");
        $data = $products;
        $add_button = "<button class=\"button\" onclick=\"window.location.href='add_product.php'\">Добавить изделие</button>";
        $sort_button = "<form method=\"post\"><button class=\"button\" type=\"submit\" name=\"sort_cost\">Отсортировать по пробе (возрастанию)</button></form>";
        $show_master_products_button = "<form method=\"post\"><button class=\"button\" type=\"submit\" name=\"show_master_products\">Показать изделия мастера</button></form>";
    } elseif (isset($_POST['sort_cost'])) {
        // Выполнить запрос на сортировку по пробе
        try {
            $stmt_product = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, m.surname 
                        FROM product p
                        LEFT JOIN master m ON p.master_id = m.id
                        LEFT JOIN product_type t ON p.type_id = t.id
                        ORDER BY p.sample DESC, t.name DESC'); // Сортировка по пробе
            $products = $stmt_product->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
        $title = "Список изделий:";
        $headers = array("Вид изделия", "Вес", "Проба", "Фамилия мастера");
        $data = $products;
        $add_button = "<button class=\"button\" onclick=\"window.location.href='add_product.php'\">Добавить изделие</button>";
        $sort_button = "<form method=\"post\"><button class=\"button\" type=\"submit\" name=\"sort_cost\">Отсортировать по стоимости (убыванию)</button></form>";
    } elseif (isset($_POST['show_master_products'])) {
        try {
            $stmt_product = $pdo->query('SELECT t.name AS product_type, p.weight, p.sample, 
                                            m.surname AS master_surname
                                    FROM product p
                                    LEFT JOIN master m ON p.master_id = m.id
                                    LEFT JOIN product_type t ON p.type_id = t.id
                                    ORDER BY m.surname ASC, t.name ASC');

            $products = $stmt_product->fetchAll(PDO::FETCH_ASSOC);

            $stmt_total_count = $pdo->query('SELECT COUNT(*) AS total_count FROM product');
            $total_count = $stmt_total_count->fetchColumn();
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }

        $title = "Список изделий:";
        $headers = array("Вид изделия", "Вес", "Проба", "Фамилия мастера");
        $data = $products;
        $add_button = "<button class=\"button\" onclick=\"window.location.href='add_product.php'\">Добавить изделие</button>";
        $sort_button = "<form method=\"post\"><button class=\"button\" type=\"submit\" name=\"sort_cost\">Отсортировать по пробе (возрастанию)</button></form>";

        if (isset($_POST['master_surname'])) {
            $selected_master_surname = $_POST['master_surname'];
            $master_products = getProductsByMaster($pdo, $selected_master_surname);
        } else {
            echo "Не выбрана фамилия мастера.";
        }
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
            padding: 8px;
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
        <?php if (isset($add_button)): ?>
            <div class="add-button-container"><?= $add_button ?></div>
        <?php endif; ?>
        <?php if (isset($sort_button)): ?>
            <div class="add-button-container"><?= $sort_button ?></div>
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
        <?php if (isset($total_count)) : ?>
            <div class="total-count">Общее количество изделий в магазине: <?= $total_count ?></div>
        <?php endif; ?>
        <?php if (isset($show_master_products_button)): ?>
            <form method="post">
                <label for="master_surname">Выберите фамилию мастера:</label>
                <select name="master_surname" id="master_surname">
                    <?php foreach ($master_surnames as $surname) : ?>
                        <option value="<?= $surname ?>"><?= $surname ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="button" type="submit" name="show_master_products">Показать изделия мастера</button>
            </form>
        <?php endif; ?>
        <?php if (isset($master_products)) : ?>
            <h2>Изделия мастера <?= $selected_master_surname ?></h2>
            <table>
                <tr>
                    <th>Вид изделия</th>
                    <th>Вес</th>
                    <th>Проба</th>
                    <th>Стоимость</th>
                </tr>
                <?php foreach ($master_products as $product) : ?>
                    <tr>
                        <td><?= $product['product_type'] ?></td>
                        <td><?= $product['weight'] ?></td>
                        <td><?= $product['sample'] ?></td>
                        <td><?= $product['cost'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
