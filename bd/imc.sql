DROP DATABASE IF EXISTS bdimc;
CREATE DATABASE IF NOT EXISTS bdimc;
USE bdimc;

CREATE TABLE IF NOT EXISTS tusuario (
    nusuarioID INT AUTO_INCREMENT PRIMARY KEY,
    ccedula VARCHAR(15) UNIQUE NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS timc (
    nimcID INT AUTO_INCREMENT PRIMARY KEY,
    naltura DECIMAL(5,2) NOT NULL,
    npeso DECIMAL(5,2) NOT NULL,
    dfecha DATE NOT NULL DEFAULT (CURRENT_DATE()),
    nimc DECIMAL(5,2) NOT NULL,
    ccategoria VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS timc_usuario (
    nimc_usuarioID INT AUTO_INCREMENT PRIMARY KEY,
    nusuarioFK INT NOT NULL,
    nimcFK INT NOT NULL,
    FOREIGN KEY (nusuarioFK) REFERENCES tusuario(nusuarioID),
    FOREIGN KEY (nimcFK) REFERENCES timc(nimcID)
) ENGINE=InnoDB;

-- Procedimientos almacenados
DELIMITER //

CREATE PROCEDURE sp_insertar_imc(
    IN p_cedula VARCHAR(15),
    IN p_altura DECIMAL(5,2),
    IN p_peso DECIMAL(5,2),
    IN p_imc DECIMAL(5,2),
    IN p_categoria VARCHAR(20)
)
BEGIN
    DECLARE v_usuario_id INT;
    DECLARE v_imc_id INT;
    
    -- Verificar si el usuario existe
    SELECT nusuarioID INTO v_usuario_id
    FROM tusuario
    WHERE ccedula = p_cedula;

    IF v_usuario_id IS NULL THEN
        -- El usuario no existe
        SELECT 'USER_NOT_FOUND' AS result;
    ELSE
        -- Insertar el registro de IMC
        INSERT INTO timc (naltura, npeso, nimc, ccategoria)
        VALUES (p_altura, p_peso, p_imc, p_categoria);

        SET v_imc_id = LAST_INSERT_ID();

        -- Relacionar el IMC con el usuario
        INSERT INTO timc_usuario (nusuarioFK, nimcFK)
        VALUES (v_usuario_id, v_imc_id);

        SELECT 'SUCCESS' AS result;
    END IF;
END //

DELIMITER ;

insert into tusuario(ccedula)
value('1005259344'),('1005259345'),('1005259346'),('1005259347');
