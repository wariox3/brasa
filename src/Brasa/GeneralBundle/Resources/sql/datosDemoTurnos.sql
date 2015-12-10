

insert  into `tur_modalidad_servicio`(`codigo_modalidad_servicio_pk`,`nombre`,`tipo`,`porcentaje`,`comentarios`) values 
(1,'SIN ARMA',1,8,NULL),
(2,'CON ARMA',1,10,NULL),
(3,'CANINO',1,11,NULL);

insert  into `tur_pedido_tipo`(`codigo_pedido_tipo_pk`,`nombre`) values 
(1,'OCASIONAL'),
(2,'PERMANENTE');

insert  into `tur_periodo`(`codigo_periodo_pk`,`nombre`,`comentarios`) values 
(1,'MES',NULL),
(2,'DIA',NULL);

insert  into `tur_plantilla`(`codigo_plantilla_pk`,`nombre`,`estado_autorizado`,`comentarios`,`dias`) values 
(1,'2DIASx2NOCHEx2DESC',0,NULL,6),
(2,'5X3',1,NULL,0);

insert  into `tur_plantilla_detalle`(`codigo_plantilla_detalle_pk`,`codigo_plantilla_fk`,`dia_1`,`dia_2`,`dia_3`,`dia_4`,`dia_5`,`dia_6`,`dia_7`,`dia_8`,`dia_9`,`dia_10`,`dia_11`,`dia_12`,`dia_13`,`dia_14`,`dia_15`,`dia_16`,`dia_17`,`dia_18`,`dia_19`,`dia_20`,`dia_21`,`dia_22`,`dia_23`,`dia_24`,`dia_25`,`dia_26`,`dia_27`,`dia_28`,`dia_29`,`dia_30`,`dia_31`,`horas`) values 
(2,1,'A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A',0),
(3,1,'B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B',0),
(4,1,'D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D','D','A','A','B','B','D',0),
(5,2,'B','B','B','B','B','D','D','D','A','A','A','A','A','D','D','D','B','B','B','B','B','D','D','D','A','A','A','A','A','D','D',0),
(6,2,'D','D','D','A','A','A','A','A','D','D','B','B','B','B','B','D','D','D','A','A','A','A','A','D','D','B','B','B','B','B','D',0),
(7,2,'A','A','A','D','D','B','B','B','B','B','D','D','D','A','A','A','A','A','D','D','B','B','B','B','B','D','D','D','A','A','A',0);

insert  into `tur_recurso`(`codigo_recurso_pk`,`nombreCorto`,`comentarios`) values 
(1,'JOAQUIN MENDOZA JIMENEZ',NULL),
(2,'CARLOS TORRES GIRALDO',NULL),
(3,'HUGO MARTIN UMAÑA',NULL);


insert  into `tur_sector`(`codigo_sector_pk`,`nombre`,`porcentaje`,`comentarios`) values 
(1,'COMERCIAL',8.8,NULL),
(2,'RESIDENCIAL',8.6,NULL);

insert  into `tur_turno`(`codigo_turno_pk`,`nombre`,`hora_desde`,`hora_hasta`,`horas`,`horas_diurnas`,`horas_nocturnas`,`servicio`,`programacion`,`comentarios`,`novedad`) values 
('1','06:00 A 18:00','06:00:00','18:00:00',12,12,0,0,1,NULL,0),
('2','18:00 A 06:00','18:00:00','06:00:00',12,4,8,0,1,NULL,0),
('D','DESCANSO','00:00:00','00:00:00',0,0,0,0,1,NULL,1),
('EG','INCAPACIDAD ENFERMEDAD GENERAL','00:00:00','00:00:00',0,0,0,0,1,NULL,1),
('F12D','12 HORAS DIA','00:00:00','00:00:00',12,12,0,1,0,NULL,0),
('F24','24 HORAS','00:00:00','00:00:00',24,16,8,1,0,NULL,0),
('F3','3 HORAS DIURNAS','06:00:00','09:30:00',4,4,0,1,1,NULL,0);


insert  into `tur_turno_detalle`(`codigo_turno_detalle_pk`,`codigo_turno_fk`,`codigo_pago_concepto_fk`,`codigo_pago_concpeto_fk`,`cantidad`) values 
(1,'1',1,NULL,12),
(2,'2',1,NULL,4),
(3,'2',48,NULL,8),
(4,'F3',1,NULL,4);