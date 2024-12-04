CREATE DATABASE industrial_maintenance;

USE DATABASE industrial_maintenance;

CREATE TABLE equipment_location(
    id_location VARCHAR (10) PRIMARY KEY,
    name VARCHAR (50) NOT NULL
)

CREATE TABLE operator(
    id_operator INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR (40) NOT NULL,
    lastName VARCHAR (40) NOT NULL,
    secLastName VARCHAR (40),
    numTel VARCHAR (15) NOT NULL UNIQUE,
    email VARCHAR (50) NOT NULL UNIQUE,
    user VARCHAR (30) NOT NULL UNIQUE,
    password VARCHAR (20) NOT NULL UNIQUE
)

CREATE TABLE administrator(
    id_administrator INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR (40) NOT NULL,
    lastName VARCHAR (40) NOT NULL,
    secLastName VARCHAR (40),
    numTel VARCHAR (15) NOT NULL UNIQUE,
    email VARCHAR (50) NOT NULL UNIQUE,
    user VARCHAR (30) NOT NULL UNIQUE,
    password VARCHAR (20) NOT NULL UNIQUE
)

CREATE TABLE technician(
    id_technician INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR (40) NOT NULL,
    lastName VARCHAR (40) NOT NULL,
    secLastName VARCHAR (40),
    numTel VARCHAR (15) NOT NULL UNIQUE,
    email VARCHAR (50) NOT NULL UNIQUE,
    specialty VARCHAR (30) NOT NULL,
    user VARCHAR (30) NOT NULL UNIQUE,
    password VARCHAR (20) NOT NULL UNIQUE
)

CREATE TABLE maintenance_types(
    id_maintType INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR (40) NOT NULL UNIQUE,
    description VARCHAR (255) NOT NULL
)

CREATE TABLE suppliers(
    id_suppliers INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR (40) NOT NULL UNIQUE,
    numTel VARCHAR (15) NOT NULL UNIQUE,
    email VARCHAR (50) NOT NULL UNIQUE,
    addrStreet VARCHAR (30) NOT NULL,
    addrNum VARCHAR (15) NOT NULL,
    addrNeigh VARCHAR (30) NOT NULL
)

CREATE TABLE equipment(
    id_equipment INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR (50) NOT NULL,
    model VARCHAR (40) NOT NULL,
    brand VARCHAR (40) NOT NULL,
    status VARCHAR (40) NOT NULL,
    equipment_location VARCHAR (10) NOT NULL,
    FOREIGN KEY (equipment_location) REFERENCES equipment_location(id_location)
)

CREATE TABLE spare_parts(
    id_spareParts INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR (40) NOT NULL,
    price FLOAT NOT NULL,
    stock INT (5) NOT NULL,
    suppliers INT NOT NULL,
    FOREIGN KEY (suppliers) REFERENCES suppliers(id_suppliers)
)

CREATE TABLE failure(
    id_failure INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    description VARCHAR (255) NOT NULL,
    operator INT NOT NULL,
    FOREIGN KEY (operator) REFERENCES operator(id_operator)
)

CREATE TABLE purchase_orders(
    id_purchaseOrders INT PRIMARY KEY AUTO_INCREMENT,
    creationDate TIMESTAMP,
    status VARCHAR (40) NOT NULL,
    suppliers INT NOT NULL,
    administrator INT NOT NULL,
    FOREIGN KEY (suppliers) REFERENCES suppliers(id_suppliers),
    FOREIGN KEY (administrator) REFERENCES administrator(id_administrator)
)

CREATE TABLE work_orders(
    id_workOrders INT PRIMARY KEY AUTO_INCREMENT,
    creationDate TIMESTAMP,
    status VARCHAR (40) NOT NULL,
    description VARCHAR (255) NOT NULL,
    equipment INT NOT NULL,
    administrator INT NOT NULL,
    technician INT NOT NULL,
    FOREIGN KEY (equipment) REFERENCES equipment(id_equipment),
    FOREIGN KEY (administrator) REFERENCES administrator(id_administrator),
    FOREIGN KEY (technician) REFERENCES technician(id_technician)
)


CREATE TABLE maintenance(
    id_maintenance INT PRIMARY KEY AUTO_INCREMENT,
    assignedDate DATE NOT NULL,
    description VARCHAR (255) NOT NULL,
    status VARCHAR (25) NOT NULL,
    equipment INT NOT NULL,
    technician INT NOT NULL,
    maintenance_types INT NOT NULL,
    FOREIGN KEY (equipment) REFERENCES equipment(id_equipment),
    FOREIGN KEY (technician) REFERENCES technician(id_technician),
    FOREIGN KEY (maintenance_types) REFERENCES maintenance_types(id_maintType)
)

CREATE TABLE operator_equipment(
    equipment INT,
    operator INT,
    PRIMARY KEY (equipment, operator),
    FOREIGN KEY (equipment) REFERENCES equipment(id_equipment),
    FOREIGN KEY (operator) REFERENCES operator(id_operator)
)


CREATE TABLE failure_equipment(
    equipment INT,
    failure INT,
    PRIMARY KEY (equipment, failure),
    FOREIGN KEY (equipment) REFERENCES equipment(id_equipment),
    FOREIGN KEY (failure) REFERENCES failure(id_failure)
)

CREATE TABLE spare_purchases(
    spare_parts INT,
    purchase_orders INT,
    quantity INT (5) NOT NULL,
    amount FLOAT NOT NULL,
    PRIMARY KEY (spare_parts, purchase_orders),
    FOREIGN KEY (spare_parts) REFERENCES spare_parts(id_spareParts),
    FOREIGN KEY (purchase_orders) REFERENCES purchase_orders(id_purchaseOrders)
)

CREATE TABLE maintenance_history(
    id_history INT PRIMARY KEY AUTO_INCREMENT,
    completionDate DATE NOT NULL,
    results VARCHAR (40) NOT NULL,
    observations TEXT NOT NULL,
    equipment INT NOT NULL,
    maintenance INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (equipment) REFERENCES equipment(id_equipment),
    FOREIGN KEY (maintenance) REFERENCES maintenance(id_maintenance)
)

CREATE TABLE spare_history(
    spare_parts INT,
    maintenance_history INT,
    usedQuantity INT (2),
    usageDate DATE,
    PRIMARY KEY (spare_parts, maintenance_history),
    FOREIGN KEY (spare_parts) REFERENCES spare_parts(id_spareParts),
    FOREIGN KEY (maintenance_history) REFERENCES maintenance_history(id_history)
)




-- Insertar registros en la tabla ubicacion_de_equipos
INSERT INTO equipment_location (id_location, name) VALUES 
('U001', 'Planta de Ensamblaje de Componentes'),
('U002', 'Área de Pintura de Vehículos'),
('U003', 'Zona de Pruebas de Equipos de Ensamblaje'),
('U004', 'Área de Montaje de Motores'),
('U005', 'Zona de Inspección de Calidad'), 
('U006', 'Área de Almacenamiento de Piezas'),
('U007', 'Zona de Pruebas de Componentes Electrónicos'),
('U008', 'Área de Control de Calidad de Equipos'),
('U009', 'Zona de Desmontaje de Equipos Industriales');

-- Insertar registros en la tabla operador
INSERT INTO operator (name, lastName, secLastName, numTel, email, user, password) VALUES 
('Carlos Andrés', 'Pérez', 'Gómez', '(664) 123 4567', 'carlos.perez@autoindustry.com', 'c_perez', 'CPerez182'),
('María José', 'López', NULL, '(664) 234 5678', 'maria.lopez@autoindustry.com', 'm_lopez', 'MLopez789'),
('Roberto', 'Sánchez', 'Vega', '(664) 345 6789', 'roberto.sanchez@autoindustry.com', 'r_sanchez', 'RSanchez456'),
('Ana', 'Martínez', NULL, '(664) 456 7890', 'ana.martinez@autoindustry.com', 'ana_m', 'AMartinez654'),
('Jorge', 'Fernández', 'Ruiz', '(664) 567 8901', 'jorge.fernandez@autoindustry.com', 'J_fernandez', 'JFernandez123'),
('Pedro Pablo', 'Gómez', 'Lara', '(664) 678 9012', 'pedro.gomez@autoindustry.com', 'p_gomez', 'PedroGL789'),
('Luis', 'Torres', 'Mora', '(664) 789 0123', 'luis.torres@autoindustry.com', 'l_torres', 'LTorres456'),
('Verónica', 'Sosa', NULL, '(664) 890 1234', 'veronica.sosa@autoindustry.com', 'v_sosa', 'VSosa123'),
('Andrés', 'Castro', 'Reyes', '(664) 901 2345', 'andres.castro@autoindustry.com', 'a_castro', 'ACastro789'),
('Sara Isabel', 'Ramírez', 'Fuentes', '(664) 012 3456', 'sara.ramirez@autoindustry.com', 's_ramirez', 'SRamirez654'),
('Eduardo', 'Morales', 'Martín', '(664) 019 6718', 'eduardo.morales@autoindustry.com', 'e_morales', 'EMorales123'),
('Paula', 'Vargas', NULL, '(664) 235 9781', 'paula.vargas@autoindustry.com', 'p_vargas', 'PVargas789'),
('Raúl', 'Ríos', 'Valdez', '(664) 391 1020', 'raul.rios@autoindustry.com', 'r_rios', 'RRios456'),
('Isabel', 'Álvarez', 'Campos', '(664) 807 8495', 'isabel.alvarez@autoindustry.com', 'i_alvarez', 'IAlvarez654'),
('Hugo', 'Paredes', 'Franco', '(664) 567 0206', 'hugo.paredes@autoindustry.com', 'h_paredes', 'HParedes789'),
('Patricia', 'Méndez', NULL, '(664) 710 9708', 'patricia.mendez@autoindustry.com', 'p_mendez', 'PMendez123'),
('Adrián', 'Reyes', 'Juárez', '(664) 789 3774', 'adrian.reyes@autoindustry.com', 'a_reyes', 'AReyes456'),
('Gloria Elena', 'Cruz', 'Luna', '(664) 890 2197', 'gloria.cruz@autoindustry.com', 'g_cruz', 'GCruz789'),
('Javier', 'Silva', 'Ortiz', '(664) 901 2320', 'javier.silva@autoindustry.com', 'j_silva', 'JSilva654'),
('Lorena', 'Núñez', NULL, '(664) 012 7234', 'lorena.nunez@autoindustry.com', 'l_nunez', 'LNunez123'),
('Miguel Ángel', 'Ibarra', 'Galindo', '(664) 123 3181', 'miguel.ibarra@autoindustry.com', 'm_ibarra', 'MIbarra789'),
('Elena', 'Serrano', 'Vargas', '(644) 234 6825', 'elena.serrano@autoindustry.com', 'e_serrano', 'ESerrano456'),
('Raquel', 'Aguilar', NULL, '(644) 345 1931', 'raquel.aguilar@autoindustry.com', 'r_aguilar', 'RAguilar654'),
('Felipe', 'Lara', 'Espinosa', '(664) 456 7824', 'felipe.lara@autoindustry.com', 'f_lara', 'FLara789'),
('Natalia', 'Gutiérrez', 'Suárez', '(664) 567 6492', 'natalia.gutierrez@autoindustry.com', 'n_gutierrez', 'NGutierrez123'),
('César', 'Vega', 'Jiménez', '(664) 678 2416', 'cesar.vega@autoindustry.com', 'c_vega', 'CVega456'),
('Inés', 'Roldán', NULL, '(664) 789 7490', 'ines.roldan@autoindustry.com', 'i_roldan', 'IRoldan789'),
('Héctor', 'Muñoz', 'Beltrán', '(664) 890 6438', 'hector.munoz@autoindustry.com', 'h_munoz', 'HMunoz654'),
('Daniela', 'Valle', 'Castillo', '(664) 901 1625', 'daniela.valle@autoindustry.com', 'd_valle', 'DValle123'),
('Tomás', 'Moreno', NULL, '(664) 012 7962', 'tomas.moreno@autoindustry.com', 't_moreno', 'TMoreno789'),
('Marisol', 'Soto', 'Duarte', '(664) 123 1664', 'marisol.soto@autoindustry.com', 'm_soto', 'MSoto456'),
('Gustavo', 'Pérez', 'Villalobos', '(664) 234 3772', 'gustavo.perez@autoindustry.com', 'g_perez', 'GPerez654'),
('Silvia', 'Cordero', NULL, '(664) 345 0161', 'silvia.cordero@autoindustry.com', 's_cordero', 'SCordero789'),
('Rodrigo', 'Escobar', 'Ramos', '(664) 456 9052', 'rodrigo.escobar@autoindustry.com', 'r_escobar', 'REscobar123'),
('Monica', 'Peña', 'León', '(664) 567 9834', 'monica.pena@autoindustry.com', 'm_pena', 'MPena456'),
('Jonathan', 'Figueroa', 'Salinas', '(664) 678 2109', 'jonathan.figueroa@autoindustry.com', 'j_figueroa', 'JFigueroa789'),
('Irene', 'Román', NULL, '(664) 789 1173', 'irene.roman@autoindustry.com', 'i_roman', 'IRoman654'),
('Salvador', 'Gallardo', 'Ponce', '(664) 890 0134', 'salvador.gallardo@autoindustry.com', 's_gallardo', 'SGallardo123');




-- Insertar registros en la tabla supervisor
INSERT INTO administrator (name, lastName, secLastName, numTel, email, user, password) VALUES 
('Santiago', 'Méndez', 'Herrera', '(664) 111 1733', 'santiago.mendez@autoindustry.com', 's_mendez', 'SMendez123'),
('Lucía', 'García', 'Santos', '(664) 222 3740', 'lucia.garcia@autoindustry.com', 'l_garcia', 'LGarcia456'),
('Fernando', 'Castillo', NULL, '(664) 130 4459', 'fernando.castillo@autoindustry.com', 'f_castillo', 'FCastillo789'),
('Gabriela', 'Moreno', 'Flores', '(664) 414 5146', 'gabriela.moreno@autoindustry.com', 'g_moreno', 'GMoreno654'),
('Ricardo', 'Navarro', 'Guzmán', '(664) 554 1278', 'ricardo.navarro@autoindustry.com', 'r_navarro', 'RNavarro987'),
('Diana', 'Serrano', NULL, '(664) 624 3478', 'diana.serrano@autoindustry.com', 'd_serrano', 'DSerrano321'),
('Samuel', 'Jiménez', 'Ortiz', '(664) 277 8199', 'samuel.jimenez@autoindustry.com', 's_jimenez', 'SJimenez123'),
('Claudia', 'Ávila', 'Pérez', '(644) 825 9700', 'claudia.avila@autoindustry.com', 'c_avila', 'CAvila456'),
('Manuel', 'Vargas', 'Sánchez', '(664) 829 0011', 'manuel.vargas@autoindustry.com', 'm_vargas', 'MVargas789'),
('Mónica', 'Rojas', 'Díaz', '(664) 027 1134', 'monica.rojas@autoindustry.com', 'm_rojas', 'MRojas654');



-- Insertar registros en la tabla tecnico
INSERT INTO technician (name, lastName, secLastName, numTel, email, specialty, user, password) VALUES 
('Juan', 'Martínez', 'González', '(664) 123 4567', 'juan.martinez@autoindustry.com', 'Mantenimiento de Equipos Industriales', 'j_martinez', 'JMartinez456'),
('Ana', 'López', 'Hernández', '(664) 234 5678', 'ana.lopez@autoindustry.com', 'Mantenimiento de Sistemas Mecánicos', 'a_lopez', 'ALopez812'),
('Pedro', 'García', 'Ramírez', '(664) 345 6789', 'pedro.garcia@autoindustry.com', 'Mantenimiento de Sistemas Electrónicos', 'p_garcia', 'PGarcia378'),
('María', 'Fernández', 'Sánchez', '(664) 456 7890', 'maria.fernandez@autoindustry.com', 'Mantenimiento de Equipos de Pintura', 'm_fernandez', 'MFernandez903'),
('Luis', 'Torres', 'Mora', '(664) 567 8901', 'luis.torres@autoindustry.com', 'Mantenimiento de Prensas y Herramientas', 'l_torres', 'LTorres680'),
('Sofía', 'Jiménez', 'Pérez', '(664) 678 9012', 'sofia.jimenez@autoindustry.com', 'Mantenimiento de Equipos de Medición', 's_jimenez', 'SJimenez236'),
('Carlos', 'Ramírez', 'Cruz', '(664) 789 0123', 'carlos.ramirez@autoindustry.com', 'Mantenimiento de Sistemas Hidráulicos', 'c_ramirez', 'CRamirez802'),
('Elena', 'Serrano', 'Vargas', '(664) 890 1234', 'elena.serrano@autoindustry.com', 'Mantenimiento de Equipos de Ensayo', 'e_serrano', 'ESerrano203'),
('Javier', 'Silva', 'Ortiz', '(664) 901 2345', 'javier.silva@autoindustry.com', 'Mantenimiento de Sistemas de Control', 'j_silva', 'JSilva934');


-- Insertar registros en la tabla tipos_de_mantenimiento
INSERT INTO maintenance_types (name, description) VALUES 
('Mantenimiento Preventivo', 'Acciones programadas para prevenir fallas en los equipos.'),
('Mantenimiento Correctivo', 'Reparaciones realizadas después de la detección de fallas.'),
('Mantenimiento Predictivo', 'Monitoreo y análisis de datos para prever fallas antes de que ocurran.'),
('Mantenimiento Proactivo', 'Acciones para mejorar la fiabilidad y disponibilidad de los equipos.'),
('Mantenimiento de Emergencia', 'Intervenciones rápidas para resolver fallas críticas que afectan la producción.'),
('Mantenimiento de Rutina', 'Revisiones periódicas y ajustes menores para asegurar el buen funcionamiento.'),
('Mantenimiento de Instalación', 'Actividades relacionadas con la instalación de nuevos equipos.');


-- Insertar registros en la tabla proveedores
INSERT INTO suppliers (name, numTel, email, addrStreet, addrNum, addrNeigh) VALUES 
('Repuestos Automotrices del Norte S.A.', '(664) 123 4567', 'contacto@repuestosnorte.com', 'Avenida Industrial', '101', 'Colonia Industrial'),
('Herramientas y Equipos S.A. de C.V.', '(664) 234 5678', 'ventas@herramientasyequipos.com', 'Calle de la Innovación', '202', 'Colonia Tecnológica'),
('Suministros Automotrices de México', '(664) 345 6789', 'info@suministrosautomotrices.com', 'Boulevard de la Industria', '303', 'Colonia Progreso'),
('Mantenimiento y Repuestos S.A.', '(664) 456 7890', 'soporte@mantenimiento.com', 'Calle de la Calidad', '404', 'Colonia Eficiencia'),
('Proveedores de Maquinaria y Herramientas', '(664) 567 8901', 'contacto@maquinariayherramientas.com', 'Calle del Progreso', '505', 'Colonia Avance'),
('Componentes y Accesorios Automotrices S.A.', '(664) 678 9012', 'ventas@componentesya.com', 'Calle de la Tecnología', '606', 'Colonia Innovación'),
('Distribuidora de Equipos Industriales S.A. de C.V.', '(664) 789 0123', 'info@distribuidoraequipos.com', 'Avenida de la Producción', '707', 'Colonia Desarrollo');


-- Insertar registros en la tabla equipos para la Planta de Ensamblaje de Componentes (U001)
INSERT INTO equipment (name, model, brand, status, equipment_location) VALUES 
('Robot Soldador', 'RS-200', 'AutoTech', 'Operativo', 'U001'),
('Máquina de Ensamblaje', 'ME-600', 'AssemPro', 'Operativo', 'U001'),
('Cinta Transportadora', 'CT-300', 'TranspoSys', 'Operativo', 'U001'),
('Elevador Hidráulico', 'EH-1100', 'LiftPro', 'Operativo', 'U001'),
('Robot de Montaje', 'RM-1500', 'AssemBot', 'Operativo', 'U001'),
('Máquina de Corte por Laser', 'MCL-1000', 'LaserCut', 'operativo', 'U001'),
('Sistema de Monitoreo de Producción', 'SMP-4000', 'ProdMonitor', 'Operativo', 'U001'),
('Horno de Curado', 'HC-500', 'HeatTech', 'Operativo', 'U002'),
('Máquina de Pintura Automática', 'MPA-1400', 'PaintMaster', 'Operativo', 'U002'),
('Robot de Pintura', 'RP-2000', 'PaintBot', 'Operativo', 'U002'),
('Compresora de Aire', 'CA-900', 'AirMaster', 'Operativo', 'U002'),
('Cabina de Pintura', 'CP-2500', 'PaintCab', 'Operativo', 'U002'),
('Sistema de Ventilación', 'SV-2600', 'VentTech', 'Operativo', 'U002'),
('Equipo de Medición de Color', 'EMC-2700', 'ColorCheck', 'Operativo', 'U002'),
('Prensa Hidráulica', 'PH-150', 'HydroPress', 'Operativo', 'U003'),
('Máquina de Pruebas de Resistencia', 'MPR-3300', 'ResistTest', 'Operativo', 'U003'),
('Cortadora de Plasma', 'CP-1800', 'PlasmaCut', 'Operativo', 'U003'),
('Máquina de Ensayo de Materiales', 'MEM-1900', 'TestMaster', 'Operativo', 'U003'),
('Prensa de Corte', 'PC-1600', 'CutPress', 'Operativo', 'U003'),
('Sistema de Refrigeración', 'SR-2300', 'CoolTech', 'Operativo', 'U003'),
('Máquina de Corte CNC', 'CNC-2400', 'CNCPro', 'Operativo', 'U003'),
('Taladro Industrial', 'TI-400', 'DrillMaster', 'Operativo', 'U004'),
('Robot de Manipulación', 'RM-2500', 'Manipulador', 'Operativo', 'U004'),
('Máquina de Soldadura por Puntos', 'MSP-2200', 'SpotWeld', 'Operativo', 'U004'),
('Compresor de Tornillo', 'CT-2100', 'ScrewAir', 'Operativo', 'U004'),
('Máquina de Acabado', 'MA-2700', 'FinishPro', 'Operativo', 'U004'),
('Sistema de Transporte Neumático', 'STN-2800', 'PneumaTrans', 'Operativo', 'U004'),
('Generador de Vapor', 'GV-1200', 'SteamGen', 'Operativo', 'U004'),
('Sistema de Control de Calidad', 'CCQ-1300', 'QualiCheck', 'Operativo', 'U005'),
('Máquina de Pruebas Hidráulicas', 'MPH-2900', 'HydroTest', 'Operativo', 'U005'),
('Robot de Inspección', 'RI-3400', 'InspectBot', 'Operativo', 'U005'),
('Máquina de Pruebas de Vibración', 'MPV-3700', 'VibraTest', 'Operativo', 'U005'),
('Equipo de Medición de Precisión', 'EMP-700', 'MeasureTech', 'Operativo', 'U005'),
('Máquina de Ensayo de Fatiga', 'MEF-3900', 'FatigueTest', 'Operativo', 'U005'),
('Sistema de Monitoreo de Calidad', 'SMQ-4000', 'QualiMonitor', 'Operativo', 'U005'),
('Sistema de Almacenamiento Automatizado', 'SAA-3600', 'AutoStore', 'Operativo', 'U006'),
('Carretilla Elevadora', 'CE-4100', 'LiftMaster', 'Operativo', 'U006'),
('Estantería Industrial', 'EI-4200', 'ShelfPro', 'Operativo', 'U006'),
('Escáner de Código de Barras', 'ECB-4300', 'ScanTech', 'Operativo', 'U006'),
('Sistema de Gestión de Inventario', 'SGI-4400', 'InventoryPro', 'Operativo', 'U006'),
('Transpaleta Manual', 'TM-4500', 'PalletPro', 'Operativo', 'U006'),
('Cinta Transportadora Modular', 'CTM-3200', 'ModularTrans', 'Operativo', 'U006'),
('Sistema de Pruebas Electrónicas', 'SPE-800', 'TestEquip', 'Operativo', 'U007'),
('Osciloscopio', 'OSC-4600', 'WaveTech', 'Operativo', 'U007'),
('Multímetro Digital', 'MD-4700', 'MeasureAll', 'Operativo', 'U007'),
('Máquina de Ensayo de Componentes', 'MEC-4800', 'CompTest', 'Operativo', 'U007'),
('Generador de Señales', 'GS-4900', 'SignalGen', 'Operativo', 'U007'),
('Analizador de Espectro', 'AE-5000', 'SpecAnalyzer', 'Operativo', 'U007'),
('Fuente de Alimentación', 'FA-5100', 'PowerSupply', 'Operativo', 'U007'),
('Máquina de Inspección Visual', 'MIV-600', 'InspectTech', 'Operativo', 'U008'),
('Sistema de Pruebas de Estrés', 'SPE-700', 'StressTest', 'Operativo', 'U008'),
('Calibrador de Dimensiones', 'CD-800', 'DimensioCheck', 'Operativo', 'U008'),
('Máquina de Ensayo de Tensión', 'MET-900', 'TensionTest', 'Operativo', 'U008'),
('Sistema de Monitoreo de Calidad', 'SMQ-1000', 'QualiMonitor', 'Operativo', 'U008'),
('Prueba de Ciclo de Vida', 'PV-1100', 'LifeCycleTest', 'Operativo', 'U008'),
('Escáner de Calidad', 'EQC-1200', 'QualityScan', 'Operativo', 'U008'),
('Herramientas Manuales', 'HM-5200', 'ToolMaster', 'Operativo', 'U009'),
('Mesa de Trabajo', 'MT-5300', 'WorkBench', 'Operativo', 'U009'),
('Carro de Herramientas', 'CH-5400', 'ToolCart', 'Operativo', 'U009'),
('Máquina de Corte de Chapa', 'MCC-3500', 'SheetCut', 'Operativo', 'U009'),
('Sistema de Diagnóstico', 'SD-5500', 'DiagSystem', 'Operativo', 'U009'),
('Elevador de Taller', 'ET-5600', 'ShopLift', 'Operativo', 'U009'),
('Compresor de Aire Portátil', 'CAP-5700', 'PortableAir', 'Operativo', 'U009');


-- Insertar registros en la tabla repuestos para equipos industriales
INSERT INTO spare_parts (name, price, stock, suppliers) VALUES 
('Motor Eléctrico', 500.00, 20, 1),
('Bomba de Agua', 150.00, 30, 2),
('Válvula de Control', 75.00, 50, 3),
('Cinta Transportadora', 200.00, 15, 4),
('Rodillo de Compresión', 300.00, 10, 5),
('Frenos Neumáticos', 120.00, 25, 6),
('Cojinete de Rodillo', 45.00, 40, 7),
('Sensor de Presión', 60.00, 35, 1),
('Filtro de Aire', 20.00, 100, 2),
('Correa de Transmisión', 15.00, 80, 3),
('Amortiguador Hidráulico', 90.00, 20, 4),
('Cilindro Neumático', 110.00, 15, 5),
('Manguera Hidráulica', 25.00, 60, 6),
('Banda de Rodadura', 40.00, 50, 7),
('Engranaje de Transmisión', 70.00, 30, 1),
('Placa de Circuito', 200.00, 10, 2),
('Interruptor de Seguridad', 30.00, 45, 3),
('Relé de Control', 25.00, 50, 4),
('Transmisor de Temperatura', 80.00, 20, 5),
('Batería de Respaldo', 100.00, 15, 6),
('Compresor de Aire', 400.00, 5, 7),
('Filtro de Aceite', 15.00, 90, 1),
('Cilindro de Gas', 60.00, 40, 2),
('Tubería de Acero', 50.00, 30, 3),
('Conector Eléctrico', 10.00, 100, 4),
('Cámara de Combustión', 250.00, 10, 5),
('Válvula de Seguridad', 70.00, 25, 6),
('Tapa de Acceso', 20.00, 60, 7),
('Cinta de Medición', 5.00, 200, 1),
('Soporte de Motor', 45.00, 50, 2),
('Rodillo de Transporte', 90.00, 15, 3),
('Cinta de Transmisión', 35.00, 40, 4),
('Frenos de Disco', 150.00, 20, 5),
('Cilindro Hidráulico', 120.00, 30, 6),
('Módulo de Control', 300.00, 10, 7),
('Sensor de Movimiento', 50.00, 45, 1),
('Placa de Soporte', 25.00, 60, 2),
('Tornillo de Ajuste', 5.00, 200, 3),
('Engranaje de Reducción', 80.00, 25, 4),
('Cinta de Rodadura', 40.00, 50, 5),
('Bomba de Aceite', 100.00, 20, 6),
('Filtro de Refrigerante', 30.00, 70, 7),
('Manguera de Refrigeración', 20.00, 90, 1),
('Cilindro de Compresión', 150.00, 15, 2),
('Válvula de Escape', 60.00, 40, 3),
('Compresor de Refrigeración', 400.00, 5, 4),
('Sensor de Flujo', 35.00, 50, 5),
('Cámara de Presión', 80.00, 20, 6),
('Tapa de Válvula', 25.00, 60, 7),
('Rodillo de Prueba', 90.00, 15, 1),
('Cinta de Medición Digital', 15.00, 80, 2),
('Bomba de Combustible', 200.00, 10, 3),
('Filtro de Partículas', 100.00, 25, 4),
('Cilindro de Almacenamiento', 150.00, 20, 5),
('Válvula de Control de Flujo', 70.00, 30, 6),
('Módulo de Potencia', 250.00, 10, 7),
('Sensor de Nivel', 40.00, 45, 1),
('Placa de Distribución', 60.00, 35, 2),
('Tubería Flexible', 20.00, 100, 3),
('Conector de Manguera', 10.00, 200, 4),
('Cámara de Aire', 80.00, 25, 5),
('Válvula de Alivio', 50.00, 40, 6),
('Tapa de Acceso de Seguridad', 30.00, 60, 7),
('Rodillo de Compresión Neumático', 300.00, 5, 1),
('Cinta de Rodadura de Alta Resistencia', 90.00, 15, 2),
('Bomba de Aceite de Alta Capacidad', 150.00, 20, 3),
('Filtro de Aire de Alta Eficiencia', 25.00, 70, 4),
('Cilindro Neumático de Alta Presión', 120.00, 30, 5),
('Válvula de Control de Alta Precisión', 80.00, 25, 6),
('Módulo de Control de Alta Eficiencia', 300.00, 10, 7),
('Sensor de Temperatura de Alta Precisión', 50.00, 45, 1),
('Placa de Soporte de Alta Resistencia', 60.00, 35, 2),
('Tornillo de Ajuste de Alta Resistencia', 5.00, 200, 3),
('Engranaje de Transmisión de Alta Eficiencia', 70.00, 50, 4),
('Cinta de Rodadura de Alta Capacidad', 40.00, 60, 5),
('Bomba de Agua de Alta Capacidad', 150.00, 20, 6),
('Filtro de Refrigerante de Alta Eficiencia', 30.00, 70, 7),
('Manguera de Refrigeración de Alta Resistencia', 20.00, 90, 1),
('Cilindro de Compresión de Alta Capacidad', 150.00, 15, 2),
('Válvula de Escape de Alta Eficiencia', 60.00, 40, 3),
('Compresor de Refrigeración de Alta Capacidad', 400.00, 5, 4),
('Sensor de Flujo de Alta Precisión', 35.00, 50, 5),
('Cámara de Presión de Alta Capacidad', 80.00, 20, 6),
('Tapa de Válvula de Alta Resistencia', 25.00, 60, 7),
('Rodillo de Prueba de Alta Capacidad', 90.00, 15, 1),
('Cinta de Medición Digital de Alta Precisión', 15.00, 80, 2),
('Bomba de Combustible de Alta Capacidad', 200.00, 10, 3),
('Filtro de Partículas de Alta Eficiencia', 100.00, 25, 4),
('Cilindro de Almacenamiento de Alta Capacidad', 150.00, 20, 5),
('Válvula de Control de Flujo de Alta Precisión', 70.00, 30, 6),
('Módulo de Potencia de Alta Capacidad', 250.00, 10, 7),
('Sensor de Nivel de Alta Precisión', 40.00, 45, 1),
('Placa de Distribución de Alta Capacidad', 60.00, 35, 2),
('Tubería Flexible de Alta Resistencia', 20.00, 100, 3),
('Conector de Manguera de Alta Capacidad', 10.00, 200, 4),
('Cámara de Aire de Alta Capacidad', 80.00, 25, 5),
('Válvula de Alivio de Alta Precisión', 50.00, 40, 6),
('Tapa de Acceso de Seguridad de Alta Capacidad', 30.00, 60, 7),
('Rodillo de Compresión de Alta Capacidad', 300.00, 5, 1),
('Cinta de Rodadura de Alta Capacidad', 90.00, 15, 2);


-- Insertar registros en la tabla falla
INSERT INTO failure (date, description, operator) VALUES 
('2024-01-10', 'El motor no arranca debido a un fallo eléctrico.', 1),
('2024-01-12', 'Se detecta una fuga de aceite en la bomba.', 1),
('2024-01-15', 'La cinta transportadora se detuvo inesperadamente.', 2),
('2024-01-20', 'El sistema se sobrecalienta durante la operación.', 2),
('2024-01-25', 'El sensor de presión no está funcionando correctamente.', 3),
('2024-01-30', 'El compresor no genera aire comprimido.', 3),
('2024-02-05', 'Los rodillos de la cinta transportadora están desgastados.', 4),
('2024-02-10', 'El sistema hidráulico presenta pérdida de presión.', 4),
('2024-02-15', 'El sistema de refrigeración no enfría adecuadamente.', 5),
('2024-02-20', 'El amortiguador hidráulico no responde.', 5),
('2024-02-25', 'El cilindro neumático no se desplaza.', 6),
('2024-03-01', 'Se detecta una fuga de refrigerante en el sistema.', 6),
('2024-03-05', 'El sistema de control presenta errores.', 7),
('2024-03-10', 'Las correas de transmisión están desgastadas.', 7),
('2024-03-15', 'El interruptor de seguridad no funciona.', 1),
('2024-03-20', 'La bomba de agua no está funcionando.', 2),
('2024-03-25', 'El sistema de lubricación no distribuye aceite.', 3),
('2024-04-01', 'Se detecta una fuga de aire en el sistema neumático.', 4),
('2024-04-05', 'El generador no produce energía.', 5),
('2024-04-10', 'El sistema de monitoreo no muestra datos.', 6),
('2024-04-15', 'Los componentes del sistema están desgastados.', 7),
('2024-04-20', 'El sistema de ventilación no funciona.', 1),
('2024-04-25', 'El sensor de temperatura no responde.', 2),
('2024-05-01', 'Se detecta una fuga de combustible en el sistema.', 3),
('2024-05-05', 'El compresor de refrigeración no enfría.', 4),
('2024-05-10', 'El sistema de transmisión presenta fallas.', 5),
('2024-05-15', 'Los rodamientos están desgastados.', 6),
('2024-05-20', 'El sistema de almacenamiento no responde.', 7),
('2024-05-25', 'El sistema de pruebas no funciona.', 1),
('2024-06-01', 'El sistema de calefacción no calienta.', 2),
('2024-06-05', 'Se detecta una fuga de vapor en el sistema.', 3),
('2024-06-10', 'El sistema de filtración no está funcionando.', 4),
('2024-06-15', 'El sistema de carga no carga correctamente.', 5),
('2024-06-20', 'Los sellos están desgastados.', 6),
('2024-06-25', 'El sistema de seguridad no activa alarmas.', 7),
('2024-07-01', 'El sistema de control de calidad no responde.', 1),
('2024-07-05', 'El sistema de alarma no suena.', 2),
('2024-07-10', 'Se detecta una fuga de refrigerante en el compresor.', 3),
('2024-07-15', 'El sistema de enfriamiento no enfría.', 4),
('2024-07-20', 'El sistema de distribución presenta fallas.', 5),
('2024-07-25', 'Las cadenas de transmisión están desgastadas.', 6),
('2024-08-01', 'El sistema de suministro no entrega materiales.', 7),
('2024-08-05', 'El sistema de envasado no funciona.', 1),
('2024-08-10', 'El sistema de transporte no opera.', 2),
('2024-08-15', 'Se detecta una fuga de aceite en la máquina.', 3),
('2024-08-20', 'El sistema de control de temperatura no responde.', 4),
('2024-08-25', 'El sistema de mantenimiento no está operativo.', 5),
('2024-09-01', 'Los componentes eléctricos están desgastados.', 6),
('2024-09-05', 'El sistema de inspección no funciona.', 7),
('2024-09-10', 'El sistema de carga de materiales no opera.', 1),
('2024-09-15', 'El sistema de control de inventario presenta fallas.', 2),
('2024-09-20', 'Se detecta una fuga de agua en el sistema.', 3),
('2024-09-25', 'El sistema de almacenamiento de energía no responde.', 4),
('2024-09-30', 'El sistema de distribución de energía presenta fallas.', 5),
('2024-09-28', 'Los filtros están desgastados.', 6),
('2024-09-27', 'El sistema de control de procesos químicos no funciona.', 7),
('2024-09-26', 'El sistema de monitoreo de calidad no muestra datos.', 1),
('2024-09-25', 'El sistema de control de fluidos presenta fallas.', 2),
('2024-09-24', 'Se detecta una fuga de gases en el compresor.', 3),
('2024-09-23', 'El sistema de control de procesos de producción no responde.', 4),
('2024-09-22', 'El sistema de control de calidad del producto presenta fallas.', 5),
('2024-09-21', 'Los equipos de medición están desgastados.', 6);


-- Insertar registros en la tabla ordenes_de_compra
INSERT INTO purchase_orders (creationDate, status, suppliers, administrator) VALUES 
('2024-01-05', 'Completada', 1, 1),
('2024-01-10', 'Completada', 2, 2),
('2024-01-15', 'Completada', 3, 1),
('2024-01-20', 'Completada', 4, 3),
('2024-01-25', 'Completada', 5, 2),
('2024-02-01', 'Completada', 6, 1),
('2024-02-05', 'Completada', 7, 4),
('2024-02-10', 'Completada', 1, 3),
('2024-02-15', 'Completada', 2, 1),
('2024-02-20', 'Completada', 3, 2),
('2024-03-01', 'Completada', 4, 3),
('2024-03-05', 'Completada', 5, 4),
('2024-03-10', 'Completada', 6, 1),
('2024-03-15', 'Completada', 7, 2),
('2024-04-01', 'Completada', 1, 3),
('2024-04-05', 'Completada', 2, 1),
('2024-04-10', 'Completada', 3, 4),
('2024-04-15', 'Completada', 4, 2),
('2024-05-01', 'Completada', 5, 3),
('2024-05-05', 'Completada', 6, 1),
('2024-05-10', 'Completada', 7, 4);



-- Insertar registros en la tabla ordenes_de_trabajo
INSERT INTO work_orders (creationDate, status, description, equipment, administrator, technician) VALUES 
('2024-01-05', 'Completada', 'Mantenimiento preventivo de la máquina de ensamblaje.', 1, 1, 1),
('2024-01-10', 'Completada', 'Reparación del sistema hidráulico.', 2, 2, 2),
('2024-01-15', 'Completada', 'Inspección de la cinta transportadora.', 3, 1, 3),
('2024-01-20', 'Completada', 'Calibración del sensor de presión.', 4, 3, 4),
('2024-01-25', 'Completada', 'Cambio de aceite en el compresor.', 5, 2, 1),
('2024-02-01', 'Completada', 'Reemplazo de rodillos en la cinta.', 6, 1, 2),
('2024-02-05', 'Completada', 'Mantenimiento del sistema de refrigeración.', 7, 4, 3),
('2024-02-10', 'Completada', 'Reparación de la bomba de agua.', 1, 3, 4),
('2024-02-15', 'Completada', 'Inspección de seguridad de la máquina de corte.', 2, 2, 1),
('2024-02-20', 'Completada', 'Mantenimiento del sistema eléctrico.', 3, 1, 2),
('2024-03-01', 'Completada', 'Revisión del sistema de control.', 4, 3, 3),
('2024-03-05', 'Completada', 'Ajuste de la máquina de soldadura.', 5, 2, 4),
('2024-03-10', 'Completada', 'Mantenimiento de la prensa hidráulica.', 6, 1, 1),
('2024-03-15', 'Completada', 'Reparación del sistema de ventilación.', 7, 4, 2),
('2024-04-01', 'Completada', 'Calibración de la máquina de pruebas.', 1, 3, 3),
('2024-04-05', 'Completada', 'Inspección de la máquina de pintura.', 2, 2, 4),
('2024-04-10', 'Completada', 'Mantenimiento del sistema de transporte.', 3, 1, 1),
('2024-04-15', 'Completada', 'Reparación del generador.', 4, 3, 2),
('2024-05-01', 'Completada', 'Cambio de filtros en el sistema de aire.', 5, 2, 3),
('2024-05-05', 'Completada', 'Mantenimiento de la máquina de ensamble.', 6, 1, 4);



-- Insertar registros en la tabla mantenimiento
INSERT INTO maintenance (assignedDate, description, status, equipment, technician, maintenance_types) VALUES 
('2024-01-05', 'Mantenimiento preventivo del robot soldador.', 'Completado', 1, 1, 1),
('2024-01-10', 'Reparación de la máquina de ensamblaje.', 'Completado', 2, 2, 2),
('2024-01-15', 'Inspección de la cinta transportadora.', 'Completado', 3, 1, 1),
('2024-01-20', 'Calibración del sensor de presión.', 'Completado', 4, 3, 2),
('2024-01-25', 'Cambio de aceite en el compresor.', 'Completado', 5, 2, 1),
('2024-02-01', 'Reemplazo de rodillos en la cinta.', 'Completado', 6, 1, 2),
('2024-02-05', 'Mantenimiento del sistema de refrigeración.', 'Completado', 7, 4, 1),
('2024-02-10', 'Reparación de la bomba de agua.', 'Completado', 1, 3, 2),
('2024-02-15', 'Inspección de seguridad de la máquina de corte.', 'Completado', 2, 2, 1),
('2024-02-20', 'Mantenimiento del sistema eléctrico.', 'Completado', 3, 1, 2),
('2024-03-01', 'Revisión del sistema de control.', 'Completado', 4, 3, 1),
('2024-03-05', 'Ajuste de la máquina de soldadura.', 'Completado', 5, 2, 2),
('2024-03-10', 'Mantenimiento de la prensa hidráulica.', 'Completado', 6, 1, 1),
('2024-03-15', 'Reparación del sistema de ventilación.', 'Completado', 7, 4, 2),
('2024-04-01', 'Calibración de la máquina de pruebas.', 'Completado', 1, 3, 1),
('2024-04-05', 'Inspección de la máquina de pintura.', 'Completado', 2, 2, 2),
('2024-04-10', 'Mantenimiento del sistema de transporte.', 'Completado', 3, 1, 1),
('2024-04-15', 'Reparación del generador.', 'Completado', 4, 3, 2),
('2024-05-01', 'Cambio de filtros en el sistema de aire.', 'Completado', 5, 2, 1),
('2024-05-05', 'Mantenimiento de la máquina de ensamble.', 'Completado', 6, 1, 2);




-- Insertar registros en la tabla operador_equipo
INSERT INTO operator_equipment (equipment, operator) VALUES 
(1, 1),  -- Operador 1 trabaja en el equipo 1
(1, 2),  -- Operador 2 trabaja en el equipo 1
(2, 1),  -- Operador 1 trabaja en el equipo 2
(2, 3),  -- Operador 3 trabaja en el equipo 2
(3, 2),  -- Operador 2 trabaja en el equipo 3
(3, 4),  -- Operador 4 trabaja en el equipo 3
(4, 1),  -- Operador 1 trabaja en el equipo 4
(4, 5),  -- Operador 5 trabaja en el equipo 4
(5, 2),  -- Operador 2 trabaja en el equipo 5
(5, 3),  -- Operador 3 trabaja en el equipo 5
(6, 4),  -- Operador 4 trabaja en el equipo 6
(6, 5),  -- Operador 5 trabaja en el equipo 6
(7, 1),  -- Operador 1 trabaja en el equipo 7
(7, 3),  -- Operador 3 trabaja en el equipo 7
(8, 2),  -- Operador 2 trabaja en el equipo 8
(8, 4),  -- Operador 4 trabaja en el equipo 8
(9, 1),  -- Operador 1 trabaja en el equipo 9
(9, 5),  -- Operador 5 trabaja en el equipo 9
(10, 3), -- Operador 3 trabaja en el equipo 10
(10, 6), -- Operador 6 trabaja en el equipo 10
(11, 2), -- Operador 2 trabaja en el equipo 11
(11, 7), -- Operador 7 trabaja en el equipo 11
(12, 1), -- Operador 1 trabaja en el equipo 12
(12, 8), -- Operador 8 trabaja en el equipo 12
(13, 3), -- Operador 3 trabaja en el equipo 13
(13, 9), -- Operador 9 trabaja en el equipo 13
(14, 4), -- Operador 4 trabaja en el equipo 14
(14, 10), -- Operador 10 trabaja en el equipo 14
(15, 5), -- Operador 5 trabaja en el equipo 15
(15, 11), -- Operador 11 trabaja en el equipo 15
(16, 6), -- Operador 6 trabaja en el equipo 16
(16, 12), -- Operador 12 trabaja en el equipo 16
(17, 7), -- Operador 7 trabaja en el equipo 17
(17, 13), -- Operador 13 trabaja en el equipo 17
(18, 8), -- Operador 8 trabaja en el equipo 18
(18, 14), -- Operador 14 trabaja en el equipo 18
(19, 9), -- Operador 9 trabaja en el equipo 19
(19, 15), -- Operador 15 trabaja en el equipo 19
(20, 10), -- Operador 10 trabaja en el equipo 20
(20, 16), -- Operador 16 trabaja en el equipo 20
(21, 11), -- Operador 11 trabaja en el equipo 21
(21, 17), -- Operador 17 trabaja en el equipo 21
(22, 12), -- Operador 12 trabaja en el equipo 22
(22, 18), -- Operador 18 trabaja en el equipo 22
(23, 13), -- Operador 13 trabaja en el equipo 23
(23, 19), -- Operador 19 trabaja en el equipo 23
(24, 14), -- Operador 14 trabaja en el equipo 24
(24, 20), -- Operador 20 trabaja en el equipo 24
(25, 15), -- Operador 15 trabaja en el equipo 25
(25, 21), -- Operador 21 trabaja en el equipo 25
(26, 16), -- Operador 16 trabaja en el equipo 26
(26, 22), -- Operador 22 trabaja en el equipo 26
(27, 17), -- Operador 17 trabaja en el equipo 27
(27, 23), -- Operador 23 trabaja en el equipo 27
(28, 18), -- Operador 18 trabaja en el equipo 28
(28, 24), -- Operador 24 trabaja en el equipo 28
(29, 19), -- Operador 19 trabaja en el equipo 29
(29, 25), -- Operador 25 trabaja en el equipo 29
(30, 20), -- Operador 20 trabaja en el equipo 30
(30, 26), -- Operador 26 trabaja en el equipo 30
(31, 21), -- Operador 21 trabaja en el equipo 31
(31, 27), -- Operador 27 trabaja en el equipo 31
(32, 22), -- Operador 22 trabaja en el equipo 32
(32, 28), -- Operador 28 trabaja en el equipo 32
(33, 23), -- Operador 23 trabaja en el equipo 33
(33, 29), -- Operador 29 trabaja en el equipo 33
(34, 24), -- Operador 24 trabaja en el equipo 34
(34, 30), -- Operador 30 trabaja en el equipo 34
(35, 25), -- Operador 25 trabaja en el equipo 35
(35, 31), -- Operador 31 trabaja en el equipo 35
(36, 26), -- Operador 26 trabaja en el equipo 36
(36, 32), -- Operador 32 trabaja en el equipo 36
(37, 27), -- Operador 27 trabaja en el equipo 37
(37, 33), -- Operador 33 trabaja en el equipo 37
(38, 28), -- Operador 28 trabaja en el equipo 38
(38, 34); -- Operador 34 trabaja en el equipo 38


-- Insertar registros en la tabla failure_equipment
INSERT INTO failure_equipment (equipment, failure) VALUES 
-- Fallas relacionadas con motores y sistemas eléctricos
(1, 1),  -- Robot Soldador - Fallo eléctrico del motor
(2, 2),  -- Máquina de Ensamblaje - Fuga de aceite
(3, 3),  -- Cinta Transportadora - Se detuvo inesperadamente
(4, 4),  -- Elevador Hidráulico - Sobrecalentamiento
(5, 5),  -- Robot de Montaje - Sensor de presión no funciona
(6, 6),  -- Máquina de Corte por Laser - Compresor no genera aire
(7, 7),  -- Sistema de Monitoreo - Rodillos desgastados
(8, 8),  -- Horno de Curado - Sistema hidráulico con pérdida
(9, 9),  -- Máquina de Pintura - Sistema de refrigeración
(10, 10), -- Robot de Pintura - Amortiguador hidráulico
(11, 11), -- Compresora de Aire - Cilindro neumático
(12, 12), -- Cabina de Pintura - Fuga de refrigerante
(13, 13), -- Sistema de Ventilación - Sistema de control
(14, 14), -- Equipo de Medición - Correas desgastadas
(15, 15), -- Prensa Hidráulica - Interruptor de seguridad
(16, 16), -- Máquina de Pruebas - Bomba de agua
(17, 17), -- Cortadora de Plasma - Sistema de lubricación
(18, 18), -- Máquina de Ensayo - Fuga de aire
(19, 19), -- Prensa de Corte - Generador no produce energía
(20, 20), -- Sistema de Refrigeración - Sistema de monitoreo
(21, 21), -- Máquina de Corte CNC - Componentes desgastados
(22, 22), -- Taladro Industrial - Sistema de ventilación
(23, 23), -- Robot de Manipulación - Sensor de temperatura
(24, 24), -- Máquina de Soldadura - Fuga de combustible
(25, 25), -- Compresor de Tornillo - Compresor de refrigeración
(26, 26), -- Máquina de Acabado - Sistema de transmisión
(27, 27), -- Sistema de Transporte - Rodamientos desgastados
(28, 28), -- Generador de Vapor - Sistema de almacenamiento
(29, 29), -- Sistema de Control - Sistema de pruebas
(30, 30); -- Sistema de Control de Calidad - Sistema de calefacción



-- Insertar registros en la tabla spare_purchases
INSERT INTO spare_purchases (spare_parts, purchase_orders, quantity, amount) VALUES 
-- Orden de compra 1 - Repuestos Automotrices del Norte S.A.
(1, 1, 2, 1000.00),   -- 2 Motores Eléctricos
(8, 1, 5, 300.00),    -- 5 Sensores de Presión
(15, 1, 3, 210.00),   -- 3 Engranajes de Transmisión
(2, 2, 3, 450.00),    -- 3 Bombas de Agua
(9, 2, 10, 200.00),   -- 10 Filtros de Aire
(16, 2, 2, 400.00),   -- 2 Placas de Circuito
(3, 3, 5, 375.00),    -- 5 Válvulas de Control
(10, 3, 15, 225.00),  -- 15 Correas de Transmisión
(17, 3, 8, 240.00),   -- 8 Interruptores de Seguridad
(4, 4, 2, 400.00),    -- 2 Cintas Transportadoras
(11, 4, 4, 360.00),   -- 4 Amortiguadores Hidráulicos
(18, 4, 10, 250.00),  -- 10 Relés de Control
(5, 5, 3, 900.00),    -- 3 Rodillos de Compresión
(12, 5, 3, 330.00),   -- 3 Cilindros Neumáticos
(19, 5, 5, 400.00),   -- 5 Transmisores de Temperatura
(6, 6, 5, 600.00),    -- 5 Frenos Neumáticos
(13, 6, 10, 250.00),  -- 10 Mangueras Hidráulicas
(20, 6, 3, 300.00),   -- 3 Baterías de Respaldo
(7, 7, 8, 360.00),    -- 8 Cojinetes de Rodillo
(14, 7, 10, 400.00),  -- 10 Bandas de Rodadura
(21, 7, 1, 400.00),   -- 1 Compresor de Aire
(22, 8, 15, 225.00),  -- 15 Filtros de Aceite
(23, 8, 5, 300.00),   -- 5 Cilindros de Gas
(24, 8, 6, 300.00),   -- 6 Tuberías de Acero
(25, 9, 20, 200.00),  -- 20 Conectores Eléctricos
(26, 9, 2, 500.00),   -- 2 Cámaras de Combustión
(27, 9, 5, 350.00),   -- 5 Válvulas de Seguridad
(28, 10, 10, 200.00), -- 10 Tapas de Acceso
(29, 10, 30, 150.00), -- 30 Cintas de Medición
(30, 10, 8, 360.00),  -- 8 Soportes de Motor
(31, 11, 3, 270.00),  -- 3 Rodillos de Transporte
(32, 11, 8, 280.00),  -- 8 Cintas de Transmisión
(33, 11, 3, 450.00),  -- 3 Frenos de Disco
(34, 12, 5, 600.00),  -- 5 Cilindros Hidráulicos
(35, 12, 2, 600.00),  -- 2 Módulos de Control
(36, 12, 10, 500.00), -- 10 Sensores de Movimiento
(37, 13, 15, 375.00), -- 15 Placas de Soporte
(38, 13, 50, 250.00), -- 50 Tornillos de Ajuste
(39, 13, 5, 400.00),  -- 5 Engranajes de Reducción
(40, 14, 10, 400.00), -- 10 Cintas de Rodadura
(41, 14, 4, 400.00),  -- 4 Bombas de Aceite
(42, 14, 15, 450.00), -- 15 Filtros de Refrigerante
(43, 15, 20, 400.00), -- 20 Mangueras de Refrigeración
(44, 15, 3, 450.00),  -- 3 Cilindros de Compresión
(45, 15, 8, 480.00);  -- 8 Válvulas de Escape



-- Insertar registros en la tabla maintenance_history
INSERT INTO maintenance_history (completionDate, results, observations, equipment, maintenance) VALUES 
('2024-01-07', 'Exitoso', 'Se completó el mantenimiento preventivo del robot soldador. Todos los componentes funcionan correctamente.', 1, 1),
('2024-01-12', 'Exitoso', 'Reparación exitosa de la máquina de ensamblaje. Se reemplazaron las piezas desgastadas.', 2, 2),
('2024-01-17', 'Exitoso', 'Inspección completa de la cinta transportadora. Se ajustaron los rodillos y la tensión.', 3, 3),
('2024-01-22', 'Exitoso', 'Calibración exitosa del sensor de presión. Se verificó la precisión de las lecturas.', 4, 4),
('2024-01-27', 'Exitoso', 'Cambio de aceite completado. Se limpiaron los filtros y se verificó el funcionamiento.', 5, 5),
('2024-02-03', 'Exitoso', 'Reemplazo exitoso de rodillos. La cinta funciona sin vibraciones ni ruidos.', 6, 6),
('2024-02-07', 'Exitoso', 'Sistema de refrigeración funcionando óptimamente. Se verificaron todas las conexiones.', 7, 7),
('2024-02-12', 'Requiere seguimiento', 'Se reparó la bomba de agua pero se recomienda monitoreo en próximas 48 horas.', 8, 8),
('2024-02-17', 'Exitoso', 'Todos los sistemas de seguridad funcionando correctamente. Documentación actualizada.', 9, 9),
('2024-02-22', 'Exitoso', 'Sistema eléctrico reparado y probado. Se verificaron todas las conexiones.', 10, 10),
('2024-03-03', 'Exitoso', 'Sistema de control actualizado y funcionando correctamente. Sin errores detectados.', 11, 11),
('2024-03-07', 'Requiere seguimiento', 'Ajustes realizados en máquina de soldadura. Programar revisión en una semana.', 12, 12),
('2024-03-12', 'Exitoso', 'Prensa hidráulica funcionando a capacidad óptima. Presión estabilizada.', 13, 13),
('2024-03-17', 'Exitoso', 'Sistema de ventilación reparado. Flujo de aire normalizado.', 14, 14),
('2024-04-03', 'Exitoso', 'Calibración completada con éxito. Todas las mediciones dentro de parámetros.', 15, 15);


-- Insertar registros en la tabla spare_history
INSERT INTO spare_history (spare_parts, maintenance_history, usedQuantity, usageDate) VALUES 
-- Historial para mantenimiento 1
(1, 1, 1, '2024-01-07'),   -- Motor Eléctrico
(8, 1, 2, '2024-01-07'),   -- Sensores de Presión
(9, 1, 3, '2024-01-07'),   -- Filtros de Aire
(2, 2, 1, '2024-01-12'),   -- Bomba de Agua
(10, 2, 2, '2024-01-12'),  -- Correas de Transmisión
(15, 2, 1, '2024-01-12'),  -- Engranaje de Transmisión
(4, 3, 1, '2024-01-17'),   -- Cinta Transportadora
(7, 3, 4, '2024-01-17'),   -- Cojinetes de Rodillo
(14, 3, 2, '2024-01-17'),  -- Banda de Rodadura
(8, 4, 1, '2024-01-22'),   -- Sensor de Presión
(16, 4, 1, '2024-01-22'),  -- Placa de Circuito
(36, 4, 2, '2024-01-22'),  -- Sensor de Movimiento
(22, 5, 3, '2024-01-27'),  -- Filtros de Aceite
(41, 5, 1, '2024-01-27'),  -- Bomba de Aceite
(13, 5, 2, '2024-01-27'),  -- Manguera Hidráulica
(7, 6, 6, '2024-02-03'),   -- Cojinetes de Rodillo
(31, 6, 2, '2024-02-03'),  -- Rodillos de Transporte
(14, 6, 3, '2024-02-03'),  -- Banda de Rodadura
(42, 7, 2, '2024-02-07'),  -- Filtros de Refrigerante
(43, 7, 3, '2024-02-07'),  -- Mangueras de Refrigeración
(25, 7, 4, '2024-02-07'),  -- Conectores Eléctricos
(2, 8, 1, '2024-02-12'),   -- Bomba de Agua
(13, 8, 2, '2024-02-12'),  -- Manguera Hidráulica
(27, 8, 1, '2024-02-12'),  -- Válvula de Seguridad
(17, 9, 2, '2024-02-17'),  -- Interruptores de Seguridad
(18, 9, 3, '2024-02-17'),  -- Relés de Control
(20, 9, 1, '2024-02-17'),  -- Batería de Respaldo
(25, 10, 5, '2024-02-22'), -- Conectores Eléctricos
(16, 10, 1, '2024-02-22'), -- Placa de Circuito
(20, 10, 2, '2024-02-22'); -- Batería de Respaldo

SELECT e.type AS equipment_type, AVG(TIMESTAMPDIFF(DAY, f1.date, f2.date)) AS avg_days_between_failures
FROM failure f1
JOIN failure f2 ON f1.equipment = f2.equipment AND f1.date < f2.date
JOIN equipment e ON f1.equipment = e.id
GROUP BY e.type;

SELECT e.name AS equipment_name, COUNT(f.id) AS total_failures, 
       GROUP_CONCAT(DISTINCT mh.completionDate ORDER BY mh.completionDate DESC SEPARATOR ', ') AS maintenance_dates
FROM equipment e
LEFT JOIN failure f ON e.id = f.equipment
LEFT JOIN maintenance_history mh ON e.id = mh.equipment
GROUP BY e.id
ORDER BY total_failures DESC
LIMIT 1;


DELIMITER //
CREATE TRIGGER before_insert
BEFORE INSERT ON operator
FOR EACH ROW
BEGIN
SET NEW.password = AES_ENCRYPT(NEW.password, "key");
END //
DELIMITER ;

SELECT AES_DECRYPT(password, "key")
FROM operator
WHERE id_operator=39

INSERT INTO failure (date, description, operator)
VALUES (CURDATE(), 'Falla en el motor', 39); 

INSERT INTO failure_equipment (failure, equipment)
VALUES (@last_failure_id, 2);

GRANT INSERT, SELECT, UPDATE ON industrial_maintenance.* to 'operator'@'localhost'; 



DELIMITER //

CREATE TRIGGER before_insert_administrator
BEFORE INSERT ON administrator
FOR EACH ROW
BEGIN
    SET NEW.user = AES_ENCRYPT(NEW.user, "key");
    SET NEW.password = AES_ENCRYPT(NEW.password, "key");
END //

DELIMITER ;