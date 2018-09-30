# shopping_list_app
Shopping list application

## Test dataabse

CREATE DATABASE shopping_list;

CREATE TABLE IF NOT EXISTS list (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    max_spend DECIMAL(6,2) NOT NUL,
    is_alert TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    list_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(6,2) NOT NULL,
    is_checked TINYINT(1) NOT NULL DEFAULT 0,
    position INT UNSIGNED NOT NULL,
    FOREIGN KEY (list_id) REFERENCES lists(id)
) ENGINE=InnoDB;


INSERT INTO list (title)
    VALUES ("My Shopping List");


INSERT INTO products (list_id, name, price, is_checked) VALUES
        (1, 'Heinz Baked Beans In Tomato Sauce 4 X415g', 2.00, 0),
        (1, 'Dr. Oetker Ristorante Pizza Pepperoni Salame 320G', 2.50, 0),
        (1, 'Mcvities Dark Chocolate Digestive 266G', 1.00, 1),
        (1, 'Mcvities Jaffa Cakes Triple Pack 30 Cakes', 1.25, 1),
        (1, 'Pepsi Max 24 X 330Ml', 8.00, 0);
