-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th4 25, 2023 lúc 07:19 AM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dallasorganic`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `createOrder` (IN `recieve_address` VARCHAR(200), IN `recieve_phonenum` INT(11), IN `note` VARCHAR(200), IN `order_date` DATE, IN `order_status` VARCHAR(30), IN `ship_fee` INT(11), IN `userID_ordcus` INT(11))   BEGIN
   INSERT INTO orders (recieve_address, recieve_phonenum, note, order_date, order_status, ship_fee, userID_ordcus) 
   VALUES (recieve_address, recieve_phonenum, note, order_date, order_status, ship_fee, userID_ordcus);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllBlogTitle` (IN `p_offset` INT)   BEGIN 
    DECLARE off_set INT DEFAULT 0;
	SET off_set = p_offset*9; 
    SELECT id, title, created_by, created_at, image
	FROM blogs
	ORDER BY created_at DESC
    LIMIT 9
    OFFSET off_set;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllFeedback` ()   BEGIN
    SELECT fullname, username, product_name, feedback.comment, rating, feedback_datetime		
	FROM feedback
	INNER JOIN user_account ON feedback.customerID=user_account.id
    INNER JOIN products ON products.id = feedback.productID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllProductInCart` (IN `user_id` INT(11))   BEGIN
    SELECT product_name, price, quantity
	FROM cart
	INNER JOIN products ON cart.productID = products.id
    WHERE cart.userID = user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllProductTitle` (IN `p_offset` INT, IN `sort_order` VARCHAR(5), IN `sort_field` VARCHAR(10), IN `p_price` INT, IN `p_cate` INT, IN `p_name` VARCHAR(50))   BEGIN 
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBestSellers` ()   BEGIN
    SELECT id, product_name, price, image
	FROM products
	ORDER BY sold_number DESC
	LIMIT 8;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getProductFeedback` (IN `product_id` INT(11))   BEGIN
    SELECT fullname, avatar, username, feedback.comment, rating, feedback_datetime	
	FROM feedback
	INNER JOIN user_account ON feedback.customerID=user_account.id
    WHERE productID = product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getSomeBlogTitle` ()   BEGIN
    SELECT id, title, created_by, created_at, image
	FROM blogs
	ORDER BY created_at DESC
	LIMIT 3;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `placeOrder` (IN `recieve_address` VARCHAR(200), IN `recieve_phonenum` INT(11), IN `note` VARCHAR(200), IN `order_date` DATE, IN `order_status` VARCHAR(30), IN `ship_fee` INT(11), IN `userID_ordcus` INT(11))   BEGIN
	DECLARE max_order_id INT;
	IF NOT check_cart_and_update_product(userID_ordcus) THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid cart';
	ELSE 
		CALL createOrder(recieve_address, recieve_phonenum, note, order_date, order_status, ship_fee, userID_ordcus);
		SELECT MAX(id) INTO max_order_id FROM orders;
        SELECT insertNewOrders(userID_ordcus, max_order_id);
    END IF;
END$$

--
-- Các hàm
--
CREATE DEFINER=`root`@`localhost` FUNCTION `calculate_average_rating` (`product_id` INT(11)) RETURNS DECIMAL(3,1)  BEGIN
    DECLARE rating_sum DECIMAL(5,2);
    DECLARE rating_count INT;
    DECLARE average_rating DECIMAL(3,1);

    SELECT SUM(rating), COUNT(*) INTO rating_sum, rating_count
    FROM feedback
    WHERE productID = product_id;

    IF (rating_count > 0) THEN
        SET average_rating = rating_sum / rating_count;
    ELSE
        SET average_rating = 0;
    END IF;

    RETURN average_rating;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `check_cart_and_update_product` (`user_id` INT(11)) RETURNS TINYINT(1)  BEGIN
  DECLARE cart_qty, product_qty INT;
  DECLARE product_id INT;
  DECLARE cursor_finished BOOLEAN DEFAULT FALSE;
  
  -- Declare cursor for products in cart
  DECLARE cart_cursor CURSOR FOR
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
	else 
		UPDATE products
		SET remain_number = remain_number - cart_qty, sold_number = sold_number + cart_qty
		WHERE id = product_id;
    END IF;
  END LOOP;
  
  -- Close cursor and return true
  CLOSE cart_cursor;
  RETURN TRUE;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `count_products` () RETURNS INT(11)  BEGIN
  DECLARE _count INT;
  
  SELECT COUNT(*) INTO _count FROM products;
  
  RETURN _count;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `insertNewOrders` (`user_id` INT(11), `order_id` INT(11)) RETURNS INT(11)  BEGIN
	DECLARE cart_qty, product_id INT;
	DECLARE cursor_finished BOOLEAN DEFAULT FALSE;
  -- Declare cursor for products in cart
	DECLARE cart_cursor CURSOR FOR
    SELECT productID, quantity
    FROM cart
    where cart.userID = user_id;
  
  -- Declare exception handler for cursor
	DECLARE CONTINUE HANDLER FOR NOT FOUND
    SET cursor_finished = TRUE;

	OPEN cart_cursor;
	--   INSERT INTO ordered_product () 
    
    cart_loop: LOOP
    -- Fetch next row from cursor
		FETCH cart_cursor INTO product_id, cart_qty;
    
    -- Exit loop if no more rows
		IF cursor_finished THEN
			LEAVE cart_loop;
		END IF;

		INSERT INTO ordered_product (productID, orderID, quantity) 
		VALUES (product_id, order_id ,cart_qty);
	END LOOP;
  
	-- Close cursor and return true
	CLOSE cart_cursor;
	RETURN NULL;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_account`
--

CREATE TABLE `admin_account` (
  `userID_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_account`
--

INSERT INTO `admin_account` (`userID_admin`) VALUES
(2),
(4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banks`
--

CREATE TABLE `banks` (
  `bank_name` varchar(130) NOT NULL,
  `bank_shortname` varchar(10) NOT NULL,
  `bank_logo` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `banks`
--

INSERT INTO `banks` (`bank_name`, `bank_shortname`, `bank_logo`) VALUES
('Ngân hàng Bản Việt ', 'VietCapita', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-ngan-hang-VietCapitalBank.png'),
('Ngân hàng Công thương Việt Nam', 'VietinBank', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-Vietinbank.png'),
('Ngân hàng ngoại thương Việt Nam', 'VietcomBan', 'https://apithanhtoan.com/wp-content/uploads/2020/08/logo-vietcombank.png'),
('Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam', 'Agribank', 'https://apithanhtoan.com/wp-content/uploads/2020/08/logo-agribank.png'),
('Ngân hàng Phương Đông', 'OCB', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-ngan-hang-OCB.png'),
('Ngân hàng Sài Gòn - Hà Nội', 'SHB', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-ngan-hang-SHB.png'),
('Ngân hàng thương mại cổ phần Á Châu', 'ACB', 'https://apithanhtoan.com/wp-content/uploads/2020/08/logo-ACB.png'),
('Ngân hàng thương mại cổ phần Kỹ Thương Việt Nam', 'Techcomban', 'https://apithanhtoan.com/wp-content/uploads/2020/08/logo-techcombank.png'),
('Ngân hàng Thương mại Cổ phần Quân đội', 'MBBank', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-MBBank.png'),
('Ngân hàng thương mại cổ phần Sài Gòn Thương Tín', 'Sacombank', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-Sacombank.png'),
('Ngân hàng Tiên Phong', 'TPBank', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-ngan-hang-TPBank.png'),
('Ngân hàng Việt Nam Thịnh Vượng', 'VPBank', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-VPBank.png'),
('Ngân hàng Đầu tư và Phát triển Việt Nam', 'BIDV', 'https://apithanhtoan.com/wp-content/uploads/2020/08/Logo-BIDV.png'),
('Ví Momo', 'Momo', 'https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png'),
('Zalopay', 'Zalopay', 'https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bank_account`
--

CREATE TABLE `bank_account` (
  `bank_name` varchar(130) NOT NULL,
  `acc_number` varchar(20) NOT NULL,
  `customerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bank_account`
--

INSERT INTO `bank_account` (`bank_name`, `acc_number`, `customerID`) VALUES
('Ví Momo', '0908123456', 1),
('Zalopay', '0908123456', 1),
('Ngân hàng Phương Đông', '0000012225555', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT 'Admin',
  `min_read` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `subtitle`, `created_by`, `min_read`, `content`, `created_at`, `image`) VALUES
(1, '5 Healthy Lifestyle Tips to Improve Your Overall Health', 'Reduce your tiredness and boost your health with these simple changes', 'Admin', 2, '<div style=\"display: flex; justify-content: center;\">\r\n                        <img src=\"https://imgeng.jagran.com/images/2023/mar/healthy-lifestyle-changes-for-weight-loss1677901066822.jpg\"\r\n                            alt=\"Healthy food\" class=\"img-fluid mt-2\">\r\n                    </div>\r\n                    <p class=\"mt-4\">Living a healthy lifestyle is important for maintaining a strong body and mind. Here are 5 tips\r\n                        to help you improve your overall health:</p>\r\n                    <ol>\r\n                        <li>Get enough sleep: Aim for 7-9 hours of sleep every night to give your body time to rest and\r\n                            recharge.</li>\r\n                        <li>Eat a balanced diet: Focus on eating whole foods such as fruits, vegetables, whole grains,\r\n                            and lean proteins. Avoid processed foods, sugary drinks, and excessive amounts of alcohol.\r\n                        </li>\r\n                        <li>Stay active: Exercise regularly to maintain a healthy weight, build muscle, and reduce the\r\n                            risk of chronic diseases such as heart disease and diabetes.</li>\r\n                        <li>Reduce stress: Find ways to relax and manage stress levels, such as practicing yoga,\r\n                            meditation, or deep breathing exercises.</li>\r\n                        <li>Stay hydrated: Drink plenty of water throughout the day to keep your body hydrated and\r\n                            functioning properly.</li>\r\n                    </ol>\r\n                    <p>By following these healthy lifestyle tips, you can improve your overall health and well-being.\r\n                        Remember, small\r\n                        changes can make a big difference!</p>', '2023-04-23 08:56:33', 'https://imgeng.jagran.com/images/2023/mar/healthy-lifestyle-changes-for-weight-loss1677901066822.jpg'),
(2, 'Thực Phẩm Hữu Cơ - Sự Lựa Chọn Sức Khỏe', 'Tại Sao Bạn Nên Cân Nhắc Ăn Thực Phẩm Hữu Cơ', 'Admin', 2, '<p>Thực phẩm hữu cơ đã trở nên ngày càng phổ biến trong những năm gần đây, khi mọi người đang ngày càng nhận\r\n            thức được những lợi ích của việc ăn thực phẩm hữu cơ. Nhưng thực phẩm hữu cơ là gì chính xác?</p>\r\n        <div style=\"display: flex; justify-content: center;\">\r\n            <img src=\"https://i.ndtvimg.com/i/2015-05/organic_625x350_41430972446.jpg\"\r\n                alt=\"Healthy food\" class=\"img-fluid mt-3 mb-4\">\r\n        </div>\r\n        <p>Thực phẩm hữu cơ được trồng trọt và chế biến mà không sử dụng các hóa chất tổng hợp, chẳng hạn như thuốc trừ\r\n            sâu\r\n            và phân bón hóa học. Thay vào đó, những người trồng trọt hữu cơ sử dụng các phương pháp tự nhiên để kiểm\r\n            soát\r\n            sâu bệnh và cải thiện tính mùn bãi của đất, chẳng hạn như luân canh, phân bón hữu cơ và các động vật ăn côn\r\n            trùng tự nhiên. Có nhiều lý do vì sao bạn nên cân nhắc ăn thực phẩm hữu cơ. Trước hết, nó tốt hơn cho môi\r\n            trường. Bằng cách\r\n            tránh\r\n            sử dụng các hóa chất tổng hợp, những người trồng trọt hữu cơ đang giảm tác động của mình lên môi trường và\r\n            giúp\r\n            bảo vệ đa dạng sinh học. Thứ hai, thực phẩm hữu cơ tốt hơn cho sức khỏe của bạn. Nghiên cứu đã chứng minh\r\n            rằng thực phẩm hữu cơ thường\r\n            giàu chất dinh dưỡng hơn, chẳng hạn như vitamin và chất chống oxy hóa. Trong khi đó, các chất độc hại và\r\n            thuốc trừ sâu được sử dụng trong trồng trọt thông thường có thể gây hại cho\r\n            sức\r\n            khỏe của bạn. Những chất này có thể tích tụ trong cơ thể của bạn qua thực phẩm, gây ra các vấn đề sức khỏe\r\n            như ung\r\n            thư, rối loạn nội tiết và hư hại hệ thống miễn dịch.</p>\r\n        <p>Vì vậy, nếu bạn quan tâm đến sức khỏe của mình và muốn ăn những thực phẩm tốt cho sức khỏe và môi trường, hãy\r\n            cân\r\n            nhắc sử dụng thực phẩm hữu cơ. Hãy tìm kiếm các sản phẩm hữu cơ tại cửa hàng thực phẩm hữu cơ hoặc các chợ\r\n            nông sản\r\n            địa phương, và hãy ủng hộ các nông dân và nhà sản xuất hữu cơ địa phương của bạn.</p>', '2023-04-23 08:56:33', 'https://i.ndtvimg.com/i/2015-05/organic_625x350_41430972446.jpg'),
(3, 'Lợi ích tuyệt vời của cà chua', 'Tại sao bạn nên ăn cà chua mỗi ngày?', 'Admin', 5, '<p>Cà chua được mệnh danh là một nhà máy dinh dưỡng vì nó cung cấp rất nhiều thành phần có lợi cho\r\n            sức khỏe,\r\n            ngay lập tức hãy cho cà chua vào thực đơn ăn uống của mình bạn nhé!</p>\r\n        <p>Cà chua rất giàu vitamin A, C, K, vitamin B6, kali, folate, thiamin, magiê, niacin, đồng và phốt pho, là\r\n            những vi chất\r\n            cần thiết để duy trì một sức khỏe tốt. Điều tuyệt vời hơn ở cà chua là chúng chứa rất ít\r\n            cholesterol, chất béo\r\n            bão hòa, natri và calo. Bạn có thể ăn cà chua sống kẹp với bánh mì, làm salad, nước sốt, sinh tố, thậm\r\n            chí nấu\r\n            súp. Sau đây là 9 lợi ích của cà chua.</p>\r\n        <div style=\"display: flex; justify-content: center;\">\r\n            <img src=\"https://vfa.gov.vn/data/PHUNGHA_VFA/ca%20chua%201.jpg\" alt=\"Healthy food\"\r\n                class=\"img-fluid mb-4\" style=\"max-width: 50%;\">\r\n        </div>\r\n        <h2 style=\"color: green\"> 1. Cải thiện thị lực </h2>\r\n        <p> Cà chua là nguồn cung cấp vitamin A và C tuyệt vời giúp ngăn ngừa bệnh quáng gà và tăng thị lực cho đôi mắt\r\n            của bạn.\r\n            Một nghiên cứu gần đây cho thấy hàm lượng vitamin A cao của cà chua có thể ngăn ngừa thoái hóa điểm vàng,\r\n            một bệnh\r\n            nghiêm trọng có thể dẫn đến mù mắt. Hơn nữa, cà chua có thể giảm nguy cơ đục thủy tinh thể. Trong cà chua\r\n            còn có các\r\n            chất chống oxy hóa như lycopene, lutein và zeaxanthin. </p>\r\n        <h2 style=\"color: green\"> 2. Phòng chống ung thư </h2>\r\n        <p> Ăn nhiều cà chua có thể giúp chống lại ung thư tuyến tiền liệt. Cà chua cũng có thể giúp giảm nguy cơ một số\r\n            bệnh\r\n            ung thư khác như dạ dày, phổi, cổ tử cung, vòm họng, trực tràng, đại tràng, thực quản, và ung thư buồng\r\n            trứng nhờ\r\n            các chất chống ôxy hóa, đặc biệt là nhờ vào hàm lượng lycopene rất cao có trong cà chua. Tác dụng phòng\r\n            chống ung\r\n            thư của cà chua hơn nhiều khi nấu loại quả này với dầu ô liu. </p>\r\n        <div style=\"display: flex; justify-content: center;\">\r\n            <img src=\"https://cafefcdn.com/203337114487263232/2021/10/19/-16346173216542069545051.jpg\" alt=\"Healthy food\" class=\"img-fluid mb-4\"\r\n                style=\"max-width: 50%;\">\r\n        </div>\r\n        <h2 style=\"color: green\"> 3. Làm sáng da </h2>\r\n        <p> Cà chua chứa lycopene, một chất chống oxy hóa mạnh bảo vệ da khỏi ánh nắng mặt trời và làm cho làn da của\r\n            bạn ít\r\n            nhạy cảm với tia cực tím, một trong những nguyên nhân gây ra nếp nhăn ở da. Chà bột cà chua lên làn da thô\r\n            ráp của\r\n            bạn giúp se lỗ chân lông, tái tạo và làm săn da mặt. </p>\r\n        <h2 style=\"color: green\"> 4. Giảm lượng đường trong máu </h2>\r\n        <p> Cà chua chứa rất ít carbohydrate nên giúp làm giảm lượng đường trong máu. Một vài nghiên cứu tìm thấy vai\r\n            trò của\r\n            các chất chống ôxy hoá trong cà chua bảo vệ thành mạch và thận - những cơ quan hay bị tổn thương do\r\n            bệnh tiểu đường. Cà chua còn chứa crom và chất xơ giúp kiểm\r\n            soát lượng đường trong máu. </p>', '2023-04-23 08:56:33', 'https://cafefcdn.com/203337114487263232/2021/10/19/-16346173216542069545051.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`userID`, `productID`, `quantity`) VALUES
(1, 2, 3),
(1, 3, 2),
(1, 4, 5),
(2, 2, 3),
(2, 12, 7),
(2, 8, 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `cate_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id`, `cate_name`) VALUES
(1, 'Rau củ hữu cơ'),
(2, 'Trái cây hữu cơ'),
(3, 'Thực phẩm khô'),
(4, 'Thịt - Hải sản'),
(5, 'Thức uống hữu cơ'),
(6, 'Bánh mì hữu cơ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_account`
--

CREATE TABLE `customer_account` (
  `userID_customer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_account`
--

INSERT INTO `customer_account` (`userID_customer`) VALUES
(1),
(3),
(5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedback`
--

CREATE TABLE `feedback` (
  `productID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `feedback_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `feedback`
--

INSERT INTO `feedback` (`productID`, `customerID`, `comment`, `rating`, `feedback_datetime`) VALUES
(5, 1, 'Dâu đợt này không tươi', 3, '2023-04-09 21:30:00'),
(5, 3, 'Dâu tươi ngon', 5, '2023-03-20 14:00:09'),
(23, 3, 'Sản phẩm cực kì tuyệt vời, gia đình chúng tôi rất hài lòng!', 5, '2023-03-20 13:00:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ordered_product`
--

CREATE TABLE `ordered_product` (
  `productID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ordered_product`
--

INSERT INTO `ordered_product` (`productID`, `orderID`, `quantity`) VALUES
(2, 1, 3),
(2, 2, 3),
(2, 3, 3),
(2, 4, 3),
(2, 5, 3),
(3, 1, 2),
(3, 2, 2),
(3, 3, 2),
(3, 4, 2),
(3, 5, 2),
(4, 2, 5),
(4, 3, 5),
(4, 4, 5),
(4, 5, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `recieve_address` varchar(200) NOT NULL,
  `recieve_phonenum` int(11) NOT NULL,
  `note` varchar(200) NOT NULL,
  `order_date` date NOT NULL,
  `order_status` varchar(30) NOT NULL,
  `ship_fee` int(11) NOT NULL,
  `userID_ordcus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `recieve_address`, `recieve_phonenum`, `note`, `order_date`, `order_status`, `ship_fee`, `userID_ordcus`) VALUES
(1, '175 Trương Định, Phường 9, Quận 3, TPHCM', 908402431, 'giao trước 19h', '2023-03-20', 'Đang chuẩn bị', 15000, 1),
(2, '89 Trương Định, Phường 9, Quận 3, TPHCM', 908402431, 'giao trước 19h', '2023-04-09', 'Đang chuẩn bị', 15000, 1),
(3, '89 Trương Định, Phường 9, Quận 3, TPHCM', 908402431, 'giao trước 19h', '2023-04-09', 'Đang chuẩn bị', 15000, 1),
(4, '89 Trương Định, Phường 9, Quận 3, TPHCM', 908402431, 'giao trước 19h', '2023-04-09', 'Đang chuẩn bị', 15000, 1),
(5, '89 Trương Định, Phường 9, Quận 3, TPHCM', 908402431, 'giao trước 19h', '2023-04-09', 'Đang chuẩn bị', 15000, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(80) NOT NULL,
  `price` int(11) NOT NULL,
  `unit` varchar(15) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `origin` varchar(50) NOT NULL,
  `sold_number` int(11) NOT NULL,
  `remain_number` int(11) NOT NULL,
  `image` varchar(300) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `unit`, `description`, `origin`, `sold_number`, `remain_number`, `image`, `category_id`) VALUES
(1, 'Cà rốt', 42000, 'kg', 'Cà rốt là thực phẩm không thể thiếu đối với căn bếp của gia đình bạn, trong cà rốt có hàm lượng dinh dưỡng dồi dào và  đặc biệt rất tốt cho mắt. Chúng tôi mang cà rốt đến từ Đà Lạt với chất lượng vượt trội, sạch và không sử dụng thuốc hóa học đến với người tiêu dùng, sản phẩm có nguồn gốc rõ ràng do chính các hộ nộng dân Đà Lạt vun trồng, vậy nên quý khách hàng sẽ an tâm hơn khi mua hàng tại Dallas Organic.', 'Đà Lạt, Việt Nam', 100, 200, 'https://bizweb.dktcdn.net/100/437/874/products/50395098-036e-45bc-bb4c-9dbe31704930.jpg?v=1645611457', 1),
(2, 'Táo đỏ Mỹ', 100000, 'kg', 'Táo đỏ là trái cây nhập khẩu 100% từ Mỹ đạt tiêu chuẩn xuất khẩu toàn cầu. Táo đỏ được bảo quản tươi ngon đến tận tay khách hàng. Táo ngon nhất khi có màu đậm, trái chắc, không bị mềm. Táo có thịt trắng, thơm, khi chín ít bột, giòn nhẹ, ngọt thanh, nhiều nước.', 'Mỹ', 95, 185, 'https://product.hstatic.net/1000301274/product/tao-envy-my-size-64-88_dbcc53a439b44ae393543b650e0412a6.png', 2),
(3, 'Bưởi da xanh túi lưới', 59000, 'trái', 'Bưởi là một loại thực phẩm vô cùng lành mạnh để đưa vào chế độ ăn uống. Phần ruột màu hồng trắng với các tép mọng nước vô cùng hấp dẫn. Trái có vị ngọt, bưởi hái xuống để càng lâu độ ngọt càng cao.', 'Việt Nam', 80, 40, 'https://product.hstatic.net/1000141988/product/buoi_da_xanh_loai_1_tui_luoi_6c8bedf2099f4aba9b8537d343caf55f_large_030b93dffd764b0c9c9abec096534c6a_large.jpg', 2),
(4, 'Dưa lưới giòn', 89000, 'trái', 'Trái có bề ngoài hình bầu dục, trái non màu xanh khi chín chuyển sang màu vàng rất bắt mắt và có vân lưới nhẹ. Thịt dày màu vàng cam, có hương thơm đặc trưng, có độ ngọt cao nhất, giòn, nhiều nước chứ không giòn khô như khác loại dưa lưới thông thường.\r\nTrọng lượng: 1.2kg-1.8kg/trái.', 'Việt Nam', 80, 32, 'https://test.dannygreenbiomarkt.vn/wp-content/uploads/2021/08/Dua-le-Hong-Kim.png', 2),
(5, 'Dâu Hàn Quốc', 219000, 'hộp 250G', 'Dâu tây Hàn Quốc chinh phục khách hàng bởi hương thơm tự nhiên, vị ngọt thanh, \"Ngoại hình\" đỏ au bắt mắt, nguyên cuống xanh tươi. Đóng gói với trọng lượng 250g, tiện lợi cho việc sử dụng và bảo quản. Dâu cũng được xem như \"người bạn\" sắc đẹp, hỗ trợ ngăn ngừa và xóa mờ các nếp nhăn.', 'Hàn Quốc', 43, 37, 'https://product.hstatic.net/1000141988/product/dau_hq_250g_3c3502ff0b144807aa9353ab44564515_1024x1024.jpg', 2),
(6, 'Quýt Úc', 129000, 'kg', 'Quýt được nhập khẩu từ Úc với vị ngọt xen chút chua, tép thịt căng mọng, không bị sượng và rất nhiều nước. Quýt chứa nhiều vitamin tốt cho da, giúp phục hồi sức khỏe nhanh.', 'Úc', 55, 12, 'https://product.hstatic.net/1000141988/product/quyt_uc___moi____2__b59051e032d64624ad538b8021f8f9d1_1024x1024.jpg', 2),
(7, 'Táo Rockit New Zealand', 139000, 'ống 4 trái', 'Táo đỏ đến từ New Zealand, trái vừa ăn, giòn, ngọt và có chút chua nhẹ. Loại táo ngon này được nhiều người yêu thích. Trái to vừa với hộp ống tiện lợi mang đi. Táo có thể dùng ăn trực tiếp, làm nước ép hoặc làm bánh đều ngon.', 'New Zealand', 70, 50, 'https://product.hstatic.net/1000141988/product/tao_rockit_-_ong_4_trai_79d6d52210494552b933aca563dca372_1024x1024.jpg', 2),
(8, 'Dưa hấu đỏ không hạt', 168000, 'trái', 'Dưa Hấu Đỏ Không Hạt thuộc giống dưa không hạt, mọng nước hơn và ngọt đậm. Dưa được trồng và thu hoạch đạt tiêu chuẩn an toàn thực phẩm, đảm bảo cho người tiêu dùng. Mỗi trái có khối lượng từ 3.5 đến 4kg. Dưa hấu có thể ăn trực tiếp, làm nước ép, xay sinh tố, ngon hơn khi ướp lạnh trước khi ăn.', 'Long An, Việt Nam.', 145, 55, 'https://product.hstatic.net/200000319547/product/105639_3-1_8f544c9863e845f2b22b8d84fa5b9495_grande.jpg', 2),
(9, 'Cải bó xôi hữu cơ USDA Food King', 40250, 'túi 250G', 'Cải Bó Xôi Hữu Cơ Food King (250G) còn được gọi là rau chân vịt. Đây là loại rau có vị ngọt thanh, cuống nhỏ và lá xanh đậm, lá mọc chụm lại ở một gốc bé xíu. Cải bó xôi không những là một món ăn ngon mà còn có tác dụng rất “thần kỳ” trong y học để phòng và chữa nhiều bệnh.\r\nTrong rau cải bó xôi có một loại hóa chất steroid tên khoa học là phutoecdy có tác dụng thúc đẩy sự sản xuất protein tự nhiên trong cơ thể lên tới 20%. Rau rất giàu thành phần beta carotene, canxi, tốt cho xương và răng. Carotenoid trong cải bó xôi có khả năng phòng và ngừa ung thư tiền liệt tuyến. Ngoài ra, cải bó xôi chứa nhiều chất kaempferol giúp giảm thiểu nguy cơ ung thư buồng trứng. Cải bó xôi không chỉ giàu vitamin K, mà còn chứa cả mage, đây là một dưỡng chất tuyệt vời trong việc tạo xương. Không những thế, trong cải bó xôi các nhà khoa học còn tìm thấy Luteun. Đây là 1 loại carotenoid có tác dụng ngăn ngừa các bệnh về mắt như bệnh thoái hóa điểm đen và đục thủy tinh thể ở người già.', 'Việt Nam', 210, 100, 'https://product.hstatic.net/1000141988/product/59_a7f254ae45af46cfa0a198ebf0736e47_1024x1024.png', 1),
(10, 'Dưa leo VietGAP', 19500, 'vỉ 500G', 'Dưa Leo An Toàn VietGap là một loại rau ăn quả quen thuộc đối với người Việt Nam. Dưa leo rất mát, giòn, ngon ngọt, thơm hấp dẫn. Với mô hình nông trại khép kín và theo tiêu chuẩn nuôi trông khắt khe. Dưa leo có vị ngọt, thanh mát tự nhiên. Được dùng để ăn sống, làm nước ép và nấu canh.', 'Việt Nam', 225, 150, 'https://product.hstatic.net/1000141988/product/dua_leo_vietgap_be1076cb7a364db4861b8061015d3c76_grande.jpg', 1),
(11, 'Cải xoăn Kale hữu cơ Food King', 44000, 'túi 250G', 'Cải Xoăn Kale Hữu Cơ Food King (250G) là một loại rau xanh rất giàu dinh dưỡng chứa nhiều chất dinh dưỡng và khoáng chất có lợi cho sức khỏe. Cải xoăn Kale là loại thực phẩm tuyệt vời để cải thiện quá trình tiêu hóa, trị táo bón, huyết áp… nhờ hàm lượng chất xơ cao. Đây là sự lựa chọn hoàn hảo để làm những món canh hoặc xào hoặc ép nước cho cả gia đình cùng thưởng thức. \r\nCải xoăn Kale có màu xanh sẫm, thuộc gia đình họ cải và được đánh giá là một trong những loại rau có giá trị dinh dưỡng cao, mang lại nhiều lợi ích cho sức khỏe như: vitamin A, vitamin C và vitamin K, chất xơ. Ngoài ra, cải xoăn Kale còn chứa nhiều chất khoáng cần thiết cho sức khỏe như folate, sắt, canxi, kali, mangan và phốt pho giúp tăng cường hệ miễn dịch, tăng cường chiều cao của trẻ nhỏ, đồng thời cũng chống loãng xương ở người cao tuổi. Vitamin K trong cải xoăn giúp giảm quầng thâm dưới mắt và se lỗ chân lông trên da làm giảm các nếp nhăn. Hơn nữa, nó cũng giúp giảm sưng và vết sẹo có thể hình thành. ', 'Việt Nam', 120, 80, 'https://product.hstatic.net/1000141988/product/website_-_thuong__36__9834c08dbbe84b75bb47495c52ffc37c_1024x1024.jpg', 1),
(12, 'Cải ngọt hữu cơ USDA Food King', 70000, 'túi 250G', 'Cải Ngọt Hữu Cơ Food King (250G) là loại rau thuộc họ Cải, rất dễ ăn và giàu chất dinh dưỡng. Cải ngọt ăn giòn và có vị ngọt nhẹ, thường dùng trong món luộc, xào, nấu canh hoặc ăn lẩu. Sản phẩm đạt chuẩn hữu cơ USDA, đem đến cho bạn một loại thực phẩm an toàn và tốt cho sức khỏe. Ăn cải ngọt giúp ngăn ngừa ung thư gan, hỗ trợ tiêu hóa, tốt cho gan. Rau có vị ngọt tự nhiên, dễ bảo quản và chế biến, phù hợp để luộc, xào, nấu canh hoặc ăn lẩu.', 'Việt Nam', 289, 150, 'https://product.hstatic.net/1000141988/product/website_-_thuong_7c25f47c56154b2fb18509aabc071770_1024x1024.jpg', 1),
(13, 'Hành tây hữu cơ USDA Food King', 56350, 'túi 350G', 'Hành Tây Hữu Cơ Food King thuộc cây thảo, họ hành, có tên khoa học là Allium cepa. Hành tây có vị cay nồng và là thực phẩm thông dụng trong bữa ăn gia đình Việt. Sau khi chế biến, hành tây sẽ cho ra vị ngọt tự nhiên, làm tăng hương vị cho các thực phẩm chế biến đi kèm.\r\nTrong hành tây rất giàu vitamin A, B, C. Ngoài ra, đây còn là một nguồn tiềm năng của các chất acid folic, canxi, phốt pho, magiê, crom, sắt, chất xơ, kali và selen. Chất prostaglandin (prostagladin A, PG) cùng fibrin trong hành giúp giảm huyết áp và chống lại những chất gây tăng áp trong cơ thể. Bên cạnh đó,  hành tây có tác dụng chống oxy hoá rất mạnh và khử các gốc tự do nhờ hai hoạt chất là selen và quercetin. Theo nhiều nghiên cứu cho thấy hành tây có thể giúp tăng mật độ xương, tốt cho phụ nữ mãn kinh. Hành có tác dụng diệt vi khuẩn lây nhiễm, bao gồm cả vi khuẩn E. coli và Salmonella.', 'Việt Nam', 130, 63, 'https://product.hstatic.net/1000141988/product/website_-_thuong__43__dd86a026daa84ad980560fd18bca4661_1024x1024.jpg', 1),
(14, 'Đậu cove hữu cơ USDA Food King', 35000, 'vỉ 250G', 'Đậu Cove Hữu Cơ Food King có tên khoa học là Phaseolus vulgaris. Đậu cove chứa rất ít calo, không chứa chất béo bão hòa mà rất giàu vitamin, khoáng chất và vi chất dinh dưỡng thực vật. Ngoài ra, đậu cove tươi còn có lợi ích đáng kể với sức khỏe người dùng. Sản phẩm được trồng không sử dụng thuốc bảo vệ thực vật. Tính ôn, có tác dụng nhuận tràng, bồi bổ nguyên khí. Thích hợp với những người bị bệnh tim, thận, cao huyết áp. Có thể luộc, làm salad, gỏi hoặc xào đều rất ngon.', 'Việt Nam', 95, 255, 'https://product.hstatic.net/1000141988/product/website_-_thuong__1__00d0f6f953d5424a9e7e6518ab490576_1024x1024.jpg', 1),
(15, 'Bột nêm rau củ hữu cơ Sottolestelle', 105000, 'hộp 100G', 'Bột nêm dưới dạng viên nén dễ hòa tan, giúp rút ngắn thời gian hầm rau củ quả như thông thường mà vẫn cho nước dùng màu sắc tự nhiên với mùi thơm tự nhiên như nước hầm nấu kĩ.\r\nBột nêm rau củ với công nghệ sấy của Sottolestelle và bí quyết chế biến độc đáo giúp bảo toàn được chất xơ, khoáng chất trong mỗi nguyên liệu, không chỉ an toàn, thuần vị tự nhiên mà còn giúp cho bữa ăn của gia đình bạn thêm an lành, tròn vị.\r\nThành phần: Muối, đường nâu, bột rau củ 13,3% (hành tây, cần tây, cà rốt, cà chua, rau bina, rau mùi tây), tinh bột ngô, dầu hướng dương, chiết xuất men, bột nghệ 1%.\r\nSản phẩm dùng làm canh, soup, nước lẩu ngon ngọt tự nhiên mà không cần ninh rau củ; dùng nêm, nếm, tẩm ướp, xào nấu, gia vị chấm các món ăn, bất kể là món chay hay măn, giúp món thịt có thêm chất sơ lợi tiêu hóa.', 'Sottolestelle, Ý', 99, 101, 'https://product.hstatic.net/200000423303/product/bot-nem-rau-cu-huu-co-sotto-1-500x500_3ac6bc9999ff48c2b29480077ac85160.jpg', 3),
(16, 'Ngũ cốc chocoball hữu cơ Bauckhof', 189000, 'túi', '', 'Đức', 35, 55, 'https://product.hstatic.net/200000423303/product/ngu-coc-chocoball-huu-co-bauckhof-300g_25bf85d75c364c94a1887d77f2d24e29_grande.png', 3),
(17, 'Bánh ngũ cốc thanh Annie\'s organic', 186000, 'hộp 151G', 'Bánh ngũ cốc Annie’s Organic Chewy Granola Bars Peanut Butter Chocolate Chip Hộp 151g gồm có 6 thanh x 25g/thanh.\r\nVị sô cô la và hoàn hảo để mang theo bên bạn mọi lúc mọi nơi, những thanh ngũ cốc Annie’s Chocolate Chip Chewy Granola chứa đầy hương vị thơm ngon mà trẻ em và người lớn đều yêu thích. Được tạo ra với hương vị thơm ngon và kết cấu dai, món ăn nhẹ này được chứng nhận hữu cơ và được đóng gói mỗi thanh với 9 gam ngũ cốc nguyên cám.\r\n\r\nNhững thanh granola này là một lựa chọn lành mạnh cho cả gia đình ăn vặt bất cứ khi nào bạn muốn. Hãy mang theo những thanh ngũ cốc hấp dẫn để bữa sáng thêm thú vị, hoặc dùng vào bữa ăn nhẹ. Annie’s sản xuất các sản phẩm thuộc hơn 20 danh mục phù hợp với gia đình – từ đồ ăn nhẹ có hương vị trái cây và ngũ cốc đến pho mát.', 'Mỹ', 35, 65, 'https://product.hstatic.net/200000423303/product/banh_ngu_coc_thanh_annie_s_organic_chewy_ganola_bars_151g_f2ae2725d99346989f20319c97771cb6_grande.png', 3),
(18, 'Bánh ngũ cốc Chocolate chip', 186000, 'hộp 151G', 'Bánh ngũ cốc Annie’s Organic Chewy Granola Bars, Chocolate Chip Hộp 151g gồm có 6 thanh x 25g/thanh.\r\nVị sô cô la và hoàn hảo để mang theo bên bạn mọi lúc mọi nơi, những thanh ngũ cốc Annie’s Chocolate Chip Chewy Granola chứa đầy hương vị thơm ngon mà trẻ em và người lớn đều yêu thích. Được tạo ra với hương vị thơm ngon và kết cấu dai, món ăn nhẹ này được chứng nhận hữu cơ và được đóng gói mỗi thanh với 9 gam ngũ cốc nguyên cám.\r\n\r\nNhững thanh granola này là một lựa chọn lành mạnh cho cả gia đình ăn vặt bất cứ khi nào bạn muốn. Hãy mang theo những thanh ngũ cốc hấp dẫn để bữa sáng thêm thú vị, hoặc dùng vào bữa ăn nhẹ. Annie’s sản xuất các sản phẩm thuộc hơn 20 danh mục phù hợp với gia đình – từ đồ ăn nhẹ có hương vị trái cây và ngũ cốc đến pho mát.', 'Mỹ', 44, 56, 'https://product.hstatic.net/200000423303/product/h_ngu_coc_thanh_annie_s_organic_chewy_ganola_bars__chocolate_chip_151g_6191539d523e4d1e9e152cc629c32b3e_grande.png', 3),
(19, 'Bánh gạo lứt Homnin hữu cơ Lumlum', 89000, 'hộp 100G', '', 'Thái Lan', 120, 110, 'https://product.hstatic.net/200000423303/product/banh_gao_luc_homnin_huu_co_lumlum_9bce676b87df45bf8000db6d9fe10797_grande.jpg', 3),
(20, ' Bánh gạo lứt Jasmine hữu cơ Lumlum', 89000, 'hộp 100G', '', 'Thái Lan', 86, 102, 'https://product.hstatic.net/200000423303/product/banh_gao_lut_jasmine_huu_co_100g_lumlum_-_100g_74293317eacf4ca29790030a561bbf94_grande.jpg', 3),
(21, 'Bánh quy hạt kê hữu cơ cho bé Sottolestelle', 149000, 'túi 250G', '', 'Sottolestelle, Ý', 19, 101, 'https://product.hstatic.net/200000423303/product/banh_qui_hat_ke_huu_co_cho_be_sottolestelle_250g_64ff0445f8e744d7922d3372d9735f90_grande.jpg', 3),
(22, ' Bánh hỏi hữu cơ Bích Chi', 40000, 'hộp 200G', 'Thành phần Gạo hữu cơ 100%.\r\nCách dùng:\r\n- Chế nước sôi vào cho ngập bánh hỏi.\r\n- Đậy kín lại khoảng 1.5 - 2 phút.\r\n- Vớt ra để ráo rồi dùng ngay.\r\n- Hoặc ngâm bánh hỏi trong nước ấm khoảng 2 phút. Hấp khoảng 4 - 5 phút.\r\nLưu ý - phải thoa dầu vô xửng hấp', 'Việt Nam', 101, 59, 'https://product.hstatic.net/200000423303/product/banh_hoi_huu_co_bich_chi_519aa91d028c4c248ab6d126f317bde8_grande.jpg', 3),
(23, 'Ba chỉ bò Obe hữu cơ', 225000, 'vỉ 300G', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò hữu cơ OBE nhé! \r\n- 100% Bò OBE không sử dụng thuốc kháng sinh, hóc môn tăng trưởng.\r\n- Giống bò chất lượng ngon nhất, không biến đổi gene, không sử dụng các chất kích thích.\r\n- Bò ăn mềm, ngọt, thơm, ngậy béo....ĐẬM ĐÀ một cách tự nhiên.\r\n- Nhập khẩu chính thức, có giấy tờ, chứng nhận ORGANIC MỸ, ÚC. ', 'Úc', 120, 45, 'https://product.hstatic.net/200000423303/product/ba-chi-bo-obe_5f33606b744e49bb91eb662ed3ef7b3c_grande.png', 4),
(24, 'Ba rọi rút sườn heo organic', 197500, '500G', NULL, 'Việt Nam', 95, 12, 'https://product.hstatic.net/200000423303/product/ba_roi_rut_suon_heo_huu_co_eef9b174de464c9ba735ee4c2126c8fa_grande.jpg', 4),
(25, 'Bắp bò hữu cơ obe - obe shin', 255000, '300G', 'Thịt bò Obe organic của Úc chuẩn hữu cơ quốc tế: \r\n- 100% Bò OBE được nuôi cỏ tự nhiên.\r\n- 100% Bò OBE không sử dụng thức ăn công nghiệp.\r\n- 100% Bò OBE không sử dụng thuốc kháng sinh, hóc môn tăng trưởng.\r\n- Giống bò chất lượng ngon nhất, không biến đổi gene, không sử dụng các chất kích thích. - Bò ăn mềm, ngọt, thơm, ngậy béo....ĐẬM ĐÀ một cách tự nhiên.\r\n- Nhập khẩu chính thức, có giấy tờ, chứng nhận ORGANIC MỸ, ÚC.', 'Úc', 76, 24, 'https://product.hstatic.net/200000423303/product/bap-bo-obe-huu-co_d5b0d23c4939402ba43e978a01bbd7da_grande.png', 4),
(26, 'Đuôi heo hữu cơ', 102000, '300G', 'Đuôi heo có chứa nhiều chất dinh dưỡng có ích như: protein 26,4%, lipid 22,7%, glucid 4%, nhiều chất khoáng vi lượng như can-xi, photpho, sắt... Chất protein của đuôi động vật (chủ yếu là ở da) gồm nhiều chất hợp thành như: collagen, elastin, keratin, albumin, globulin...\r\nTheo Đông y, đuôi heo có tác dụng bồi bổ thận tinh, ích não tủy, bổ âm, làm mạnh tỳ vị, làm mạnh xương sống và thất lưng, tăng cường chức năng hoạt động của da, giúp phát triển cơ bắp và thông huyết mạch, có ích cho người bị phong thấp, đau nhức tay chân, đau lưng mỏi gối, đau các khớp xương. Thường dùng trong các trường hợp: thận suy, tinh kém, đau lưng, đau xương sống, cổ lưng cứng đờ khó cúi ngửa, rối loạn tâm thần, động kinh, suy nhược thần kinh, da bị lão hóa. Đuôi heo có thể được chế biến thành nhiều món ngon & bổ dưỡng như: canh đuôi heo hầm đậu phộng, canh đuôi heo & bí đỏ, soup cà rốt khoai tây, đuôi heo chiên giòn...', 'Việt Nam', 24, 16, 'https://product.hstatic.net/200000423303/product/duoi-heo-huu-co_c21cb690881d4ce481e512d403a8a8d1_grande.jpg', 4),
(27, 'Cốt lết heo hữu cơ', 172500, '500G', 'Cốt lết hữu cơ là phần thịt ngon, mềm và nhiều thịt nhất so với các phần khác của thịt heo sạch hữu cơ. Cốt lết hữu cơ rất giàu protein, là nguồn dinh dưỡng cần thiết để tái tạo lại cơ bắp sau khi tập thể dục và giúp tăng cường hệ miễn dịch. Ngoài ra, thịt cốt lết hữu cơ còn cung cấp một lượng axit amin cần thiết cho chế độ ăn của bạn và gia đình bạn. Sử dụng thực phẩm thịt cốt lết FAU, bạn hoàn toàn an tâm về một sản phẩm cao cấp đúng chuẩn hữu cơ, dựa vào quy trình chăn nuôi khép kín từ thức ăn, chăn nuôi chọn lọc, đến giết mổ và vận chuyển.  Đây là loại thịt đầu tiên và duy nhất của Việt Nam đáp ứng tiêu chuẩn khắt khe của Hiệp hội hữu cơ Canada, đảm bảo vệ sinh an toàn thực phẩm . Thành phần dinh dưỡng:  Cốt lết hữu cơ là nguồn cung cấp protein, vitamin và các khoáng chất khác nhau như selen, kẽm, vitamin B12, vitamin B6, niacin, phốt pho, sắt … ', 'Việt Nam', 55, 45, 'https://product.hstatic.net/200000423303/product/cot_let_heo_huu_co_-_500g_eade48f1a6104af7b273a469a8a10205_grande.jpg', 4),
(28, 'Bộ xương cá hồi hữu cơ Vinkenco', 190000, 'bộ', 'Bảo quản: ngăn mát tủ lạnh dùng trong 2 ngày; ngăn đông tủ lạnh dùng trong tháng;\r\nChế biến: Dùng cho canh chua, canh riêu cá hồi, lẩu cá hồi,...\r\nMỗi bộ xương cá hồi từ 1kg - 1.2kg.', 'Nauy', 13, 7, 'https://gofood.vn/upload/r/san-pham/ca-hoi-organic-vikenco/xuong-ca-hoi-organic/xuong-ca-organic-3.jpg', 4),
(29, 'Cá chẽm tự nhiên phi lê', 188000, '330G', 'Cá chẽm là một trong những loài cá thuộc họ cá vượt, rất nổi tiếng trên thế giới vì hàm lượng dinh dưỡng cao không thua kém cá hồi.\r\nCá chẽm Người giữ rừng là cá được khai thác thiên nhiên.\r\nKích thước cá lớn, chọn lọc những con cá từ 5 kilogram trở lên, có con lên đến 15 kilogram. Điều đó càng làm cho giá trị của cá được nâng cao. Lưu ý rằng: cá chẽm nuôi chỉ đạt kích thước từ 0.8-1 kilogram.', 'Bến Tre, Việt Nam', 95, 7, 'https://product.hstatic.net/200000423303/product/ca_chem_filet_aad1cc03fef2472088b07e9e83f1963d_grande.jpg', 4),
(30, 'Bào ngư tươi nhập khẩu (size 10-12)', 294000, '300G', '', 'Hàn Quốc', 15, 25, 'https://product.hstatic.net/200000423303/product/bao_ngu_thuong_hang_c523abf2e9ad446dbeb5fd9a23a57d1a_grande.png', 4),
(31, 'Bò lúc lắc Obe organic', 232500, 'vỉ 300G', 'Thịt Bò Úc Tươi Chuẩn Hữu Cơ 100% - Nhãn Hiệu OBE Một phần thịt bò hữu cơ ăn cỏ tự nhiên hoàn toàn trong suốt quá trình sinh trưởng có thể đáp ứng đầy đủ lượng dinh dưỡng cần thiết trong ngày cho protein, sắt, kẽm và vitamin B. Ngon và bổ dưỡng! Với sản phẩm tươi sống, trọng lượng thực tế có thể chênh lệch khoảng 10%, sản phẩm cắt viên nhỏ sẵn tầm 250-300gr/vỉ', 'Úc', 210, 215, 'https://product.hstatic.net/200000423303/product/bo-luc-lac-obe_e0d3ed426bac446f93181db29ae7fb72_grande.png', 4),
(32, 'Lõi vai bò úc hữu cơ obe', 232500, '300G', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò hữu cơ OBE nhé!\r\n- 100% Bò OBE không sử dụng thuốc kháng sinh, hóc môn tăng trưởng.\r\n- Giống bò chất lượng ngon nhất, không biến đổi gene, không sử dụng các chất kích thích.\r\n- Bò ăn mềm, ngọt, thơm, ngậy béo....ĐẬM ĐÀ một cách tự nhiên.\r\n- Nhập khẩu chính thức, có giấy tờ, chứng nhận ORGANIC MỸ, ÚC. ', 'Úc', 96, 12, 'https://product.hstatic.net/200000423303/product/loi-vai-bo-uc-huu-co-obe_d78295a76d6e463da43183efb561e868_grande.jpg', 4),
(33, 'Nước ép nho hữu cơ 750ml Bioitalia', 139000, 'chai', 'Nước Ép Nho Hữu Cơ BioItalia (750ml) với thành phần 100% Organic được nhập khẩu trực tiếp từ Ý.\r\nThành phần: 100% nước ép nho hữu cơ.\r\nHướng dẫn sử dụng: Dùng trực tiếp.\r\nHướng dẫn bảo quản: Nơi khô ráo, thoáng mát, tránh tiếp xúc trực tiếp ánh nắng mặt trời. Bảo quản lạnh sau khi mở nắp và sử dụng trong vòng 1 tuần. Hiện tượng lắng tự nhiên có thể xảy ra.\r\nLắc đều trước khi dùng.', 'Ý', 85, 17, 'https://product.hstatic.net/200000423303/product/nuoc_ep_nho_huu_co_750ml_-_bioitalia_cb7e26db09e44a0389678d51671b6a1a_grande.jpg', 5),
(34, 'Nước ép táo hữu cơ 750ml Bioitalia', 139000, 'chai', 'Nước Ép Táo Hữu Cơ BioItalia (750ml) với thành phần 100% Organic được nhập khẩu trực tiếp từ Ý.\r\nThành phần: 100% nước ép táo hữu cơ.\r\nHướng dẫn sử dụng: Dùng trực tiếp sau khi mở nắp chai.\r\nHướng dẫn bảo quản: Nơi khô ráo, thoáng mát, tránh tiếp xúc trực tiếp ánh nắng mặt trời. Bảo quản lạnh sau khi mở nắp và sử dụng trong vòng 1 tuần. Hiện tượng lắng tự nhiên có thể xảy ra. Lắc đều trước khi dùng.', 'Ý', 129, 1, 'https://product.hstatic.net/200000423303/product/nuoc_ep_tao_huu_co_750ml_-_bioitalia_a2bfa6e8876d4aa8ab94fed115ed5281_grande.jpg', 5),
(35, 'Nước ép việt quất hữu cơ Lakewood 946ml', 365000, 'chai', 'Lakewood Juice là thường hiệu nước ép trái cây hữu cơ hàng đầu Hoa Kỳ và được xuất khẩu sang nhiều cường quốc tiên tiến trên thế giới. Với dây chuyền sản xuất khép kín, hiện đại cùng các công nghệ tối tân, đầu vào là những loại trái cây hữu cơ siêu sạch, Lakewood đã mang tới cho người tiêu dùng trên khắp thế giới những sản phẩm bổ dưỡng và tinh khiết nhất. Mọi khâu từ sàng lọc trái cây, ép đều thực hiện trong môi trường vệ sinh, đóng chai thủy tinh, vô trùng tuyệt đối. Để đảm bảo chất lượng tự nhiên, toàn bộ sản phẩm nước ép trái cây hữu cơ của Lakewood đều chỉ được sản xuất theo đúng mùa vụ của từng loại trái cây.\r\nNước ép hỗn hợp trái cây hữu cơ Lakewood với vị ngọt tự nhiên từ hơn 1,4kg trái cây tươi hữu cơ nguyên chất 100% trong mỗi chai, không chứa gluten; không chứa chất biến đổi gen, phân bón tổng hợp, thuốc trừ sâu, thuốc diệt cỏ độc hại hoặc chất điều hòa sinh trưởng; không chất bảo quản; không gây dị ứng.', 'Mỹ', 48, 2, 'https://product.hstatic.net/200000423303/product/nuoc_ep_viet_quat_huu_co_lakewood_946ml_8b0443f22b3a426593b38a35a09ea95e_grande.jpg', 5),
(36, 'Nước lựu hữu cơ Georgia\'s Natural 750ml', 189000, 'chai', 'Sản phẩm nước ép từ Georgia\'s Natural sử dụng 100% trái cây tươi được trồng và sản xuất theo tiêu chuẩn châu Âu, được chứng nhận USDA ORGANIC. Những trái lựu mọng nước được trồng ở những khu vườn hữu cơ tại Georgia trong môi trường tự nhiên và an toàn nhất. Để đảm bảo chất lượng tự nhiên, toàn bộ sản phẩm nước ép Georgia\'s Natural đều chỉ được sản xuất theo đúng mùa vụ của từng loại trái cây và rau củ.', 'Georgia, Mỹ', 98, 2, 'https://product.hstatic.net/200000423303/product/nuoc_luu_huu_co_georgia_s_natural_253e38f68f2d4277aaf56f65574a5eda_grande.jpg', 5),
(37, 'Nước mận hữu cơ Taylor 946ml', 259000, 'chai', 'Nước Mận Hữu Cơ Taylor 946ml là một sản phẩm của công ty Taylor Brother Farms California với thành phần 100% là mận nguyên chất. Do đó, có thể giữ nguyên được hương vị đặc trưng. Do có rất nhiều vitamin nên việc uống nước mận hàng ngày sẽ rất tốt cho sức khỏe đặc biệt giúp làm đẹp da, hỗ trợ giảm cân.', 'California, Mỹ', 35, 47, 'https://product.hstatic.net/200000423303/product/nuoc-man-thien-nhien-taylor-946ml_0494c028001a4403a616bbdc5904e237_grande.jpg', 5),
(38, 'Bánh mì sandwich bột mì đen Saint Honore', 47000, 'gói', NULL, 'Việt Nam', 105, 23, 'https://product.hstatic.net/1000141988/product/sandwich_bot_mi_den_6bc95c17297d4c88a7005bf27980b15d_1024x1024.jpg', 6),
(39, 'Bánh mì gối ngũ cốc kiểu Pháp Saint Honore', 39000, 'gói', NULL, 'Việt Nam', 43, 12, 'https://product.hstatic.net/1000141988/product/banh_goi_ngu_coc_836b6d4f93bc43c19f0c21a19c926d1b_grande.jpg', 6),
(40, 'Bánh mì hoa cúc mini Harry\'s', 115000, 'túi 210G', 'Bánh mỳ hoa cúc Harrys Brioche Tressée Pháp được làm từ những nguyên liệu chọn lọc kĩ lưỡng như: bột mì nguyên chất, trứng tươi, sợi lúa mì, bột lúa mạch. Sản phẩm hoàn toàn không chứa chất phụ gia, chất bảo quản, không chứa thành phần biến đổi gen, các hooc-môn tăng trưởng tuyệt đối an toàn cho sức khỏe của trẻ. Sản phẩm được chế biến trên dây chuyền công nghệ và quy trình hiện đại của Pháp, kết hợp cùng sự tỉ mỉ trong việc tạo hình dưới bàn tay tài hoa của người thợ làm bánh, mang đến những chiếc bánh đẹp mắt và hương vị đặc biệt thơm ngon. Bánh dài, xốp mềm, màu vàng hơi sậm, có mùi hương dịu ngọt của hoa cúc, của mật ong, kích thích mọi giác quan, mang đến giờ ăn nhẹ lí tưởng cho cả gia đình. Bánh mỳ hoa cúc bổ sung nguồn năng lượng dồi dào tiếp sức cho cơ thể trong một ngày dài vui chơi, học tập và làm việc. Sản phẩm đóng gói 6 chiếc 1 túi, nhỏ gọn thích hợp cho bé mang đi chơi, đi học, vận động ở bên ngoài hay khi đi picnic cùng gia đình.', 'Pháp', 23, 7, 'https://product.hstatic.net/1000141988/product/thiet_ke_khong_ten_ff26ba3e4aba4186ba9979f624d3b842_1024x1024.jpg', 6),
(41, 'Organic Tower Bread 750g - Bánh mì tháp hữu cơ', 175000, 'gói', 'Thành phần cấu tạo: Lúa mạch đen hữu cơ (28%), nước, hạt hướng dương hữu cơ (13%), bột mì nguyên cám hữu cơ, bột lúa mạch đen hữu cơ, cà rốt hữu cơ, bột chua khô hữu cơ (bột lúa mì đen nguyên cám hữu cơ, nước), hạt lanh hữu cơ, hạt óc chó hữu cơ, muối ăn không iốt, hạt bí ngô hữu cơ, men, bột mạch nha hữu cơ (lúa mạch đen), bột lúa mạch đen hữu cơ. Có thể có nguồn gốc của sữa, đậu nành.', 'Anh', 56, 12, 'https://product.hstatic.net/200000166691/product/50g_hgbtk_organic-tower-bread_750g-fp_4032c35f9dfd48e88c39f4d04fc31c36_9084bb8e299b4906aaf757abaafc3f3e_1024x1024.jpg', 6),
(42, 'Organic Wholemeal Loaf 750g - Ổ bánh mì nguyên cám hữu cơ', 165000, 'gói', 'Thành phần cấu tạo: Bột cám hữu cơ (lúa mạch đen, lúa mì), nước, bột lúa mạch đen nguyên cám hữu cơ, bột chua khô hữu cơ (bột mì hữu cơ, nước), hạt hướng dương hữu cơ, yến mạch hữu cơ, muối ăn không iốt, men, bột lúa mì hữu cơ, hạt caraway hữu cơ, thì là hữu cơ, rau mùi hữu cơ. Có thể có nguồn gốc của sữa, hạt, đậu nành.', 'Anh', 42, 8, 'https://product.hstatic.net/200000166691/product/_hgbtk_organic_wholemeal_loaf_750g-fp_d8df7fdff8114e9b82d7c9f0a90635b0_314009f6cd0642d987c4f98df3530e73_1024x1024.jpg', 6),
(43, 'Pumpkin Seed Bread 500g - Bánh mì hạt bí ngô', 145000, 'gói', 'Thành phần cấu tạo: Bột mì (bột mì, bột lúa mạch đen), nước, hạt bí ngô (12%), bột lúa mạch đen, bột mì, bột đậu nành, hạt lanh, bột mạch nha (lúa mạch), bột mì (lúa mì), bột lúa mạch đen lúa mạch đen, cám lúa mì, men, bột chua (bột lúa mạch đen, nước), muối ăn không iốt, dextrose, dầu thực vật (hạt cải dầu), bột mạch nha lúa mạch đen, axit hóa: axit axetic. Có thể có nguồn gốc từ sữa, hạt, hạt vừng.', 'Anh', 32, 18, 'https://product.hstatic.net/200000166691/product/500g_hgbtk_pumpkin_seed_bread_500g-fp_99664250aea94cfcb4744e1d72044cc3_dc7612f25c2b4f42b8a707984540a41d_1024x1024.jpg', 6),
(44, 'Crusty Bread 500g - Bánh mì giòn', 95000, 'gói', 'Thành phần cấu tạo: Bột (lúa mạch đen, lúa mì), bột chua tự nhiên (bột lúa mạch đen, nước), nước, muối ăn không iốt, men, ngò rí, hạt caraway, thì là, hồi, bột chua (bột lúa mạch đen, nước), gluten lúa mì. Có thể có nguồn gốc từ sữa, hạt, đậu nành.', 'Anh', 98, 2, 'https://product.hstatic.net/200000166691/product/sterl_500g_hgbtk_crusty_bread_500g-fp_55b9e196c3f94c4ea7f36b10a62a04f5_411d8171861a4bc393dd5695298316ae_1024x1024.jpg', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_account`
--

CREATE TABLE `user_account` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `fullname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sex` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `DoB` date NOT NULL,
  `phonenumber` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT 'https://i.pinimg.com/736x/cc/16/0c/cc160c19dbd165c43046c176223f10fe.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_account`
--

INSERT INTO `user_account` (`id`, `username`, `password`, `fullname`, `sex`, `DoB`, `phonenumber`, `email`, `address`, `avatar`) VALUES
(1, 'congtran14', '12345678', 'Trần Chí Công', 'Nam', '2002-05-14', '0908999999', 'cong.tran1452002@hcmut.edu.vn', '175 Trương Định, Phường 9, Quận 3, TPHCM', 'https://i.pinimg.com/736x/cc/16/0c/cc160c19dbd165c43046c176223f10fe.jpg'),
(2, 'khoanguyen333', '87654321', 'Nguyễn Đặng Anh Khoa', 'Nam', '2002-01-01', '0908999888', 'khoanguyen2002@gmail.com', '2 Cách Mạng Tháng 8', 'https://i.pinimg.com/736x/cc/16/0c/cc160c19dbd165c43046c176223f10fe.jpg'),
(3, 'vy.khanhho12', 'vyvyvy999', 'Hồ Vũ Khánh Vy', 'Nữ', '2002-12-24', '0909123654', 'vykhanhh1213@gmail.com', '16/9 Kỳ Đồng, Phường 9, Quận 3, TPHCM', 'https://i.pinimg.com/736x/cc/16/0c/cc160c19dbd165c43046c176223f10fe.jpg'),
(4, 'phuck21', 'phuc1357', 'Huỳnh Nguyên Phúc', 'Nam', '2003-02-28', '0909123456', 'phuchuynh.k21@hcmut.edu.vn', 'Ký túc xá khu A', 'https://i.pinimg.com/736x/cc/16/0c/cc160c19dbd165c43046c176223f10fe.jpg'),
(5, 'thangduong.k21', 'duongthang2468', 'Dương Phúc Thắng', 'Nam', '2003-04-30', '0908987654', 'thangduong2003@gmail.com', 'Ký túc xá khu A', 'https://i.pinimg.com/736x/cc/16/0c/cc160c19dbd165c43046c176223f10fe.jpg');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin_account`
--
ALTER TABLE `admin_account`
  ADD PRIMARY KEY (`userID_admin`);

--
-- Chỉ mục cho bảng `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`bank_name`);

--
-- Chỉ mục cho bảng `bank_account`
--
ALTER TABLE `bank_account`
  ADD PRIMARY KEY (`bank_name`,`acc_number`),
  ADD KEY `customerid_bankacc_constraint` (`customerID`);

--
-- Chỉ mục cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customer_account`
--
ALTER TABLE `customer_account`
  ADD PRIMARY KEY (`userID_customer`);

--
-- Chỉ mục cho bảng `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`productID`,`customerID`),
  ADD KEY `customID_feedback_constraint` (`customerID`);

--
-- Chỉ mục cho bảng `ordered_product`
--
ALTER TABLE `ordered_product`
  ADD PRIMARY KEY (`productID`,`orderID`),
  ADD KEY `orderID_constraint` (`orderID`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_orders_constraint` (`userID_ordcus`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_constraint` (`category_id`);

--
-- Chỉ mục cho bảng `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT cho bảng `user_account`
--
ALTER TABLE `user_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin_account`
--
ALTER TABLE `admin_account`
  ADD CONSTRAINT `admin_accounts` FOREIGN KEY (`userID_admin`) REFERENCES `user_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bank_account`
--
ALTER TABLE `bank_account`
  ADD CONSTRAINT `bankname_constraint` FOREIGN KEY (`bank_name`) REFERENCES `banks` (`bank_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customerid_bankacc_constraint` FOREIGN KEY (`customerID`) REFERENCES `customer_account` (`userID_customer`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `customer_account`
--
ALTER TABLE `customer_account`
  ADD CONSTRAINT `customer_accounts` FOREIGN KEY (`userID_customer`) REFERENCES `user_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `customID_feedback_constraint` FOREIGN KEY (`customerID`) REFERENCES `customer_account` (`userID_customer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productID_feedback_constraint` FOREIGN KEY (`productID`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `ordered_product`
--
ALTER TABLE `ordered_product`
  ADD CONSTRAINT `orderID_constraint` FOREIGN KEY (`orderID`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `productID_constraint` FOREIGN KEY (`productID`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `customers_orders_constraint` FOREIGN KEY (`userID_ordcus`) REFERENCES `customer_account` (`userID_customer`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `category_constraint` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
