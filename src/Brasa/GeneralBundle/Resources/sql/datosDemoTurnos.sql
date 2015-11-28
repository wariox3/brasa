insert into `tur_recurso` (`codigo_recurso_pk`, `nombreCorto`, `comentarios`) values('1','JUAN EUCLIDES MONTOYA ARBELAEZ',NULL);
insert into `tur_recurso` (`codigo_recurso_pk`, `nombreCorto`, `comentarios`) values('2','JOSE MARTIN GUTIERREZ HALO',NULL);
insert into `tur_plantilla` (`codigo_plantilla_pk`, `nombre`, `comentarios`, `estado_autorizado`) values('1','24x3',NULL,'1');
insert into `tur_plantilla_detalle` (`codigo_plantilla_detalle_pk`, `codigo_plantilla_fk`, `dia_1`, `dia_2`, `dia_3`, `dia_4`, `dia_5`, `dia_6`, `dia_7`, `dia_8`, `dia_9`, `dia_10`, `dia_11`, `dia_12`, `dia_13`, `dia_14`, `dia_15`, `dia_16`, `dia_17`, `dia_18`, `dia_19`, `dia_20`, `dia_21`, `dia_22`, `dia_23`, `dia_24`, `dia_25`, `dia_26`, `dia_27`, `dia_28`, `dia_29`, `dia_30`, `dia_31`, `horas`) values('2','1','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','0');
insert into `tur_plantilla_detalle` (`codigo_plantilla_detalle_pk`, `codigo_plantilla_fk`, `dia_1`, `dia_2`, `dia_3`, `dia_4`, `dia_5`, `dia_6`, `dia_7`, `dia_8`, `dia_9`, `dia_10`, `dia_11`, `dia_12`, `dia_13`, `dia_14`, `dia_15`, `dia_16`, `dia_17`, `dia_18`, `dia_19`, `dia_20`, `dia_21`, `dia_22`, `dia_23`, `dia_24`, `dia_25`, `dia_26`, `dia_27`, `dia_28`, `dia_29`, `dia_30`, `dia_31`, `horas`) values('3','1','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','0');
insert into `tur_plantilla_detalle` (`codigo_plantilla_detalle_pk`, `codigo_plantilla_fk`, `dia_1`, `dia_2`, `dia_3`, `dia_4`, `dia_5`, `dia_6`, `dia_7`, `dia_8`, `dia_9`, `dia_10`, `dia_11`, `dia_12`, `dia_13`, `dia_14`, `dia_15`, `dia_16`, `dia_17`, `dia_18`, `dia_19`, `dia_20`, `dia_21`, `dia_22`, `dia_23`, `dia_24`, `dia_25`, `dia_26`, `dia_27`, `dia_28`, `dia_29`, `dia_30`, `dia_31`, `horas`) values('4','1','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','0');
insert into `tur_turno` (`codigo_turno_pk`, `nombre`, `hora_desde`, `hora_hasta`, `horas`, `horas_diurnas`, `horas_nocturnas`, `servicio`, `programacion`, `comentarios`) values('1','06:00 A 18:00','06:00:00','18:00:00','12','12','0','0','1',NULL);
insert into `tur_turno` (`codigo_turno_pk`, `nombre`, `hora_desde`, `hora_hasta`, `horas`, `horas_diurnas`, `horas_nocturnas`, `servicio`, `programacion`, `comentarios`) values('2','18:00 A 06:00','18:00:00','06:00:00','12','4','8','0','1',NULL);
insert into `tur_turno` (`codigo_turno_pk`, `nombre`, `hora_desde`, `hora_hasta`, `horas`, `horas_diurnas`, `horas_nocturnas`, `servicio`, `programacion`, `comentarios`) values('D','DESCANSO','00:00:00','00:00:00','0','0','0','0','1',NULL);
insert into `tur_turno` (`codigo_turno_pk`, `nombre`, `hora_desde`, `hora_hasta`, `horas`, `horas_diurnas`, `horas_nocturnas`, `servicio`, `programacion`, `comentarios`) values('F12D','12 HORAS DIA','00:00:00','00:00:00','12','12','0','1','0',NULL);
insert into `tur_turno` (`codigo_turno_pk`, `nombre`, `hora_desde`, `hora_hasta`, `horas`, `horas_diurnas`, `horas_nocturnas`, `servicio`, `programacion`, `comentarios`) values('F24','24 HORAS','00:00:00','00:00:00','24','16','8','1','0',NULL);