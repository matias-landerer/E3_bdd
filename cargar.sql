 --Ignorar, lo tengo para usarlo al probar la entrega.
/*
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;
*/

DROP TABLE IF EXISTS InstitucionSalud, Persona, Plan, Farmacia, ArancelFonasa, ArancelDCColita, Atencion, Medicamento, Orden CASCADE;
--Creación de entidades.
CREATE TABLE InstitucionSalud (
    ID SERIAL PRIMARY KEY,
    codigo INT NOT NULL UNIQUE,
    nombre VARCHAR(30) UNIQUE NOT NULL,
    tipo VARCHAR(10) CHECK (Tipo IN ('abierta','cerrada')),
    rut CHAR(12) NOT NULL UNIQUE,
    Enlace TEXT
);

CREATE TABLE Persona (
    ID SERIAL PRIMARY KEY,
    RUN CHAR(10) UNIQUE NOT NULL,
    Nombre VARCHAR(30) NOT NULL,
    Apellido VARCHAR(30) NOT NULL,
    Direccion VARCHAR(100),
    Correo VARCHAR(100) CHECK (Correo ~ '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'),
    Telefono CHAR(9) CHECK (Telefono ~ '^[1-9][0-9]{8}$'),
    tipo VARCHAR(15) CHECK (tipo IN ('titular','beneficiario')),
    titular CHAR(10) REFERENCES Persona(RUN),
    rol VARCHAR(20) CHECK (rol IN ('Staff médico', 'administrativo', 'paciente')),
    profesion VARCHAR(30) CHECK (profesion IN ('TENS','enfermero/a','kinesiólogo/a','médico(a)')),
    especialidad VARCHAR(30),
    firma TEXT,
    Institucion VARCHAR(30) REFERENCES InstitucionSalud(nombre),
    CONSTRAINT chk_especialidad_medico CHECK (
        (profesion = 'médico(a)' AND especialidad IS NOT NULL)
        OR (profesion != 'médico(a)' AND especialidad IS NULL)
    )
);

CREATE TABLE Plan (
    IDInstitucion INT PRIMARY KEY REFERENCES InstitucionSalud(ID),
    Bonificacion INT CHECK (Bonificacion BETWEEN 0 AND 100),
    Grupo VARCHAR(100)
);

CREATE TABLE Farmacia (
    ID SERIAL PRIMARY KEY,
    Cod INT UNIQUE NOT NULL,
    Nombre VARCHAR(100) UNIQUE NOT NULL,
    Descripcion TEXT NOT NULL,
    Tipo VARCHAR(30) CHECK (Tipo IN ('Alimentos','Equipamiento','Fármacos','Insumos','Psicotrópicos','Refrigerados','Sueros')),
    CodONU INT,
    ClasONU VARCHAR(30),
    Clasificacion VARCHAR(50) NOT NULL,
    Estado VARCHAR(10) CHECK (Estado IN ('Activo','Inactivo')),
    Esencial BOOLEAN,
    Precio INT CHECK (Precio >= 0)
);

CREATE TABLE ArancelFonasa (
    ID SERIAL PRIMARY KEY,
    CodF INT UNIQUE NOT NULL,
    CodA INT,
    Atencion VARCHAR(100),
    Valor INT CHECK (Valor >= 0),
    Grupo VARCHAR(30),
    Tipo VARCHAR(30)
);

CREATE TABLE ArancelDCColita (
    ID SERIAL PRIMARY KEY,
    Codigo INT UNIQUE NOT NULL,
    CodFonasa INT REFERENCES ArancelFonasa(CodF),
    Atencion VARCHAR(100),
    Valor INT CHECK (Valor >= 0)
);

CREATE TABLE Atencion (
    ID SERIAL PRIMARY KEY,
    fecha DATE,
    RunPaciente CHAR(10) REFERENCES Persona(RUN),
    RunMedico CHAR(10) REFERENCES Persona(RUN),
    Diagnostico VARCHAR(100),
    Efectuada BOOLEAN DEFAULT FALSE
);

CREATE TABLE Medicamento (
    ID SERIAL PRIMARY KEY,
    IDAtencion INT REFERENCES Atencion(ID),
    Nombre VARCHAR(100) REFERENCES Farmacia(Nombre),
    Posologia VARCHAR(100),
    Psicotropico BOOLEAN DEFAULT FALSE
);

CREATE TABLE Orden (
    ID SERIAL PRIMARY KEY,
    IDAtencion INT REFERENCES Atencion(ID),
    IDArancel INT REFERENCES ArancelDCColita(ID),
    Consulta VARCHAR(100)
);

--/home/mlanderer.e3/E3/planes/
--Insertamos los datos (perdón por tener las lineas tan largas, pero no funcionaba al usar saltos de línea).
\COPY InstitucionSalud(codigo, nombre, tipo, rut, enlace) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Instituciones previsionales de saludOK.csv' DELIMITER ';' CSV HEADER;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\\planes\Colmena de avispas S.A..csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 2 WHERE IDInstitucion IS NULL;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Cruz de Malta S.A..csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 4 WHERE IDInstitucion IS NULL;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Cruz pal cielo Ltda..csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 7 WHERE IDInstitucion IS NULL;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Fundación e imperio.csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 3 WHERE IDInstitucion IS NULL;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Medibanc S.A..csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 8 WHERE IDInstitucion IS NULL;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Menos vida S.A..csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 6 WHERE IDInstitucion IS NULL;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\salud.csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 1 WHERE IDInstitucion IS NULL;

\COPY Plan(Bonificacion, Grupo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Vida uno S.A..csv' DELIMITER ';' CSV HEADER;
UPDATE Plan SET IDInstitucion = 5 WHERE IDInstitucion IS NULL;

\COPY Persona(ID, RUN, Nombre, Apellido, Direccion, Correo, Telefono, tipo, titular, rol, profesion, especialidad, firma, Institucion) FROM '/home/mlanderer.e3/E3/' DELIMITER ';' CSV HEADER;

\COPY ArancelFonasa(CodF, CodA, Atencion, Valor, Grupo, Tipo) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Arancel fonasaOK.csv' DELIMITER ';' CSV HEADER;

\COPY ArancelDCColita(Codigo, CodFonasa, Atencion, Valor) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\Arancel DCColita de ranaOK.csv' DELIMITER ';' CSV HEADER;

\COPY Farmacia(Cod, Nombre, Descripcion, Tipo, CodONU, ClasONU, Clasificacion, Estado, Esencial, Precio) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\FarmaciaOK.csv' DELIMITER ';' CSV HEADER;

\COPY Atencion(RunPaciente, RunMedico, Diagnostico, Efectuada) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\AtencionOK.csv' DELIMITER ';' CSV HEADER;

\COPY Medicamento(fecha, IDAtencion, Nombre, Posologia, Psicotropico) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\MedicamentoOK.csv' DELIMITER ';' CSV HEADER;

\COPY Orden(IDAtencion, IDArancel, Consulta) FROM 'C:\Users\lanco_qehqoqy\OneDrive\Escritorio\E3_bdd\OrdenOK.csv' DELIMITER ';' CSV HEADER;
