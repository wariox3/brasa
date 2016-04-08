DELIMITER $$

USE `bdbrasa`$$

DROP PROCEDURE IF EXISTS `spRhuHorarioAcceso`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spRhuHorarioAcceso`(IN fecha DATE)
BEGIN
	DECLARE terminada BOOLEAN DEFAULT FALSE;
	DECLARE codigoContrato INTEGER;
	DECLARE codigoEmpleado INTEGER;
	DECLARE codigoHorarioPeriodo INTEGER;
	DECLARE codigoHorario INTEGER;
	DECLARE codigoTurno VARCHAR(5);
	DECLARE diaSemana INTEGER;
	DECLARE salidaDiaSiguiente TINYINT;
	DECLARE horaEntradaTurno TIME;
	DECLARE horaSalidaTurno TIME;
	DECLARE fechaSalida DATE;

	DECLARE c1 CURSOR FOR SELECT codigo_contrato_pk, codigo_empleado_fk FROM rhu_contrato WHERE fecha_hasta <= fecha OR indefinido = 1;
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET terminada = TRUE;

	#Crear periodo
	INSERT INTO rhu_horario_periodo (fecha_periodo, estado_generado) VALUES (fecha, 1);
	SELECT codigo_horario_periodo_pk INTO codigoHorarioPeriodo FROM rhu_horario_periodo WHERE fecha_periodo = fecha;
	SELECT DAYOFWEEK(fecha) INTO diaSemana;

	#INSERT into prueba VALUES(diaSemana);

	OPEN c1;
	c1_loop: LOOP
	FETCH c1 INTO codigoContrato, codigoEmpleado;
		IF `terminada` THEN LEAVE c1_loop; END IF;
		SELECT codigo_horario_fk INTO codigoHorario FROM rhu_empleado WHERE codigo_empleado_pk = codigoEmpleado;	

		IF diaSemana = 6 THEN
			SELECT viernes INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;
		
		SELECT salida_dia_siguiente, hora_desde, hora_hasta INTO salidaDiaSiguiente, horaEntradaTurno, horaSalidaTurno FROM rhu_turno WHERE codigo_turno_pk = codigoTurno COLLATE utf8_spanish_ci;
		IF salidaDiaSiguiente = 1 THEN
			SET fechaSalida = ADDDATE(fecha, INTERVAL 1 DAY);
		ELSE
			SET fechaSalida = fecha;
		END IF;
		#INSERT into prueba VALUES(CONCAT(codigoEmpleado, ' ', codigoHorario, ' ', codigoTurno));
		INSERT INTO rhu_horario_acceso (codigo_horario_periodo_fk, codigo_empleado_fk, codigo_turno_fk, hora_entrada_turno, hora_salida_turno, salida_dia_siguiente, fecha_entrada, fecha_salida) VALUES (codigoHorarioPeriodo, codigoEmpleado, codigoTurno, horaEntradaTurno, horaSalidaTurno, salidaDiaSiguiente, fecha, fechaSalida);
	END LOOP c1_loop;
	CLOSE c1;

	#DECLARE done BOOLEAN DEFAULT FALSE;
	#DECLARE uid integer;
	#DECLARE newdate integer;
	#DECLARE c1 cursor for SELECT id,timestamp from employers ORDER BY id ASC;
	#DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = TRUE;
	#open c1;
	#c1_loop: LOOP
	#fetch c1 into uid,newdate;
	#        IF `done` THEN LEAVE c1_loop; END IF; 
	#        UPDATE calendar SET timestamp = newdate  WHERE id=uid;
	#END LOOP c1_loop;
	#CLOSE c1;

END$$

DELIMITER ;