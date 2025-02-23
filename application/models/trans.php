CREATE PROCEDURE sp_cheque_leaves_posting(IN param_requisition_id INT, OUT param_sp_success TINYINT)

BEGIN

DECLARE details_id_loop INT DEFAULT 0;
DECLARE starting_serial_no INT;
DECLARE record_not_found INT DEFAULT 0;
DECLARE cursor_details_id CURSOR FOR SELECT details_id FROM khan_trial.table_requisition_details WHERE requisition_master_id = param_requisition_id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET record_not_found = 1;

DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
DECLARE EXIT HANDLER FOR SQLWARNING ROLLBACK;

START TRANSACTION;

SET param_sp_success = 0;

SELECT MAX(ending_serial_no) INTO starting_serial_no FROM table_cheque_leaves_posting;

IF starting_serial_no IS NULL THEN
 SET starting_serial_no = 1;
ELSE
 SET starting_serial_no = starting_serial_no + 1;
END IF;

OPEN cursor_details_id;

all_details_id:LOOP

 FETCH cursor_details_id INTO details_id_loop;
 
 IF record_not_found THEN 
  LEAVE all_details_id;
 END IF;
 
 INSERT INTO table_cheque_leaves_posting 
 VALUES(details_id_loop,starting_serial_no,starting_serial_no+9,12,12,'A Rahim Khan');
 
 SET starting_serial_no = starting_serial_no + 10;

END LOOP all_details_id;

CLOSE cursor_details_id;

UPDATE table_requisition_master SET cheque_leaves_posting = 1
WHERE requisition_id = param_requisition_id;

SET param_sp_success = 1;

COMMIT;

END