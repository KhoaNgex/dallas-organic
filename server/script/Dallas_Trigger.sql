drop trigger if exists insert_customer_account;

DELIMITER //
CREATE TRIGGER insert_customer_account
AFTER INSERT ON user_account
FOR EACH ROW
BEGIN
    INSERT INTO customer_account (userID_customer) VALUES (NEW.id);
END //
DELIMITER ;



