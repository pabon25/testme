CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

INSERT INTO students (name)
SELECT 'Pacifique'
WHERE NOT EXISTS (SELECT 1 FROM students WHERE name = 'Pacifique');

INSERT INTO students (name)
SELECT 'Elias'
WHERE NOT EXISTS (SELECT 1 FROM students WHERE name = 'Elias');