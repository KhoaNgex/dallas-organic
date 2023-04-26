use dallasorganic;

DELIMITER //
CREATE PROCEDURE getBestSellers ()
BEGIN
    SELECT id, product_name, price, image
	FROM products
	ORDER BY sold_number DESC
	LIMIT 8;
END //
DELIMITER ;

call getBestSellers();

drop procedure if exists getSomeBlogTitle;
DELIMITER //
CREATE PROCEDURE getSomeBlogTitle()
BEGIN
    SELECT id, title, created_by, created_at, image
	FROM blogs
	ORDER BY created_at DESC
	LIMIT 3;
END //
DELIMITER ;

call getSomeBlogTitle();

DELIMITER //
CREATE PROCEDURE getAllBlogTitle(IN p_offset int)
BEGIN 
    DECLARE off_set INT DEFAULT 0;
	SET off_set = p_offset*9; 
    SELECT id, title, created_by, created_at, image
	FROM blogs
	ORDER BY created_at DESC
    LIMIT 9
    OFFSET off_set;
END //
DELIMITER ;

call getAllBlogTitle(0);

select * from blogs;

drop procedure if exists getAllProductTitle;
DELIMITER //
CREATE PROCEDURE getAllProductTitle(IN p_offset int, IN sort_order varchar(5), IN sort_field varchar(10), IN p_price int, IN p_cate int, IN p_name varchar(50))
BEGIN 
	DECLARE off_set int;
    declare price_pattern text;
    declare cate_pattern text;
    declare name_pattern text;
    set off_set = p_offset*6;
    
    IF p_name = '' THEN
		set name_pattern = CONCAT('product_name LIKE ',"'%'");
	ELSE 
		set name_pattern = CONCAT('product_name LIKE',"'%",p_name,"%'");
	END IF;
    
    IF p_cate = -1 THEN
		set cate_pattern = CONCAT('category_id LIKE ',"'%'");
	ELSE 
		set cate_pattern = CONCAT('category_id=',p_cate);
	END IF;
    
    IF p_price = -1 THEN
		set price_pattern = CONCAT('price LIKE ',"'%'");
	END IF;
        
    IF (p_price >= 0 && p_price < 200000) THEN
		set price_pattern = CONCAT('price>=',
		p_price,
		' AND price<=',
		p_price+50000);
	END IF;
    
	IF (p_price >= 200000) THEN
		set price_pattern = CONCAT('price>=',
		p_price);
    END IF;
    
	SET @t1 =CONCAT(' SELECT id, product_name, price, image, sold_number
	FROM products WHERE ',
	price_pattern,
    ' AND ',
    cate_pattern,
    ' AND ',
    name_pattern,
	' ORDER BY ', 
	sort_field, 
	' ', 
	sort_order,
	' ',
	'LIMIT 6 OFFSET ',
	off_set);
	PREPARE stmt FROM @t1;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //
DELIMITER ;

call getAllProductTitle(0, 'asc', 'id', -1, -1, 'ca');

call getAllFeedback();

drop procedure if exists getProductForAdmin;
DELIMITER //
CREATE PROCEDURE getProductForAdmin(IN p_offset int)
BEGIN 
	DECLARE off_set INT DEFAULT 0;
	SET off_set = p_offset*10; 
    select products.id, product_name, cate_name, price, unit, sold_number, remain_number 
    from products 
    inner join category 
    where products.category_id = category.id
    order by products.id
	limit 10
    offset off_set;
END //
DELIMITER ;

call getProductForAdmin(0);

drop procedure if exists getAllProductInCart;
DELIMITER //
CREATE PROCEDURE `getAllProductInCart` (IN `user_id` INT(11))  
BEGIN
    SELECT products.id, product_name, price, unit, quantity, image
	FROM cart
	INNER JOIN products ON cart.productID = products.id
    WHERE cart.userID = user_id;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `updateQuantityInCart` (IN user_id INT(11), IN product_id INT(11), IN p_quantity INT(11))  
BEGIN
    UPDATE cart 
    set quantity = p_quantity
    where productID = product_id and userID = user_id;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `deleteItemInCart` (IN user_id INT(11), IN product_id INT(11))  
BEGIN
    delete from cart 
    where productID = product_id and userID = user_id;
END //
DELIMITER ;
    
select * from cart;

call updateQuantityInCart(1, 2, 23);



