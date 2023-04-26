DELIMITER //
CREATE FUNCTION count_products()
RETURNS INT
BEGIN
  DECLARE _count INT;
  
  SELECT COUNT(*) INTO _count FROM products;
  
  RETURN _count;
END//
DELIMITER ;

SELECT count_products();

drop function if exists check_cart_and_update_product;

DELIMITER //
CREATE FUNCTION `check_cart_and_update_product` (`user_id` INT(11)) RETURNS TINYINT(1)  
BEGIN
  DECLARE cart_qty, product_qty INT;
  DECLARE product_id INT;
  DECLARE cursor_finished BOOLEAN DEFAULT FALSE;
  
  -- Declare cursor for products in cart
  DECLARE cart_cursor CURSOR FOR
    SELECT productID, quantity
    FROM cart
    where cart.userID = user_id;

  DECLARE cart_cursor_2 CURSOR FOR
    SELECT productID, quantity
    FROM cart
    where cart.userID = user_id;
  
  -- Declare exception handler for cursor
  DECLARE CONTINUE HANDLER FOR NOT FOUND
    SET cursor_finished = TRUE;
    
  OPEN cart_cursor;
  
  -- Loop through products in cart
  cart_loop: LOOP
    -- Fetch next row from cursor
    FETCH cart_cursor INTO product_id, cart_qty;
    
    -- Exit loop if no more rows
    IF cursor_finished THEN
      LEAVE cart_loop;
    END IF;
    
    -- Get remaining quantity of product from products table
    SELECT remain_number INTO product_qty
    FROM products
    WHERE id = product_id;
    
    -- Check if quantity in cart is greater than remaining quantity
    IF cart_qty > product_qty THEN
      CLOSE cart_cursor;
      RETURN FALSE;
    END IF;
  END LOOP;
  
  -- Close cursor and return true
  CLOSE cart_cursor;
  
  OPEN cart_cursor_2;
  SET cursor_finished = FALSE;
  cart_loop_2: LOOP
    -- Fetch next row from cursor
    FETCH cart_cursor_2 INTO product_id, cart_qty;
    
    -- Exit loop if no more rows
    IF cursor_finished THEN
      LEAVE cart_loop_2;
    END IF;
    
    SELECT remain_number INTO product_qty
    FROM products
    WHERE id = product_id;
    
    UPDATE products
    SET remain_number = remain_number - cart_qty, sold_number = sold_number + cart_qty
    WHERE id = product_id;
  END LOOP;
  CLOSE cart_cursor_2;
  RETURN TRUE;
END//
DELIMITER ;