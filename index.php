<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQLite PHP App</title>
</head>
<body>
    <?php
    $message = '';

    try {
        $dataDirectory = __DIR__ . '/data';
        if (!is_dir($dataDirectory) && !mkdir($dataDirectory, 0775, true)) {
            throw new RuntimeException('Could not create the SQLite data directory.');
        }

        $database = new PDO('sqlite:' . $dataDirectory . '/app.db');
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $database->exec(
            'CREATE TABLE IF NOT EXISTS students (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL
            )'
        );

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if ($name !== '') {
                $statement = $database->prepare('INSERT INTO students (name) VALUES (:name)');
                $statement->execute([':name' => $name]);
                $message = 'Saved: ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            } else {
                $message = 'Please enter a name.';
            }
        }

        $students = $database->query('SELECT id, name FROM students ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $error) {
        $message = 'Database error: ' . $error->getMessage();
        $students = [];
    }
    ?>

    <h1>My PHP App</h1>
    <p>Welcome to my PHP application!</p>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Submit</button>
    </form>
    <?php if ($message !== ''): ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <h2>Students</h2>
    <ul>
        <?php foreach ($students as $student): ?>
            <li><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>