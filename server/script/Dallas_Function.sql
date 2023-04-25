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